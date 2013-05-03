<header id="header">
  
  <!-- Site Logo -->
  <div id="site-logo">
    <a href="http://engineering.asu.edu/" title="ASU Engineering">
      <?php echo Html::image('site_logo.png', 'Ira A. Fulton Schools of Engineering Logo'); ?>
    </a>
  </div>
    
    
  <!-- Righthand Links -->
  <div id="header-links">
    <a href="http://asu.edu">ASU Home</a>
    <a href="https://my.asu.edu">My ASU</a>
    <a href="http://www.asu.edu/colleges/">Colleges &amp; Schools</a>
    <a href="http://www.asu.edu/index/">A-Z Index</a>
    <a href="http://www.asu.edu/directory/">Directory</a>
    <a href="http://www.asu.edu/map/">Map</a>
    <div id="header-link-seperator">|</div>
    <div id="header-links-user">
    <?php if($this->user->isGuest) : ?>
      <?php echo Html::link('SIGN IN', array('users/login')); ?>
    <?php else: ?>
      <?php echo $this->userModel->first_name; ?>
      <?php echo Html::link('SIGN OUT', '#logout', array('submit' => array('users/logout'), 'csrf' => true)); ?>
    <?php endif ?>
    </div>
  </div>
  
  <!-- Site Name -->
  <div id="header-site-name">
    <h1><?php echo Html::link(Yii::app()->name, array('/'), array('title' => 'Return Home')); ?></h1>               
  </div>
  
  <!-- Primary Navigation -->
  <div id="header-navigation" class="container">
    <nav class="navbar pull-right">
      <?php $this->widget('zii.widgets.CMenu', array(
        // 'htmlOptions' => array('class' => 'nav'),
        'id' => 'header-navbar',
        'items'=>array(
            // Important: you need to specify url as 'controller/action',
            // not just as 'controller' even if default acion is used.
            array('label' => 'Home',   'url'=>array('welcome/index')),
            array('label' => 'My Courses', 'url' => array('courses/index'), 'visible' => $this->user->checkAccess('faculty')),
            
            // Admin Central
            array('label' => 'Users', 'url' => array('users/index'), 'visible' => $this->user->checkAccess('admin')),
        ),
        ));?>
    </nav>
  </div>
</header>
<?php if(!empty($this->menu)) : ?>
  <div id="header-submenu">
    <div class="header-shadow" style="height:5px"></div>
    <div class="container">
    <?php
      $this->widget('zii.widgets.CMenu', array(
        'htmlOptions' => array('class' => 'nav nav-pills'),
        'items'=> $this->menu,
        'encodeLabel' => false,
      )); ?>
    </div>
  </div>
<?php endif; ?>
<div class="header-shadow"></div>