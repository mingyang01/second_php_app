<?php
// error_reporting(E_ERROR|E_COMPILE_ERROR);

define('APP_PATH', dirname(dirname(__FILE__)));

defined('YII_DEBUG') or define('YII_DEBUG',false);
require_once dirname(__FILE__) . "/config/common.php";
// change the following paths if necessary

$yiic='/home/work/framework/yii/yiic.php';
//$yiic=dirname(__FILE__).'/../framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);