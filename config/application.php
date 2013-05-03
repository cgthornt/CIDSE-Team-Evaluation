<?php
/**
 * application.php
 *
 * This is the "default" configuration file used across all environments (except
 * the console environment). Each environment may override any of the configuration
 * options in this file (see config.php for more documentation).
 *
 * A few components are contained in their own configuration file, such as the
 * database and routing.
 *
 * @author Christopher Thornton
 * @package config
 */
return array(
  
  'name' => 'Team Evaluation',
  
	// Some components to preload
	'preload' => array(
    'log', 'less', 'bootstrap',
  ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
  
  // Where our models, views and controllers reside
  'basePath' => dirname(__FILE__) . '/../app/',
  
  'runtimePath' => dirname(__FILE__) . '/../runtime/',
  
  // Our extensions
  'extensionPath' => dirname(__FILE__) . '/../vendor/',
  
  'defaultController' => 'welcome',
  'components' => array(
    // BEGIN COMPONENTS
    
    
    'user' => array(
      'class' => 'WebUser',
      'loginUrl' => array('users/login'),
      'allowAutoLogin'=>true,
    ),
    
    // Only enable pretty URLs if Apache's mod_rewrite is enabled (see public/index_rewrite.php
    // for more information).
    'urlManager' => array(
      'urlFormat'      => MOD_REWRITE_ENABLED ? 'path' : 'get',
      'showScriptName' => !MOD_REWRITE_ENABLED,
    ),
		'log'=>array(
			'class'  => 'CLogRouter',
			'routes' => array(
        // Query Log
        array(
          'class' => 'CWebLogRoute',
          'levels' => 'trace',
          'categories'=>'system.db.CDbCommand',
          'enabled' => YII_DEBUG
        ),
				array(
					'class'   => 'CFileLogRoute',
					'levels'  => 'debug, info',
          'logPath' => dirname(__FILE__) . '/../log',
          'logFile' => ENVIRONMENT . '.debug.log',
          'enabled' => ENVIRONMENT == "development",
				),
				array(
					'class'   => 'CFileLogRoute',
					'levels'  => 'warning',
          'logPath' => dirname(__FILE__) . '/../log',
          'logFile' => ENVIRONMENT . '.warning.log',
				),
				array(
					'class'   => 'CFileLogRoute',
					'levels'  => 'error',
          'logPath' => dirname(__FILE__) . '/../log',
          'logFile' => ENVIRONMENT . '.error.log',
				),
        
        // Send emails on errors and warnings ONLY in production
        array(
          'class'   => 'CEmailLogRoute',
          'levels'  => 'error, warning',
          'emails'  => 'cgthornt@asu.edu',
          'enabled' => ENVIRONMENT == 'production',
        ),
      ),
    ),
    
    'request' => array( // Security measures
      'enableCookieValidation'  => true,
      'enableCsrfValidation'    => true,
      
      // HTTPS only CSRF cookie in production
      'csrfCookie' => array(
        'secure' => ENVIRONMENT == 'production'
      )
    ),
    
    'session' => array(
      'class' => 'CDbHttpSession',
      'connectionID' => 'db',
      'autoStart'   => true,
      'sessionTableName' => 'sessions',
      'autoCreateSessionTable' => false,
      'sessionName' => 'TEAMEVAL_SESSION_ID', // Use an unique session ID - just in case
      'cookieParams' => array(
        'secure' => ENVIRONMENT == 'production', // Force HTTPS secure cookie in production
        'httponly' => true, // HTTP only production
      ),
    ),
    
    // Disable builtin jQuery that Yii provides. We want to use
    // our own jQuery libraries.
    'clientScript' => array(
      'scriptMap' => array(
        'jquery.js'        => false,
        'jquery.min.js'    => false,
        'query-ui.js'      => false,
        'jquery-ui.min.js' => false,
      )
    ),
  
    'errorHandler' => array(
      'errorAction' => 'welcome/error',
    ),
    
    'email' => array(
      'class'    =>'ext.email.Email',
      'delivery' => (ENVIRONMENT == 'production' ? 'php' : 'debug'), // deliver only in production
    )
  
  // End Components
  ),
);



?>