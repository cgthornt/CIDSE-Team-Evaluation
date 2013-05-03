<?php
$flashes = Yii::app()->user->getFlashes();
if(!empty($flashes)) : ?>
<div id="flashes" class="container">
<?php foreach($flashes as $type=>$content) : ?>
  <div class="alert alert-<?php echo $type; ?>">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo $content; ?>
  </div>  
<?php endforeach ?>
</div>
<?php endif ?>