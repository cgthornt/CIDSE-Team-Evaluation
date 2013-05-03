<?php

YiiBase::import('ext.lessc');

/**
 * Overrides the default CHtml class so we can add our own custom extensions. One of
 * the most notable features is handling the application if it's in a base directory.
 * @author Christoper Thornton
 */
class Html extends CHtml {
  
  protected static $lessc, $lessPath;
  
  
  
  /**
   * Creates a link to a CSS file that defaults to the "css" directory and properly
   * handles the application if it's in a subdirectory
   * @param string $url the name of the CSS file
   * @param string $media the CSS media
   */
  public static function cssFile($url, $media='') {
    if(!is_array($url)) $url = array($url);
    $html = '';
    foreach($url as $u)
      $html .= parent::cssFile(self::normalizeUrl("ui/css/$u.css?r=" . ASSET_REVISION, $media));
    return $html;
  }
  
  /**
   * Creates a link to a JavaScript file that defaults to the "js" directory and
   * properly handles the case if it's in a subdirectory
   * @param string $url the name of the JavaScript file
   */
  public static function scriptFile($url) {
    if(!is_array($url)) $url = array($url);
    $html = '';
    foreach($url as $u)
      $html .= parent::scriptFile(self::normalizeUrl("ui/js/$u.js?r=" . ASSET_REVISION));
    return $html;
  }
  
  public static function lessFile($url, $media = '') {
    
    if(empty(self::$lessPath)) self::$lessPath = YiiBase::getPathOfAlias('webroot.ui.less');
    // die(var_dump(self::$lessPath));
    
    // Make new less compiler
    if(empty(self::$lessc)) self::$lessc = new lessc;
    
    if(!is_array($url)) $url = array($url);
    $html = '';
    foreach($url as $file) {
      $file = self::compileLessFile($file);
      $html .= parent::cssFile(self::normalizeUrl("ui/less/compiled/$file.css?r=" . ASSET_REVISION, $media));
    }
    return $html;
  }
  
  /**
   * Overriden method to handle if the application is in a subdirectory. If $url
   * is a string and does not begin with a slash "/" then append  $url with the
   * application base directory
   * @param mixed $url the url
   */
  public static function normalizeUrl($url) {
    if(is_string($url) && substr($url, 0, 1) != "/")
      return Yii::app()->baseUrl . "/$url";
    return parent::normalizeUrl($url);
  }
  
  public static function image($src, $alt, $htmlOptions = array()) {
    return parent::image(self::normalizeUrl("ui/img/$src"), $alt, $htmlOptions);
  }
  
  
  public static function globalLessFile($url, $media = '') {

    if(!is_array($url)) $url = array($url);
    foreach($url as $file) {
      $css = self::compileLessFile($file);
      Yii::app()->clientScript->registerCssFile(self::normalizeUrl("ui/less/compiled/$css.css"), $media);
    }
  }
  
  
  public static function compileLessFile($file) {
    if(empty(self::$lessPath)) self::$lessPath = YiiBase::getPathOfAlias('webroot.ui.less');
    if(empty(self::$lessc)) self::$lessc = new lessc;
    $path = self::$lessPath . "/$file.less";
    $cPath = self::$lessPath . "/compiled/$file.css";
    $dir = dirname($cPath);
    
    // Make sure the directory exists, if not, make it recursively
    // Unfortunately we need to chmod to 777 or else deleting old releases won't work!
    if(!file_exists($dir))
      mkdir($dir, 0777, true);
    self::$lessc->checkedCompile($path, $cPath);
    chmod($cPath, 0777);
    return $file;
  }
  
  public static function globalCssFile($url, $media = '') {
    if(!is_array($url)) $url = array($url);
    foreach($url as $css)
      Yii::app()->clientScript->registerCssFile(self::normalizeUrl("ui/css/$css.css"), $media);
  }
  
  public static function globalScriptFile($url) {
    if(!is_array($url)) $url = array($url);
    foreach($url as $js)
      Yii::app()->clientScript->registerScriptFile(self::normalizeUrl("ui/js/$js.js"));
  }
  
}
?>