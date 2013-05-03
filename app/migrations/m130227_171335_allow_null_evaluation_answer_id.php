<?php

class m130227_171335_allow_null_evaluation_answer_id extends CDbMigration
{
	public function up()
	{
    $this->execute("ALTER TABLE  `evaluation_responses` CHANGE  `evaluation_answer_id`  `evaluation_answer_id` INT( 11 ) NULL");
	}

	public function down()
	{
		echo "m130227_171335_delete_evaluation_answers does not support migration down.\n";
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