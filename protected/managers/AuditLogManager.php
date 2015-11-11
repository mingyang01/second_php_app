<?php
class AuditLogManager extends Manager {
    // shop_groupon_audit_comments
    private static $logsTable = 'shop_groupon_audit_comments';

    // 废弃
    public function getAuditLog($where, $files = '*') {
        if (empty($where)) {
            $where = 'where 1=1 ';
        } else {
            $where = "where $where ";
        }

        $db = Yii::app()->sdb_dolphin;

        $sql = "select {$files} from " . self::$logsTable . $where . " order by ctime desc";
        $list = $db->createCommand($sql)->queryAll();
        return $list;
    }

    // 废弃
    public function saveAuditLog($data) {
        $data['ctime'] = date("Y-m-d H:i:s");
        if (empty($data['opuser'])) {
            $data['opuser'] = getOperatorId();
        }

        $run = $this->runInsert(array('table' => self::$logsTable, 'data' => $data));
        return $run;
    }

    public function AuditStatistic($date) {
        $sql = "select audit_status, count(1) num
            from shop_groupon_audit_comments
            where audit_time >= '$date 00:00:00'
            and audit_time < '$date 24:00:00'
            group by audit_status";

        $db  = Yii::app()->sdb_brd_shop;
        $results = $db->createCommand($sql)->queryAll();
        $resultesMap = array();

        foreach ($results as $key => $value) {
            $resultesMap[$value['audit_status']] = $value['num'];
        }

        return array
        (
            array($resultesMap['20'], $resultesMap['30'], $resultesMap['40'], $resultesMap['50']),
            array($resultesMap['21'], $resultesMap['31'], $resultesMap['41'], $resultesMap['51']),
            array(
                $resultesMap['20'] + $resultesMap['21'],
                $resultesMap['30'] + $resultesMap['31'],
                $resultesMap['40'] + $resultesMap['41'],
                $resultesMap['50'] + $resultesMap['51']
            )
        );
    }

    public function exportAuditLog($date, $steps) {
        $sql = "select gid, `audit_status` audit, `audit_opname`,
            audit_time, audit_comments
            from shop_groupon_audit_comments
            where audit_time >= '$date 00:00:00'
            and audit_time < '$date 24:00:00'
            and audit_status in (". implode(', ', $steps) . ") ";

        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryAll();
        // gid list
        $list = $this->common->array_column($resultes, 'gid');

        $detail = $this->audit->getTwitterDetail($list);

        $detailMap = array();
        foreach ($detail as $key => $value) {
            $detailMap[$value['gid']] = $value;
        }
        unset($detail);

        $redis = new Redis();
        $redis->connect('127.0.0.1');
        $redis->select(13);

        foreach ($resultes as $key => &$value) {
            $detail = $detailMap[$value['gid']];
            $value['audit_user'] = $redis->hGet('cache:workIdUserMap', $value['audit_user']);
            $value['twitter'] = $detail['tid'];
            $value['first'] = $detail['goods_first_catalog'];
            $value['second'] = $detail['goods_second_catalog'];
            $value['third'] = $detail['goods_three_catalog'];
            if (intval($value['audit']) % 10) {
                $value['result'] = '失败';
            } else {
                $value['result'] = '成功';
            }
            $value['shop'] = $detail['shop'];
            $value['origin'] = $detail['origin'];
            $value['price'] = $detail['price'];
            $value['name'] = $detail['name'];

        }
        return $resultes;
    }
}