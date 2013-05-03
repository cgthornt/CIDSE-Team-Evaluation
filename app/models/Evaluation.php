<?php

/**
 * This is the model class for table "evaluations".
 *
 * The followings are the available columns in table 'evaluations':
 * @property integer $id
 * @property integer $course_id
 * @property string $name
 * @property string $description
 * @property integer $published
 * @property string $published_at
 * @property string $due_at
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property EvaluationQuestions[] $evaluationQuestions
 * @property EvaluationResponseSets[] $evaluationResponseSets
 * @property Courses $course
 */
class Evaluation extends Model
{
  

  
  protected static $_sampleGroupData;
  
  /** Get an array of people in the list. Intended to emulate people in a given group **/
  public static function sampleGroupData(User $currentUser) {
    if(empty(self::$_sampleGroupData)) {
      
      $users = array($currentUser);
      
      $sampleAttributes = array(
        array('id' => 'smokey.gilmore', 'first_name' => 'Smokey', 'last_name' => 'Gilmore', 'email' => 'smokey.gilmore@asu.edu'),
        array('id' => 'misty.alba', 'first_name' => 'Misty', 'last_name' => 'Alba', 'email' => 'misty.alba@asu.edu'),
        array('id' => 'champ.gordon', 'first_name' => 'Champ', 'last_name' => 'Gordon', 'email' => 'champ.gordon@asu.edu'),
        array('id' => 'gordon.freeman', 'first_name' => 'Gordon', 'last_name' => 'Freeman', 'email' => 'gordon.freeman@asu.edu')
      );
      
      foreach($sampleAttributes as $attributes) {
        $user = new User;
        $user->attributes = $attributes;
        $user->id = $attributes['id'];
        $users[] = $user;
      }
      
      self::$_sampleGroupData = $users;
      

    }
    return self::$_sampleGroupData;
  }
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Evaluation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'evaluations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('course_id, name', 'required'),
			array('course_id, published', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('description, published_at, due_at, created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, course_id, name, description, published, published_at, due_at, created_at', 'safe', 'on'=>'search'),
		);
	}
  
  /**
   * Checks to see if the given user took the evaluation
   * @param User $user the user to check
   * @param CourseGroup $group the course group
   * @return boolean TRUE if the user took the evaluation, FALSE otherwise.
   */
  public function userTookEvaluation(User $user, CourseGroup $group) {
    return EvaluationResponseSet::model()->where(array(
      'evaluation_id'   => $this->id,
      'course_group_id' => $group->id,
      'user_id'         => $user->id
    ))->count() != 0;
  }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'questions' => array(self::HAS_MANY, 'EvaluationQuestion', 'evaluation_id'),
			'response_sets' => array(self::HAS_MANY, 'EvaluationResponseSet', 'evaluation_id'),
			'course' => array(self::BELONGS_TO, 'Course', 'course_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'course_id' => 'Course',
			'name' => 'Name',
			'description' => 'Description',
			'published' => 'Published',
			'published_at' => 'Published At',
			'due_at' => 'Due At',
			'created_at' => 'Created At',
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
		$criteria->compare('published',$this->published);
		$criteria->compare('published_at',$this->published_at,true);
		$criteria->compare('due_at',$this->due_at,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  public function getIsPublished() {
    return ($this->published != 0);
  }
}