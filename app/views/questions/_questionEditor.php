<li class="question-item" id="question-<?php echo $question->id; ?>" data-id="<?php echo $question->id; ?>">
  <div class="question-config modal hide fade" id="question-config-<?php echo $question->id; ?>">
    <?php $this->renderPartial('/questions/_questionEditorOptions', array('question' => $question, 'type' => $type, 'evaluation' => $evaluation)); ?>
  </div>
  <div class="question-text">
    <span>
      <?php echo empty($question->title) ? '<em>Edit Question to Add Title</em>' : $question->title; ?>
    </span>
    <i class="icon-remove remove-question-btn" rel="tooltip" title="Remove Question"></i>
    <i class="icon-cog config-question-btn" rel="tooltip" title="Configure Question"></i>
  </div>
  <div class="question-body">
    <?php if(!empty($question->instructions)): ?>
    <div class="question-instructions"><?php echo h($question->instructions, true); ?></div>
    <?php endif; ?>
    <div class="question-body-content question-body-<?php echo $type->identifier; ?>">
      <?php $this->renderPartial($type->bodyViewPath, array('question' => $question, 'type' => $type, 'evaluation' => $evaluation, 'users' => $users)); ?>
    </div>
  </div>
</li>
  