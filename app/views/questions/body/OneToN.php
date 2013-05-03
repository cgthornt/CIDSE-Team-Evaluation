<table>
  <thead>
    <tr>
      <th class="the-users"></th>
      <?php for($i = 1; $i <= $type->maxValue; $i++) echo "<th class=\"itm\">$i</th>"; ?>
    </tr>
  </thead>
  <tbody>
  <?php foreach($users as $user) : ?>
    <?php if(!$question->allow_self && $this->user->id == $user->id) continue; ?>
    <tr>
      <td class="name-user"><?php echo $user->fullName; ?></td>
      <?php for($i = 1; $i <= $type->maxValue; $i++) : ?>
      <td>
        <?php
          // Radio Button:
          // question[QUESTION_ID][TARGET_USER_ID]
          // echo Html::hiddenField("question[{$question->id}][{$user->id}]", '0'); // Default so that submission always works
          echo Html::radioButton("question[{$question->id}][{$user->id}]", $i == $type->maxValue, array('value' => $i));
        ?>
      </td>
      <?php endfor; ?>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>