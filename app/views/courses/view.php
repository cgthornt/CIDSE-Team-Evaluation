<?php
/* @var $this CoursesController */
/* @var $model Course */

$this->breadcrumbs=array(
	'My Courses'=>array('index'),
	$model->name,
);

$this->renderPartial('_menuView', array('course' => $model));
?>

<?php echo $this->pageTitle($model->code . ' - '. $model->name); ?>
<?php if($model->archived) : ?>
<p><span class="badge badge-warning">Archived</span></p>
<?php endif ?>
<p>
<?php echo h($model->description, true); ?>
</p>

<div class="row-fluid">
  <div class="span6">
    <h2>Upcoming Evaluations</h2>
  </div>
</div>



