<?php
//print_r($_SERVER);
//mkdir('hwjTest');

$xml = simplexml_load_file('./appCreate.ini');

$msg = "========================================\n";
$msg .= "用法：php appCreate.php hwjTest 1\n";
$msg .= "参数说明：\n";
$msg .= "hwjTest 模块名称\n";
$msg .= "1(默认) 模板名称，使用数字\n\n";
$msg .= "目前已有模板：\n";
foreach ($xml->template as $t){
	$msg .= $t->code.": ".$t->name."	".$t->desc."\n";
}
$msg .= "========================================\n";

if (empty($_SERVER['argv'][1])){
	echo $msg;
	exit;
}

$dirName = trim($_SERVER['argv'][1]);
$typeName = ''; 
$templateName = empty($_SERVER['argv'][2]) ? 1 : trim($_SERVER['argv'][2]);

foreach ($xml->template as $t){
	if ($templateName == $t->code){
		$typeName = (string)$t->name;
		break;
	}
}

if (empty($typeName)){
	echo '模板数字id不存在！';
	exit;
}

if (mkdir('../../../views/risk/'.$dirName)){
	$classFile = file_get_contents("./{$typeName}/{$typeName}Controller.php");
	$modelFile = file_get_contents("./{$typeName}/{$typeName}Manager.php");
	$templateFile = file_get_contents("./{$typeName}/{$typeName}.tpl");

	$oneStr = strtolower(substr($dirName, 0, 1));
	$twoStr = substr($dirName, 1);
	$tplStr = $oneStr . $twoStr;

	$classFile = strtr($classFile, array($typeName=>$dirName));
	$modelFile = strtr($modelFile, array($typeName=>$dirName));
	$templateFile = strtr($templateFile, array($typeName=>$tplStr));

	file_put_contents('../controllers/'.$dirName.'Controller.php', $classFile);
	file_put_contents('../managers/'.$dirName.'Manager.php', $modelFile);
	file_put_contents('../../../views/risk/'.$dirName.'/'.$dirName.'.tpl', $templateFile);

	echo "模块{$dirName}创建完成！\n";
}else{
	echo "模块{$dirName}创建失败！\n";
}



