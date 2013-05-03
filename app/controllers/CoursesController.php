<?php

class CoursesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

  
  
	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete archive', // we only allow deletion via POST request
      'requireCourse + enroll students importstudent'
      );
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
		array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
		),
		array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'enroll', 'enrollUserLookup', 'archive', 'importStudent'),
				'roles' => array('faculty', 'admin'),
		),
		array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles' => array('admin'),
		),
		array('deny',  // deny all users
				'users'=>array('*'),
		),
		);
	}


	public function actionEnrollUserLookup() {
		$student = CASUserIdentity::findOrCreateUserByUsername($_REQUEST['username']);
		$this->renderPartial('enroll/_student_item', array('student' => $student, 'group' => new CourseGroup));
		die(); // Die just so we don't show the SQL log in dev
	}


	/**
	 * Enrolls Students. Currently a simulation
	 */
	public function actionEnroll() {
		$course = $this->currentCourse;


		// Handle posting
		if(isset($_POST['CourseGroup'])) {

			// We *must* handle this in a transaction!
			$transaction = Course::model()->dbConnection->beginTransaction();

			// Catch any errors
			try {
				$groupIds = array();
				// Now we want to parse any POST data
				foreach($_POST['CourseGroup'] as $key => $val) {
					$gid = str_replace('GR_', '', $key);
					$groupIds[$gid] = $val;
				}

				// Delete 
				$onlyIds = array_keys($groupIds);
				Yii::app()->db->createCommand()->delete('course_groups',
					array('and', 'course_id = :course_id', array('not in', 'id', $onlyIds)),
					array(':course_id' => $course->id));
				
				
				foreach($groupIds as $key => $val) {
					$groupId = $key;
					$group = null;

					// Handle any existing groups.
					// Existing groups will always be numeric. Look up the group in the DB and see if it exists.
					if(is_numeric($groupId)) {
						$group = CourseGroup::model()->where(array('course_id' => $course->id))->findByPk($groupId);

						// If a group isn't found, then possibly a hacking attempt
						if($group == null) throw new Exception("Couldn't find group with ID `$groupId` four course ID `{$course->id}`");

						// Now update attributes
						$group->attributes = $val['attributes'];
					}


					// Handle any new groups.
					// Make sure it absolutely begins with "NEW"
					if(strpos($groupId, 'NEW') === 0) {
						$group = new CourseGroup;
						$group->course_id = $course->id;
						$group->attributes = $val['attributes'];
					}


					// Now save the group and assign students
					if($group != null) {
						$new = $group->isNewRecord;
						if(!$group->save()) throw new Exception('Unable to save group!');

						// Make sure we actually have student IDS before we do anything
						if(empty($val['student_ids'])) $val['student_ids'] = array();
						$group->enrollStudents($val['student_ids'], true);
					}
				}
				
				// Now if we're here, we can commit our transaction!
				$transaction->commit();
				$this->flash('success', 'Students Updated Successfully');
				
				// Redirect just to prevent refresh duplication submits
				return $this->redirect(array('enroll'));


			} catch(Exception $e) {
				$transaction->rollback();
				throw $e;
			}
		}


		$this->render('enroll', array(
      'students' => array(),
      'course'   => $course,
		));
	}


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {

		$course = $this->loadModel($id);
		$this->currentCourse = $id;

		// If we have a return URL, then redirect us!
		if(isset(Yii::app()->session['course_return_url'])) {
			$url = Yii::app()->session['course_return_url'];
			unset(Yii::app()->session['course_return_url']);
			return $this->redirect($url);
		}

		$this->render('view',array(
			'model'=> $course,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$model = new Course;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Course']))
		{
			$model->attributes=$_POST['Course'];

			// Wrap in a transaction
			$transaction = $model->dbConnection->beginTransaction();

			if($model->save()) {
					
				// Need to associate current user as having ownership
				// @todo: make this nicer!
				$command = Yii::app()->db->createCommand();
				$command->insert('course_professors', array(
				  'user_id' 	=> $this->user->id,
					'course_id' => $model->id,
					'user_type' => 'faculty',
				));
					
				$transaction->commit();
					
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id) {
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Course'])) {
			$model->attributes=$_POST['Course'];
			if($model->save())
			$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionArchive($id) {
		$course = $this->loadModel($id, true);
		$course->archived = true;
		$course->save();
		$this->flash('success', 'Course has been archived!');
		$this->redirect(array('index'));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) {
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex() {
		// If we have a return URL, then make sure to save it in the session. Otherwise, if the
		// session varaible exists but there is no return url, then delete the return URL so that
		// the user isn't wildly redirected.
		if(isset($_GET['return_url']))
		Yii::app()->session['course_return_url'] = $_GET['return_url'];
		elseif(!empty(Yii::app()->session['course_return_url'] ))
		unset(Yii::app()->session['course_return_url']);


		$this->render('index',array(
      'courses' => $this->loadModel(null, true, true)
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin() {
		$model=new Course('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Course']))
		$model->attributes=$_GET['Course'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 * @param boolean $onlyArchived if TRUE, limits to only archived results. If FALSE, then there is no limit
	 *    to results being archived.
	 * @param boolean $limitAdmin whether to limit admin results (see {@link Course::withAccess()})
	 * @return Course the specified course
	 */
	public function loadModel($id = null, $onlyArchived = false, $limitAdmin = false) {
		$model = Course::model()->withAccess($this->userModel, $limitAdmin);
		if($onlyArchived) $model = $model->available();

		// If we're searching without an ID, then don't limit by PK
		if($id == null) return $model;
		$model = $model->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='course-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionImportStudent()
	{
		$course = $this->currentCourse;
		$file_array=$_FILES['importStudentFile'];
		//$file_dir = "..\..\..\excel";
		if (is_uploaded_file($file_array["tmp_name"])) {
      // It's best to not move the uploaded file and instead just read from the temp file
			// if (move_uploaded_file($file_array["tmp_name"], "".$file_array["name"])) {
      $reader = new ExcelFileReader();
      $returnedValue = $reader->readFromExcel($file_array["tmp_name"], $file_array["name"]);
      // Deal with the returned value.
      if (empty($returnedValue['error'])) {
        $teamInfo = $returnedValue['normal'];
        //all user_name read from file is valide through ASU authentication
        foreach($teamInfo as $group_name=>$students) {
          // Attempt to find a group with the name of $groupId for the current course. If one does not exist,
          // then we should then create one.
          $group = CourseGroup::model()->where(array('course_id' => $course->id, 'name' => $group_name))->find();
          if(empty($group)) {
            $group = new CourseGroup();
            $group->course_id = $course->id;
            $group->name      = $group_name;
            // @TODO: nicer error handling
            if(!$group->save()) throw new Exception("Unable to create group!");
          }
          //get the newest coures group information
          $group = CourseGroup::model()->where(array('course_id' => $course->id, 'name' => $group_name))->find();
          // Now import students into the group
          // @todo
          foreach($students as $user_name=>$studentName) {
            // Find student by username. If not, repeat an identical solution to above.
            // Now add an entry linking
            
            //get a user_id according to user_name
            $user = User::model()->where(array('username'=>$user_name))->find();
            //search the user is a specific group
            $studentinGroup = CourseGroupStudents::model()->where(array('user_id'=>$user->id,'course_group_id'=>$group->id))->find();
            
            if (empty($studentinGroup)) {
              //this id is not in the group I want
              if(!$group->enrollUser($user)) throw new Exception("Unable to create group!");
            }
          }
        }
        
        ///has already finish all the enrollment go back to student enroolment view
        $this->flash('success', 'Students successfully enrolled');
        $this->redirect(array('enroll'));
      }else {
        //				there's error user_name read from excel file
        //				display all the invalide user_name to user, and remind them to check
				
        $this->render("importStudent",array('invalide_user_name'=>$returnedValue['error']),false);
      }

		}else {
			$this->flash("error", "Unable to read excel file! Please make sure the file is in the correct format and try again.");
			$this->redirect(array('enroll'));
			// something wrong with the uploaded file
			// $this->render("importStudent",array('invalide_upload_error'=>'upload file error'));
		}
	}

}
