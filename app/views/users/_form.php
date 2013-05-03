<?php
/* @var $this UsersController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">
  <div class="alert alert-info">
    Most user information is provided by ASU services and cannot be changed.
  </div>

  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'user-form',
    'enableAjaxValidation'=>false,
  )); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
    <?php echo $model->username; ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'first_name'); ?>
		<?php echo $model->first_name; ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $model->last_name; ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'middle_name'); ?>
		<?php echo $model->last_name; ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $model->email; ?>
	</div>

  <h3>Roles</h3>
  <p>By default, all users are granted the role of "student" unless a higher role is granted.</p>
  
  <div class="row">
    <!-- TODO -->
    <div style="margin-left: 80px;vertical-align: middle">
    <input type="hidden" name="role[fake]" val="0"> <!-- Just a hack to get foreach() working :) -->
    
    <?php
      // We can be a little relaxed about security here because this is admin only. We will directly add these roles 
      echo Html::checkBox('role[faculty]', $model->hasRole('faculty'), array('style' => 'line-height:normal;vertical-align:middle;margin-top:0'));
    ?> Faculty <br>
    
        <?php
      // We can be a little relaxed about security here because this is admin only. We will directly add these roles 
      echo Html::checkBox('role[admin]', $model->hasRole('admin'), array('style' => 'line-height:normal;vertical-align:middle;margin-top:0'));
    ?> Administrator
   
    </div>
  </div>
  
  
	<div class="row buttons">
    <?php echo Html::link('Cancel', array('users/admin'), array('class' => 'btn')); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
  
  

  <?php $this->endWidget(); ?>

</div><!-- form -->