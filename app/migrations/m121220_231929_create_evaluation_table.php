<?php

class m121220_231929_create_evaluation_table extends CDbMigration
{
	public function up(){
    if($this->getDbConnection()->driverName == "mysql") {
      echo "Changing default stoage engine to InnoDB!\n";
      $this->execute("SET storage_engine=InnoDB");
    }
		$this->createTable('evaluations', array(
			'id' 					=> 'pk',
			'course_id' 	=> 'INT NOT NULL',
			'name'				=> 'string NOT NULL',
			'description' => 'text',
			'published'		=> 'boolean NOT NULL DEFAULT 0',
			'published_at' => 'datetime',
			'due_at'			 => 'datetime',
			'created_at' 	=> 'datetime'
		));
	}

	public function down()
	{
		echo "m121220_231929_create_evaluation_table does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}