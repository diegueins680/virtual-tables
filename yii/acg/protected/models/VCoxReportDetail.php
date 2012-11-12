<?php

/**
 * This is the model class for table "v_cox_report_detail".
 *
 * The followings are the available columns in table 'v_cox_report_detail':
 * @property string $customer_name
 * @property string $contact_name
 * @property string $primary_phone
 * @property string $inception_dt
 * @property string $bundling_category
 * @property string $call_date_time
 * @property integer $dial_count
 * @property string $call_termination_type
 * @property string $acct_activation
 * @property string $backup_activation
 * @property string $security_activation
 * @property string $csap
 * @property string $referral
 * @property string $renewal
 * @property string $Sale
 * @property string $sale_mrc
 * @property string $pending
 * @property string $bill_review
 * @property string $email_update
 * @property string $referral_intro
 * @property string $referral_company
 * @property string $referral_name
 * @property string $referral_tn
 * @property string $transferred_or_referred
 * @property string $market
 * @property string $voicemail
 * @property string $Notes
 * @property string $overall_satisfied
 * @property string $order_satisfied
 * @property string $Scheduling_Satisfied
 * @property string $install_satisfied
 * @property string $list_name
 */
class VCoxReportDetail extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return VCoxReportDetail the static model class
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
		return Yii::app()->icude;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'v_cox_report_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('call_termination_type', 'required'),
			array('dial_count', 'numerical', 'integerOnly'=>true),
			array('customer_name, contact_name, bundling_category, acct_activation, backup_activation, security_activation, csap, referral, renewal, Sale, sale_mrc, pending, bill_review, email_update, referral_intro, referral_company, referral_name, referral_tn, transferred_or_referred, market, voicemail, Notes', 'length', 'max'=>255),
			array('primary_phone', 'length', 'max'=>20),
			array('inception_dt, overall_satisfied, order_satisfied, Scheduling_Satisfied, install_satisfied, list_name', 'length', 'max'=>50),
			array('call_termination_type', 'length', 'max'=>240),
			array('call_date_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_name, contact_name, primary_phone, inception_dt, bundling_category, call_date_time, dial_count, call_termination_type, acct_activation, backup_activation, security_activation, csap, referral, renewal, Sale, sale_mrc, pending, bill_review, email_update, referral_intro, referral_company, referral_name, referral_tn, transferred_or_referred, market, voicemail, Notes, overall_satisfied, order_satisfied, Scheduling_Satisfied, install_satisfied, list_name', 'safe', 'on'=>'search'),
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
			'customer_name' => 'Customer Name',
			'contact_name' => 'Contact Name',
			'primary_phone' => 'Primary Phone',
			'inception_dt' => 'Inception Dt',
			'bundling_category' => 'Bundling Category',
			'call_date_time' => 'Call Date Time',
			'dial_count' => 'Dial Count',
			'call_termination_type' => 'Call Termination Type',
			'acct_activation' => 'Acct Activation',
			'backup_activation' => 'Backup Activation',
			'security_activation' => 'Security Activation',
			'csap' => 'Csap',
			'referral' => 'Referral',
			'renewal' => 'Renewal',
			'Sale' => 'Sale',
			'sale_mrc' => 'Sale Mrc',
			'pending' => 'Pending',
			'bill_review' => 'Bill Review',
			'email_update' => 'Email Update',
			'referral_intro' => 'Referral Intro',
			'referral_company' => 'Referral Company',
			'referral_name' => 'Referral Name',
			'referral_tn' => 'Referral Tn',
			'transferred_or_referred' => 'Transferred Or Referred',
			'market' => 'Market',
			'voicemail' => 'Voicemail',
			'Notes' => 'Notes',
			'overall_satisfied' => 'Overall Satisfied',
			'order_satisfied' => 'Order Satisfied',
			'Scheduling_Satisfied' => 'Scheduling Satisfied',
			'install_satisfied' => 'Install Satisfied',
			'list_name' => 'List Name',
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

		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('primary_phone',$this->primary_phone,true);
		$criteria->compare('inception_dt',$this->inception_dt,true);
		$criteria->compare('bundling_category',$this->bundling_category,true);
		$criteria->compare('call_date_time',$this->call_date_time,true);
		$criteria->compare('dial_count',$this->dial_count);
		$criteria->compare('call_termination_type',$this->call_termination_type,true);
		$criteria->compare('acct_activation',$this->acct_activation,true);
		$criteria->compare('backup_activation',$this->backup_activation,true);
		$criteria->compare('security_activation',$this->security_activation,true);
		$criteria->compare('csap',$this->csap,true);
		$criteria->compare('referral',$this->referral,true);
		$criteria->compare('renewal',$this->renewal,true);
		$criteria->compare('Sale',$this->Sale,true);
		$criteria->compare('sale_mrc',$this->sale_mrc,true);
		$criteria->compare('pending',$this->pending,true);
		$criteria->compare('bill_review',$this->bill_review,true);
		$criteria->compare('email_update',$this->email_update,true);
		$criteria->compare('referral_intro',$this->referral_intro,true);
		$criteria->compare('referral_company',$this->referral_company,true);
		$criteria->compare('referral_name',$this->referral_name,true);
		$criteria->compare('referral_tn',$this->referral_tn,true);
		$criteria->compare('transferred_or_referred',$this->transferred_or_referred,true);
		$criteria->compare('market',$this->market,true);
		$criteria->compare('voicemail',$this->voicemail,true);
		$criteria->compare('Notes',$this->Notes,true);
		$criteria->compare('overall_satisfied',$this->overall_satisfied,true);
		$criteria->compare('order_satisfied',$this->order_satisfied,true);
		$criteria->compare('Scheduling_Satisfied',$this->Scheduling_Satisfied,true);
		$criteria->compare('install_satisfied',$this->install_satisfied,true);
		$criteria->compare('list_name',$this->list_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}