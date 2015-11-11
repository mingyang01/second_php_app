<?php
/**
 * NoticeController.php
 * 团购通知
 * Author: ruidongsong@meilishuo.com
 * Date: 2015-7-28 下午5:57:13
 */

class NoticeController extends Controller
{
    public function ActionIndex()
    {
        $request = Yii::app()->request;
        $search_filter = $_GET;

        if (!empty($search_filter['cate_id']) && array_key_exists($search_filter['cate_id'], NoticeManager::$notice_cate_id_map)) {
            //$filter['channel'] = $search_filter['channel'];
            $filter['cate_id'] = $search_filter['cate_id'];
        } else {
            $filter['cate_id'] = array_keys(NoticeManager::$notice_cate_id_map);
        }

        if (!empty($search_filter['status']) && array_key_exists($search_filter['status'], NoticeManager::$notice_status_map)) {
            $filter['status'] = $search_filter['status'];
        }

        $order = " order by ctime desc";
        $notice_list = $this->notice->getNoticeList($filter, $order);

        if (is_array($filter['cate_id'])) {
            unset($filter['cate_id']);
        }
        $this->assign('searchFilter', $filter);
        $this->assign('noticeList', $notice_list);
        $this->render('notice/noticeList.html');
    }


    public function ActionAddNotice()
    {
        $this->render('notice/addNotice.html');
    }

    public function ActionEdit()
    {
        $request = Yii::app()->request;
        $id = $request->getQuery('id', 0);
        if (!$id) {
            throwMessage('非法操作', 'error');
        }

        $notice_info = NoticeManager::getNoticeInfo($id);

        $this->assign('notice_info', $notice_info);
        $this->render('notice/addNotice.html');
    }

    public function ActionSaveNotice()
    {
        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $db_brd_shop   = Yii::app()->db_brd_shop;

        $id        = Yii::app()->request->getPost('id', 0);
        $status    = Yii::app()->request->getPost('status', '');
        $title     = Yii::app()->request->getPost('title', '');
        $author    = Yii::app()->request->getPost('author', '');
        $cate_id    = Yii::app()->request->getPost('cate_id', 0);
        $ctime     = Yii::app()->request->getPost('ctime', '');
        $content   = Yii::app()->request->getPost('content', '');
        $utime     = date("Y-m-d H:i:s");

        if (!array_key_exists($status, NoticeManager::$notice_status_map)) {
            throwMessage('请选择正确的展示类型', 'error');
        }
        if (!$title) {
            throwMessage('请填写标题', 'error');
        }
        if (!array_key_exists($cate_id, NoticeManager::$notice_cate_id_map)) {
            throwMessage('请选择正确的分类', 'error');
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $ctime)) {
            throwMessage('请填写正确的时间', 'error');
        }
        $filter = array(
            'title'     => $title,
            'author'    => $author,
            'cate_id'   => $cate_id,
            'status'    => $status,
            'content'   => $content,
            'ctime'     => $ctime." 00:00:00",
            'utime'     => $utime,
        );

        if ($id) {
            $notice_info = NoticeManager::getNoticeInfo($id);
            if (!$notice_info) {
                throwMessage('公告信息不存在','error');
            }
            //p($filter);exit();
            $update_result = $db_brd_shop->createCommand()->update('brd_shop_posts', $filter, 'id=:id',array(':id'=>$id));

            throwMessage('更新成功', 'success', '/notice/addNotice');
        } else {
            $eventResult = $db_brd_shop->createCommand()->insert('brd_shop_posts', $filter);
            if (!$eventResult) {
                throwMessage('系统出错了~通知没有创建成功','error');
            }
            // 获取自增id
            $eventId     = $db_brd_shop->getLastInsertID();
            if (!$eventId) {
                throwMessage('系统出错了~通知没有创建成功1','error');
            }

            throwMessage('添加成功', 'success', '/notice/addNotice');
        }
    }


    /**
     * 还原活动
     */
    public function ActionRecover()
    {
        $id = Yii::app()->request->getQuery('id', 0);

        if (!$id) {
            output(array('succ'=>0, 'msg'=>'请传入要恢复的id'));
        }

        $r = $this->notice->recoverNotice($id);
        //$r = 1;
        if ($r) {
            output(array('succ'=>1, 'msg'=>'success', $id));
        } else {
            output(array('succ'=>0, 'msg'=>'还原失败'));
        }
    }

    /**
     * 删除活动
     */
    public function ActionDelete()
    {
        $id = Yii::app()->request->getQuery('id', 0);

        if (!$id) {
            output(array('succ'=>0, 'msg'=>'请传入要恢复的id'));
        }
        //output(array('succ'=>1, 'msg'=>'success', $id));
        $r = $this->notice->deleteNotice($id);
        //$r = 1;
        if ($r) {
            output(array('succ'=>1, 'msg'=>'success', $id));
        } else {
            output(array('succ'=>0, 'msg'=>'删除失败'));
        }
    }
}
?>