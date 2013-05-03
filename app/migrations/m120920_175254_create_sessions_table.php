<?php

class m120920_175254_create_sessions_table extends CDbMigration {
	public function up(){
		$this->createTable('sessions', array(
      'id'     => 'CHAR(32) PRIMARY KEY',
      'expire' => 'INTEGER',
      'data'   => 'BLOB',
    ), 'engine=InnoDB');
    $this->createIndex('sessions_expire_index', 'sessions', 'expire');
	}

	public function down(){
		$this->dropTable('sessions');
	}


}