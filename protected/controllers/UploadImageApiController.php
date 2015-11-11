<?php

class UploadImageApiController extends Controller{

	//上传图片接口

	public function ActionUploadImg(){

		$imgAl = Yii::app()->image;
		$request = Yii::app()->request;
		$callback = $request->getParam('jsonpCallback','');
		$file = $request->getParam('filename','');
        $imgStr = $imgAl->uploadWebsiteImage($_FILES['uploaod_img']);
        if (!$imgStr) {
            output(array('succ'=>0, 'msg'=>'上传失败'),$callback);
        }
        $showImg = $imgAl->getWebsiteImageUrl($imgStr);

        output(array('succ'=>1, 'img'=>$showImg, 'path'=>$imgStr),$callback);

	}
}

?>