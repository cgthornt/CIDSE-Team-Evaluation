<?php
/* @var $this CoursesController */
/* @var $model Course */
/* @var $form CActiveForm */
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'course-form',
  'htmlOptions' => array('class' => 'form form-horizontal'),
	'enableAjaxValidation'=> true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="control-group">
		<?php echo $form->labelEx($model,'code', array('class' => 'control-label')); ?>
    <div class="controls">
		<?php echo $form->textField($model,'code',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'code'); ?>
    </div>
  </div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'name', array('class' => 'control-label')); ?>
    <div class="controls">
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
    </div>
  </div>
  
	<div class="control-group">
		<?php echo $form->labelEx($model,'description', array('class' => 'control-label')); ?>
    <div class="controls">
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
    </div>
  </div>

	<div class="row buttons form-actions">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
    <?php
      if($model->isNewRecord)
        echo Html::link('Cancel', array('courses/index'), array('class' => 'btn'));
      else
        echo Html::link('Cancel', array('courses/view', 'id' => $model->id), array('class' => 'btn'));
    
     if(!$model->isNewRecord)
      echo Html::link('Archive Course', '#archive', array(
          'submit' => array('courses/archive', 'id' => $model->id),
          'csrf'   => true,
          'class'  => 'btn btn-danger',
          'style'  => 'float: right',
          'confirm' => 'Archiving a course will make this course unavailable to students and remove it from your \'My Courses\' view; continue?'
        ));
    ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->