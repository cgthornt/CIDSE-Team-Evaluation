<?php
$this->menu=array(
  array('label' => '&laquo; My Courses', 'url' => array('courses/index')),
  array('label'=> $course->name, 'url'=>array('courses/view', 'id' => $course->id)),
	array('label'=>'Students', 'url'=>array('courses/enroll')),
  array('label'=>'Evaluations', 'url'=>array('evaluations/index')),
  array('label'=>'Modify Course', 'url' => array('courses/update', 'id' => $course->id)),
);
?>