<?php

/**
 * This is the model class for table "evaluation_responses".
 *
 * The followings are the available columns in table 'evaluation_responses':
 * @property integer $id
 * @property integer $target_user_id
 * @property integer $evaluation_response_set_id
 * @property integer $evaluation_question_id
 * @property integer $evaluation_answer_id
 * @property string $value
 *
 * The followings are the available model relations:
 * @property EvaluationAnswers $evaluationAnswer
 * @property EvaluationQuestions $evaluationQuestion
 * @property EvaluationResponseSets $evaluationResponseSet
 * @property Users $targetUser
 */
class EvaluationResponse extends Model
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EvaluationResponse the static model class
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
		return 'evaluation_responses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('evaluation_response_set_id, evaluation_question_id', 'required'),
			array('target_user_id, evaluation_response_set_id, evaluation_question_id, evaluation_answer_id', 'numerical', 'integerOnly'=>true),
			array('value', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, target_user_id, evaluation_response_set_id, evaluation_question_id, evaluation_answer_id, value', 'safe', 'on'=>'search'),
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
			'evaluationAnswer' => array(self::BELONGS_TO, 'EvaluationAnswer', 'evaluation_answer_id'),
			'evaluationQuestion' => array(self::BELONGS_TO, 'EvaluationQuestion', 'evaluation_question_id'),
			'response_sets' => array(self::BELONGS_TO, 'EvaluationResponseSet', 'evaluation_response_set_id'),
			'target_user' => array(self::BELONGS_TO, 'User', 'target_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'target_user_id' => 'Target User',
			'evaluation_response_set_id' => 'Evaluation Response Set',
			'evaluation_question_id' => 'Evaluation Question',
			'evaluation_answer_id' => 'Evaluation Answer',
			'value' => 'Value',
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
		$criteria->compare('target_user_id',$this->target_user_id);
		$criteria->compare('evaluation_response_set_id',$this->evaluation_response_set_id);
		$criteria->compare('evaluation_question_id',$this->evaluation_question_id);
		$criteria->compare('evaluation_answer_id',$this->evaluation_answer_id);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}