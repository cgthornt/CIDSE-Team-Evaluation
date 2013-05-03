<?php

class m121227_214358_fix_evaluation_question extends CDbMigration
{
	public function up() {
    $this->renameColumn('evaluation_questions', 'content', 'instructions');
    $this->renameColumn('evaluation_questions', 'hint', 'title');
  
  }

	public function down()
	{
		echo "m121227_214358_fix_evaluation_question does not support migration down.\n";
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