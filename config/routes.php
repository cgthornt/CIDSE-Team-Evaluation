<?php
/**
 * routes.php
 *
 * This file defines the routes rules used internally by Yii. This file is kept
 * seperate from the main application.php file to make adding and modifying routes
 * much easier.
 *
 * @author Christopher Thornton
 * @see http://www.yiiframework.com/doc/guide/1.1/en/topics.url
 * @package config
 */

return array(
  '<controller:\w+>'                       => '<controller>/index',
  '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
  '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
);
 