<?php
YiiBase::import("application.models.questions.*");

/**
 * Handles evaluations
 *
 * @todo: don't allow updating of published evaluations!
 */
class EvaluationsController extends Controller {

	/**
	 * Takes an evaluation
	 * @param int $id the ID of the evaluation
	 *
	 * @TODO: LIMIT ONLY TO PUBLISHED EVALUATIONS!
	 */
	public function actionTake($id) {
		$evaluation = Evaluation::model()
		->with('course.groups')
		->where('t.id = :evaluation_id AND groups.id = :group_id', array(':evaluation_id' => $id, ':group_id' => $_GET['group_id']))
		->find();

		// Make sure the evaluation exists
		if($evaluation == null)
		throw new CHttpException(404, "Unable to find the specified evaluation");

		$group = CourseGroup::model()->where(array('id' => $_GET['group_id']))->find();
		if($group == null)
		throw new CHttpException(404, "Unable to find the specified group");

		// Make sure we ware actually enrolled in this group!
		if($group->enrolled()->where(array('user_id' => $this->user->id))->count() == 0)
		throw new CHttpException(403, "You are not enrolled in this group");

		if($evaluation->userTookEvaluation($this->userModel, $group)) {
			$this->flash('alert', 'You have already taken this evaluation');
			return $this->redirect(array('welcome/index'));
		}


		// Get users in group
		$students = $group->enrolled()->findAll();

		// Handle POST data
		if(isset($_POST['question'])) {
			$result = $this->handleSubmitEvaluation($evaluation, $group, $this->user->id);
			if(is_string($result)) {
				$this->flash('error', $result);
			} else {
				$this->flash('success', 'Thank you for completing the evaluation!');
				return $this->redirect(array('welcome/index'));
			}
		}


		$this->render('take', array('evaluation' => $evaluation, 'group' => $group, 'students' => $students));
	}


	protected function handleSubmitEvaluation($evaluation, $group, $userId) {

		// Create initial response set
		$responseSet = new EvaluationResponseSet;
		$responseSet->evaluation_id   = $evaluation->id;
		$responseSet->course_group_id = $group->id;
		$responseSet->user_id         = $userId;
		$responseSet->completed       = true;
		$responseSet->completed_at    = new Time;

		// Wrap in transaction
		$transaction = $responseSet->dbConnection->beginTransaction();

		// Attempt to save initial response set
		if(!$responseSet->save()) throw new Exception('Unable to save response set!');

		foreach($_POST['question'] as $questionId=>$studentIds) {
			$question = EvaluationQuestion::model()->where(array('id' => $questionId, 'evaluation_id' => $evaluation->id))->find();
			if($question == null) throw new Exception("Unable to find question ID of '$questionId' with evaluation ID of '{$evaluation->id}'!");

			foreach($studentIds as $studentId=>$value) {
				// Make sure the student ID is actually in the group
				if($group->enrolled()->where(array('t.id' => $studentId))->count() == 0)
				throw new Exception("Target student ID '$studentId' is *not* currently enrolled in group with ID '{$group->id}'!");

				// Ensure the response value is valid
				$validation = $question->questionTypeModel->validateValue($value, $responseSet, $question, $studentId);
				if(is_string($validation)) {
					$transaction->rollback(); // Don't forget to rollback!
					return $validation;
				}

				// Finally, create a new response and attempt to save it
				$response = $question->questionTypeModel->createEvaluationAnswer($responseSet, $question, $studentId, $value);
				if(!$response->save()) throw new Exception("Unable to save response: " . print_r($response->errors, true));
			}
		}

		// If we're here, then everything went dandy and we can submit it!
		$transaction->commit();
		return true;
	}

	/**
	 * Views all possible evaluations for a faculty
	 */
	public function actionIndex() {
		$this->render('index', array(
      'course' => $this->currentCourse,
      'evaluations' => Evaluation::model()->where('course_id = :cid', array(':cid' => $this->currentCourse->id))));
	}

	public function actionUpdateAjax($id) {
		$evaluation = $this->loadModel(null, true);
		// todo: validate question
		$question = EvaluationQuestion::model()->where(array('id' => $_POST['EvaluationQuestion']['id'], 'evaluation_id' => $evaluation->id))->find();
		$question->attributes = $_POST['EvaluationQuestion'];
		if($question->save())
		echo "OK";
		else
		$this->renderPartial('/questions/_questionEditorOptions', array('question' => $question, 'type' => $question->questionTypeModel, 'evaluation' => $evaluation, 'users' => Evaluation::sampleGroupData($this->user->model)));
	}

	public function actionUpdateOrderAjax($id) {
		$evaluation = $this->loadModel(null, true);
		foreach($_POST['order'] as $index=>$id) {
			$question = EvaluationQuestion::model()->where(array('id' => $id, 'evaluation_id' => $evaluation->id))->find();
			if(!empty($question)) {
				$question->order = $index;
				$question->save();
			}
		}
	}

	public function actionQuestionCreateAjax($id) {
		$evaluation = $this->loadModel(null, true);
		$question = new EvaluationQuestion;
		$question->title = "A Question";
		$question->evaluation_id = $evaluation->id;
		$question->type = $_POST['questionType'];
		$question->instructions = "Click the gear icon to modify this question";
		if($question->save())
		$this->renderPartial('/questions/_questionEditor', array('question' => $question, 'type' => $question->questionTypeModel, 'evaluation' => $evaluation, 'users' => Evaluation::sampleGroupData($this->user->model)));
		else
		var_dump($question->errors);
	}

	public function actionLoadQuestionAjax($id) {
		$evaluation = $this->loadModel();
		$question = EvaluationQuestion::model()->where(array('id' => $_GET['question_id'], 'evaluation_id' => $evaluation->id))->find();
		$this->renderPartial('/questions/_questionEditor', array('question' => $question, 'type' => $question->questionTypeModel, 'evaluation' => $evaluation, 'users' => Evaluation::sampleGroupData($this->user->model)));
	}

	public function actionDeleteQuestionAjax($id) {
		$evaluation = $this->loadModel(null, true);
		$question = EvaluationQuestion::model()->where(array('id' => $_GET['question_id'], 'evaluation_id' => $evaluation->id))->find();
		$question->delete();
	}

	public function actionCreate() {
		$evaluation = new Evaluation;
		$evaluation->course_id = $this->currentCourse->id;

		$this->performAjaxValidation($evaluation);

		if(isset($_POST['Evaluation'])) {
			$evaluation->attributes = $_POST['Evaluation'];
			if($evaluation->save()) {
				$this->flash('success', 'Evaluation created successfully');
				return $this->redirect(array('evaluations/view', 'id' => $evaluation->id));
			}
		}

		$this->render('create', array(
      'course' => $this->currentCourse,
      'evaluation' => $evaluation
		));
	}

	public function actionView($id) {
		$this->importQuestions($id);
		$evaluation = $this->loadModel();
		$this->performAjaxQuestionValidation();
		$this->render('view', array(
      'course' => $this->currentCourse,
      'evaluation' => $evaluation));
	}

	public function modifyQueLib()
	{
		$addedIntoLib = array();
		$deletedFromLib = array();
		foreach($_POST as $id=>$value)
		{
			if( substr_compare($id,"add",0,3,false)==0)
			{
				$addedIntoLib[] = $value;
			}elseif (substr_compare($id,"delete",0,6,false)==0)
			{
				$deletedFromLib[]  =$value;
			}
		}
		EvaluationQuestion::updataQueLib($addedIntoLib, $deletedFromLib);
	}
	public function importQuestions($evaluation_id)
	{
		$importedQueId = array();
		foreach($_POST as $id=>$value)
		{
			if( substr_compare($id,"select",0,6,false)==0)
			{
				$importedQueId[] = $value;
			}
		}
		EvaluationQuestion::importQueToDb($importedQueId,$evaluation_id);
	}


	public function actionHistoryEval($id) {
		$evaluation = $this->loadModel();
		$allQuestions = EvaluationQuestion::loadALLQuestions();
		$this->render('historyEval', array(
      'course' => $this->currentCourse,
      'evaluation' => $evaluation,
    'allQuestions'=>$allQuestions));
	}

	public function actionEditQuestionLib()
	{
		$allQuestions = EvaluationQuestion::loadALLQuestions();
		$questionLib = $this->loadQuestionLib();
		$this->render('editQuestionLib', array(
      	'course' => $this->currentCourse,
    	'allQuestions' => $allQuestions));
	}

	public function actionModifyQueLib()
	{
		$this->modifyQueLib();
		$this->render('index', array(
      'course' => $this->currentCourse,
      'evaluations' => Evaluation::model()->where('course_id = :cid', array(':cid' => $this->currentCourse->id))));
	}

	public function actionQuestionLib($id) {
		$evaluation = $this->loadModel();
		$questionLib = EvaluationQuestion::loadQustionLib();
		$this->render('questionLib', array(
      	'course' => $this->currentCourse,
      	'evaluation' => $evaluation,
    	'questionLib' => $questionLib));
	}


	public function actionPublish($id) {
		$evaluation = $this->loadModel();
    
    // Confirm publish
    if(isset($_POST['publish_it'])) {
      $evaluation->published = true;
      $evaluation->published_at = new Time();
      $evaluation->save();
      $this->flash('success', 'Evaluation has been published!');
      return $this->redirect(array('evaluations/view', 'id' => $evaluation->id));
    }
    
		$this->render('publish', array(
      'course' => $this->currentCourse,
      'evaluation' => $evaluation));
	}

	public function actionEdit($id) {
		$evaluation = $this->loadModel();
		$this->performAjaxValidation($evaluation);
		if(isset($_POST['Evaluation'])) {
			$evaluation->attributes = $_POST['Evaluation'];
			if($evaluation->save())
			$this->flash('success', 'Evaluation updated successfully');
		}
		$this->render('edit', array(
      'course' => $this->currentCourse,
      'evaluation' => $evaluation));
	}


	protected function loadModel($id = null, $onlyPublished = false) {
		if($id == null) $id = $_GET['id'];
		$evaluation = Evaluation::model()->where('course_id = ? AND id = ?', array($this->currentCourse->id, $id))->find();
		if($evaluation == null) throw new CHttpException(404,'The requested page does not exist.');
		if($onlyPublished && $evaluation->published) throw new Exception("You cannot select a published evaluation");
    return $evaluation;
	}

	protected  function loadQuestionLib()
	{
		$questionLib = EvaluationQuestion::loadQustionLib();
		return $questionLib;
	}

	public function accessRules() {
		return array(
		array('allow', // allow admin/ faculty for certain roles
				'actions'=>array(
					'index', 'create', 'view', 'updateAjax', 'loadQuestionAjax', 'questionCreateAjax',
					'deleteQuestionAjax', 'updateOrderAjax', 'edit', 'publish','questionLib'
					,'historyEval','editQuestionLib','modifyQueLib'),
				'roles' => array('faculty', 'admin'),
					),
					array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles' => array('admin'),
					),
					array('allow', // Allow any authenticated users to 'take' an evaluation
      'actions' => array('take'),
      'users'   => array('@'), 
					),
					array('deny',  // deny all users
				'users'=>array('*'),
					),
					);
	}


	// Filters
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete questionCreateAjax deleteQuestionAjax updateOrderAjax', // we only allow deletion via POST request
      'requireCourse - take'   // Allows us to get the current course by "$this->currentCourse"
		);
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='course-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function performAjaxQuestionValidation() {
		if(isset($_POST['ajax']) && $_POST['ajax']==='view-form') {
			$model = EvaluationQuestion::model()->where(array('id' => $_POST['EvaluationQuestion']['id']))->find();
			$model->attributes = $_POST['EvaluationQuestion'];
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


}