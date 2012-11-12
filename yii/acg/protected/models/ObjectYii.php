<?php

/**
 * This is the model class for table "object".
 *
 * The followings are the available columns in table 'object':
 * @property integer $object_id
 * @property integer $table_id
 *
 * The followings are the available model relations:
 * @property BooleanAttribute[] $booleanAttributes
 * @property DatetimeAttribute[] $datetimeAttributes
 * @property DecimalAttribute[] $decimalAttributes
 * @property IntAttribute[] $intAttributes
 * @property Table $table
 * @property ObjectAttribute[] $objectAttributes
 * @property ObjectAttribute[] $objectAttributes1
 * @property Table[] $tables
 * @property VarcharAttribute[] $varcharAttributes
 */
class ObjectYii extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ObjectYii the static model class
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
		return 'object';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('table_id', 'required'),
			array('table_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('object_id, table_id', 'safe', 'on'=>'search'),
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
			'booleanAttributes' => array(self::HAS_MANY, 'BooleanAttribute', 'object_id'),
			'datetimeAttributes' => array(self::HAS_MANY, 'DatetimeAttribute', 'object_id'),
			'decimalAttributes' => array(self::HAS_MANY, 'DecimalAttribute', 'object_id'),
			'intAttributes' => array(self::HAS_MANY, 'IntAttribute', 'object_id'),
			'table' => array(self::BELONGS_TO, 'Table', 'table_id'),
			'objectAttributes' => array(self::HAS_MANY, 'ObjectAttribute', 'object_id'),
			'objectAttributes1' => array(self::HAS_MANY, 'ObjectAttribute', 'object_attribute_value'),
			'tables' => array(self::MANY_MANY, 'Table', 'object_is_type(object_id, table_id)'),
			'varcharAttributes' => array(self::HAS_MANY, 'VarcharAttribute', 'object_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'object_id' => 'Object',
			'table_id' => 'Table',
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

		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('table_id',$this->table_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}