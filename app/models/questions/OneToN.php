<?php
class OneToN extends QuestionModel {
  
  public $maxValue = 5;
  
  public function rules() {
    return array(
      array('maxValue', 'numerical', 'integerOnly' => true, 'min' => 2, 'max' => 10),
    );
  }
  
  public function getQuestionName() {
    return "Rate 1 To N";
  }
  
  public function renderReportClass($group = null, $student = null) {
    $stats = $this->getBaseStatistics($group)
      ->select('AVG(value) AS Average, MIN(value) AS Minimum, MAX(value) AS Maximum, STDDEV(value) AS Deviation')
      ->queryRow();
    return array('stats' => $stats);
  }
}