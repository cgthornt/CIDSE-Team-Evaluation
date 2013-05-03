<div class="item">
<?php
  echo Html::activeLabelEx($model, 'maxValue');
  $data = array();
  for($i = 2; $i <= 10; $i++) $data[$i] = $i;
  echo Html::activeDropDownList($model, 'maxValue', $data, array('name' => $model->attributeLabel('maxValue')));
?>
</div>