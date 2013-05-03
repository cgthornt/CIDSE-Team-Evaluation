<?php
$this->menu=array(
  array('label' => 'My Courses', 'url' => array('courses/index')),
  array('label'=>'Create Course', 'url'=>array('courses/create')),
  array('label'=>'Administer Courses', 'url'=>array('courses/admin'), 'visible' => $this->user->role('admin')),
	
  // array('label' => 'Current Course', 'url' => array('courses/view')),
	
);
?>