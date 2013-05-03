<!DOCTYPE html>
<html>
<head>
<?php
  // Important! Make sure default layouts go here!
  echo Html::cssFile(array('lib/jquery-ui-1.9.1.min'));
  echo Html::scriptFile(array('lib/jquery-1.8.2.min', 'lib/jquery-ui-1.9.1.min'));
  echo Html::scriptFile(array('lib/bootstrap.min', 'application'));
?>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <!--[if lt IE 9]>
  <?php echo Html::scriptFile('html5shiv'); ?>
  <![endif]-->
  
  
  <title><?php echo $this->pageTitle; ?></title>
  <link rel="shortcut icon" href="<?php echo Html::normalizeUrl('favicon.ico'); ?>" type="image/x-icon">
  
  <meta name="CSRF_TOKEN" content="<?php echo Yii::app()->request->csrfToken; ?>">
  
<?php
  echo Html::cssFile(array('form'));
  // Uncomment this for fun!
  // echo Html::cssFile(array('bootstrap-fun'));
  echo Html::lessFile(array('bootstrap_mixins', 'layout'));
  
?>

  <meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<?php
  $this->renderPartial('/layouts/partials/_header');
  $this->renderPartial('/layouts/partials/_flashes');
?>
<div class="container">
  
  <!-- Confirm Modal -->
  <div class="modal hide" id="confirm-modal">
    <div class="modal-body" id="confirm-modal-body"></div>
    <div class="modal-footer">
      <a href="#" class="btn" id="confirm-modal-cancel">Cancel</a>
      <a href="#" class="btn btn-primary" id="confirm-modal-ok">OK</a>
    </div>
  </div>
  
  
  <div id="content">
    <!-- Breadcrumbs -->
    <?php if(!empty($this->breadcrumbs)) : ?>
      <section id="breadcrumbs">
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => $this->breadcrumbs,
        )); ?>
      </section>
    <?php endif ?>
  
    <!-- Main Section -->
    <section id="main-content">
      <?php echo $content; ?>
    </section>
  
  </div>
    <!-- Footer -->
      <footer>
        <p>&copy; Capstone Project 2013</p>
      </footer>

</div>
</body>
</html>