<?php
class TableTemplateManager extends Manager {
    
    public function __construct() {
    }
    
       
        //获取订单的相关信息
    public function getData(){
		//菜单id
        $menu_ids = array();
        if (isset($_GET["menu_ids"]) && !empty($_GET["menu_ids"])) {
			$menu_ids = explode(",", $_GET["menu_ids"]);
			foreach ($menu_ids as & $value) {
				$value = intval($value);
			}
		}
        
		//用户id
		$user_ids = array();
        if (isset($_GET["user_ids"]) && !empty($_GET["user_ids"])) {
            $user_ids = explode(",", $_GET["user_ids"]);
            foreach ($user_ids as & $value) {
                $value = intval($value);
            }
        }

        $res = array('total' => 0, 'rows' => array());
        if (!empty($menu_ids) || !empty($user_ids)) {
			$where_str = '';
			if (!empty($menu_ids)) {
				$menu_str = implode(',', $menu_ids);
				$where_str .= " and menu_id in ({$menu_str}) ";
			}
            
            if (!empty($user_ids)) {
                $uids_str = implode(',', $user_ids);
                $where_str .= " and buyer_uid in ({$uids_str}) ";
            }
            
            $sqlCount = "SELECT count(*) as counts FROM t_eel_admin_menu_info WHERE menu_id>0"; 
            $sqlComm = "select menu_id, menu_title, menu_url from t_eel_admin_menu_info WHERE menu_id>0";
			if (! empty($where_str)){
				$sqlCount .= $where_str;
				$sqlComm .= $where_str;
			}
            $count = Yii::app()->sdb_eel->createCommand($sqlCount)->queryRow();
            if ($count['counts'] > 0) {
                $page = (isset($_GET['page']) && $_GET['page']>0) ? intval($_GET['page']):1;
                $rows = (isset($_GET['rows']) && $_GET['rows']>0) ? intval($_GET['rows']):50;
                $offset = ($page - 1) * $rows;
                
                $sqlComm .= " LIMIT {$offset}, {$rows} ";
                $reRows = Yii::app()->sdb_eel->createCommand($sqlComm)->queryAll();

                $res['rows'] = $reRows;
                $res['total'] = $count['counts'];
            }  
        }
        
        return $res;
    }
    




}
