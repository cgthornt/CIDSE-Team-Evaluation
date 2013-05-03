<?php
/**
 * config.php
 *
 * This config file will dynamically handle some configuration changes depending
 * upon the environment of the system. In addition, it will merge some additional
 * configuration files into a single configuration array.
 * 
 * Generally, you have three different "environments" -
 * 
 *  * Development - local development, i.e. your local development machine (i.e. a laptop).
 *                  Generally with development, you will want to display error messages,
 *                  view stack traces, avoid emails, etc. In addition, caching will be
 *                  disabled to accomidate code changes.
 *                  
 *  * Production -  live code used by other people. Generally, this is when actual users will
 *                  be using this application. In this mode, you will want to supress errors
 *                  and backtraces and perhaps send an email on an error. In addition, you
 *                  will want to enable caching for the best performance.
 *                  
 *  * Test       - when unit tests or other tests are being run. Generally, this should be
 *                 as close to production as possible, but you will usually want a seperate
 *                 database than that used during development
 *
 * Yii does not provide a built in way to distinguish between these environments, nor does it
 * provide an easy way to load different configurations between different environments. As such,
 * this file will create a configuration file based upon the current system environment.
 *
 * To see how a different environment will be determined, please read the "environment.php" file.
 *
 * Configuration is generally loaded in this order:
 *
 *  1. The "default" application config (application.php) is loaded.
 *  2. The environment-specific application config (a file in /environments folder) is loaded and
 *     merged with the already loaded configuration.
 *  3. The database configuration (database.php) is loaded and set depending upon the environment.
 *     This configuration will be merged with the already loaded configuration
 *  4. The routes configuration (routes.php) will be loaded and merged with the already
 *     loaded configuration
 *  5. Finally, the server specific configuration (server.php) will be loaded and merged with the
 *     already loaded configuration
 *
 * Once all of this loading has been done, an array will then be passed to the Yii application.
 *
 * @author Christopher Thornton
 * @package config
 */

// Load the environment config if it has not been loaded already
require_once(dirname(__FILE__) . '/environment.php');

// If for some reason we haven't defined whether mod rewrite is enabled, assume that it is disabled
defined('MOD_REWRITE_ENABLED') or define('MOD_REWRITE_ENABLED', false);

// Define our asset revision for asset caching, etc. A revision at zero implies debug mode
defined('ASSET_REVISION') or define('ASSET_REVISION', 0);

// Load the default application configuration
$config = require(dirname(__FILE__) . '/application.php');

// Check to see if a specific config file exists for this environment. If it does, then
// merge it with the currently existing config
$env_file   = dirname(__FILE__) . '/environments/' . ENVIRONMENT . '.php';
if(file_exists($env_file)) $config = CMap::mergeArray($config,  require($env_file));


// Load the database configuration file. Select an environment-specific configuration and
// merge it with the existing configuration.
$db_config = require(dirname(__FILE__) . '/database.php');
$config = CMap::mergeArray($config, array('components' => array('db' => $db_config[ENVIRONMENT])));;

// Load the routes file
$route_config = require(dirname(__FILE__) . '/routes.php');
$config = CMap::mergeArray($config, array('components' => array('urlManager' => array('rules' => $route_config))));

// Server specific config
$config = CMap::mergeArray($config, require(dirname(__FILE__) . '/server.php'));

// Finally, return the config
return $config;
