<?php

/**
 * redis key 的配置
 */
return array(
	'tuan_menu_users'=>array('key'=>'tuan_%s', 'expire'=>'2592000'),  //团购用户信息菜单缓存
	'tuan_redirect'	=>	array('key'=>'tuan_redirect_%s', 'expire'=>'604800'),
	'userMap'		=>	array('key'=>'userMap', 'expire'=>'604800'),
	'crontabNextGrouponId'=>array('key'=>'crontabNextGrouponId', 'expire'=>'2592000'),
	'allBadGoods'		=>	array('key'=>'allBadGoods', 'expire'=>'240'),
	'crontabNextGrouponIdToShops'=>array('key'=>'crontabNextGrouponIdToShops', 'expire'=>'2592000'), //店铺脚本存储最后操作的key
);
