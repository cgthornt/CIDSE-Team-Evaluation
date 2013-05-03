<?php
/**
 * I really appologize for this huge mess. It's near the end of the capstone course and I'm tired. Too much work to do a good job...
 *
 */

?>
<div class="row">
  <div class="span12">
    <div class="tabbable tabs-left">
      <ul class="nav nav-tabs">
        <?php
          $first = true;
          $averages = array();
          foreach($group->students as $s) {
            
            // Really crappy average utility - please forgive me.
            $count = 0; $sum = 0;
            $avg = EvaluationResponse::model()
              ->with(array('response_sets.user'))
              ->where('t.target_user_id = ? AND t.evaluation_question_id = ? AND response_sets.course_group_id = ?', array($s->id, $question->id, $group->id));
            
            foreach($avg->findAll() as $a) {
              $sum += $a->value;
              $count++;
            }
            
            $averages[$s->id] = $count == 0 ? 0 : $sum/$count;
            
            // Only show tabs if using PDF. Exporting to PDF doesn't play nicely with links
            if(!$pdf)
              echo '<li class="' . ($first ? 'active' : '') . '"><a href="#stu-' . $question->id . '-' . $group->id . '-' . $s->id . '" data-toggle="tab">' . sprintf('%.2f', $averages[$s->id]) . ' / '. h($s->fullName) . '</a></li>';
            $first = false;
            
            
          }
        ?>
      </ul>
      <div class="tab-content">
      <?php
        $first = true;
        foreach($group->students as $s) :
        echo '<div class="tab-pane ' . ($first ? 'active' : '') . '" id="stu-' . $question->id . '-' .$group->id . '-' . $s->id . '">'; ?>
          
        
        
          <p>
            <strong><?php echo $s->fullName; ?>'s teammates rated him/her with the following:</strong>
          </p>
          <?php
            $responses = EvaluationResponse::model()
              ->with(array('response_sets.user'))
              ->where('t.target_user_id = ? AND t.evaluation_question_id = ? AND response_sets.course_group_id = ?', array($s->id, $question->id, $group->id))
              ->findAll();
          ?>
          
          <?php if(empty($responses)) : ?>
            <div class="alert alert-info"><?php echo $s->fullName; ?> hasn't been evaluated.</div>
          <?php else: ?>
          
            <table class="table table-condensed" style="width:auto">
              <tbody>
              <?php foreach($responses as $r) : ?>
                <tr>
                  <td style="min-width:40px"><?php echo $r->value; ?></td>
                  <td style="background:#eee;min-width:400px;"><?php echo $r->response_sets->user->fullName; ?></td>
                </tr>
              <?php endforeach; ?>
                <tr>
                  <td><b><?php echo sprintf('%.2f', $averages[$s->id]); ?></b></td>
                  <td style="background:#eee;min-width:400px;"><b>Average</b></td>
                </tr>
              </tbody>
            </table>
            
          <?php endif; ?>
          
        
        <?php
        echo '</div>';
        $first = false;
        endforeach;
        ?>
      </div>
    </div>
  </div>
</div>