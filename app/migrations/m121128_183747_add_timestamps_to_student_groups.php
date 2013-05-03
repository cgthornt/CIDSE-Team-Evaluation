<?php

class m121128_183747_add_timestamps_to_student_groups extends CDbMigration {
	public function up(){
		$this->addColumn('course_group_students', 'date_enrolled', 'datetime');
		$this->addColumn('course_group_students', 'date_dropped', 'datetime');
		$this->createIndex('enrolled_index', 'course_group_students', 'course_group_id, date_enrolled, date_dropped');
		$this->createIndex('enrolled_student_index', 'course_group_students', 'course_group_id, user_id, date_enrolled, date_dropped');
	}

	public function down(){
		echo "m121128_183747_add_timestamps_to_student_groups does not support migration down.\n";
		return false;
	}
}