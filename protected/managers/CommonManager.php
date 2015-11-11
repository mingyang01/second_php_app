<?php
class CommonManager extends Manager {
    public function userMap() {
        $speed = new Speed();

        $redis = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);
        $key = "userMap";
        if ($redis->exists($key, array())) {
            return json_decode($redis->get($key, array()), true);
        }

        $sql = "select * from t_eel_admin_user";
        $db = Yii::app()->sdb_eel;
        $results = $db->createCommand($sql)->queryAll();

        $map = array();
        foreach ($results as $k => $value) {
            $user = $speed->getUser($value['username']);
            $map[$value['user_id']] = array(
                'username'=>$value['username'],
                'realname'=>$value['realname'],
                'depart'=>$user['departname'],
                'id'=>$user['sid']
            );
        }

        $redis->set($key, array(), json_encode($map));
        return $map;
    }
    // works
    public function getUid($username) {
        $sql = "select user_id from t_eel_admin_user where username='$username'";
        $db = Yii::app()->sdb_eel;
        $results = $db->createCommand($sql)->queryColumn();

        return $results[0];
    }

    public function getDepartUsers($depart) {
        $params = Yii::app()->params;
        $token = $params['TOKEN'];
        $api = $params['SPEED'];
        $url = $api."/user/show_depart_user?token=$token&depart_id=$depart";

        $output = Yii::app()->curl->get($url);
        $results = json_decode($output['body'], true);
        $results = $results['data'];

        return $results;
    }

    public function getUser($username) {
        $output = Yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&mail=".$username);
        $results = (array)json_decode($output['body']);
        $results = $results['data'];
        return $results;
    }

    function array_column($array, $column) {
        if(function_exists("array_column")) {
            return array_column($array, $column);
        } else {
            if(!is_array($array) || count($array) === 0) {
                return array();
            }
            $ret = array();
            foreach($array as $row) {
                $ret[] = $row[$column];
            }
            return $ret;
        }
    }


    function exportExcel($titles, $columns, $rows, $filename){
        // export to excel
        if(empty($filename)) $filename = date('Ymd') . '.xls';
        $excel = new CPHPExcel();
        $sheet = $excel->getActiveSheet();
        $excel->getProperties()->setCreator("focus");

        $data = array();
        $data[] = $titles;

        // data transform
        foreach ($rows as $row) {
            $tmp = array();
            foreach ($columns as $check) {
                $tmp[$check] = $row[$check];
            }
            $data[] = $tmp;
        }

        $sheet->fromArray($data, null, "A1");
        $excel->dumpToClient($filename);
    }

    function exportHtml($titles, $columns, $rows, $filename=''){
        if(empty($filename)) $filename = date('Ymd') . '.xls';
        Header ( "Content-type:   application/octet-stream " );
        Header ( "Accept-Ranges:   bytes " );
        Header ( "Content-type:application/vnd.ms-excel;charset=Big5" );
        Header ( "Content-Disposition:attachment;filename=" . $filename );
        header ( 'content-Type:application/vnd.ms-excel;charset=utf-8' );

        $html  = "";
        $html .='<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $html .='<table border=1><thead><tr>';

        foreach ($titles as $key=>$val){
            $html .= '<td style="background:cornsilk">'.$val.'</td>';
        }
        $html .='</tr></thead>';

        foreach ( $rows as $key=>$val){
            $html .='<tbody><tr>';
            //数据字段
            foreach ($columns as $k=>$v){
                if(isset($val[$v])){
                    $html .='<td>'.$val[$v].'</td>';
                } else {
                    $html .='<td></td>';
                }
            }
            $html .='</tr>';
        }

        $html .='</tbody></table>';

        echo $html;
    }
}