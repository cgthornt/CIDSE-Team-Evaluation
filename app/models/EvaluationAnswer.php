<?php

/**
 * This is the model class for table "evaluation_answers".
 *
 * The followings are the available columns in table 'evaluation_answers':
 * @property integer $id
 * @property integer $evaluation_question_id
 * @property string $content
 * @property string $hint
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property EvaluationQuestions $evaluationQuestion
 * @property EvaluationResponses[] $evaluationResponses
 */
class EvaluationAnswer extends Model
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EvaluationAnswer the static model class
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
		return 'evaluation_answers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('evaluation_question_id', 'required'),
			array('evaluation_question_id, order', 'numerical', 'integerOnly'=>true),
			array('hint', 'length', 'max'=>255),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, evaluation_question_id, content, hint, order', 'safe', 'on'=>'search'),
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
			'evaluationQuestion' => array(self::BELONGS_TO, 'EvaluationQuestion', 'evaluation_question_id'),
			'evaluationResponses' => array(self::HAS_MANY, 'EvaluationResponse', 'evaluation_answer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'evaluation_question_id' => 'Evaluation Question',
			'content' => 'Content',
			'hint' => 'Hint',
			'order' => 'Order',
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
		$criteria->compare('evaluation_question_id',$this->evaluation_question_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('hint',$this->hint,true);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}