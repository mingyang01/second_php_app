<?php
/**
                   _ooOoo_
                  o8888888o
                  88" . "88
                  (| -_- |)
                  O\  =  /O
               ____/`---'\____
             .'  \\|     |//  `.
            /  \\|||  :  |||//  \
           /  _||||| -:- |||||-  \
           |   | \\\  -  /// |   |
           | \_|  ''\---/''  |   |
           \  .-\__  `-`  ___/-. /
         ___`. .'  /--.--\  `. . __
      ."" '<  `.___\_<|>_/___.'  >'"".
     | | :  `- \`.;`\ _ /`;.`/ - ` : | |
     \  \ `-.   \_ __\ /__ _/   .-` /  /
======`-.____`-.___\_____/___.-`____.-'======
                   `=---='
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         佛祖保佑       永无BUG

 * QatestManager.php
 * Qa测试
 * Author: ruidongsong@meilishuo.com
 * Date: 2015-9-10 下午12:56:35
 */

class QatestManager extends Manager
{
    // 团购id
    public static $tuangou_event_id = 2402;
    // 秒杀id
    public static $miaosha_event_id = 2404;
    // 清仓id
    public static $qingcang_event_id = 2406;
    // 长期活动id
    public static $changqi_event_id = 2408;

    public static $typeMap = array(2402=>'团购', 2404=>'秒杀', 2406=>'清仓', 2408=>'长期活动');


    public static $type_title_map = array(
        '2' => '测试商品-团购',
        '10' => '测试商品-秒杀',
        '15' => '测试商品-清仓',
        '16' => '测试商品-长期活动',
    );
}
?>