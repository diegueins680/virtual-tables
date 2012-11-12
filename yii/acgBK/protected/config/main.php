<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return
[
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ACG Systems',

	'preload'=>['log'],

	// autoloading model and component classes
	'import'=>
	[
		'application.models.*',
		'application.components.*',
		'application.modules.rights.*',
		'application.modules.rights.components.*',
	],
	'modules'=>
	[
		'hop',
	// uncomment the following to enable the Gii tool
		'gii'=>
		[
			'class'=>'system.gii.GiiModule',
			'password'=>'acgwpb12',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>['127.0.0.1','::1'],
		]
	],
	// application components
	'components'=>
	[
		'user'=>
		[
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		],
		// uncomment the following to enable URLs in path-format	
			'urlManager'=>
			[
				'urlFormat'=>'path',
				'rules'=>
				[
					'<controller:\w+>/<id:\d+>'=>'<controller>/view',
					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				],
			],
			'db'=>
			[
				/*'connectionString' => 'mysql:host='.APP::$CONF_MYSQL_RW[AppConst::HOST]
					.';dbname='.APP::$CONF_MYSQL_RW[AppConst::DB_NAME],
				'emulatePrepare' => true,
				'username' => APP::$CONF_MYSQL_RW[AppConst::USER],
				'password' => App::$CONF_MYSQL_RW[AppConst::PASS],
				'charset' => 'utf8',*/
			],
			'indosoftDb'=>
			[
				'connectionString' => 'mysql:host=192.168.1.235;dbname=cti',
				'emulatePrepare' => true,
				'username' => 'acg-admin',
				'password' => 'jasu5Red',
				'charset' => 'utf8',
				'class' => 'CDbConnection'
			],		
						/*
			'db'=>
			[
				'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
			]
		*/
			'errorHandler'=>
			[
				// use 'site/error' action to display errors
				'errorAction'=>'site/error',
			],
			'log'=>
			[
				'class'=>'CLogRouter',
				'routes'=>
				[
					[
						'class'=>'CFileLogRoute',
						'levels'=>'error, warning',
					],
					// uncomment the following to show log messages on web pages
					
					[
						'class'=>'CWebLogRoute',
					]
				]
			]
		],
		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>
		[
			// this is used in contact page
			'adminEmail'=>'diego.saa@onlineacg.com',
		]
	];

/*
return 
[
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ACG Systems',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'acgwpb12',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>
	[
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		/*
		'db'=>
		[
			'connectionString' => 'mysql:host='.APP::$CONF_MYSQL_RW[AppConst::HOST]
				.';dbname='.APP::$CONF_MYSQL_RW[AppConst::DB_NAME],
			'emulatePrepare' => true,
			'username' => APP::$CONF_MYSQL_RW[AppConst::USER],
			'password' => App::$CONF_MYSQL_RW[AppConst::PASS],
			'charset' => 'utf8',
		],
		
		'indosoftDb'=>
		[
			'connectionString' => 'mysql:host=192.168.1.235;dbname=cti',
			'emulatePrepare' => true,
			'username' => 'acg-admin',
			'password' => 'jasu5Red',
			'charset' => 'utf8',
			'class' => 'CDbConnection'
		],
		
		'errorHandler'=>
		[
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		],
		'log'=>
		[
			'class'=>'CLogRouter',
			'routes'=>
			[
				[
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				],
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*//*
			],
		],
	],
*/
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	/*
	'params'=>
	[
		// this is used in contact page
		'adminEmail'=>'diego.saa@onlineacg.com',
	],
];*/
?>