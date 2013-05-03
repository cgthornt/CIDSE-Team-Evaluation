<?php
$this->breadcrumbs=array(
	'My Courses'  => array('index'),
	$course->name => array('view','id'=>$course->id),
	'Evaluations' => array('evaluations/index'),
  'Create Evaluation'
);
$this->renderPartial('_menu', array('course' => $course));
echo $this->pageTitle('Create Evaluation', $course->name);
?>
<div class="alert alert-info">
  You will be able to add questions on the next screen.
</div>
<?php echo $this->renderPartial('_form', array('model'=>$evaluation)); ?>