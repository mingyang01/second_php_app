<?php
/**
 * 公共处理方法
 * @author linglingqi
 * @version 2015-05-29
 */
class Json{

	public static function succ($msg="成功！", $errno="1", $data=array()) {
		$re = array('errno' => $errno, 'msg' => $msg, 'result'=>$data);
		echo json_encode($re);
		exit;
	}

	public static function fail($msg="失败", $errno="0", $data=array()) {
		$re = array('errno' => $errno, 'msg' => $msg, 'result'=>$data);
		echo json_encode($re);
		exit;
	}

	public static function rederText($text) {
		echo $text;
		exit;
	}

	//脚本记录执行日志
	public static function outPut($file, $start, $end) {

		$filename = "/home/work/logs/tuan/". $file .'.log';
		$str = "$file文件运行的开始时间为:". date('Y-m-d H:i:s',$start) ."; 结束时间为：". date('Y-m-d H:i:s',$end) .";共执行：".$end-$start;
		file_put_contents($filename, $str. "\r\n", FILE_APPEND);
	}
}