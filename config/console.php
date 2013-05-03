<?php
/**
 * console.php
 *
 * Yii cannot use the same configuration for the console and the web application.
 * The reason is that the console does not use some components that the web app uses,
 * such as routing.
 * 
 * This config file contains a watered down version of what the normal configuration
 * would look like.
 *
 * @author Christopher Thornton
 * @package config
 */

require_once(dirname(__FILE__) . '/environment.php');

// Console Specific Settings
$config = array(
  'basePath'      => dirname(__FILE__) . '/../app/',
  'extensionPath' => dirname(__FILE__) . '/../vendor/',
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
  'components' => array(
    'email' => array(
      'class'    => 'ext.email.Email',
      'delivery' => (ENVIRONMENT == 'production' ? 'php' : 'debug'), // deliver only in production
    )
  )
);

// Get the database configuration
$db_config = require(dirname(__FILE__) . '/database.php');
$config = CMap::mergeArray($config, array('components' => array('db' => $db_config[ENVIRONMENT])));;

return $config;

?>