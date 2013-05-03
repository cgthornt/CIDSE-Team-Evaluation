<li class="group-student">
  <?php
    echo Html::activeHiddenField($group, "[GR_{$group->id}][student_ids]{$student->id}", array('value' => $student->id));
  ?>
  <?php echo h($student->fullName); ?> &sdot; <?php echo h($student->email); ?>
  <i class="icon-remove remove-student-icon"></i>
</li>