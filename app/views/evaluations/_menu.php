<?php
$this->menu=array(
  array('label' => '&laquo; ' . $course->name, 'url' => array('courses/view', 'id' => $course->id)),
  array('label' => 'Course Evaluations', 'url' => array('evaluations/index')),
  array('label' => 'Create Evalaution', 'url' => array('evaluations/create')),
  array('label' => 'Edit Question Library', 'url' => array('evaluations/editQuestionLib'))
);
?>