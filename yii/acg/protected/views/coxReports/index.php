<?php
/* @var $this CoxReportsController */
/* @var $dataProvider CActiveDataProvider */
/* @var $dataProvReportType CActiveDataProvider */
/* @var $dataProvReportCategory CActiveDataProvider */
Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$this->breadcrumbs=
[
	'Cox Reports',
];

$this->menu=
[
	//array('label'=>'Create HopData', 'url'=>array('create')),
	//array('label'=>'Manage HopData', 'url'=>array('admin')),
];

function showReportResults($results)
{
	$this->widget
	(
			'zii.widgets.CListView',
			[
			'dataProvider'=>$results,
			'itemView'=>'_view',
			]
	);
}
?>

<h1>Cox Reports</h1>
<div class="form">

<?php $form=$this->beginWidget
(
		'CActiveForm', 
		[
			'id'=>'reports-form',
			'enableClientValidation'=>true,
			'clientOptions'=>
			[
				'validateOnSubmit'=>true,
			]
		]
); ?>

	<p class="note">Please, select the starting, ending date and a market (if you want to limit your query to a single market).</p>

	<?php 
	$formModel = new ReportsForm();
	$date = new DateTime();
	echo $form->errorSummary($formModel);
	?>
	<div class="row">
		<?php 
		echo $form->labelEx($formModel,'dateRange');
		//echo $form->error($formModel,'dateRange');
		$formModel->dateRange = $date->format('m/d/Y');
		$formModel->market = '';
		if(isset($_POST['ReportsForm']))
		{
			if(isset($_POST['dateRange']))
			{
				$formModel->dateRange  = $_POST['dateRange'];
							
			}
			else
			{
				$formModel->dateRange  = $formModel->dateRange;
			}
			$formModel->market = (isset($_POST['ReportsForm']['market']) ? $_POST['ReportsForm']['market'] : '');
		}
		
		$range = explode('-', $formModel->dateRange);
		$formModel->startDate = trim($range[0]);
		if(isset($range[1]))
		{
			$formModel->endDate = trim($range[1]);
		}
		else
		{
			$endDate = new DateTime($formModel->startDate);
			
		}
		$this->widget
		(
				'zii.widgets.EDateRangePicker.EDateRangePicker',
				[
					'id'=>'dateRange',
					'name'=>'dateRange',
					'model'=>$formModel,
					'value'=>$formModel->dateRange,
					'options'=>array('arrows'=>true),
					'htmlOptions'=>array('class'=>'ReportsForm', 'size'=>15)
				]
		);

/*	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'publishDate',
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'fold',
    ),
    'htmlOptions'=>array(
        'style'=>'height:20px;'
    ),
));*/
?>
		</div>

	<div class="row">
		<?php echo $form->labelEx($formModel,'market'); ?>
		<?php echo $form->textField($formModel,'market'); ?>
		<?php echo $form->error($formModel,'market'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php //var_dump($dataProvReportCategory);
$stats = Stats::getInstance();
$params = new QueryParameter(); 
$params->model = $formModel;
foreach($dataProvReportCategory->getData() as $category)
{ 
	echo '<h3>'.$category->report_category_name.'</h3>';
	$dataProvReportType=new CActiveDataProvider
	(
			'ReportType', 
			[
				'criteria'=>
				[
					'condition'=>'report_category_id='.$category->report_category_id,
				],
				'pagination'=>
				[
					'pageSize' => 20,
				]
			]
	);
	foreach($dataProvReportType->getData() as $reportType)
	{
		echo '<h5>'.$reportType->report_name.'</h5>';
		if($reportType->show)
		{
			$params->functionName = $reportType->function_name;
			$dataProvider=new CArrayDataProvider($stats->getReportData($params), []);
			var_dump($dataProvider);
		}
		// or using: $rawData=User::model()->findAll();
		
				//'id'=>'user',
			//	'sort'=>array(
			//			'attributes'=>array(
			//					'id', 'username', 'email',
				//		),
			//	),
			//	'pagination'=>array(
			//			'pageSize'=>10,
			//	),
	}
	// $dataProvider->getData() will return a list of Post objects
	
	/*$reportTypes = Yii::app()->icude->createCommand()
	->select(array('count(*) as num'))
	// ->select('customer_name')
	->from('report_type')
	//    ->join('tbl_profile p', 'u.id=p.user_id')
	->where('report_category_name=:report_category_name', array(':report_category_name'=>$report_category_name))
	->queryRow();
	var_dump($reportTypes);*/
	
}
/*
$this->widget
		(
			'zii.widgets.CListView', 
			[
				'dataProvider'=>$dataProvReportCategory,
				'itemView'=>'_view',
			]
		); */?>
