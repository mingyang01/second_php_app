<?php

class ScheduleController extends Controller
{
    /**
     * 取消排期
     */
    public function ActionCancelSchedule()
    {
        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }
        //output(array('succ'=>0, 'msg'=>'少年，请不要非法操作~'));

        $tuanId         = $request->getPost('tuan_id', '');
        $auditStatus    = $request->getPost('audit_status', '51');

        if (!$tuanId) {
            output(array('succ'=>0, 'msg'=>'请选择加入排期的商品'));
        }
        if ($auditStatus != 51 && $auditStatus != 40) {
            output(array('succ'=>0, 'msg'=>'非法操作'));
        }

        // 排期
        $scheduleResault = $this->schedule->cancelSchedule($tuanId, array(), $auditStatus);
        if ($scheduleResault['succ'] == 1) {
            output(array('succ'=>1, 'msg'=>'success', $scheduleResault));
        } else {
            output($scheduleResault);
        }
    }
}
