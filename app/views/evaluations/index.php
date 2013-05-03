<?php
$this->breadcrumbs=array(
	'My Courses'=>array('index'),
	$course->name=>array('view','id'=>$course->id),
	'Evaluations',
);

$this->renderPartial('_menu', array('course' => $course));
echo $this->pageTitle('Course Evaluations', $course->name);
?>
<p>You have the following course evaluations.</p>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CActiveDataProvider($evaluations),
    'cssFile' => false,
    'columns' => array(
      'name',
      'due_at',
      'published',
      array(
        'type' => 'raw',
        'value' => function($data,$row) { return Html::link('View Evaluation', array('evaluations/view', 'id' => $data->id)); }
      )
    ),
));

?>