<?php echo $this->pageTitle('My Dashboard'); echo "<br/>"?>
<?php
$evaluations = $this->userModel->currentEvaluations(true); // Change to true once the first param actually works
if(!empty($evaluations)) {
	//echo '<h3>Hello '.$this->userModel->getFullName().'</h3>';
	?>

<h3>My Evaluations</h3>
<p>You have these evaluations you need to take. <strong>Timezones are in
	<?php echo Time::getSystemTimezone()->getName(); ?>!</strong></p>

<table class="table table-striped table-hover table-condensed">
	<thead>
		<tr>
			<th>Evaluation Name</th>
			<th>Due At</th>
			<th>Course</th>
			<th>Course Code</th>
			<th>Group Name</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($evaluations as $eval) : ?>
		<tr>
			<td><?php echo h($eval['evaluation_name']); ?></td>
			<td><?php
			$time = new Time($eval['due_at'], Time::getUtcTimezone());
			echo $time->toSystemTimezone()->format(Time::$FMT_DATETIME_SMALL);
      if($time < new Time)
        echo ' <span class="label label-important">Late</span>';
      
      ?>
			</td>
			<td><?php echo h($eval['course_name']); ?></td>
			<td><?php echo h($eval['course_code']); ?></td>
			<td><?php echo h($eval['group_name']); ?></td>
			<td><?php
			echo Html::link('Take Evaluation',
			array('evaluations/take', 'id' => $eval['evaluation_id'], 'group_id' => $eval['group_id']),
			array('class' => 'btn btn-small'));
			?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<hr>

		<?php } else { ?>
<h3>My Evaluations</h3>
<p>Horray! You don't have any evaluations that you need to take!</p>
<hr>

		<?php } ?>

<div class="row">
<div class="span7 well"><!-- change made my Jess -->
<h4>My Courses</h4>
<ul>
<?php
$courses = $this->userModel->currentCoursesAssociatedWithUser();
foreach($courses as $oneCourse)
{
	echo '<li>';
	echo Html::link($oneCourse['code'].' '.$oneCourse['name'],
	array('courses/view', 'id' => $oneCourse['id']));
	echo '</li>';
}
?>
</ul>
</div>
</div>

<?php
if($this->userModel->getRole_primary()=='faculty')
{
	echo '<div class="row">';
	echo '<div class="span7 well">';
	echo '<h3>Recent Evaluation</h3>';
	echo '<ul>';

	$allEvaluations = $this->userModel->allEvaluationsCreatedByCurrentUser();
	foreach($allEvaluations as $oneEvaluation)
	{
		//	echo $oneCourse
		echo '<li>';
		echo Html::link($oneEvaluation['course_code'].' '.$oneEvaluation['course_name'],
		array('courses/view', 'id' => $oneEvaluation['course_id']));
		echo Html::link(' '.$oneEvaluation['evaluation_name'],
		array('evaluations/view', 'id' => $oneEvaluation['evaluation_id']));

		//	echo '<li><a>'.$oneCourse['code'].' '.$oneCourse['name'].'</a></li>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
	echo '</div>';
	echo '<div class="row">';
	echo '<div class="span7 well">';
	echo '<h3>Short Cut</h3>';
	echo Html::link('Create Courses',
	array('courses/create'));
	echo '<br/>';
	echo Html::link('Edit Question Library',
	array('evaluations/editQuestionLib'));
	echo '</div>';
	echo '</div>';
}
?>


