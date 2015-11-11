<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

$config = array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '../',
	'name' => 'tuan',
	// preloading 'log' component
	'preload' => array('log'),
	'defaultController' => 'audit',
	// autoloading model and component classes
	'import' => array(
		'framework.components.*',
		'framework.extensions.*',
		'framework.extensions.smarty.sysplugins.*',
		'framework.extensions.yii-mail.*',
		'application.models.*',
		'application.components.*',
		'application.managers.*',
		'application.extensions.*',
		'application.modules.*',
		'application.modules.qingcang.*',
		'application.modules.qingcang.managers.*',
	),
	'modules' => array(
			'qingcang',
	),
	// application components
	'components' => array(
		'curl' => array(
			'class' => 'Curl',
			'options' => array(),
		),
		'session' => array(
			'autoStart' => true,
			'class' => 'CCacheHttpSession',
			'sessionName' => 'tuan',
			'cacheID' => 'sessionCache',
			'cookieMode' => 'only',
			'timeout' => 7200,
			//'cookieParams' => array('domain' => "focus.meiliworks.com"),
		),
		'sessionCache' => array(
			'class' => 'CRedisCache',
			'hostname' => 'localhost',
			'port' => 6379,
			'database' => 0,
		),
		'cache' => array(
			'class' => 'CRedisCache',
			'hostname' => 'localhost',
			'port' => 6379,
			'database' => 0,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array(
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
		),
		'user' => array(
			// enable cookie-based authentication
			'class' => 'User',
			'allowAutoLogin' => true,
		),
		'errorHandler' => array(
			// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
			),
		),
		'smarty' => array(
			'class' => 'framework.extensions.CSmarty',
		),
		'mail' => array(
			'class' => 'framework.extensions.yii-mail.YiiMail',
			'transportType' => 'smtp',
			'viewPath' => 'application.views.mail',
		),
		'image' => array(
		        'class' => 'ImageLogic',
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => require (dirname(__FILE__) . '/params.php'),
);

$database = require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'db.php';

if (!empty($database)) {
	$config['components'] = CMap::mergeArray($config['components'], $database);
}

return $config;
