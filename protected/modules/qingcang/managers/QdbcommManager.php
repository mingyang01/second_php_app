<?php
/**
 * 清仓对数据表的公用操作封装
 * @author linglingqi@
 * @version 2015-08-10
 */
class QdbcommManager extends Manager {

	/**
	 * 对数据库写操作后的set统一处理
	 */
	public static function formatedit($data, $file, $type="insert") {

		if (!$data || !$file) {
			return false;
		}
		$set = '';
		foreach ($file as $key=>$val) {
			if ($type == 'insert') {
				if ($val && !$data[$key]) {
					return false;
				}
			} elseif ($type == 'update') {
				if ($val || !isset($data[$key])) {
					continue;
				}
			}

			if ($set) {
				$set .= ", $key='$data[$key]'";
			} else {
				$set .= "set $key='$data[$key]'";
			}
		}
		return $set;
	}

}
?>