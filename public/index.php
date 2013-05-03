<?php
/**
 * index.php
 *
 * The /public directory is intended to be the "root" directory of the application.
 * Generally, the only .php files in this folder should be the index.php and
 * the index_rewrite.php files.
 *
 * In addition, the /public directory should contain static assets that Apache will
 * directly serve.
 *
 * As you can see here, very little is done and the main purpose of this file is to
 * load configuration files and to call Yii to run the application.
 *
 * @author Christopher Thornton
 */

date_default_timezone_set("America/Phoenix");
 
// Load Composer
require_once(dirname(__FILE__) . '/../.composer/autoload.php');

// If Apache's mod_rewrite module is enabled, this file will have been included by the
// index_rewrite.php and should have already defined the constant MOD_REWRITE_ENABLED.
//
// If MOD_REWRITE_ENABLED is *not* defined, that means that Apache's mod_rewrite is
// not defined (or .htaccess isn't supported), so we will default MOD_REWRITE_ENABLED
// to false.
//
// Note that if MOD_REWRITE_ENABLED is false, pretty url's will be disabled and instead
// ugly url's will be used, i.e. "exmaple.com?q=welcome/about"
defined('MOD_REWRITE_ENABLED') or define('MOD_REWRITE_ENABLED', false);

// We want to load the environment file. This defines the current environment
require_once(dirname(__FILE__) . '/../config/environment.php');

// We want enable Yii's debug mode *only* if the current environment is development
defined('YII_DEBUG') or define('YII_DEBUG', ENVIRONMENT == "development");

// Force HTTPS
if(ENVIRONMENT == 'production' && $_SERVER["HTTPS"] != "on") {
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  die();
}


// Now we want to define the location of the Yii framework and the /config/config.php file
$yii    = dirname(__FILE__) . '/../.composer/yiisoft/yii/framework/yii.php';
$config = dirname(__FILE__) . '/../config/config.php';

// Now we can load up Yii and let Yii handle the rest of the details!
require_once($yii);
Yii::createWebApplication($config)->run();
