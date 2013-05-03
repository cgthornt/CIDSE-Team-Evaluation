<li class="question-item" id="question-<?php echo $question->id; ?>" data-id="<?php echo $question->id; ?>">
  <div class="question-text">
    <span>
      <?php echo h($question->title) ?>
    </span>
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
  