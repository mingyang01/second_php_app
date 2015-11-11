<?php
/**
 * 公用函数库
 */

/**
 * 统一输出json格式
 * @param array $r
 * @param string $callback
 */
function output($r=array(), $callback=null)
{
    if ($callback) {
        echo $callback."(".json_encode($r).")";
        exit();
    } else {
        exit(json_encode($r));
    }
}

/**
 *调试
 */
function debug()
{
    if (isset($_GET['debug'])) {
        $args=func_get_args();  //获取多个参数
        echo '<div style="width:100%;text-align:left"><pre>';
        //多个参数循环输出
        foreach($args as $arg){
            if(is_array($arg)){
                print_r($arg);
                echo '<br>';
            }else if(is_string($arg)){
                echo $arg.'<br>';
            }else{
                var_dump($arg);
                echo '<br>';
            }
        }
        echo '</pre></div>';
    }
}

/**
 * 调试
 * @FIXME 请勿使用，正在废弃，随时都有可能删除 ，请使用 debug()
 */
function p()
{
    $args=func_get_args();  //获取多个参数

    echo '<div style="width:100%;text-align:left"><pre>';
    //多个参数循环输出
    foreach($args as $arg){
        if(is_array($arg)){
            print_r($arg);
            echo '<br>';
        }else if(is_string($arg)){
            echo $arg.'<br>';
        }else{
            var_dump($arg);
            echo '<br>';
        }
    }
    echo '</pre></div>';
}

/**
 * 错误消息提示页面函数
 * @param string $msg  提示内容
 * @param string $type  类型  success  正确  error错误样式
 * @param string $redirect_url   跳转地址
 */
function throwMessage($msg="",$type="error", $redirect_url="")
{
    // array('操作成功', 'success', 'event/admin');
    $smarty = Yii::app()->smarty;
    $smarty->assign('msg', $msg);
    $smarty->assign('type', $type);
    $smarty->assign('redirect_url', $redirect_url);
    $smarty->display('layouts/msg.html');
    exit();
}

/**
 * 获取当前登录用户id
 * @return number
 */
function getOperatorId()
{
    if (Yii::app()->user->id) {
        return Yii::app()->user->id;
    } else {
        return 0;
    }
}

/**
 * 获取登录用户名 又向前缀
 * @return string
 */
function getOperatorName(){
    if (Yii::app()->user->username) {
        return Yii::app()->user->name;
    } else {
        return '';
    }
}

/**
 * 获取登录用户名 又向前缀
 * @return string
 */
function getOperatorUserName(){
    if (Yii::app()->user->username) {
        return Yii::app()->user->username;
    } else {
        return '';
    }
}

/**
 * 数组转换
 */
function DataToArray($dbData, $keyword) {
    $retArray = array ();
    if (is_array ( $dbData ) == false or empty ( $dbData )) {
        return $retArray;
    }
    foreach ( $dbData as $oneData ) {
        if (isset ( $oneData [$keyword] ) and empty ( $oneData [$keyword] ) == false) {
            $retArray [] = $oneData [$keyword];
        }
    }
    return $retArray;
}

/**
 * 获取图片地址
 * @param str $path 图片路径
 * @param 缩略图参数 $thumbParam  [0] 裁图类型 [1] 宽 [2] 高
 * @return url
 */
function getImageUrl($path, $thumbParam=array())
{
    if (!$path) return false;
    if ($thumbParam) {
        $thumbPath = Yii::app()->image->generateThumbUrl($path, $thumbParam[0], $thumbParam[1], $thumbParam[2]);
        return Yii::app()->image->getWebsiteImageUrl($thumbPath);
    }
    return Yii::app()->image->getWebsiteImageUrl($path);
}

/**
 * 截取字符串
 * @param string $str
 * @param int $len 截取的长度
 * @param string $fix 补充的省略符
 * @return string
 */
function cutStr($str, $len, $fix = '...')
{
    if (strlen($str) - strlen($fix) <= $len) {
        return $str;
    }

    $returnStr = '';
    $i = 0;
    $n = 0;
    while (($n < $len) && ($i <= strlen($str))) {
        $tempStr = substr($str, $i, 1);
        $ascnum = ord($tempStr);//得到字符串中第$i位字符的ascii码

        //如果ASCII位高与224，
        if ($ascnum >= 224) {
            $returnStr .= substr($str, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i += 3; //实际Byte计为3
            $n++; //字串长度计1

            //如果ASCII位高与192，
        } elseif ($ascnum >= 192) {
            $returnStr .= substr($str, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i += 2; //实际Byte计为2
            $n++; //字串长度计1

            //如果是大写字母，
        } elseif ($ascnum >= 65 && $ascnum <= 90) {
            $returnStr .= substr($str, $i, 1);
            $i++; //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符

            //其他情况下，包括小写字母和半角标点符号，
        } else {
            $returnStr .= substr($str, $i, 1);
            $i++; //实际的Byte数计1个
            $n += 0.5; //小写字母和半角标点等与半个高位字符宽...
        }
    }

    if (strlen($returnStr) != strlen($str)) {
        $returnStr .= $fix;
    }

    return $returnStr;
}

/**
 * 添加系统日志
 */
function addSystemAccessLog()
{
    // 不保存的日志
    $unSaveFilter = array(
        '/audit/endCatagory',           // 末级类目
        '/goods/SysComparePriceInfo',   // 比价
    );

    $action = $_SERVER['REQUEST_URI'];
    if (stripos($_SERVER['REQUEST_URI'], "?") !== false) {
        $action = substr($_SERVER['REQUEST_URI'], 0, stripos($_SERVER['REQUEST_URI'], "?"));
    }
    $ip     = Yii::app()->request->getUserHostAddress();
    $url    = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $param  = array(
            'get'    => $_GET,
            'post'   => $_POST,
    );

    // 如果存在不保存的日志直接return
    if (in_array($action, $unSaveFilter)) {
        return true;
    }

    $user   = isset(Yii::app()->user->name) ? Yii::app()->user->name : '未知';
    $userId = isset(Yii::app()->user->id) ? Yii::app()->user->id : '0';

    $dateTime = date("Y-m-d H:i:s");

    $nowMonth = date("Ym");

    // file_put_contents("/home/work/logs/tuan/system.access.log", $dateTime." --- ".$ip." --- ".$userId." --- ".$user." --- ".$action." --- ".$url." --- ".json_encode($param)." --- ".PHP_EOL."\r\n", FILE_APPEND);
    file_put_contents("/home/work/logs/tuan/access/system.access.{$nowMonth}.log", $dateTime." --- ".$ip." --- ".$userId." --- ".$user." --- ".$action." --- ".$url." --- ".json_encode($param).PHP_EOL."\r\n", FILE_APPEND);
}

/**
 * 转换数组某个值为key
 * @param array $arr
 * @param string $key
 * @return array
 */
function setArrayKey($arr, $key)
{
    if (!$arr || !$key) return $arr;

    $newArr = array();
    foreach ($arr as $k=>$v) {
        if (isset($v[$key])) {
            $newArr[$v[$key]] = $v;
        }
    }

    return $newArr;
}