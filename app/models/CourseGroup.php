<?php

/**
 * This is the model class for table "course_groups".
 *
 * The followings are the available columns in table 'course_groups':
 * @property integer $id
 * @property integer $course_id
 * @property string $name
 * @property string $description
 *
 * The followings are the available model relations:
 * @property CourseGroupStudents[] $courseGroupStudents
 * @property Courses $course
 */
class CourseGroup extends Model {
	
	/**
	 * Enrolls students in 
	 */
	public function enrollStudents($studentIds, $removeNotInList = false) {
		
		// "Remove" any students not in the list by flagging the "dropped_at" field to now
		if($removeNotInList) {
			Yii::app()->db->createCommand()->update('course_group_students',
				array('date_dropped' => new Time()),
				array('and', 'course_group_id = :group_id AND date_dropped IS NULL', array('not in', 'user_id', $studentIds)),
				array(':group_id' => $this->id)
			);
		}
		
		$enrollTime = new Time();
		
		// Finally, insert each id into the database if they do not already exist
		foreach($studentIds as $id) {
			// Now let's make sure that the student isn't already in the group
			$count = Yii::app()->db->createCommand()
				->select('count(*) as cnt')
				->from('course_group_students')
				->where(
					'user_id = :user AND course_group_id = :group AND date_dropped IS NULL',
					array(':user' => $id, ':group' => $this->id))
				->queryRow();

			// Now we know that the user isn't already in the group, add him!
			if($count['cnt'] == 0) {
				Yii::app()->db->createCommand()->insert('course_group_students', array(
					'user_id' 				=> $id,
					'course_group_id' => $this->id,
					'date_enrolled'		=> $enrollTime
				));
			}
		}
	}
	
	/**
	 * Get users enrolled in this group at a particular point in time
	 * @param Time $atTime the time to check. If none specified, defaults to now.
	 * @return User a user query object.
	 */
	public function enrolled(Time $atTime = null) {
		if($atTime == null) $atTime = new Time;
		return User::model()->with(array('course_groups' => array(
			// 'select' => false,
			'condition' => 'course_groups_course_groups.course_group_id = :group_id AND course_groups_course_groups.date_enrolled <= :time AND (course_groups_course_groups.date_dropped > :time OR course_groups_course_groups.date_dropped IS NULL)',
			'params' 		=> array(':time' => $atTime, ':group_id' => $this->id)),
		));
	}
		
		/**
		 * Enrolls a single user in this group
		 * @param User $user the user to enroll
		 * @return boolean true if enrollment was successful, false otherwise
		 */
		public function enrollUser(User $user) {
      $student = new CourseGroupStudents();
			$student->user_id = $user->id;
			$student->course_group_id = $this->id;
			$student->date_enrolled = new Time();
			return $student->save();
		}
		
		/**
		 * Drops users who are not in the list of $userIds. Pass array(-1) to drop all users.
		 * @param array $userIds an array of user IDs to drop students if not in this course
		 *
		public function dropUsersNotIn(array $userIds) {
			Yii::app()->db->createCommand()
				->update('course_group_students',
					// Update `date_dropped` to current time
					array('date_dropped' => new Time()),
					
					// Set bounds to this course and student IDs
					array('and', 'course_group_id = :group_id AND date_dropped IS NULL', array('not in', 'user_id', $userIds)),
					array(':group_id' => $this->id));
		} */
	
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CourseGroup the static model class
	 */
	public static function model($className=__CLASS__) { return parent::model($className); }

	/**
	 * @return string the associated database table name
	 */
	public function tableName() { return 'course_groups'; }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('course_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, course_id, name, description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'students' => array(self::MANY_MANY, 'User', 'course_group_students(course_group_id,user_id)'),
      'student_groups' => array(self::HAS_ONE, 'CourseGroupStudents', 'course_group_id'),
			'course' => array(self::BELONGS_TO, 'Course', 'course_id'),
		);
	}
	

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'course_id' => 'Course',
			'name' => 'Name',
			'description' => 'Description',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('course_id',$this->course_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}