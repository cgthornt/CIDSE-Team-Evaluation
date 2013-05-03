<?php echo $this->pageTitle('Login', 'login to continue'); ?>
<p>
  Click below to login through ASU. This uses your ASUrite ID and password.
</p>
<p>
<?php echo Html::button('Login Through ASU', array('class' => 'btn', 'submit' => array('users/loginCAS'), 'csrf' => true)); ?>
</p>

<?php if(YII_DEBUG) : ?>
  <h3>Emulate ASUrite ID</h3>
  <p>Since YII_DEBUG is true, you may emulate an ASUrite ID without using CAS</p>
  <?php echo Html::beginForm(array('users/loginEmulate'), 'post', array('class' => 'form')); ?>
    <input type="text" name="asurite" id="asurite" placeholder="ASUrite ID"> <br>
    <input type="submit" value="Login" class="btn">
  <?php echo Html::endForm(); ?>
<?php endif ?>