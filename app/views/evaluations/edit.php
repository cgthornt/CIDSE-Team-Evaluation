<?php
$this->breadcrumbs=array(
	'My Courses'  => array('index'),
	$course->name => array('view','id'=>$course->id),
	'Evaluations' => array('evaluations/index'),
  'View Evaluation' => array('evaluations/view', 'id' => $evaluation->id),
  'Modify Evaluation'
);
$this->renderPartial('_menuDetail', array('evaluation' => $evaluation));
echo $this->pageTitle('Modify Evaluation', $course->name);
echo $this->renderPartial('_form', array('model'=>$evaluation));
?>