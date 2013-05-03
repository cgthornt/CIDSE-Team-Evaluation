<div class="row">
  <div class="span12">
    <?php $this->renderPartial('/questions/reports/basicStatistics', array('statistics' => $stats)); ?>
    <?php if($group != null)
        $this->renderPartial('/questions/reports/basicAnswers', array('group' => $group, 'question' => $question, 'pdf' => $pdf));
    ?>
  </div>
</div>