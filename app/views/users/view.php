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
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
); */
?>

<?php echo $this->pageTitle($model->username, 'View User'); ?>
<p>
  Below are the attributes for this user.
</p>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'role_primary',
		'first_name',
		'last_name',
		'middle_name',
		'email',
		'profile_last_updated',
		'updated_at',
	),
)); ?>

<div class="form-actions">
  <?php echo Html::link('Cancel', array('users/admin'), array('class' => 'btn')); ?>
</div>
