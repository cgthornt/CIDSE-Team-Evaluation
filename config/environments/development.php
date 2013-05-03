<?php
/**
 * development.php
 *
 * This file should contain configuration settings specific to the development environment.
 * Any changes here will override / be merged with any config options in the main application
 * configuration.
 * 
 * @author Christopher Thornton
 * @package config
 */

return array(
  'modules' => array(
    
    // Load Gii in development and add some precautions to make sure only
    // in development mode
    'gii' => array(
      'class'     => 'system.gii.GiiModule',
      'password'  => 'password',
      'ipFilters' =>array('127.0.0.1'),
    ),
  ),
  'components' => array(
    'urlManager'=>array(
      'rules'=>array(
          'gii'=>'gii',
          'gii/<controller:\w+>'=>'gii/<controller>',
          'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
      ),
    ),
  ),
);
