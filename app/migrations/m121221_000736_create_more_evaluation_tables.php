<?php

class m121221_000736_create_more_evaluation_tables extends CDbMigration
{
	public function up() {
    // Ensure InnoDB!
    if($this->getDbConnection()->driverName == "mysql") {
      echo "Changing default stoage engine to InnoDB!\n";
      $this->execute("SET storage_engine=InnoDB");
    }
    
    // Add a FK to the evaluations table (forgot to add to previous evaluation)
    $this->addForeignKey('evaluation_course_id',
      'evaluations', 'course_id',
      'courses', 'id',
      'cascade', 'cascade');
    
    // Evaluation Questions
    $this->createTable('evaluation_questions', array(
      'id' => 'pk',
      'evaluation_id' => 'int NOT NULL',
      'content' => 'text',
      'hint'    => 'string',
      'type'    => 'string NOT NULL',
      'order'   => 'integer NOT NULL DEFAULT 0',
      'allow_self' => 'boolean NOT NULL DEFAULT 1',
      'options'    => 'text',
    ));
    
    $this->addForeignKey('evaluation_questions_eval',
      'evaluation_questions', 'evaluation_id',
      'evaluations', 'id',
      'cascade', 'cascade');
    
    
    // Evaluation Answers
    $this->createTable('evaluation_answers', array(
      'id' => 'pk',
      'evaluation_question_id' => 'int NOT NULL',
      'content' => 'text',
      'hint'    => 'string',
      'order'   => 'integer NOT NULL DEFAULT 0',
    ));
    
    $this->addForeignKey('eval_answers_question',
      'evaluation_answers', 'evaluation_question_id',
      'evaluation_questions', 'id',
      'cascade', 'cascade'); 
    
    // Now a response set 
    $this->createTable('evaluation_response_sets', array(
      'id'  => 'pk',
      'evaluation_id' => 'int NOT NULL',
      'course_group_id' => 'int NOT NULL',
      'user_id'       => 'int NOT NULL', // student ID
      'completed'     => 'boolean NOT NULL DEFAULT 0',
      'completed_at'  => 'datetime',
      'UNIQUE(evaluation_id, course_group_id, user_id)'
    )); 
    
    $this->addForeignKey('eval_fk',
      'evaluation_response_sets', 'evaluation_id',
      'evaluations', 'id',
      'cascade', 'cascade'); 
    
    $this->addForeignKey('thegroup_fk',
      'evaluation_response_sets', 'course_group_id',
      'course_groups', 'id',
      'cascade', 'cascade'); 
    
    $this->addForeignKey('someuser_fk',
      'evaluation_response_sets', 'user_id',
      'users', 'id',
      'cascade', 'cascade'); 
      
      
    // Now for responses and a crapload of FKs
    $this->createTable('evaluation_responses', array(
      'id'  => 'pk',
      'target_user_id'          => 'int NULL',
      'evaluation_response_set_id' => 'int NOT NULL',
      'evaluation_question_id'  => 'int NOT NULL',
      'evaluation_answer_id'    => 'int NOT NULL',
      'value'                   => 'string'
    )); 
    
    
    $this->addForeignKey('theuser_fk',
      'evaluation_responses', 'target_user_id',
      'users', 'id',
      'cascade', 'cascade');
    
    $this->addForeignKey('set_fk',
      'evaluation_responses', 'evaluation_response_set_id',
      'evaluation_response_sets', 'id',
      'cascade', 'cascade');
    
    $this->addForeignKey('question_fk',
      'evaluation_responses', 'evaluation_question_id',
      'evaluation_questions', 'id',
      'cascade', 'cascade');

    $this->addForeignKey('answer_fk',
      'evaluation_responses', 'evaluation_answer_id',
      'evaluation_answers', 'id',
      'cascade', 'cascade');
    
    // Finally, we're done!
    
	}

	public function down()
	{
		echo "m121221_000736_create_more_evaluation_tables does not support migration down.\n";
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