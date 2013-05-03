<?php

/**
 * Custom DateTime class for database field manipulation. The primairy purpose is
 * because of {@link:EDateTimeBehavior} to override the toString method!
 *
 * Also contains some very americain-specific date formats. In addition, helps with
 * conversion to UTC times when storing in database.
 */
class Time extends DateTime {
  
  
  /**
   * A very verbose date, i.e. "Saturday, January 3 2012"
   */
  public static $FMT_DATE_COMPLETE = "l, F j Y";
  
  /**
   * A long date, i.e. "January 3 2012
   */
  public static $FMT_DATE_LONG = "F j Y";
  
  /**
   * A small date, i.e. "Jan 3 2012"
   */
  public static $FMT_DATE_SMALL = "M j Y";
  
  /**
   * A purely numeric date, i.e. "1/3/2012"
   */
  public static $FMT_DATE_NUMERIC = "n/j/y";
  
  
  /**
   * A time string that does not contain any leading zeroes, i.e. "1:30 am"
   */
  public static $FMT_TIME_SHORT = "g:i a";
  
  /**
   * A very verbose datetime, i.e. "Saturday, January 3 2012, 1:30 am"
   */
  public static $FMT_DATETIME_COMPLETE = "l, F j Y, g:i a";
  
  /**
   * A long datetime, i.e. "January 3 2012, 1:30 am"
   */
  public static $FMT_DATETIME_LONG = "F j Y, g:i a";
  
  /**
   * A small datetime, i.e. "Jan 3 2012, 1:30 am"
   */
  public static $FMT_DATETIME_SMALL = "M j Y, g:i a";
  
  /**
   * A purely numeric datetime, i.e. "1/3/2012 1:30 am"
   */
  public static $FMT_DATETIME_NUMERIC = "n/j/y g:i a";
  
  /**
   * The SQL time format
   */
  protected static $sqlDateFormat = 'Y-m-d';
  
  /**
   * The SQL datetime format
   */
  protected static $sqlDateTimeFormat = 'Y-m-d H:i:s';
  
  /**
   * The UTC timezone
   */
  protected static $utcTimezone, $systemTimezone;
  
  public static function getUtcTimezone() {
    if(self::$utcTimezone == null) self::$utcTimezone = new DateTimeZone('UTC');
    return self::$utcTimezone;
  }
  
  
  public static function getSystemTimezone() {
    if(self::$systemTimezone == null) self::$systemTimezone = new DateTimezone(date_default_timezone_get());
    return self::$systemTimezone;
  }
  
  
  
  /**
   * Converts the timezone to UTC.
   */
  public function toUtc() {
    $this->setTimezone(self::getUtcTimezone());
    return $this;
  }
  
  
  public function toSystemTimezone() {
    $this->setTimezone(self::getSystemTimezone());
    return $this;
  }
  
  public function formatUtc($format) {
    $timezone = $this->getTimezone();
    $str = $this->toUtc()->format($format);
    $this->setTimezone($timezone);
    return $str;
  }
  
  public function toLocalDbDateTime() {
    return $this->format(self::$sqlDateTimeFormat);
  }
  
  public function toDbDateTime() {
    return $this->formatUtc(self::$sqlDateTimeFormat);
  }
  
  public function toDbDate() {
    // Note: we don't want to change the timezone for UTC! 
    return $this->format(self::$sqlDateFormat);
  }
  
  
  /**
   * Default formats with Database date
   */
  public function __toString() {
    return $this->toDbDateTime();
  }
  
}


?>