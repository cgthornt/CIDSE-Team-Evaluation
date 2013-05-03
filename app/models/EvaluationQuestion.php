<?php

YiiBase::import("application.models.questions.*");

/**
 * This is the model class for table "evaluation_questions".
 *
 * The followings are the available columns in table 'evaluation_questions':
 * @property integer $id
 * @property integer $evaluation_id
 * @property string $content
 * @property string $hint
 * @property string $type
 * @property integer $order
 * @property integer $allow_self
 * @property string $options
 *
 * The followings are the available model relations:
 * @property EvaluationAnswers[] $evaluationAnswers
 * @property Evaluations $evaluation
 * @property EvaluationResponses[] $evaluationResponses
 */
class EvaluationQuestion extends Model
{



	protected $_questionTypeModel;

	public function getQuestionTypeModel() {
		if(empty($this->_questionTypeModel)) {
			$type = $this->type;
			if(!in_array($type, self::$QUESTION_TYPES)) $type = 'OneToN';
			$this->_questionTypeModel = new $type($this);
			$this->_questionTypeModel->attributes = $this->options;
		}
		return $this->_questionTypeModel;
	}

	public static $QUESTION_TYPES = array('OneToN', 'PercentPie');

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EvaluationQuestion the static model class
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
		return 'evaluation_questions';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

		array('type', 'in', 'range' => self::$QUESTION_TYPES),

		array('evaluation_id, type, title', 'required'),
		array('evaluation_id, order, allow_self', 'numerical', 'integerOnly'=>true),
		array('title, type', 'length', 'max'=>255),
		array('instructions', 'length', 'max' => 3000),
		array('content, options', 'safe'),
		// The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array('id, evaluation_id, title, instructions, type, order, allow_self, options', 'safe', 'on'=>'search'),
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
			'answers' => array(self::HAS_MANY, 'EvaluationAnswer', 'evaluation_question_id'),
			'evaluation' => array(self::BELONGS_TO, 'Evaluation', 'evaluation_id'),
			'responses' => array(self::HAS_MANY, 'EvaluationResponse', 'evaluation_question_id'),
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
			'content' => 'Question Text',
			'hint' => 'Hint',
			'type' => 'Type',
			'order' => 'Order',
			'allow_self' => 'Allow students to evaluate themself',
			'options' => 'Options',
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('hint',$this->hint,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('order',$this->order);
		$criteria->compare('allow_self',$this->allow_self);
		$criteria->compare('options',$this->options,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function onBeforeValidate() {
		$mdl = $this->questionTypeModel;
		$mdl->attributes = $this->options;
		if(!$mdl->validate()) {
			foreach($mdl->errors as $attribute => $errors) {
				foreach($errors as $e)
				$this->addError('options', $e);
			}
		}
	}

	public function onBeforeSave() {
		$this->options = serialize($this->options);
	}

	public function onAfterFind() {
		$this->options = unserialize($this->options);
	}

	public function defaultScope() {
		return array('order' => '`order` ASC');
	}

	static public function  loadQustionLib()
	{
		$cmd = Yii::app()->db->createCommand()
		->select('*')
		->from('evaluation_questions')
		->where('in_lib = 1');
			
		return $cmd->queryAll();
	}

	static public function  loadALLQuestions()
	{
		$cmd = Yii::app()->db->createCommand()
		->select('*')
		->from('evaluation_questions');
			
		return $cmd->queryAll();
	}

	static public function importQueToDb($id,$evaluation_id)
	{
		foreach($id as $copiedId)
		{
			//firstly load the question
			$cmd = Yii::app()->db->createCommand()
			->select('*')
			->from('evaluation_questions')
			->where('id ='.$copiedId);
			$sampleQue = $cmd->queryAll();
			$sampleQue = $sampleQue[0];
			// then insert into database as a copy
			$cmd = Yii::app()->db->createCommand()
			->insert('evaluation_questions', array('evaluation_id'=>$evaluation_id,
  						'instructions'=>$sampleQue['instructions'],'title'=>$sampleQue['title'],
						'type'=>$sampleQue['type'],'allow_self'=>$sampleQue['allow_self'],
  						'options'=>$sampleQue['options'],));
		}
	}


	static public function updataQueLib($addedIntoLib,$deletedFromLib)
	{
		//add questions into library by update the column in_lib
		foreach($addedIntoLib as $addQueId)
		{
			$cmd = Yii::app()->db->createCommand()
			->update('evaluation_questions', array('in_lib'=>'1'),'id='.$addQueId);
		}
		
			foreach($deletedFromLib as $deletedQueId)
		{
			$cmd = Yii::app()->db->createCommand()
			->update('evaluation_questions', array('in_lib'=>'0'),'id='.$deletedQueId);
		}
	}

}