<?php

class HahaCommand extends CFocusConsoleCommand {
    public function runImp() {
        $input = trim($this->args["input"]);
        if($input === "" || !file_exists($input)) {
            echo "fail to open input {$input}\n";
            return false;
        }
        // 获取店铺列表
        $lines = file($input);
        $shop_ids = array();
        foreach($lines as $line) {
            $shop_id = intval($line);
            if($shop_id <= 0) continue;
            $shop_ids[] = $shop_id;
        }
        // 取得所有机器提交的头部商家
        $ret = $this->shopLevelStat->submitTop(10, $shop_ids, "机器筛选", $detail, true, false);
        print_r($detail);
        return $ret;
    }
}
