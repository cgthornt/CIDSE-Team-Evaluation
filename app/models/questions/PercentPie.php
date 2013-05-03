<?php
class PercentPie extends QuestionModel {
  public function getQuestionName() {
    return "Percentage of 100%";
  }

  
  public function validateValue($value, $responseSet, $question, $targetUserId) {
    // $parent = parent::validateValue($value, $responseSet, $question, $targetUserId);
    // if(is_string($parent)) return $parent;
    $total = 0;
    foreach($_POST['question'][$question->id] as $targetUser => $value)
      $total += (int) $value;
    // die(var_dump($total));
    if($total != 100)
      return "Total percentage for '{$question->title}' must be 100%!";
    return true;
  }
  
  
  
  
  public function renderReportClass($group = null, $student = null)  {
    $stats = $this->getBaseStatistics($group)
      ->select('AVG(value) AS Average, MIN(value) AS Minimum, MAX(value) AS Maximum,  STDDEV(value) AS Deviation')
      ->queryRow();
    return array('stats' => $stats);
  }
  

}


?>