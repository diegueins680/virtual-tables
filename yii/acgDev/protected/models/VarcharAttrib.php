<?php

/**
 * This is the model class for table "varchar_attribute".
 *
 * The followings are the available columns in table 'varchar_attribute':
 * @property integer $varchar_attribute_id
 * @property integer $object_id
 * @property integer $attribute_type_id
 * @property integer $table_id
 * @property string $varchar_attribute_value
 *
 * The followings are the available model relations:
 * @property AttributeType $attributeType
 * @property AttributeType $table
 * @property Object $object
 */
class VarcharAttrib extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return VarcharAttrib the static model class
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
		return 'varchar_attribute';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('object_id, attribute_type_id, table_id, varchar_attribute_value', 'required'),
			array('object_id, attribute_type_id, table_id', 'numerical', 'integerOnly'=>true),
			array('varchar_attribute_value', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('varchar_attribute_id, object_id, attribute_type_id, table_id, varchar_attribute_value', 'safe', 'on'=>'search'),
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
			'attributeType' => array(self::BELONGS_TO, 'AttributeType', 'attribute_type_id'),
			'table' => array(self::BELONGS_TO, 'AttributeType', 'table_id'),
			'object' => array(self::BELONGS_TO, 'Object', 'object_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'varchar_attribute_id' => 'Varchar Attribute',
			'object_id' => 'Object',
			'attribute_type_id' => 'Attribute Type',
			'table_id' => 'Table',
			'varchar_attribute_value' => 'Varchar Attribute Value',
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

		$criteria->compare('varchar_attribute_id',$this->varchar_attribute_id);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('attribute_type_id',$this->attribute_type_id);
		$criteria->compare('table_id',$this->table_id);
		$criteria->compare('varchar_attribute_value',$this->varchar_attribute_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}