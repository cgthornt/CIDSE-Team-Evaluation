<?php

/**
 * This is the model class for table "evaluation_response_sets".
 *
 * The followings are the available columns in table 'evaluation_response_sets':
 * @property integer $id
 * @property integer $evaluation_id
 * @property integer $course_group_id
 * @property integer $user_id
 * @property integer $completed
 * @property string $completed_at
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Evaluations $evaluation
 * @property CourseGroups $courseGroup
 * @property EvaluationResponses[] $evaluationResponses
 */
class EvaluationResponseSet extends Model
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EvaluationResponseSet the static model class
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
		return 'evaluation_response_sets';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('evaluation_id, course_group_id, user_id', 'required'),
			array('evaluation_id, course_group_id, user_id, completed', 'numerical', 'integerOnly'=>true),
			array('completed_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, evaluation_id, course_group_id, user_id, completed, completed_at', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'evaluation' => array(self::BELONGS_TO, 'Evaluation', 'evaluation_id'),
			'courseGroup' => array(self::BELONGS_TO, 'CourseGroup', 'course_group_id'),
			'evaluationResponses' => array(self::HAS_MANY, 'EvaluationResponse', 'evaluation_response_set_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'evaluation_id' => 'Evaluation',
			'course_group_id' => 'Course Group',
			'user_id' => 'User',
			'completed' => 'Completed',
			'completed_at' => 'Completed At',
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
		$criteria->compare('evaluation_id',$this->evaluation_id);
		$criteria->compare('course_group_id',$this->course_group_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('completed',$this->completed);
		$criteria->compare('completed_at',$this->completed_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}