<?php

class m121106_231625_fix_schema_issues extends CDbMigration
{
	public function up() {
    
    // Make usernames not null
    $this->execute('ALTER TABLE  `users` CHANGE  `username`  `username` VARCHAR( 255 ) NOT NULL');    

    // Make usernames unique
    $this->execute('ALTER TABLE  `users` DROP INDEX  `username` , ADD UNIQUE  `username` (  `username` )');
  
  
  }

	public function down()
	{
		echo "m121106_231625_fix_schema_issues does not support migration down.\n";
		return false;
	}

}