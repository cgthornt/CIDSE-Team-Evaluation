<h1>You have upcoming evaluations!</h1>
<p><?php echo $student->fullName; ?>,</p>
<p>You have some upcoming evaluations:</p>
<ul>
<?php for($i = 0; $i < count($evaluations); $i++): ?>
  <?php $eval = $evaluations[$i]; $group = $groups[$i]; ?>
  <li><?php echo $eval->name; ?> / <em>group <?php echo $group->name; ?></em> / <b>due at <?php echo $eval->due_at->toSystemTimezone()->format(Time::$FMT_DATETIME_COMPLETE); ?></b></li>
<?php endfor; ?>
</ul>
<p>
  Thanks,<br>
  The course evaluation team
</p>