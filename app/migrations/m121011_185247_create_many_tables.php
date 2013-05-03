<?php

class m121011_185247_create_many_tables extends CDbMigration{
	public function up(){
    // Set the default storage engine
    if($this->getDbConnection()->driverName == "mysql") {
      echo "Changing default stoage engine to InnoDB!\n";
      $this->execute("SET storage_engine=InnoDB");
    }
    
    // Courses Table
    $this->createTable("courses", array(
      'id' => 'pk',
      'code' => 'string',
      'name' => 'string',
      'description' => 'text',
      'created_at'  => 'datetime',
      'archived'    => 'boolean',
      'index(archived)',
    ));
    
    // Professors for Courses
    $this->createTable("course_professors", array(
      'id' => 'pk',
      'user_id' => 'integer',
      'course_id' => 'integer',
      'user_type' => 'string',
    ));
    $this->addForeignKey('prof_user_id',
      'course_professors', 'user_id',
      'users', 'id',
      'cascade', 'cascade');
    $this->addForeignKey('prof_course_id', 
      'course_professors', 'course_id',
      'courses', 'id',
      'cascade', 'cascade');
    
    
    // Add a group for courses
    $this->createTable("course_groups", array(
      'id' => 'pk',
      'course_id' => 'integer',
      'name' => 'string',
      'description' => 'text'
    ));
    $this->addForeignKey('course_id_fk',
      'course_groups', 'course_id',
      'courses', 'id',
      'cascade', 'cascade');
    
    // Tables so students may be in groups
    $this->createTable('course_group_students', array(
      'id' => 'pk',
      'user_id' => 'integer',
      'course_group_id' => 'integer'
    ));
    $this->addForeignKey('user_fk',
      'course_group_students', 'user_id',
      'users', 'id',
      'cascade', 'cascade');
    $this->addForeignKey('group_fk',
      'course_group_students', 'course_group_id',
      'course_groups', 'id',
      'cascade', 'cascade');
	}

	public function down()
	{
		//echo "m121011_185247_create_many_tables does not support migration down.\n";
		//return false;
	}
}