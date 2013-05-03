<?php

// Timezone
date_default_timezone_set("America/Phoenix");

// Load Composer
require_once(dirname(__FILE__) . '/.composer/autoload.php');

// change the following paths if necessary
$yiic    = dirname(__FILE__) . '/.composer/yiisoft/yii/framework/yiic.php';
$config = dirname(__FILE__) . '/config/console.php';

require_once($yiic);
