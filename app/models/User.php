<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $role_primary
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $email
 * @property string $profile_last_updated
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property UsersRoles[] $usersRoles
 */
class User extends Model {
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
  
  public static $ROLE_ADMIN = 'admin', $ROLE_FACULTY = 'faculty', $ROLE_STUDENT = 'student';
  
  
  // Profile should be updated every 30 days
  public static $MAX_PROFILE_SECONDS = 2592000;

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'users';
	}

  /**
   * Gets the full name of the user, i.e.
   *
   *    // Prints "Joe Smith"
   *    echo $user->fullName;
   */
  public function getFullName() {
    return $this->first_name . ' ' . $this->last_name;
  }
  
  
  /**
   * Gets an email name. Perfectly able to use with emails.
   *
   *  // Prints "Joe Smith <Joe.Smith@example.com>"
   *  echo $user->emailName;
   */
  public function getEmailName() {
    return $this->fullName . ' <' . $this->email . '>';
  }
  
  public function getRole_primary()
  {
  	return $this->role_primary;
  }
  
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
    return array(
			array('username, role_primary, first_name, last_name, middle_name, email', 'length', 'max' => 255, 'allowEmpty' => false),
			array('profile_last_updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, role_primary, first_name, last_name, middle_name, email, profile_last_updated, updated_at', 'safe', 'on'=>'search'),
      array('updated_at', 'default', 'value' => new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'update'),
		);
	}
  
  /**
   * Gets a search model for evaluations that this user has. Excludes archived courses. You will
   * need to append the result with `->findAll()` to get an array of results.
   * @param boolean $onlyUnsubmitted if TRUE, limits evaluations to those that have not
   *  yet been submitted by the user. If FALSE, then it will return evaluations, even if
   *  it has been submitted by the user.
   * @param Time $time the system takes snapshots of when users have been added and removed to courses.
   *  if specified, you may use a Time object to view evaluations at a given snapshot. If unspecified,
   *  only returns current courses.
   * @param boolean $onlyPublished if TRUE, will only limit results to published evaluations. If FALSE,
   *  then results will be displayed, regardless of whether it has been published.
   * @return array an array of evaluations.
   */
  public function currentEvaluations($onlyUnsubmitted = true, $time = null, $onlyPublished = false) {
    if($time == null) $time = new Time();
    
    // FYI we go through this whole process to handle the case where a single user is enrolled in many
    // groups in a single course. ActiveRecord doesn't do this nicely.
    //if($onlyUnsubmitted) $where .= ' AND ((r.user_id = :student_id AND r.completed != 1) OR r.user_id IS NULL)';
    //if($onlyPublished) $where .= ' AND e.published = 1';
    
    $cmd = Yii::app()->db->createCommand()
      ->select('c.name AS course_name, c.code AS course_code, e.name AS evaluation_name, e.due_at AS due_at, cg.name as group_name, e.id AS evaluation_id, cg.id AS group_id, r.completed AS completed')
      ->from('evaluations e')
      
      // Joins
      ->join('courses c', 'c.id = e.course_id')
      ->join('course_groups cg', 'c.id = cg.course_id')
      ->join('course_group_students s', 'cg.id = s.course_group_id')
      ->leftJoin('evaluation_response_sets r', 'e.id = r.evaluation_id AND r.course_group_id = cg.id AND r.user_id = s.user_id')
      
      // Select where the course is archived
      ->where('c.archived = 0 AND s.user_id = :student_id AND s.date_enrolled <= :time AND (s.date_dropped > :time OR s.date_dropped IS NULL)',
              array(':student_id' => $this->id, ':time' => $time))
      ->order('e.due_at ASC'); // Closer due date should be prioritized
      
      
      
    // Only published limit
    if($onlyPublished) $cmd->andWhere('e.published = 1');
    
    // Any unsubmitted evaluations
    if($onlyUnsubmitted) {
      /*$result = array();
      foreach($cmd->queryAll() as $row) {
        die(var_dump($row));
        if($row['completed'] != '1')
          $result[] = $row;
      }
      return $result;*/
      $cmd->andWhere('r.completed != 1 OR r.completed IS NULL');
    }
    
    return $cmd->queryAll();
  }
  
  /**
   * return all the coures information related with current login user
   * @author Jesse
   * */
  public function currentCoursesAssociatedWithUser()
  {
  	$cmd = Yii::app()->db->createCommand()
  	->select('*')
  	->from('courses c')
  	->rightJoin('course_professors cp', 'c.id=cp.course_id')
  	->where('cp.user_id=:current_user_id',array(':current_user_id'=>$this->id));
  	
  	return $cmd->queryAll();
  	
  }

    /**
   * return all the evalutions that are created by current user.
   * @author Jesse
   * */
  public function allEvaluationsCreatedByCurrentUser()
  {
  	 $cmd = Yii::app()->db->createCommand()
  	->select('c.code AS course_code 
  	,c.name AS course_name 
  	,e.name AS evaluation_name 
  	,e.due_at AS evaluation_due_time
  	,c.id AS course_id
  	,e.id AS evaluation_id')
  	->from('evaluations e,courses c')
  	->rightJoin('course_professors cp', 'c.id=cp.course_id')
  	->where('cp.user_id=:current_user_id AND c.id=e.course_id',array(':current_user_id'=>$this->id))
  	->order('e.due_at ASC');
  	
  	return $cmd->queryAll();
  	
  }
	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'roles' => array(self::HAS_MANY, 'UserRole', 'user_id'),
			'course_groups' => array(self::MANY_MANY, "CourseGroup", "course_group_students(user_id,course_group_id)")
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'role_primary' => 'Role Primary',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'middle_name' => 'Middle Name',
			'email' => 'Email',
			'profile_last_updated' => 'Profile Last Updated',
			'updated_at' => 'Updated At',
		);
	}
  
  
  protected $_allRoles;
  
  /**
   * All roles available by this user
   */
  public function getAllRoles() {
    if(empty($_allRoles)) {
      $roles = array(); // Removed default role
      foreach($this->roles as $role) $roles[] = $role->role;
      $this->_allRoles = $roles;
    }
    return $this->_allRoles;
  }
  
  
  /**
   * Checks whether this user contains a given role
   * @param string $role the role to check
   * @return boolean TRUE if the user is in $role, FALSE otherwise
   */
  public function hasRole($role) {
    return in_array($role, $this->allRoles);
  }
  
  
  /**
   * Check wheter the profile should be updated
   * @return boolean TRUE if the data from this user object is old (or doesn't exist) and should be updated, FALSE
   * if there is no need to update the profile information
   */
  public function getRequiresProfileRefresh() {
    return empty($this->profile_last_updated) || time() - $this->profile_last_updated->getTimestamp() >  self::$MAX_PROFILE_SECONDS;
  }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('role_primary',$this->role_primary,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('middle_name',$this->middle_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('profile_last_updated',$this->profile_last_updated,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}