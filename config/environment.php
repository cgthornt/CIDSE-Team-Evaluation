<?php
/**
 * environment.php
 *
 * The environment file should be used to define specific constants. Upon deploy,
 * a production server will contain its own version of this file and will copy
 * over this version of the file. This is how Yii will know whether we're using
 * a production or development server.
 *
 * Any values here are default to development and will be overriden on deploy.
 *
 * @author Christopher Thornton
 * @package config
 */

/**
 * The ENVIRONMENT variable tells the application what mode the application is running.
 * This can be either of <i>development</i>, <i>production</i> or <i>test</i>.
 *
 * <ul>
 *  <li><b>development</b> enables Yii debugging (YII_DEBUG to TRUE). The SQL log will
 *  be displayed at the bottom of the page and you will be able to log in as any user.</li>
 *
 *  <li><b>production</b> disables Yii debugging (YII_DEBUG to FALSE) and enables several
 *  caching features to enhance performance.</li>
 *
 *  <li><b>test</b> has not been implemented yet.
 * </ul>
 */
define('ENVIRONMENT', 'development');

?>