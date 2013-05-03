<?php

/**
 * This is the model class for table "courses".
 *
 * The followings are the available columns in table 'courses':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property integer $archived
 *
 * The followings are the available model relations:
 * @property CourseGroups[] $courseGroups
 * @property CourseProfessors[] $courseProfessors
 */
class Course extends Model
{
  
  // Default to models being archived
  public $archived = false;
  
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Course the static model class
	 */
	public static function model($className=__CLASS__) { return parent::model($className); }

	/**
	 * @return string the associated database table name
	 */
	public function tableName() { return 'courses'; }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('archived', 'boolean', 'allowEmpty' => false),
      array('code, name', 'required'),
			array('code, name', 'length', 'max'=>255),
			array('description, created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, name, description, created_at, archived', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'groups' => array(self::HAS_MANY, 'CourseGroup', 'course_id'),
			'professors' => array(self::MANY_MANY, 'User', 'course_professors(course_id, user_id)'),
      'evaluations' => array(self::HAS_MANY, 'Evaluation', 'course_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'description' => 'Description',
			'created_at' => 'Created At',
			'archived' => 'Archived',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('archived',$this->archived);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  
  /**
   * Helps find courses that a user can access, for example
   *
   *  Course::model()->withAccess($someUser)->findAll(...)
   *
   * @param User $user The User object to check access
   * @param boolean $limitAdmin If TRUE, creates no special consideration for admin-type users.
   *    If FALSE, then lets an admin user to access any course.
   * @return Course this course object, for chaining.
   */
  public function withAccess(User $user, $limitAdmin = false) {
    
    // If the user is an admin, don't bother adding any other search criteria
    if(!$limitAdmin && $user->hasRole('admin'))
      return $this;
    
    // If the user is a faculty, make sure to check if the professor has access
    if($user->hasRole('faculty')) {
      $this->dbCriteria->mergeWith(array(
        'join'      => 'LEFT OUTER JOIN `course_professors` `professor_check` ON `t`.`id` = `professor_check`.`course_id`',
        'condition' => '`professor_check`.`user_id` = :prof_check_id',
        'params'    => array(':prof_check_id' => $user->id),
      ));
      return $this;
    }
    
    // Otherwise, assume student access
    $this->dbCriteria->mergeWith(array(
      'join'      => 'LEFT OUTER JOIN `course_groups` `cg_chk` ON `t`.`id` = `cg_chk`.`course_id` ' .
                     'LEFT OUTER JOIN `course_group_students` `cgs_chk` ON `cg_chk`.`id` = `cgs_chk`.`course_group_id`',
      'condition' => '`cgs_chk`.`user_id` = :student_check_id',
      'params'    => array(':student_check_id' => $user->id),
    ));
    return $this;
  }
  
  public function scopes() {
    return array(
      'available' => array(
        'condition' => 'archived=0',
      ),
      'archived' => array(
        'condition' => 'archived=1',
      ),
    );
  }
}