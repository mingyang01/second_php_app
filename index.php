<?php

define('YII_DEBUG', true);
//define('CHECKACCESS', true);

require_once dirname(__FILE__) . "/protected/config/common.php";
// change the following paths if necessary
define('APP_PATH', dirname(__FILE__));
define('FRAMEWORK', '/home/work/framework');
require_once FRAMEWORK . '/yii/yii.php';
Yii::setPathOfAlias('framework', FRAMEWORK);

if (YII_DEBUG) {
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
    error_reporting(E_ERROR);
    require_once dirname(__FILE__) . "/protected/config/local/main.php";
} else {
    error_reporting(E_ERROR);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 1);
}


// 加载公用函数库
require_once dirname(__FILE__) . "/protected/config/helper.php";

//
$config = dirname(__FILE__) . '/protected/config/main.php';

$application = Yii::createWebApplication($config);

// 系统日志, 在开发机可以注释掉
//addSystemAccessLog();

$application->run();