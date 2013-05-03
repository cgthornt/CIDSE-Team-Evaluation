<?php
  
$this->breadcrumbs=array(
	'My Courses'  => array('index'),
	$evaluation->course->name => array('view','id'=>$evaluation->course->id),
	'Evaluations' => array('evaluations/index'),
  $evaluation->name => array('evaluations/view', 'id' => $evaluation->id),
  'Report'
);

$this->renderPartial('/evaluations/_menuDetail', array('evaluation' => $evaluation));
  
?>


<?php /** Non-PDF Export **/ ?>
<?php if(!$pdf): ?>
  
  <?php echo $this->pageTitle("View Report", $evaluation->name); ?>

  <?php $form = $this->beginWidget('CActiveForm', array('method' => 'GET', 'action' => array('reports/index'))); ?>
    <div class="row">
      <div class="span12">
        <?php
          $groups = $evaluation->course->groups;
          $list = Html::listData($groups, 'id', 'name');
          echo Html::dropDownList('group_select', $group, $list,
              array('empty' => 'All Teams', 'style' => 'padding: 0;line-height:normal;display:inline-block;margin-top:9px'));
        ?>
        <input type="submit" class="btn btn-small" value="Change Team" style="margin-top:0px">
        <?php // echo Html::link('Export to PDF', array('reports/pdf', 'id' => $evaluation->id), array('class' => 'btn btn-link')); ?>
        <?php echo Html::hiddenField('evaluation_id', $evaluation->id); ?>
        
      </div>
    </div>
  <?php $this->endWidget(); ?>


  <?php if($group != null) : ?>
  <div class="alert alert-info">
    Viewing team <?php echo $group->name; ?>
  </div>
  <?php endif; ?>
  
  
  
  <hr>
<?php endif; ?>

<?php foreach($evaluation->questions as $question) : ?>
<h3><?php echo $question->title; ?></h3>


<?php
  echo h($question->instructions, true);
  $this->renderPartial(
    $question->questionTypeModel->reportViewPath,
    array_merge(
      $question->questionTypeModel->renderReportClass($group),
      array('group' => $group, 'question' => $question, 'pdf' => $pdf)
    ));
?>


<hr>

<?php endforeach; ?>