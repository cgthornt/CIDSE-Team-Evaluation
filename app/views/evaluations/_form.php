<?php Yii::import('ext.CJuiDateTimePicker.CJuiDateTimePicker'); ?>
<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'course-form',
  'htmlOptions' => array('class' => 'form form-horizontal'),
	'enableAjaxValidation'=> true,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php echo $form->errorSummary($model); ?>

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
  
  
  <!-- TODO TODO TODO : Enable Due At -->
	<div class="control-group">
		<?php echo $form->labelEx($model,'due_at', array('class' => 'control-label')); ?>
    <div class="controls">
		<?php
		$time = (is_string($model->due_at) || $model->due_at == null) ? new Time($model->due_at, Time::getUtcTimezone()) : $model->due_at;
		$time->toSystemTimezone();
		$model->due_at = $time->toLocalDbDateTime();
    $this->widget('CJuiDateTimePicker',array(
        'model'=>$model, //Model object
        'attribute'=>'due_at', //attribute name
        'mode'=>'datetime', //use "time","date" or "datetime" (default)
        'options'=>array(
					'dateFormat' => 'yy-mm-dd',
					'timeFormat' => 'hh:mm:ss tt',
					// 'ampm' => true,
				),
				'language' => '',
		));
		
		?>
		<?php echo $form->error($model,'due_at'); ?>
    </div>
  </div>
  <!-- -->
  
	<div class="row buttons form-actions">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
    <?php
      if($model->isNewRecord)
        echo Html::link('Cancel', array('evaluations/index'), array('class' => 'btn'));
      else
        echo Html::link('Cancel', array('evaluations/view', 'id' => $model->id), array('class' => 'btn'));
    ?>
	</div>

<?php $this->endWidget(); ?>
</div>