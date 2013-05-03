<?php
/* @var $this UsersController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('users/admin'),
	$model->username,
);

/*
$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage User', 'url'=>array('admin')),
); */
?>

<?php echo $this->pageTitle($model->username, "Edit User"); ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>