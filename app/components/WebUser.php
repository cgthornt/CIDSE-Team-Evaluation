<?php

class WebUser extends CWebUser {
  
  private $_roles, $_userModel;
  
  
  /**
   * Gets the User object for the currently logged in user, or null if the user is not logged in
   * @return User the user object
   */
  public function getUserModel() {
    if($this->isGuest) return null;
    if($this->_userModel == null)
      $this->_userModel = User::model()->findByPk($this->id);
      return $this->_userModel;
  }
  
  public function getModel() {
    return $this->userModel;
  }
  
  
  /**
   * Gets an array of roles
   */
  public function getRoles() {
    if($this->isGuest) return array('guest');
    if($this->_roles == null) {
      // Note: due to requirements, we must manually specify someone as an admin / faculty
      // $this->_roles = array($this->userModel->role_primary);
      $this->_roles = array();
      foreach($this->userModel->roles as $role)
        $this->_roles[] = $role->role;
    }
    return $this->_roles;
  }
  
  /**
   * Checks to see if the user is in a role
   * @param mixed $roles a string of a role or an array or foles
   * @return boolean true if the user is in one or more of the roles, false otherwise
   */
  public function role($roles) {
    if(!is_array($roles)) $roles = array($roles);
    foreach($roles as $role) {
      if(in_array($role, $this->roles))
        return true;
    }
    return false;
  }
  
  
  /**
   * Checks access
   * @param string $operation the role to check
   * @param array $params not used
   * @return boolean TRUE if user is of role '$operation', FALSE otherwise
   */
  public function checkAccess($operation, $params = array()) {
    return in_array($operation, $this->roles);
  }
  
  
  
}