<?php
/* @var $this CoursesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs =array(
	'My Courses',
);

$this->renderPartial('_menu');

?>
<?php echo $this->pageTitle('My Courses');

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CActiveDataProvider($courses),
    'cssFile' => false,
    'columns' => array(
      'code',
      'name',
      array(
        'name' => 'professors',
        'value' => function($data,$row) {
          $names = array();
          foreach($data->professors as $p) $names[] = $p->fullName;
          return implode(', ', $names);
        }
      ),
      array(
        'type' => 'raw',
        'value' => function($data,$row) { return Html::link('View Course', array('courses/view', 'id' => $data->id), array('class' => 'btn btn-mini')); }
      )
    ),
));

?>