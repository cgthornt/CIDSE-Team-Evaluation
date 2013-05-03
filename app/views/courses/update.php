<?php
/* @var $this CoursesController */
/* @var $model Course */

$this->breadcrumbs=array(
	'My Courses'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->renderPartial('_menuView', array('course' => $model));

echo $this->pageTitle('Modify Course');

$this->renderPartial('_form', array('model'=>$model));

?>