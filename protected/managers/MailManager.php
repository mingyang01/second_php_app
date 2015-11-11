<?php
/**
 * 发邮件功能
 * @author linglingqi
 *
 */
class MailManager extends Manager {

    /**
     * 自定义文案发邮件
     * @param  $to 发送给的用户名
     * @param  $subject 主题
     * @param  $content 内容
     */
    public static function sendCommMail($to, $subject, $content, $ext=array(), $bolHtml=FALSE) {

    	$headers = 'From: <developer@meilishuo.com>' . "\r\n";
        if($bolHtml) {
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        }
    	$subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
    	!is_array($to) && $to = array($to);
    	$to_address = array();
    	foreach ($to as $val) {
    		$to_address[] = $val ."@meilishuo.com";
    	}
    	$to = implode(",", $to_address);
    	if(isset($ext['to'])) {
    		$to = $to .','. $ext['to'];
    	}
    	$ret = mail($to, $subject, $content, $headers);
    	return true;
    }

    public static function sendWarnning($content) {

    	$to = Config::get('mailconf.warn');
    	$subject = "报警邮件";
    	$ret = self::sendCommMail($to, $subject, $content, true);
    	return $ret;
    }

}
