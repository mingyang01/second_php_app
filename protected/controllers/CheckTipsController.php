<?php

class CheckTipsController extends Controller
{
    public function ActionIndex()
    {
        if (!isset($_GET['status'])) {
            $_GET['status'] = 1;
        }
        if (!isset($_GET['channel'])) {
            $_GET['channel'] = 0;
        }
        $checkTipsData = $this->checkTips->getList($_GET);

        //p($checkTipsData);

        $this->assign('checkTipsData', $checkTipsData);
        $this->assign('pager', $checkTipsData['pager']);
        $this->assign('searchFilter', $_GET);

        $this->render('checkTips/tipsList.html');
    }

    /**
     * 添加
     */
    public function ActionAdd()
    {
        $id = Yii::app()->request->getparam('id', 0);
        $this->render('checkTips/add.html');
    }

    /**
     * 编辑
     */
    public function ActionEdit()
    {
        $id = Yii::app()->request->getparam('id', 0);

        if (!$id) {
            throwMessage("请传入id", 'error');
        }

        // 先查找活动信息
        $sql           = "select * from t_groupon_check_tips where id={$id}";
        $sdb_groupon   = Yii::app()->sdb_groupon;
        $tipInfo     = $sdb_groupon->createCommand($sql)->queryRow();
        if (!$tipInfo) {
            throwMessage("原因不存在", 'error');
        }

        $this->assign('tipInfo', $tipInfo);
        $this->render('checkTips/add.html');
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

        $r = $this->checkTips->recoverCheckTips($id);
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
        $r = $this->checkTips->deleteCheckTips($id);
        //$r = 1;
        if ($r) {
            output(array('succ'=>1, 'msg'=>'success', $id));
        } else {
            output(array('succ'=>0, 'msg'=>'删除失败'));
        }
    }


    /**
     * 保存
     */
    public function ActionSave()
    {
        $id       = Yii::app()->request->getPost('id', 0);

        $type     = Yii::app()->request->getPost('type', '');
        $content  = Yii::app()->request->getPost('content', '');
        $opname   = getOperatorName();
        $addTime = date("Y-m-d H:i:s");


        if (!$type) {
            throwMessage('请选择类型', 'error');
        }
        if (!array_key_exists($type, CheckTipsManager::$tipsTypeEnum)) {
            throwMessage('类型错误', 'error');
        }
        if (!$content) {
            throwMessage('请填写内容', 'error');
        }

        $insertSql  = "insert into t_groupon_check_tips (`type`, `content`, `opname`, `add_time`) values('{$type}', '{$content}', '{$opname}', '{$addTime}')";
        $updateSql  = "update t_groupon_check_tips set `type`='{$type}', `content`='{$content}', `opname`='{$opname}' where `id`='{$id}'";

        $sdb_groupon  = Yii::app()->sdb_groupon;
        $db_groupon   = Yii::app()->db_groupon;

        // 有过有id是更新
        if ($id) {
            $noticeSql  = "select * from t_groupon_check_tips where id={$id}";
            $noticeInfo = $sdb_groupon->createCommand($noticeSql)->queryRow();
            if (!$noticeInfo) {
                throwMessage('信息不存在','error');
            }

            $result = $db_groupon->createCommand($updateSql)->execute();
            throwMessage('更新成功', 'success', '/checkTips');
            // 如果没有是新增
        } else {
            $result = $db_groupon->createCommand($insertSql)->execute();
            $lastId = $db_groupon->getLastInsertID();
            if ($result) {
                throwMessage('添加成功', 'success', '/checkTips');
            } else {
                throwMessage('添加失败', 'error');
            }
        }

        //p($type,$content);
    }

    public function ActionTest()
    {
        p($this->speed->username, $this->user->username, $this->user->id, $this->user->name,getOperatorName());
    }
}