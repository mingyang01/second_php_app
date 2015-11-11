<?php
/**
* 团购，清仓，秒杀的权限控制
* @author mingyang@meilishuo.com
* @version 2015-7-22
*/
class AuthController extends Controller
{
    public function ActionIndex()
    {
        $sid = Yii::app()->user->id;
        //$auth = $this->auth->judgeRole($sid);
        $item = $this->auth->getRedirect($sid);
        if($item){
            $this->redirect($item);
        }else{
            $this->render('auth/index.tpl');
        }
    }

    //根据权限生成菜单
    public function ActionCreateMenu($item)
    {
        $business = 'tuan_'.$item;
        if($item=='qing'){
            $business = 'qingcang';
        }
        $domain = false;
        $uid = yii::app()->user->id;
        $this->auth->createMenu($business,$item,$uid,$domain);
        $item = $this->auth->redirectAuto($uid,$item);
        $this->redirect($item);
        
    }
    //清除重定向redis
    public function actionCleanRedirect(){
        $sid = Yii::app()->user->id;
        $this->auth->getRedirect($sid,true);
        $this->render('auth/index.tpl');
    }
    
}