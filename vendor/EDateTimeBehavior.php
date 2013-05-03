<?php
/**
 * Author: Duc Nguyen Ta Quang <ducntq@gmail.com>
 *
 * Automatically convert date and datetime field to PHP5 DateTime object
 *
 * Inspired from DateTimeI18NBehavior
 *
 * Date: 5/15/12
 * Time: 2:14 PM
 * Version: 1.0.0
 * Tested with yii-1.1.10.r3566
 *
 * Edits from Christopher - automatically stores dates in the UTC timezone!
 */

class EDateTimeBehavior extends CActiveRecordBehavior
{
  private $mySqlDateFormat = 'Y-m-d';
  private $mySqlDateTimeFormat = 'Y-m-d H:i:s';

  public function afterFind($event)
  {
    foreach($event->sender->tableSchema->columns as $columnName => $column){
      if (($column->dbType != 'date') and ($column->dbType != 'datetime')) continue;
  
      if (!strlen($event->sender->$columnName)){
          $event->sender->$columnName = null;
          continue;
      }
  
      $timestamp = $event->sender->$columnName;
      
      // Assume time is in UTC if using datetime
      if($column->dbType == 'datetime') {
        $time = new Time($timestamp, Time::getUtcTimezone());
        $time->toSystemTimezone();
        
      // If not using datetime, then don't convert timezones!
      } else {
        $time = new Time($timestamp);
      }
  
      $event->sender->$columnName = $time;
    }
  }

    public function beforeSave($event)
    {
        foreach($event->sender->tableSchema->columns as $columnName => $column){
            if (($column->dbType != 'date') and ($column->dbType != 'datetime')) continue;
            
            // Convert string to time
            if(is_string($event->sender->$columnName)) {
              $event->sender->$columnName = new Time($event->sender->$columnName, Time::getSystemTimezone());
              $event->sender->$columnName->toUtc();
            }
            if($event->sender->$columnName instanceof Time)
            {
                if (($column->dbType == 'date'))
                {
                    $time = $event->sender->$columnName;
                    $event->sender->$columnName = $time->toDbDate();
                }
                else
                {
                    $time = $event->sender->$columnName;
                    $event->sender->$columnName = $time->toDbDateTime();
                }
            }
        }
    }
}
