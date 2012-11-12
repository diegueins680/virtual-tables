<?php

/**
 * This is the model class for table "ht_agent_entry".
 *
 * The followings are the available columns in table 'ht_agent_entry':
 * @property integer $ID
 * @property string $call_termination
 * @property string $Start_Date
 * @property string $Acct_Activation
 * @property string $Agent
 * @property integer $call_list_id
 * @property string $ht_BTN
 * @property string $station
 * @property string $Backup_Activation
 * @property string $Security_Activation
 * @property string $CSAP
 * @property string $Referral
 * @property string $Renewal
 * @property string $Sale
 * @property string $Sale_MRC
 * @property string $Pending
 * @property string $Bill_Review
 * @property string $Email_Update
 * @property string $Referral_Intro
 * @property string $Referral_Company
 * @property string $Referral_Name
 * @property string $Referral_Tn
 * @property string $Transferred_or_referred
 * @property string $Market
 * @property string $Voicemail
 * @property string $Notes
 */
class HtAgentEntry extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return HtAgentEntry the static model class
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
		return 'ht_agent_entry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('call_termination', 'required'),
			array('call_list_id', 'numerical', 'integerOnly'=>true),
			array('call_termination, Acct_Activation, Agent, station, Backup_Activation, Security_Activation, CSAP, Referral, Renewal, Sale, Sale_MRC, Pending, Bill_Review, Email_Update, Referral_Intro, Referral_Company, Referral_Name, Referral_Tn, Transferred_or_referred, Market, Voicemail, Notes', 'length', 'max'=>255),
			array('ht_BTN', 'length', 'max'=>20),
			array('Start_Date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, call_termination, Start_Date, Acct_Activation, Agent, call_list_id, ht_BTN, station, Backup_Activation, Security_Activation, CSAP, Referral, Renewal, Sale, Sale_MRC, Pending, Bill_Review, Email_Update, Referral_Intro, Referral_Company, Referral_Name, Referral_Tn, Transferred_or_referred, Market, Voicemail, Notes', 'safe', 'on'=>'search'),
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
			'ID' => 'ID',
			'call_termination' => 'Call Termination',
			'Start_Date' => 'Start Date',
			'Acct_Activation' => 'Acct Activation',
			'Agent' => 'Agent',
			'call_list_id' => 'Call List',
			'ht_BTN' => 'Ht Btn',
			'station' => 'Station',
			'Backup_Activation' => 'Backup Activation',
			'Security_Activation' => 'Security Activation',
			'CSAP' => 'Csap',
			'Referral' => 'Referral',
			'Renewal' => 'Renewal',
			'Sale' => 'Sale',
			'Sale_MRC' => 'Sale Mrc',
			'Pending' => 'Pending',
			'Bill_Review' => 'Bill Review',
			'Email_Update' => 'Email Update',
			'Referral_Intro' => 'Referral Intro',
			'Referral_Company' => 'Referral Company',
			'Referral_Name' => 'Referral Name',
			'Referral_Tn' => 'Referral Tn',
			'Transferred_or_referred' => 'Transferred Or Referred',
			'Market' => 'Market',
			'Voicemail' => 'Voicemail',
			'Notes' => 'Notes',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('call_termination',$this->call_termination,true);
		$criteria->compare('Start_Date',$this->Start_Date,true);
		$criteria->compare('Acct_Activation',$this->Acct_Activation,true);
		$criteria->compare('Agent',$this->Agent,true);
		$criteria->compare('call_list_id',$this->call_list_id);
		$criteria->compare('ht_BTN',$this->ht_BTN,true);
		$criteria->compare('station',$this->station,true);
		$criteria->compare('Backup_Activation',$this->Backup_Activation,true);
		$criteria->compare('Security_Activation',$this->Security_Activation,true);
		$criteria->compare('CSAP',$this->CSAP,true);
		$criteria->compare('Referral',$this->Referral,true);
		$criteria->compare('Renewal',$this->Renewal,true);
		$criteria->compare('Sale',$this->Sale,true);
		$criteria->compare('Sale_MRC',$this->Sale_MRC,true);
		$criteria->compare('Pending',$this->Pending,true);
		$criteria->compare('Bill_Review',$this->Bill_Review,true);
		$criteria->compare('Email_Update',$this->Email_Update,true);
		$criteria->compare('Referral_Intro',$this->Referral_Intro,true);
		$criteria->compare('Referral_Company',$this->Referral_Company,true);
		$criteria->compare('Referral_Name',$this->Referral_Name,true);
		$criteria->compare('Referral_Tn',$this->Referral_Tn,true);
		$criteria->compare('Transferred_or_referred',$this->Transferred_or_referred,true);
		$criteria->compare('Market',$this->Market,true);
		$criteria->compare('Voicemail',$this->Voicemail,true);
		$criteria->compare('Notes',$this->Notes,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}