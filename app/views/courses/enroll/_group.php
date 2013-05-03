
<div class="box box-collapsable student-group-box" id="group-GR_<?php echo $group->id; ?>">
  <div class="box-title">
    <i class="box-collapse-icon icon-font"></i>
    <?php echo $form->textField($group, "[GR_{$group->id}][attributes]name", array('placeholder' => 'Team Name')) ?>
    <i class="icon-trash remove-group-btn"></i>
  </div>
  <ul class="box-content group-accept" data-group-id="<?php echo $group->id; ?>">
    <?php foreach($group->enrolled()->findAll() as $student) echo showStudent($student, $group, $form); ?>
  </ul>
</div>