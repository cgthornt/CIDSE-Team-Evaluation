<?php

class m130417_001046_add_in_archive_to_question extends CDbMigration
{
	public function up()
	{
    $this->addColumn('evaluation_questions', 'in_lib', 'boolean default 0');
	}

	public function down()
	{
		echo "m130417_001046_add_in_archive_to_question does not support migration down.\n";
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