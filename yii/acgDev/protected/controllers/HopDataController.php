<?php

class HopDataController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return 
		[
			[
				'allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>['index','view'],
				'users'=>['@'],
			],
			[
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>['create','update', 'admin'],
				'roles'=>['Agent'],
			],
			[
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>['admin','delete'],
				'roles'=>['Admin'],
			],
			
			[
				'deny',  // deny all users
				'actions'=>['create','update', 'admin','delete'],
				'users'=>['*']
			],
		];
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		//var_dump(Yii::app()->user);
		$this->render
		(
				'view',
				[
					'model'=>$this->loadModel($id)
				]
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new HopData;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['HopData']))
		{
			$model->attributes=$_POST['HopData'];
			if($model->save())
				$this->redirect
				(
						[
							'view','id'=>$model->idhop_data_id
						]
				);
		}

		$this->render
		(
				'create',
				[
					'model'=>$model
				]
		);
	}
	
	public function generateHttpPost($id)
	{
		$model = $this->loadModel($id);
		$ignoreFields = ['hop_data_id', 'add_id', 'idcrrt'];
		$httpPostString = '';
		var_dump($model->getMetaData()->columns);
		foreach($model->attributeLabels() as $fieldKey => $fieldLabel)
		{
			if(!in_array($fieldKey, $ignoreFields))
			{
				$httpPostString .= "'".urlencode($model->$fieldKey)."',";
			}
		}
		$httpPostString = 'http://www.dxc-inc.com/HOPEnergy?sql=execute dxc_sp_HOP_Energy_TM_Data_Insert '.$httpPostString;
		$httpPostString = trim($httpPostString, ',');
		$httpPostString.= '&root=dxc';

		return $httpPostString;
	}
	
	public function sendHttpPost($id)
	{
		$url = $this->generateHttpPost($id);
		$url = str_replace(" ", '%20', $url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		return curl_exec($ch);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['HopData']))
		{
			$model->attributes=$_POST['HopData'];
			if($model->save())
			{
				$this->sendHttpPost($id);
				$this->redirect
				(
						[
							'view','id'=>$model->idhop_data_id
						]
				);
			}
		}

		$this->render
		(
				'update',
				[
					'model'=>$model
				]
		);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('HopData');
		$this->render
		(
				'index',
				[
					'dataProvider'=>$dataProvider,
				]
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new HopData('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['HopData']))
			$model->attributes=$_GET['HopData'];

		$this->render
		(
				'admin',
				[
					'model'=>$model
				]
		);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=HopData::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='hop-data-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
