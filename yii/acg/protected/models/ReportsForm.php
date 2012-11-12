<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ReportsForm extends CFormModel
{
	public $startDate;
	public $endDate;
	public $market;
	public $dateRange;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('startDate, endDate, dateRange', 'required'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return 
		[
			'startDate'=>'Start Date',
			'endDate'=>'End Date',
			'market'=>'Market',
			'dateRange'=>'Date Range'
		];
	}
}