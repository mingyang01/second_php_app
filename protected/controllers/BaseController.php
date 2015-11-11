<?php
/**
 * 公用方法
 */
class BaseController extends Controller
{
    /**
     * 此方法为ueditor 本地上传的图片处理。
     */
    function ActionUEditorImgUp(){

        $imgAll = $_FILES['upfile'];
        $imgAl = Yii::app()->image;
        $imgStr = $imgAl->uploadWebsiteImage($imgAll['tmp_name']);
        if (!imgStr) {
            output(array('succ'=>0, 'msg'=>'上传失败'));
        }
        $showImg = $imgAl->getWebsiteImageUrl($imgStr);

        echo "{'url':'" . $showImg. "','title':'hello','original':'" . $imgAll["name"] . "','state':'SUCCESS'}";
    }
}
?>