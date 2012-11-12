<?php

/**
 * This is the model class for table "call_list".
 *
 * The followings are the available columns in table 'call_list':
 * @property integer $call_list_id
 * @property string $callback_date_time
 * @property string $call_date_time
 * @property integer $campaign_id
 * @property integer $employee_id
 * @property string $phone_number
 * @property string $firstname
 * @property string $lastname
 * @property string $e_mail_id
 * @property string $street
 * @property string $st_or_province
 * @property string $city
 * @property string $postal_code
 * @property integer $time_zone_id
 * @property string $leadcode
 * @property integer $list_id
 * @property integer $priority
 * @property integer $last_termination
 * @property string $comment
 * @property string $date_inserted
 * @property integer $upload_id
 * @property string $callerid_num
 * @property string $callerid_name
 * @property integer $hold_time
 * @property integer $customerId
 * @property string $account_code
 * @property integer $locale_id
 * @property integer $test_id
 * @property integer $deleted
 * @property integer $prev_termination
 * @property integer $temp_call_list_id
 * @property integer $callable
 * @property string $transfer_callerid_name
 * @property string $transfer_callerid_num
 * @property integer $call_count
 * @property integer $dial_count
 * @property integer $max_dial_count
 * @property integer $max_call_count
 * @property integer $rule_id
 * @property integer $phone_type_id
 * @property integer $phone_loaded_index
 *
 * The followings are the available model relations:
 * @property CallHistory[] $callHistories
 * @property Tests $test
 * @property Campaign $campaign
 * @property Employee $employee
 * @property Lists $list
 * @property TimeZoneLocale $locale
 * @property CallTerminations $lastTermination
 * @property TimeZone $timeZone
 * @property CustomQuestionResponseHistory[] $customQuestionResponseHistories
 * @property CustomQuestions[] $customQuestions
 * @property IswitchCallHistory[] $iswitchCallHistories
 */
class CallList extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CallList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return CDbConnection database connection
	 */
	public function getDbConnection()
	{
        return Yii::app()->indosoftDb;
    }
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'call_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaign_id, employee_id, time_zone_id, list_id, priority, last_termination, upload_id, hold_time, customerId, locale_id, test_id, deleted, prev_termination, temp_call_list_id, callable, call_count, dial_count, max_dial_count, max_call_count, rule_id, phone_type_id, phone_loaded_index', 'numerical', 'integerOnly'=>true),
			array('phone_number, callerid_num, account_code', 'length', 'max'=>20),
			array('firstname, lastname, e_mail_id, city, callerid_name, transfer_callerid_name, transfer_callerid_num', 'length', 'max'=>50),
			array('street', 'length', 'max'=>240),
			array('st_or_province', 'length', 'max'=>15),
			array('postal_code, leadcode', 'length', 'max'=>10),
			array('callback_date_time, call_date_time, comment, date_inserted', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('call_list_id, callback_date_time, call_date_time, campaign_id, employee_id, phone_number, firstname, lastname, e_mail_id, street, st_or_province, city, postal_code, time_zone_id, leadcode, list_id, priority, last_termination, comment, date_inserted, upload_id, callerid_num, callerid_name, hold_time, customerId, account_code, locale_id, test_id, deleted, prev_termination, temp_call_list_id, callable, transfer_callerid_name, transfer_callerid_num, call_count, dial_count, max_dial_count, max_call_count, rule_id, phone_type_id, phone_loaded_index', 'safe', 'on'=>'search'),
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
			'callHistories' => array(self::HAS_MANY, 'CallHistory', 'call_list_id'),
			'test' => array(self::BELONGS_TO, 'Tests', 'test_id'),
			'campaign' => array(self::BELONGS_TO, 'Campaign', 'campaign_id'),
			'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
			'list' => array(self::BELONGS_TO, 'Lists', 'list_id'),
			'locale' => array(self::BELONGS_TO, 'TimeZoneLocale', 'locale_id'),
			'lastTermination' => array(self::BELONGS_TO, 'CallTerminations', 'last_termination'),
			'timeZone' => array(self::BELONGS_TO, 'TimeZone', 'time_zone_id'),
			'customQuestionResponseHistories' => array(self::HAS_MANY, 'CustomQuestionResponseHistory', 'call_list_id'),
			'customQuestions' => array(self::MANY_MANY, 'CustomQuestions', 'custom_question_responses(call_list_id, question_id)'),
			'iswitchCallHistories' => array(self::HAS_MANY, 'IswitchCallHistory', 'call_list_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'call_list_id' => 'Call List',
			'callback_date_time' => 'Callback Date Time',
			'call_date_time' => 'Call Date Time',
			'campaign_id' => 'Campaign',
			'employee_id' => 'Employee',
			'phone_number' => 'Phone Number',
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'e_mail_id' => 'E Mail',
			'street' => 'Street',
			'st_or_province' => 'St Or Province',
			'city' => 'City',
			'postal_code' => 'Postal Code',
			'time_zone_id' => 'Time Zone',
			'leadcode' => 'Leadcode',
			'list_id' => 'List',
			'priority' => 'Priority',
			'last_termination' => 'Last Termination',
			'comment' => 'Comment',
			'date_inserted' => 'Date Inserted',
			'upload_id' => 'Upload',
			'callerid_num' => 'Callerid Num',
			'callerid_name' => 'Callerid Name',
			'hold_time' => 'Hold Time',
			'customerId' => 'Customer',
			'account_code' => 'Account Code',
			'locale_id' => 'Locale',
			'test_id' => 'Test',
			'deleted' => 'Deleted',
			'prev_termination' => 'Prev Termination',
			'temp_call_list_id' => 'Temp Call List',
			'callable' => 'Callable',
			'transfer_callerid_name' => 'Transfer Callerid Name',
			'transfer_callerid_num' => 'Transfer Callerid Num',
			'call_count' => 'Call Count',
			'dial_count' => 'Dial Count',
			'max_dial_count' => 'Max Dial Count',
			'max_call_count' => 'Max Call Count',
			'rule_id' => 'Rule',
			'phone_type_id' => 'Phone Type',
			'phone_loaded_index' => 'Phone Loaded Index',
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

		$criteria->compare('call_list_id',$this->call_list_id);
		$criteria->compare('callback_date_time',$this->callback_date_time,true);
		$criteria->compare('call_date_time',$this->call_date_time,true);
		$criteria->compare('campaign_id',$this->campaign_id);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('e_mail_id',$this->e_mail_id,true);
		$criteria->compare('street',$this->street,true);
		$criteria->compare('st_or_province',$this->st_or_province,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('time_zone_id',$this->time_zone_id);
		$criteria->compare('leadcode',$this->leadcode,true);
		$criteria->compare('list_id',$this->list_id);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('last_termination',$this->last_termination);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('date_inserted',$this->date_inserted,true);
		$criteria->compare('upload_id',$this->upload_id);
		$criteria->compare('callerid_num',$this->callerid_num,true);
		$criteria->compare('callerid_name',$this->callerid_name,true);
		$criteria->compare('hold_time',$this->hold_time);
		$criteria->compare('customerId',$this->customerId);
		$criteria->compare('account_code',$this->account_code,true);
		$criteria->compare('locale_id',$this->locale_id);
		$criteria->compare('test_id',$this->test_id);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('prev_termination',$this->prev_termination);
		$criteria->compare('temp_call_list_id',$this->temp_call_list_id);
		$criteria->compare('callable',$this->callable);
		$criteria->compare('transfer_callerid_name',$this->transfer_callerid_name,true);
		$criteria->compare('transfer_callerid_num',$this->transfer_callerid_num,true);
		$criteria->compare('call_count',$this->call_count);
		$criteria->compare('dial_count',$this->dial_count);
		$criteria->compare('max_dial_count',$this->max_dial_count);
		$criteria->compare('max_call_count',$this->max_call_count);
		$criteria->compare('rule_id',$this->rule_id);
		$criteria->compare('phone_type_id',$this->phone_type_id);
		$criteria->compare('phone_loaded_index',$this->phone_loaded_index);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}