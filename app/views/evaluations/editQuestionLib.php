<?php
Html::globalCssFile('evaluation_editor');
Html::globalScriptFile('evaluation_editor');

$this->breadcrumbs=array(
	'My Courses'  => array('index'),
$course->name => array('view','id'=>$course->id),
	'Evaluations' => array('evaluations/index'),
  	'Edit Question Library'
);

$this->renderPartial('_menu', array('course' => $course));

echo Html::beginForm(array('modifyQueLib'),'post'
, array('class' => 'form','data-toggle'=>"buttons-checkbox"));
?>
<table class="table table-striped table-hover table-condensed"
	data-toggle="buttons-checkbox">
	<thead>
		<tr>
			<th>Instruction</th>
			<th>Title</th>
			<th>Type</th>
			<th>Evaluate Self</th>
			<th>Add</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ($allQuestions as $libQuestion)
	{
		echo '<tr>';
		echo '<td>'.$libQuestion['instructions'].'</td>';
		echo '<td>'.$libQuestion['title'].'</td>';
		echo '<td>'.$libQuestion['type'].'</td>';
		echo '<td>';
		if($libQuestion['allow_self'])
		echo 'true';
		else
		echo 'false';
		echo '</td>';
		echo '<td>';
		if($libQuestion['in_lib']==0)
		echo '<input type="checkbox" name="add'.$libQuestion['id'].'" value="'.$libQuestion['id'].'" />'.'</td>';
		echo '</td>';

		echo '<td>';
		if($libQuestion['in_lib']==1)
		echo '<input type="checkbox" name="delete'.$libQuestion['id'].'" value="'.$libQuestion['id'].'" />'.'</td>';
		echo '</td>';

		echo '</tr>';

	}?>

	</tbody>
</table>
<input type='Submit'
	class='btn' value='Submit'>
	<?php
	echo Html::endForm();
	?>