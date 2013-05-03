<?php

/**
 * This migration creates the users and users_roles table
 */
class m120924_180040_create_users_table extends CDbMigration {
	public function up() {
    // The Users Table
    // Make sure to index username, email and roles!
    $this->createTable('users', array(
      'id' => 'pk',
      'username' => 'string',
      'role_primary' => 'string',
      'first_name'   => 'string',
      'last_name'    => 'string',
      'middle_name'  => 'string',
      'email'        => 'string',
      'profile_last_updated' => 'datetime',
      'updated_at'           => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
      'INDEX(username)',
      'INDEX(email)',
      'INDEX(role_primary)'
    ), 'engine=InnoDB');
    
    // Create Roles Table
    $this->createTable('users_roles', array(
      'id'      => 'pk',
      'user_id' => 'integer',
      'role'    => 'string',
      'INDEX(role)'
    ), 'engine=InnoDB');
    
    // Add a Forieng Key from the Roles table to the Users table
    $this->addForeignKey('role_user_fk',
      'users_roles', 'user_id',
      'users', 'id',
      'CASCADE', 'CASCADE');
    
	}

	public function down() {
    $this->dropTable('users_roles');
		$this->dropTable('users');
	}

}