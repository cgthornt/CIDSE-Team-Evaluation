<script type="text/javascript">
<!--
loadAllPieInfo();
//-->
</script>
<div class="row pie-container">
  <div class="span2" style="width:200px">
    <table>
      <thead>
        <tr>
          <th>Teammate</th>
          <th>%</th>
        </tr>
      </thead>
      <tbody class="pie-body">
      <?php foreach($users as $user) : ?>
        <?php if(!$question->allow_self && $this->user->id == $user->id) continue; ?>
        <tr>
          <td class="name-user"><?php echo $user->fullName; ?></td>
          <td><?php
            // Field names:
            // questions[QUESTION_ID][TARGET_USER]
            echo '<input type="text" name="question[' . $question->id . '][' . $user->id . ']" class="spinner-pie" value="0">';
          ?>
        </tr>
      <?php endforeach; ?>
        <tr>
          <td style="font-weight:bold;text-align: right">Total</td>
          <td class="total-percent">0%</td>
      </tbody>
    </table>
    <small>Percentages must add up to about 100%</small>
  </div>
  <div class="span4" style="width: 390px;">
    <div id="percentpie-chart-<?php echo $question->id; ?>" class="percentpie-chart"></div>
  </div>
  
  <div class="span8">
    <div class="alert alert-info pie-alert">Change a value to begin</div>
  </div>
</div>

