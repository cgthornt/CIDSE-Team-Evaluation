<?php
abstract class QuestionModel extends CFormModel {
  
  
  
  protected $question;
  
  protected $_form, $_parentForm;
  
  
  public function __construct(EvaluationQuestion $question) {
    $this->question = $question;
    parent::__construct();
  }
  
  
  public abstract function getQuestionName();
  
  
  public function getIdentifier() {
    return get_class($this);
  }
  
  public function getConfigModelPath() {
    return "application.views.questions.configs." . $this->identifier;
  }
  
  public function attributeLabel($attribute) {
    return "EvaluationQuestion[options][{$attribute}]";
  }
  
  public function getOptionViewPath() {
    return "/questions/configs/" . $this->identifier;
  }
  
  public function getBodyViewPath() {
    return "/questions/body/" . $this->identifier;
  }
  
  public function getReportViewPath($type = 'class') {
    return "/questions/reports/$type/" . $this->identifier;
  }
  
  /**
   * Should return an array of local variables!
   */
  public function renderReportClass($groupId = nil, $studentId = nil) {
    // Override me
  }
  
  /**
   * Default functionality
   */
  public function createEvaluationAnswer($responseSet, $question, $targetUserId, $value) {
    $response = new EvaluationResponse;
    $response->target_user_id = $targetUserId;
    $response->evaluation_question_id = $question->id;
    $response->value = $value;
    $response->evaluation_response_set_id = $responseSet->id;
    return $response;
  }
  
  /**
   * Validates a given value
   * @param mixed $value the value to validate
   * @return TRUE, if everything was successful, STRING for an error message.
   */
  public function validateValue($value, $responseSet, $question, $targetUserId) {
    if(empty($value)) {
      $student = User::model()->findByPk($targetUserId);
      return "Question '{$question->title}' for teammate '{$student->fullName}' cannot contain a blank response";
    }
    return true;
  }
  
  
  
  /**
   * Gets a query builder for base statistics
   *  r - `evaluation_responses`
   *  q - `evaluation_questions`
   *  s - `evaluation_response_sets`
   */
  protected function getBaseStatistics($group = null) {
   $cmd =  Yii::app()->db->createCommand()
    ->from('evaluation_responses r')
    ->leftJoin('evaluation_questions q', 'q.id = r.evaluation_question_id')
    ->leftJoin('evaluation_response_sets s', 's.id = r.evaluation_response_set_id')
    // ->leftJoin('evaluations e', 'r.evaluation_id = e.id')
    ->where('q.id = :qid', array(':qid' => $this->question->id));
    
    if($group != null)
      $cmd->andWhere('s.course_group_id = :cg_id', array(':cg_id' => $group->id));
      
    return $cmd;
  }
}