<?php

// Default base Model class
class Model extends CActiveRecord {
  public function behaviors() {
    return array(
      'modeltimestampbehavior' => array('class' => 'ext.ModelTimestampBehavior'),
      'edatetimebehavior' => array('class' => 'ext.EDateTimeBehavior')
    );
  }
  
  
  /**
   * Allows for chaining. Clones this object. Example:
   *
   *    $model->where(array('col' => 'val', 'col2' => 'val'))->findAll();
   *    $model->where('something > :a', array(':a' => '123'))->findAll();
   * 
   */ 
  public function where($condition_or_array, $params = array(), $operator = 'AND') {
    // Might be dangerous!
    // $newObject = clone $this;
    $newObject = $this;
    $criteria = $newObject->getDbCriteria();
    
    // If the first element is an array, then assume a column criteria
    if(is_array($condition_or_array)) {
      $criteria->addColumnCondition($condition_or_array, $operator);
      
    // Otherwise, handle normally
    } else {
      $criteria->mergeWith(array(
        'condition' => $condition_or_array,
        'params'    => $params
      ));
    }
    
    return $newObject;
  }
  
  
}