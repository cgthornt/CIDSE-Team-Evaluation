<?php
/**
 * Base Application Controller
 * All controllers should override this
 */
class Controller extends CController {
  
  public $layout = 'column1';
  
  
  /**
   * @var array context menu items. This property will be assigned to {@link CMenu::items}.
   */
  public $menu=array();
  /**
   * @var array the breadcrumbs of the current page. The value of this property will
   * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
   * for more details on how to specify this property.
   */
  public $breadcrumbs=array();
  
  /**
   * {@inheritdoc }
   * In addition, this will automatically load any helpers.
   */
  public function __construct($id, CWebModule $module = NULL) {
    parent::__construct($id, $module);
    $appHelper = Yii::getPathOfAlias('application.helpers.ApplicationHelper') . '.php';
    $controllerHelper = Yii::getPathOfAlias('application.helpers.' . $this->controllerName . 'Helper') . '.php';
    file_exists($appHelper) && require_once($appHelper);
    file_exists($controllerHelper) && require_once($controllerHelper);
  }
  
  public function pageTitle($pageTitle, $subtext = null) {
    $this->pageTitle = $pageTitle;
    $html = '<div class="page-title"><h1>' . $pageTitle;
    if(!empty($subtext)) $html .= ' <small>' . $subtext . '</small>';
    return $html .= '</h1></div>';
  }
  
  
  public function filters() {
    return array( 'accessControl');
  }
  
  
  /**
   * Gets the name of this controller, without the "Controlelr" part. For
   * example, "UsersController" will return "Users".
   * @return string the name of this controller
   */
  public function getControllerName() {
    return substr(get_class($this), 0, -10);
  }
  

  
  public function getRequest() { return Yii::app()->request; }
  
  public function getIsUseMenu() {
    return !empty($this->menu);
  }
  
  public function flash($type, $message) {
    Yii::app()->user->setFlash($type, $message);
  }
  
  /**
   * Shorthand so that you may do $this->user
   */ 
  public function getUser() { return Yii::app()->user; }
  
  public function getUserModel() {
    if(!$this->user->isGuest) return $this->user->model;
  }
  
  
  
  private $_currentCourse;
  
  /**
   * Gets the current course the user has selected. Returns null if one hasn't been selected.
   */
  public function getCurrentCourse() {
    if(empty($this->_currentCourse)) {
      if(!empty(Yii::app()->session['course_id']))
        $this->_currentCourse = Course::model()->withAccess($this->userModel)->findByPk(Yii::app()->session['course_id']);
    }
    return $this->_currentCourse;
  }
  
  
  /**
   * Sets the current course. Does access checks to make sure that the user can even view the course. For example,
   * it makes sure that the current student is enrolled or the professor is in it.
   */
  public function setCurrentCourse($courseId) {
    $course = Course::model()->withAccess($this->userModel)->findByPk($courseId);
    if($course == null) throw new Exception("User '" . $this->userModel->username . "' does not have permission to access course '#" . $courseId . "' or course does not exist!");
    Yii::app()->session['course_id'] = $course->id;
    $this->_currentCourse = $course;
    return $course;
  }
  
  /**
   * A yii filter to require a user to select a course
   *
   * @see http://www.yiiframework.com/doc/guide/1.1/en/basics.controller#filter
   */
  public function filterRequireCourse($filterChain) {
    
    // If we have selected a course, then we can run the chain as normal
    if($this->currentCourse != null)
      return $filterChain->run();
    
    $url = $this->request->requestUri;
    $this->flash("alert", "Please Select a Course to Continue");
    $this->redirect(array('courses/index', 'return_url' => $url));
    
  }
  
  
}