<?php

class UsersController extends Controller {

  public $layout = 'column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
      array('allow',  // Only guests should be able to login
        'actions' => array('login', 'loginEmulate', 'loginCAS'),
        'users'   => array('?'),
      ),
			array('allow', // allow authenticated user to logout
				'actions'=>array('logout'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to do general-purpose admin stuff
				'actions'=> array('admin', 'index', 'view', 'edit', 'update'),
				'roles'  => array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
  
  
  /**
   * Logins using an emulated username - specifically made to not work when YII_DEBUG is false
   */
  public function actionLoginEmulate() {
    if(!YII_DEBUG) throw new CHttpException(403, "You cannot access this in non-debug mode");
    // die("Test");
	if($this->request->isPostRequest) {
      $identity = new CASUserIdentity();
	  if($identity->authenticateEmulate($_POST['asurite'])) {
        $this->user->login($identity);
        $this->flash('success', 'Login Successful!');
        return $this->redirect($this->user->returnUrl);
      }
    }
  }
  
  public function actionLoginCAS() {
    $identity = new CASUserIdentity();
    if($identity->authenticate()) {
      $this->user->login($identity);
      $this->flash('success', 'Login Successful!');
      return $this->redirect($this->user->returnUrl);
    }
  }
  
  /**
   * Logs out the user
   * @todo have a flash message
   */
  public function actionLogout() {
    if($this->request->isPostRequest) {
      $this->user->logout();
      CASUserIdentity::logout();
      $this->flash('success', 'Logout Successful');
      return $this->redirect(array('welcome/index'));
    }
  }
  
  /**
   * Logs a user in
   */
  public function actionLogin() {
    $this->render('login');
  }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	} */

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['role'])) {
      // Begin a DB transaction
      $transaction = $model->dbConnection->beginTransaction();
      foreach($model->roles as $role)
        $role->delete();
      foreach($_POST['role'] as $roleName=>$val) {
        if($val != "1") continue; // Ignore any that are not "1"
        $role = new UserRole;
        $role->user_id = $model->id;
        $role->role    = $roleName;
        $role->save();
      }
      $transaction->commit(); // Commit transaction
      $this->flash('success', 'User information updated!');
      // Reload Model
      $model = $this->loadModel($id);
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	/**
	 * Lists all models.
	 */
	public function actionIndex() {
		$this->redirect(array('users/admin'));
    /**
    $dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		)); **/
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
