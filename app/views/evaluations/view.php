<?php

Html::globalCssFile('evaluation_editor');

// Don't want dragging if published!
if(!$evaluation->published)
  Html::globalScriptFile('evaluation_editor');

$this->breadcrumbs=array(
	'My Courses'  => array('index'),
	$course->name => array('view','id'=>$course->id),
	'Evaluations' => array('evaluations/index'),
  $evaluation->name
);

$this->renderPartial('_menuDetail', array('evaluation' => $evaluation));
echo $this->pageTitle('Evaluation Details', $evaluation->name);

function addQuestionType($type) {
  Html::globalLessFile('questions/' . $type->identifier);
  Html::globalScriptFile('questions/' . $type->identifier);
  return '<div class="possible-question" data-type="' . $type->identifier . '" rel="tooltip" title="Click to Add Question">' . $type->questionName . '</div>';
}

?>

<!-- Some useful attributes for AJAX requests -->
<div id="evaluation-id"
  data-id="<?php echo $evaluation->id ?>"
  data-view-url="<?php echo Html::normalizeUrl(array('evaluations/loadQuestionAjax', 'id' => $evaluation->id, 'question_id' => 'QUESTION_ID')); ?>"
  data-new-url="<?php echo Html::normalizeUrl(array('evaluations/questionCreateAjax', 'id' => $evaluation->id)); ?>"
  data-delete-url="<?php echo Html::normalizeUrl(array('evaluations/deleteQuestionAjax', 'id' => $evaluation->id, 'question_id' => 'QUESTION_ID')); ?>"
  data-sort-url="<?php echo Html::normalizeUrl(array('evaluations/updateOrderAjax', 'id' => $evaluation->id)); ?>"
  style="display:none"></div>

<!-- Placeholder for the loading dialog -->
<div id="loading-placeholder" style="display: none">
  <div class="loading">
    <?php echo Html::image('loading.gif', 'Loading'); ?>
    <p>Loading, please wait...</p>
  </div>
</div>

<p>Click questions on the right to add a new question</p>

<div class="row">
  
  <?php if(!$evaluation->published): ?>
    <div class="span12">
      <div class="alert alert-info" id="status-holder">Changes are updated automatically</div>
    </div>
  <?php endif; ?>
  
  <!-- Questions -->
  <div class="span4" style="float:right;padding-top:2px;width:200px">
    
    <?php if(!$evaluation->published) { ?>
      <div class="well well-small" style="margin-bottom:7px;">
      <h3>Available Questions</h3>
      <p>Click to add a new question</p>
      <?php
        echo addQuestionType(new OneToN(new EvaluationQuestion));
        echo addQuestionType(new PercentPie(new EvaluationQuestion));
      ?>
      </div>
      <p style="line-height: normal;font-size:11px">
        The area to the left is a live preview; what you see is similar to what
        students will see.
        <br><br>
        
      <div class="well well-small" style="margin-bottom:7px;">
      <h3>Import Questions</h3>
      <p>Choose Source</p>
  
        <?php echo Html::link("Question Library",array('evaluations/questionLib', 'id' => $evaluation->id));?>
      <br/>
        <?php echo Html::link("History Evaluation",array('evaluations/historyEval', 'id' => $evaluation->id));?>
  
        </div>
        <strong>Changes are saved automatically!</strong>
      </p>
    <?php } else {
      // Add question types anyway because we need to load assets
      addQuestionType(new OneToN(new EvaluationQuestion));
      addQuestionType(new PercentPie(new EvaluationQuestion));
    } ?>
  </div>

  
  
  <!-- Holds Questions -->
  <div class="span6" id="question-holder">
    <ul id="question-list">
      <?php
        foreach($evaluation->questions as $q) {
          $view = $evaluation->published ? '/questions/_questionView' : '/questions/_questionEditor';
          $this->renderPartial($view, array(
            'question'   => $q,
            'type'       => $q->questionTypeModel,
            'evaluation' => $evaluation,
            'users'      => Evaluation::sampleGroupData($this->user->model)));
        }
      ?>
    </ul>
  </div>
</div>




<?php if(!$evaluation->isPublished) : ?>
<div class="form-actions">
  <!-- 
  <?php echo Html::submitButton('Save Evaluation', array('class' => 'btn btn-primary')); ?> -->
</div>
<?php endif ?>


