<?php
class WelcomeController extends Controller {
  
	/**
	 * A list of HTTP errors that are handleable
	 */
	protected static $HTTP_ERRORS_HANDLED = array(403, 404);
	
	
  public function accessRules() {
    return array(
      
      // Allow any user to view the index page
      array('allow', 'actions' => array('index'), 'users' => array('*')),
    );
  }
  
	/**
	 * If the user is logged in, show the main index page. Otherwise, we will want to show the dashboard
	 */
  public function actionIndex() {
		
		// If a guest, then just show the index page. Otherwise, show a dashboard.
		if($this->user->isGuest)
			return $this->render('index');
		
		
		$this->render('dashboard');
  }
	
	
	
	/**
	 * Handle any errors by using our own template
	 */
	public function actionError() {
		$error =  Yii::app()->errorHandler->error;
		if($error['type'] == 'CHttpException' && in_array($error['code'], self::$HTTP_ERRORS_HANDLED)) {
			return $this->render("errors/error{$error['code']}", array('error' => $error));
		}
		$this->render('errors/generic', array('error' => $error));
	}
  
  
}
?>