<?php

// Load CAS library
require_once(Yii::app()->extensionPath . '/CAS/CAS.php');

/**
 * This identity class handles authentication from the ASU CAS service. In addition, it handles features
 * related to the ASU profile service.
 *
 * CAS authentication allows us to "log in" a user from another organization. If we "trust" a particular
 * CAS server, we may then use another server to handle the details of user login and authentication.
 * Afterwards, we will get only the "username" back from the CAS server. Since we trust this server, this
 * means that the user has successfully logged in.
 *
 * It would be very costly and slow to always query CAS and the user profile service for user information
 * for every user request. As such, we want to save a copy of the user information in our local database
 * and so fourth. Every so often, we will want to refresh this data from the user profile service (about
 * every thirty days).
 *
 * After CAS authentication, we check to see if this user already has cached user data on our server. If
 * he does not, we will then query the user profile service from the username we got from the CAS server
 * and create a new user in our database. In addition, we will use this profile service to determine wheter
 * someone is a faculty or student.
 *
 * Afterwards, once we get the user information from our database, we will then resort to using sessions to
 * track whether a user is logged in and no longer rely on any other services for user authentication, until
 * the user logs off.
 * 
 * @author Christopher Thornton
 */
class CASUserIdentity extends CUserIdentity {
  
  private $_id;

  
  /**
   * CAS doesn't require us to submit user credentials. Instead, it only
   * sends us back the username. As such, we want to overrite the default
   * CUserIdentity behavior and to not provide a default username and password.
   */
  public function __construct() {
    parent::__construct("", "");
  }
  
  
  /**
   * Certificate verification is *essential* to the security of CAS authentication and
   * to retrieving data from the ASU profile service. As such, if we want the best possible
   * security, we must do our own verification of ASU's SSL certificate to prevent man in
   * the middle attacks, etc.
   *
   * If you are need of a CA cert, you can download one from {@link http://curl.haxx.se/ca/cacert.pem}
   *
   * @return string the path to the CA root certificate bundle
   * @see authenticate() for CAS authentication
   * @see getUserProfileInformation() for retrieving profile information
   */
  private static function getCaCert() {
    return Yii::app()->extensionPath . '/cacert.pem';
  }
  
  /**
   * Simply returns the database ID of this user, or NULL if one is not set
   */
  public function getId() { return $this->_id; }
  
  
  /**
   * Authenticates this user using CAS authentication. 
   */
  public function authenticate() {
    phpCAS::client(CAS_VERSION_2_0, 'weblogin.asu.edu', 443, 'cas');
    phpCAS::setCasServerCACert(self::getCaCert());
    phpCAS::forceAuthentication();
    $this->username = phpCAS::getUser();
    $user = self::findOrCreateUserByUsername($this->username);
    $this->_id = $user->id;
    return !self::ERROR_NONE;
  }
  
  /**
   * Bypass any form of CAS authentication and assume that $username 
   */
  public function authenticateEmulate($username) {
    if(!YII_DEBUG) throw new Exception("You cannot emulate a login unless in debug mode!");
    $this->username = $username;
    $user = self::findOrCreateUserByUsername($username);
    $this->_id = $user->id;
    return !self::ERROR_NONE;
  }
  
  public static function logout() {
    phpCAS::client(CAS_VERSION_2_0, 'weblogin.asu.edu', 443, 'cas');
    phpCAS::setCasServerCACert(self::getCaCert());
    phpCAS::logout();
  }
  
  
  /**
   * Attempts to find a user with the username '$username'. If the user does not exist, or it does exist but
   * requires a profile update, then attempt to look it up from the ASU service. Afterwards, create a new user
   * in the Database (if the user did not exist).
   * @param string $username the username to search
   * @return User the user object
   */
  public static function findOrCreateUserByUsername($username) {
    
    // Attempt to find the user by username. If he does not exist, then make a new one
    $user = User::model()->find('username=?', array($username));
    if($user == null) {
      $user = new User;
      $user->username = $username;
    }
    
    // Make sure to only update from the user profile service if nescesary. 
    if($user->requiresProfileRefresh) {
      $profileInfo = self::getUserProfileInformation($username);
      $user->attributes = $profileInfo['attributes'];
      
      // IMPORTANT: Make sure to update the last profile updated time!
      $user->profile_last_updated = new Time();
      
      // Finally, attempt to save the user
      if(!$user->save()) throw new Exception("Unable to save user: " . var_export($user->errors, true));
    }
    
    return $user;
  }
  
  
  /**
   * Gets a user from the profile service. Should return an array in the format of:
   *
   *    array(
   *      'attributes' => array('first_name' => 'Joe', 'middle_name' => 'M', 'last_name' => 'Smith', 'email' => 'example@example.com', 'role_primary' => 'student'),
   *      'xml'        => simplexml Object,
   *      'body'       => xml response body
   *    )
   *
   * @param string $username the username to lookup
   * @return array the user attributes
   */
  public static function getUserProfileInformation($username) {
    // Create an exception we can easily throw if user lookup is invalid
    $failure = new Exception("Unable to lookup username '$username' from profile service!");

    $url = 'https://webapp4.asu.edu/directory/ws/search?asuriteId=' . urlencode($username);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CAINFO, self::getCaCert());
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 5 second timeout
    $body = curl_exec($ch);
    curl_close($ch);
    
    
    try { 
      $xml    = simplexml_load_string($body);
    } catch(Exception $e) {
      throw new Exception("Unable to load XML response from '$url'");
    }
    
    $person = $xml->person;
    
    // Empty person means invalid ASURITE
    if(empty($person)) throw $failure;
    
    // Basic User Information. Assume they're a student up to this point
    $userInformation = array(
      'first_name'   => $person->firstName . '',
      'middle_name'  => $person->middleName . '',
      'last_name'    => $person->lastName . '',
      'email'        => $person->email . '',
      'role_primary' => User::$ROLE_STUDENT
    );
    
    // Now check to see if they might be a faculty. Loop through each job and check the
    // value of the 'employeeClass'
    if(!empty($person->jobs)) {
      foreach($person->jobs->job as $job) {
        $employeeClass = strtolower($job->employeeClass);
        if($employeeClass == 'faculty') { // We've discovered that they're a faculty user!
          $userInformation['role_primary'] = User::$ROLE_FACULTY;
          break;
        }
      }
    }
    
    return array(
      'attributes' => $userInformation,
      'xml'        => $xml,
      'body'       => $body,
    );
  }
  
}