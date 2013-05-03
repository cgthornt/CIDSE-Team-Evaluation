<?php
// Renders basic statistics
if(empty($statistics)) {
  echo '<p><em>No Statistics Given!</em></p>';
} else { ?>
<table class="table table-condensed" style="margin:10px">
  <thead>
    <tr>
      <?php foreach($statistics as $k=>$v): ?>
      <th><?php echo $k; ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <tr>
      <?php foreach($statistics as $k=>$v): ?>
        <td><?php echo $v; ?></td>
      <?php endforeach; ?>
    </tr>
  </tbody>
</table>
  
  
<?php } ?>