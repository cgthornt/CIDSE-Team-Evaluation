<?php

/**
 * Automatically handles any `created_at` and `updated_at` timestamp columns.
 *
 * @author Christopher Thornton
 */
class ModelTimestampBehavior extends CActiveRecordBehavior {
  
  public function beforeSave($event) {
    $columns = $event->sender->tableSchema->columns;
    
    // If we have a new record and a column 'created_at' exists and it is a datetime object, set the time to now
    if($event->sender->isNewRecord && !empty($columns['created_at']) && $columns['created_at']->dbType == 'datetime') {
      $name = 'created_at';
      // If the 'created_at' field is empty, set the current time to now
      if(empty($event->sender->$name))
        $event->sender->$name = new Time();
    }
    
    // Now if we have a column 'updated_at' which exists and it is a datetime object, set it to now
    if(!empty($columns['updated_at']) && $columns['updated_at']->dbType == 'datetime') {
      $name = 'updated_at';
      $event->sender->$name = new Time();
    }
    
    // Done!
  }
  
  
}