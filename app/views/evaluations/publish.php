<?php
$this->breadcrumbs=array(
	'My Courses'  => array('index'),
	$course->name => array('view','id'=>$course->id),
	'Evaluations' => array('evaluations/index'),
  'View Evaluation' => array('evaluations/view', 'id' => $evaluation->id),
  'Publish Evaluation'
);
$this->renderPartial('_menuDetail', array('evaluation' => $evaluation));
echo $this->pageTitle('Publish Evaluation', $evaluation->name);
?>
<div class="well" style="text-align: center;margin-top: 20px">
  <h3>Are you sure you want to publish this evaluation?</h3>
</div>
<p>
  Once you publish this evaluation, students will be able to take the evaluation.
</p>
<p>
  <strong>You cannot change evaluation questions after it has been published.</strong>
</p>


<?php $form = $this->beginWidget('CActiveForm'); ?>
<input type="hidden" name="publish_it" value="1">
<div class="form-actions">
  <?php echo Html::link('Cancel', array('evaluations/view', 'id' => $evaluation->id), array('class' => 'btn')); ?>
  <input type="submit" class="btn btn-primary" value="Publish">
</div>
<?php $this->endWidget(); ?>