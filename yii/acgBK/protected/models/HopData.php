<?php

/**
 * This is the model class for table "hop_data".
 *
 * The followings are the available columns in table 'hop_data':
 * 
 * @property string $vendor_id
 * @property string $agent_id
 * @property string $idhop_data_id
 * @property string $util_type
 * @property string $program_code
 * @property string $sales_state
 * @property string $auth_fname
 * @property string $auth_mi
 * @property string $auth_lname
 * @property string $bill_fname
 * @property string $bill_mi
 * @property string $bill_lname
 * @property string $company_name
 * @property string $company_title
 * @property string $btn
 * @property string $serv_address
 * @property string $serv_city
 * @property string $serv_state
 * @property string $serv_zip
 * @property string $serv_county
 * @property string $bill_address
 * @property string $bill_city
 * @property string $bill_state
 * @property string $bill_zip
 * @property string $acct_type
 * @property string $acct_num
 * @property string $meter_num
 * @property string $rate_class
 * @property string $lead_type
 * @property string $customer_name_key
 * @property string $email_address
 * @property string $serv_ref_num
 * @property string $epod_id
 * @property string $tax_id
 */
class HopData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return HopData the static model class
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
        return Yii::app()->hopDB;
    }
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hop_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('idhop_data_id', 'required'),
//			array('idhop_data_id', 'numerical', 'integerOnly'=>true),
			array('vendor_id', 'length', 'max'=>3),
			array('agent_id, btn', 'length', 'max'=>10),
			array('util_type, company_name, rate_class', 'length', 'max'=>200),
			array('program_code, email_address', 'length', 'max'=>100),
			array('sales_state', 'length', 'max'=>2),
			array('auth_fname, auth_lname, bill_fname, bill_lname, epod_id, tax_id', 'length', 'max'=>25),
			array('auth_mi, bill_mi', 'length', 'max'=>1),
			array('company_title', 'length', 'max'=>50),
			array('serv_address, serv_city, serv_county, bill_address, bill_city', 'length', 'max'=>500),
			array('serv_state, bill_state', 'length', 'max'=>150),
			array('serv_zip, bill_zip, acct_type, acct_num, meter_num, lead_type, serv_ref_num', 'length', 'max'=>250),
			array('customer_name_key', 'length', 'max'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('vendor_id, agent_id, util_type, program_code, sales_state, auth_fname, auth_mi, auth_lname, bill_fname, bill_mi, bill_lname, company_name, company_title, btn, serv_address, serv_city, serv_state, serv_zip, serv_county, bill_address, bill_city, bill_state, bill_zip, acct_type, acct_num, meter_num, rate_class, lead_type, customer_name_key, email_address, serv_ref_num, epod_id, tax_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idhop_data_id' => 'Add_Id',
			'vendor_id' => 'Vendor',
			'agent_id' => 'Agent',
			//'add_id' => 'Add',
			'util_type' => 'Util Type',
			'program_code' => 'Program Code',
			'sales_state' => 'Sales State',
			'auth_fname' => 'Auth Fname',
			'auth_mi' => 'Auth Mi',
			'auth_lname' => 'Auth Lname',
			'bill_fname' => 'Bill Fname',
			'bill_mi' => 'Bill Mi',
			'bill_lname' => 'Bill Lname',
			'company_name' => 'Company Name',
			'company_title' => 'Company Title',
			'btn' => 'Btn',
			'serv_address' => 'Serv Address',
			'serv_city' => 'Serv City',
			'serv_state' => 'Serv State',
			'serv_zip' => 'Serv Zip',
			'serv_county' => 'Serv County',
			'bill_address' => 'Bill Address',
			'bill_city' => 'Bill City',
			'bill_state' => 'Bill State',
			'bill_zip' => 'Bill Zip',
			'acct_type' => 'Acct Type',
			'acct_num' => 'Acct Num',
			'meter_num' => 'Meter Num',
			'rate_class' => 'Rate Class',
			'lead_type' => 'Lead Type',
			'customer_name_key' => 'Customer Name Key',
			'email_address' => 'Email Address',
			'serv_ref_num' => 'Serv Ref Num',
			'epod_id' => 'Epod',
			'tax_id' => 'Tax',
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

		$criteria->compare('idhop_data_id',$this->idhop_data_id);
		//$criteria->compare('vendor_id',$this->vendor_id,true);
		$criteria->compare('agent_id',$this->agent_id,true);
		//$criteria->compare('add_id',$this->add_id,true);
		$criteria->compare('util_type',$this->util_type,true);
		$criteria->compare('program_code',$this->program_code,true);
		$criteria->compare('sales_state',$this->sales_state,true);
		$criteria->compare('auth_fname',$this->auth_fname,true);
		$criteria->compare('auth_mi',$this->auth_mi,true);
		$criteria->compare('auth_lname',$this->auth_lname,true);
		$criteria->compare('bill_fname',$this->bill_fname,true);
		$criteria->compare('bill_mi',$this->bill_mi,true);
		$criteria->compare('bill_lname',$this->bill_lname,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('company_title',$this->company_title,true);
		$criteria->compare('btn',$this->btn,true);
		$criteria->compare('serv_address',$this->serv_address,true);
		$criteria->compare('serv_city',$this->serv_city,true);
		$criteria->compare('serv_state',$this->serv_state,true);
		$criteria->compare('serv_zip',$this->serv_zip,true);
		$criteria->compare('serv_county',$this->serv_county,true);
		$criteria->compare('bill_address',$this->bill_address,true);
		$criteria->compare('bill_city',$this->bill_city,true);
		$criteria->compare('bill_state',$this->bill_state,true);
		$criteria->compare('bill_zip',$this->bill_zip,true);
		$criteria->compare('acct_type',$this->acct_type,true);
		$criteria->compare('acct_num',$this->acct_num,true);
		$criteria->compare('meter_num',$this->meter_num,true);
		$criteria->compare('rate_class',$this->rate_class,true);
		$criteria->compare('lead_type',$this->lead_type,true);
		$criteria->compare('customer_name_key',$this->customer_name_key,true);
		$criteria->compare('email_address',$this->email_address,true);
		$criteria->compare('serv_ref_num',$this->serv_ref_num,true);
		$criteria->compare('epod_id',$this->epod_id,true);
		$criteria->compare('tax_id',$this->tax_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}