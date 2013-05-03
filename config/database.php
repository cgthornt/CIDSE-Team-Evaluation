<?php
/**
 * database.php
 *
 * This file contains database configurations for each environment. A different
 * configuration will be loaded depending upon the current environment.
 * 
 * @author Christopher Thornton
 * @todo Add a group for the test environment
 * @package config
 */

return array(
  'development' => array(
    'connectionString'  => 'mysql:host=localhost;dbname=capstone',
    'emulatePrepare'    => true,
    'username'          => 'root',
    'password'          => '',
    'charset'           => 'utf8',
    'enableParamLogging'=>true,
    'enableProfiling'   =>true,
  ),
  'production' => array(
    'connectionString'  => 'mysql:host=localhost;dbname=capstone',
    'emulatePrepare'    => true,
    'username'          => 'root',
    'password'          => '',
    'charset'           => 'utf8',
  ),
);