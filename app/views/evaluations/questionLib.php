<?php
Html::globalCssFile('evaluation_editor');
Html::globalScriptFile('evaluation_editor');

$this->breadcrumbs=array(
	'My Courses'  => array('index'),
	$course->name => array('view','id'=>$course->id),
	'Evaluations' => array('evaluations/index'),
  $evaluation->name=> array('evaluations/view','id'=>$evaluation->id),
  'Import From Library'
);

$this->renderPartial('_menuDetail', array('evaluation' => $evaluation));

echo Html::beginForm(array('view&id='.$evaluation->id),'post'
, array('class' => 'form','data-toggle'=>"buttons-checkbox"));
?>
<table class="table table-striped table-hover table-condensed" data-toggle="buttons-checkbox">
 <thead>
    <tr>
      <th>Instruction</th>
      <th>Title</th>
      <th>Type</th>
      <th>Evaluate Self</th>
      <th>select</th>
    </tr>
  </thead>
  <tbody>
  <?php 
foreach ($questionLib as $libQuestion)
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
	echo '<td>'.'<input type="checkbox" name="select'.$libQuestion['id'].'" value="'.$libQuestion['id'].'" />'.'</td>';
	echo '</td>';
	echo '</tr>';
	
}?>

</tbody>
</table>
<input type='submit' class='btn' value='Import' >
<?php 
echo Html::endForm();
?>