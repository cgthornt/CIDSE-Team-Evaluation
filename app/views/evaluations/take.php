<?php
// Load eval editor CSS
Html::globalCssFile('evaluation_editor');

// Now we need to load specifics
foreach(EvaluationQuestion::$QUESTION_TYPES as $type) {
  Html::globalLessFile('questions/' . $type);
  Html::globalScriptFile('questions/' . $type);
}

echo $this->pageTitle($evaluation->name, "Take Evaluation");
echo h($evaluation->description, true);

$form = $this->beginWidget('CActiveForm');
?>
<div class="row">
  <div class="span6" id="question-holder">
    <ul id="question-list">
    <?php foreach($evaluation->questions as $question) {
      $this->renderPartial('/questions/_questionView', array(
        'question'   => $question,
        'type'       => $question->questionTypeModel,
        'evaluation' => $evaluation,
        'users'      => $students));
    
    } ?>
    </ul>
  </div>
</div>

<div class="form-actions">
  <input type="submit" class="btn btn-primary" value="Submit Evaluation">
</div>

<?php $this->endWidget(); ?>