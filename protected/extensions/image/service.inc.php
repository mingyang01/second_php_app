<?php
/**
 * image 相关配置文件
 */

$GLOBALS['GOODS_URL_PICTURE'] = array(
        "http://d06.res.meilishuo.net" ,
        "http://imgtest.meiliworks.com" ,
        "http://imgtest.meiliworks.com" ,
        "http://imgtest.meiliworks.com" ,
        "http://imgtest.meiliworks.com"
        );
//$GLOBALS['GOODS_URL_PICTURE_COUNT'] = 1;

$GLOBALS['PICTURE_DOMAINS'] = array(
        'a' => 'http://imgtest.meiliworks.com', //tx
        'b' => 'http://d04.res.meilishuo.net', //tx
        'c' => 'http://imgtest-dl.meiliworks.com', //dl
        'd' => 'http://d06.res.meilishuo.net', //dl
        'e' => 'http://d05.res.meilishuo.net', //ws
        'f' => 'http://d01.res.meilishuo.net', //ws
        'g' => 'http://d02.res.meilishuo.net', //tx
        'h' => 'http://d03.res.meilishuo.net', //tx
        );
$GLOBALS['PICTURE_DOMAINS_ALLOCATION'] = 'aaddbbgggggggggggghhhhhhhhhhhhbbbbbbbbbbccddddddddggdddddddddddddddddeeeeeeeeeeeeeeeeeeeeeeeeeedddff';


//图片t_picture接口
$GLOBALS['GOODS_PICTURE_API'] = array (
    'http://172.16.0.23:8080/record/get?id=',
    'http://172.16.0.200:8080/record/get?id=',
    'http://172.16.0.199:8080/record/get?id='
    );

$GLOBALS['IMAGE_SERVICE']['SERVERS'] = array("http://172.16.0.23:8080","http://172.16.0.200:8080","http://172.16.0.199:8080");
$GLOBALS['IMAGE_SERVICE']['RECORD_ADD'] = "/record/add";
$GLOBALS['IMAGE_SERVICE']['RECORD_GET'] = "/record/get?";
$GLOBALS['IMAGE_SERVICE']['RECORD_UPDATE'] = "/record/update";
$GLOBALS['IMAGE_SERVICE']['RECORD_USER_COUNT'] = "/record/uc?";
$GLOBALS['IMAGE_SERVICE']['PIC_UPLOAD'] = "/pic/commupload";
$GLOBALS['IMAGE_SERVICE']['PIC_GET'] = "/";
$GLOBALS['IMAGE_SERVICE']['PIC_GETPICID'] = "/pic/getpicid?";
