<?php

class TableTemplateController extends RController {

        public function actionIndex() {
			$data = array();
            $this->render('risk/TableTemplate/TableTemplate.tpl',$data);
		}
        
        //获取search数据
        public function actionGetData() {
            $re = $this->TableTemplate->getData();
            echo json_encode($re);
            exit();
		}

		public function actionOther(){
            $ids = Yii::app()->request->getParam('ids');
			if (empty($ids)){
				echo json_encode(array('code'=>-1, 'msg'=>'参数错误！'));
				exit;
			}

			/**开始处理**/
			$msg = '处理的ID：'.$ids;
			/**结束处理**/
			
			echo json_encode(array('code'=>1, 'msg'=>'处理完成！'.$msg));
			exit;
		}
        
}
