<?php
  $form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=> true,
    'id' => "view-form-{$question->id}",
    'action' => array('evaluations/updateAjax', 'id' => $evaluation->id),
    'htmlOptions' => array('data-id' => $question->id, 'class' => 'update-questions-form'))); ?>
  <div class="modal-header"><h3>Configure Question</h3></div>
   <div class="modal-body">
     <?php echo $form->hiddenField($question, 'id'); ?>
     <?php echo $form->errorSummary($question); ?>
     <div class="question-config-header">
       <div class="item">
         <?php
          echo $form->label($question, "title") . '<br>';
          echo $form->textField($question, "title", array('placeholder' => 'Question Title'));
          echo $form->error($question, "title");
         ?>
       </div>
       <div class="item nob" style="vertical-align: middle;padding-top:20px;">
         <?php
           echo $form->checkBox($question, "allow_self");
           echo $form->label($question, "allow_self");
         ?>
       </div>
       <div class="item newline" style="margin-top:10px">
        <?php
          echo $form->label($question, 'instructions') . '<br>';
          echo $form->textArea($question, 'instructions');
          echo $form->error($question, "instructions");  
        ?>
       </div>
     </div>
     <div class="top-seperator"></div>
     <div class="question-config-main">
       <?php $this->renderPartial($type->optionViewPath, array('model' => $type, 'question' => $question, 'form' => $form)); ?>
     </div>
     <div style="clear:both"></div>
   </div>
   <div class="modal-footer">
     <a href="#" class="btn editor-close-btn">Cancel</a>
     <?php echo Html::submitButton("Save", array('class' => 'btn btn-primary')); ?>
   </div>
<?php $this->endWidget(); ?>