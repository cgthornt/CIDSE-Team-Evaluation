<?php
/**
 * index_rewrite.php
 *
 * If Apache's mod_rewrite module is enabled AND .htaccess files are enabled, then
 * this file will be loaded instead of index.php. If this is the case, then we want
 * to define MOD_REWRITE_ENABLED to true and then load index.php normally.
 *
 * If Apache's mod_rewrite module is not enabled OR .htaccess files are not enabled, then
 * index.php will be loaded normally.
 *
 * @author Christopher Thornton
 */

 date_default_timezone_set("America/Phoenix");
defined('MOD_REWRITE_ENABLED') or define('MOD_REWRITE_ENABLED', true);
require(dirname(__FILE__) . '/index.php')
?>