<?php
/* @var $this UsersController */
/* @var $model User */


$this->breadcrumbs=array(
	'Users',
);

/*

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
); */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php echo $this->pageTitle('Users'); ?>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'username',
    'email',
		'first_name',
		'last_name',
		'middle_name',
      array(
        'type' => 'raw',
        'value' => function($data,$row) { return Html::link('View User', array('users/update', 'id' => $data->id), array('class' => 'btn btn-mini')); }
      )
	),
)); ?>
