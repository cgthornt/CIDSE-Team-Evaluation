<?php
/* @var $this CoursesController */
/* @var $model Course */

$this->breadcrumbs=array(
	'My Courses'=>array('index'),
	'Create',
);

$this->renderPartial('_menu');

echo $this->pageTitle('Create Course');
echo $this->renderPartial('_form', array('model'=>$model));

?>