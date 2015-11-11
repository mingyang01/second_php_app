<?php
/**
 * 图片处理类，使用方法
 * 初始化图片上传类
 * $imgAl = Yii::app()->image;
 * 上传图片
 * $imgStr = $imgAl->uploadWebsiteImage($_FILES['upload_banner_pc']['tmp_name']);
 * 获取图片地址
 * $showImg = $imgAl->getWebsiteImageUrl($imgStr);
 * 裁剪图片并获取裁剪图片后的路径 （s1为切割算法，详见函数）
 * $url = $imgAl->getWebsiteImageUrl($imgAl->generateThumbUrl($imgStr, 's1', '229', '320'));
 */
define('THUMB_SCALE_WIDTH',  1); // 0001
define('THUMB_SCALE_HEIGHT', 2); // 0010
define('THUMB_SCALE_BOTH',   THUMB_SCALE_WIDTH & THUMB_SCALE_HEIGHT); // 0000
define('THUMB_SCALE_ONE',    THUMB_SCALE_WIDTH | THUMB_SCALE_HEIGHT); // 0011

define('IMAGE_SERVICE_DEFAULT', 0);
define('IMAGE_SERVICE_GET_RECORD', 1);
define('IMAGE_SERVICE_SET_RECORD', 2);
define('IMAGE_SERVICE_GET_IMAGE', 3);
define('IMAGE_SERVICE_SET_IMAGE', 4);
define('IMAGE_SERVICE_DOWNLOAD_IMAGE', 5);

require_once 'image/Image.class.php';
require_once 'image/service.inc.php';

function ImageLog($level,$str){
   list($usec,$sec) = explode(' ',microtime());
   $milliSec = (int)((float)$usec * 1000);
   $intSec = intval($sec);
   //file_put_contents(LOG_FILE_BASE_PATH . '/imgget.' . date('YmdH',$intSec) . '.log',
   //    sprintf("%s %s:%d %s\n",$level,date('Y-m-d H:i:s', $intSec),$milliSec,$str),FILE_APPEND);
}
function ImageDebug($level,$str){
    ImageLog('DEBUG',$str);
}
function ComposeImgServiceReqUrl($uri, $request_type = IMAGE_SERVICE_DEFAULT) {
    $server_addr = $GLOBALS['IMAGE_SERVICE']['SERVERS'][rand() % count($GLOBALS['IMAGE_SERVICE']['SERVERS'])];
    switch ($request_type) {
    case IMAGE_SERVICE_GET_RECORD:
	if (isset($GLOBALS['IMAGE_SERVICE']['RECORD_GET_SERVERS'])) {
	    $server_addr = $GLOBALS['IMAGE_SERVICE']['RECORD_GET_SERVERS'][rand() % count($GLOBALS['IMAGE_SERVICE']['RECORD_GET_SERVERS'])];
	}
	break;
    case IMAGE_SERVICE_SET_RECORD:
	if (isset($GLOBALS['IMAGE_SERVICE']['RECORD_SET_SERVERS'])) {
	    $server_addr = $GLOBALS['IMAGE_SERVICE']['RECORD_SET_SERVERS'][rand() % count($GLOBALS['IMAGE_SERVICE']['RECORD_SET_SERVERS'])];
	}
	break;
    case IMAGE_SERVICE_GET_IMAGE:
	if (isset($GLOBALS['IMAGE_SERVICE']['IMAGE_GET_SERVERS'])) {
	    $server_addr = $GLOBALS['IMAGE_SERVICE']['IMAGE_GET_SERVERS'][rand() % count($GLOBALS['IMAGE_SERVICE']['IMAGE_GET_SERVERS'])];
	}
	break;
    case IMAGE_SERVICE_SET_IMAGE:
	if (isset($GLOBALS['IMAGE_SERVICE']['IMAGE_SET_SERVERS'])) {
	    $server_addr = $GLOBALS['IMAGE_SERVICE']['IMAGE_SET_SERVERS'][rand() % count($GLOBALS['IMAGE_SERVICE']['IMAGE_SET_SERVERS'])];
	}
	break;
    case IMAGE_SERVICE_DOWNLOAD_IMAGE:
	if (isset($GLOBALS['IMAGE_SERVICE']['IMAGE_DOWNLOAD_IMAGE_SERVERS'])) {
	    $server_addr = $GLOBALS['IMAGE_SERVICE']['IMAGE_DOWNLOAD_IMAGE_SERVERS'][rand() % count($GLOBALS['IMAGE_SERVICE']['IMAGE_DOWNLOAD_IMAGE_SERVERS'])];
	}
	break;
    case IMAGE_SERVICE_DEFAULT:
    default:
	break;
    }
    return $server_addr . $uri;
}

/**
 * 判断是否是合法的图片扩展名.
 * @param extName
 * @return : TRUE or FALSE.
 */
function isValidImageExt($extName)
{
    $lower = strtolower($extName);
    $EXT_ARR = array('jpg','jpeg','png','gif');
    foreach($EXT_ARR as $ext){
        if(strcmp($lower,$ext) == 0){
            return TRUE;
        }
    }
    return FALSE;
}

define('THUMB_TYPE_ORIG','_o');

class ImageStg {
    static $imgKinds;
    public function __construct() {
        self::$imgKinds = array('pic','ap','img','glogo','tmp');
    }
    /**
     * 分析id中包含的信息.
     * id的形式{kind}/{thumb_type}/{middle}/
     *     {base}_{width}_{height}[_{filter}].{ext}.
     *  还有一些组合信息.
     *  {file_name} = {base}_{width}_{height}.{ext}
     *  {orig_id} = {kind}/_o/{middle}/{base}_{width}_{height}.{ext}
     *  {last_id} 为最近一次处理前的id，比如如果有filter，则去掉filter。
     *            如果有thumb，则将thumb换成_o.
     *            目前只支持两次操作，即先thumb,再filter.
     *
     * @param id: 源图id.
     * @return : 成功返回数组,array(
     *          'width'=>$width,
     *           'height'=>$height,
     *           'ext'=>$ext,
     *           'middle'=>路径的中间部分.
     *           'thumb_type'=>$thumbType,
     *           'kind' => $kind,
     *           'filter' => $filter
     *           'file_name' => ,
     *           'orig_id' =>,
     *           'last_id' =>)
     */
    static public function parseImgId($id){
        $id = trim($id);
        if(!is_string($id) || empty($id)){
            return FALSE;
        }
        $pos = strrpos($id,'.');
        if($pos === FALSE){
            return FALSE;
        }
        $result['ext'] = substr($id,$pos + 1);
        if(!IsValidImageExt($result['ext'])){
            return FALSE;
        }
        $pos1 = strpos($id,'/');
        if($pos1 === FALSE){
            return FALSE;
        }
        $result['kind'] = substr($id,0,$pos1);
        $pos2 = strpos($id,'/',$pos1 + 1);
        if($pos2 === FALSE){
            return FALSE;
        }
        $result['thumb_type'] = substr($id,$pos1 + 1, $pos2 - $pos1 - 1);

        $pos3 = strrpos($id,'/');
        if($pos3 < $pos2){
            return FALSE;
        }
        $result['middle'] = substr($id,$pos2 + 1, $pos3 - $pos2 - 1);

        $fileBase = substr($id,$pos3 + 1, $pos - $pos3 - 1);
        $result['file_name'] = substr($id,$pos3 + 1);
        $parts = explode('_',$fileBase);
        if(count($parts) < 3 || count($parts) > 5) {
            return FALSE;
        }
        $result['base'] = $parts[0];
        $result['width'] = intval($parts[1],10);
        $result['height'] = intval($parts[2],10);
        $result['filter'] = '';
        if(count($parts) == 4){
            if(ctype_digit($parts[3])){
                $result['version'] = intval($parts[3]);
            }
            else {
                $result['filter'] = $parts[3];
            }
        }
        else if(count($parts) == 5){
            $result['filter'] = $parts[3];
            $result['version'] = intval($parts[4]);
        }
       $result['orig_id'] = sprintf('%s/%s/%s/%s_%d_%d.%s',
            $result['kind'],THUMB_TYPE_ORIG,$result['middle'],$result['base'],
            $result['width'], $result['height'],$result['ext']);
        return $result;
    }
    static function calcPictureThumbInfo($oWidth,$oHeight,$tWidth,$tHeight,$method){
        if($oWidth == 0 || $oHeight == 0 || $tWidth == 0 || $tHeight == 0){
            return FALSE;
        }
        $tRatio = $tWidth / $tHeight;
        $oRatio = $oWidth / $oHeight;
        if (THUMB_SCALE_ONE == $method) {
            $method = $tRatio > $oRatio ? THUMB_SCALE_WIDTH : THUMB_SCALE_HEIGHT;
        }
        switch ($method) {
        case THUMB_SCALE_BOTH:
            return Image::calcScaleImgSize($oWidth,$oHeight,$tWidth,$tHeight);
        case THUMB_SCALE_WIDTH:
            $sizeInfo = Image::calcScaleImgSize($oWidth,$oHeight,$tWidth);
            $currentWidth = $oWidth <= $tWidth ? $oWidth : $tWidth;
            $currentHeight = $oHeight * $currentWidth / $oWidth;
            // if current size is smaller, do not crop more
            $currentWidth < $tWidth && $tWidth = $currentWidth;
            $currentHeight < $tHeight && $tHeight = $currentHeight;
            if ($tWidth != $currentWidth || !$tHeight != $currentHeight) {
                $sizeInfo['width'] = (int)$tWidth;
                $sizeInfo['height'] = (int)$tHeight;
            }
            return $sizeInfo;

        case THUMB_SCALE_HEIGHT:
            $sizeInfo = Image::calcScaleImgSize(NULL, $tHeight);
            // We crop the image anyway.
            $currentHeight = $oHeight <= $tHeight ? $oHeight : $tHeight;
            $currentWidth = $oWidth * $currentHeight / $oHeight;
            // if current size is smaller, do not crop more
            $currentWidth < $tWidth && $tWidth = $currentWidth;
            $currentHeight < $tHeight && $tHeight = $currentHeight;
            if ($tWidth != $currentWidth || $tHeight != $currentHeight) {
                $sizeInfo['width'] = (int)$tWidth;
                $sizeInfo['height'] = (int)$tHeight;
            }
            return $sizeInfo;
            break;
        default:
            // should never come in here!
            break;
        }
        return FALSE;
    }
    static public function getThumbId($origId,$thumbType) {
        if(empty($origId) || empty($thumbType)){
            return '';
        }
        $thumbId = str_replace('/_o/','/' . $thumbType . '/',$origId);
        return $thumbId;
    }
    /**
     * 根据图片id获取图片内容.
     * @param id: 图片path,是t_dolphin_twitter_picture
     * 或者user_profile_ext中存储的图片路径.
     * 实际是key-value存储的key,或者是文件系统的路径.
     * @return 成功返回图片代表图片内容的string，失败返回FALSE.
     */
    public function getImage($id) {
        ImageLog('LOG',"act=getImage id=".$id);
        $bytes = FALSE;
        $id = trim($id);
        if(!is_string($id) || empty($id)){
            return FALSE;
        }
        //$num = rand() % count($GLOBALS['IMAGE_SERVICE']['SERVERS']);
        //$domain = $GLOBALS['IMAGE_SERVICE']['SERVERS'][$num];
        //$url = $domain . $GLOBALS['IMAGE_SERVICE']['PIC_GET'] . $id;
        $uri = $GLOBALS['IMAGE_SERVICE']['PIC_GET'] . $id;
        $url = ComposeImgServiceReqUrl($uri, IMAGE_SERVICE_GET_IMAGE);
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ch,CURLOPT_TIMEOUT,3);

        $body = curl_exec($ch);
        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($ch,CURLINFO_CONTENT_TYPE);
        curl_close($ch);
        if($code != 200){
            ImageLog('ERROR',sprintf('pos=%s:%d act=getImage'
                . '  id=%s code=%d',__FILE__,
                __LINE__,$id, $code));
            return FALSE;
        }
        ImageLog('DEBUG',sprintf('pos=%s:%d act=getImage'
            . '  id=%s code=%d url=%s',__FILE__,
            __LINE__,$id, $code,$url));
        return $body;
    }

    static public function genFilterImgId($id,$filterType){
        if(!is_string($filterType) || empty($filterType)){
            return FALSE;
        }
        $pos = strrpos($id,'.');

        if($pos === FALSE){
            return FALSE;
        }
        return substr($id,0,$pos) . '_' . $filterType  . substr($id,$pos);
    }
    /**
     *  保存源图接口.
     *  @param bytes: 图的内容，是个二进制数组
     *  @ext : 扩展名,如.jpg,.png等.
     *  @kind : 图片种类,avatar,pic.
     *  @return : 源图的id,thunb的id可以简单通过加上前缀{缩略图类型}而得到
     *         比如源图为sxxx.jpg,缩略图为T1sxxx.jpg.
     *         失败返回FALSE
     */
    public function saveOrigImage($bytes,$ext,$kind) {
        if(!isValidImageExt($ext)){
            ImageLog('ERROR',sprintf('act=save_img err=invalid_ext ext=%s'
                .' kind=%s', $ext,$kind));
            return FALSE;
        }
        //图片类别不支持.
        if(!self::isValidImgKind($kind)){
            ImageLog('ERROR',sprintf('act=save_img err=invalid_kind ext=%s'
                .' kind=%s', $ext,$kind));
            return FALSE;
        }
        $info = $this->postImage($bytes, $ext, $kind);
        if ($info == FALSE) {
            ImageLog('ERROR',sprintf('act=save_img err=postImage failed'));
            return FALSE;
        }
        if($info['ret'] == 0){
            ImageLog('INFO',sprintf('act=save_orig ext=%s'
                .' kind=%s id=%s ret=%s', $ext,$kind,$info['data']['n_pic_file'],strval($info['ret'])));
            return $info['data']['n_pic_file'];
        }
        ImageLog('ERROR',sprintf('act=save_img err=save ext=%s'
            .' kind=%s', $ext,$kind));
        return FALSE;
    }
    static public function isValidImgKind($kind){
        if(in_array($kind,self::$imgKinds)){
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 生成源图的唯一id,这个id目前是兼容文件系统路径的.
     * @return string
     */
    static public function genOrigImgId($bytes,$ext,$width,$height,$kind) {
        $md5 = md5($bytes);
        $level1Dir = substr($md5,0,2);
        $level2Dir = substr($md5,2,2);
        $base = substr($md5,4);
        return sprintf('%s/%s/%s/%s/%s_%d_%d.%s',$kind,THUMB_TYPE_ORIG,$level1Dir,
                       $level2Dir,$base, $width, $height,$ext);
    }
    /**
     * 使用图片上传接口，将图片存储
     * @return array 返回成功时举例如下
     * array(3) {
     *   ["ret"]=>  string(1) "0"
     *   ["msg"]=>  string(7) "success"
     *   ["data"]=> array(5) {
     *                 ["nwidth"]=>int(200)
     *                 ["nheight"]=>int(800)
     *                 ["n_pic_file"]=>string(53) "pic/_o/e0/32/1109d19f3efa7a9ce95643fd1fa8_200_800.jpg"
     *                 ["pic_id"]=>string(7) "2343973"
     *                 ["size"]=>int(67343)
     *               }
     *  }
     */
    public function postImage($bytes, $ext, $kind) {
        //$name = self::genOrigImgId($bytes,$ext,$width,$height,$kind);
        $name = md5($bytes);
        //$num = rand() % count($GLOBALS['IMAGE_SERVICE']['SERVERS']);
        //$domain = $GLOBALS['IMAGE_SERVICE']['SERVERS'][$num];
        //$url = $domain . $GLOBALS['IMAGE_SERVICE']['PIC_UPLOAD'];
        $uri = $GLOBALS['IMAGE_SERVICE']['PIC_UPLOAD'];
        $url = ComposeImgServiceReqUrl($uri, IMAGE_SERVICE_SET_IMAGE);
        ImageLog('LOG',sprintf('act=postImage url=%s',$url));

        $boundary = uniqid('------------------');
        $MPboundary = '--'.$boundary;
        $endMPboundary = $MPboundary. '--';
        $multipartbody = $MPboundary . "\r\n";
        $multipartbody .= 'Content-Disposition: form-data; filename='.$name. "\r\n";
        switch($ext) {
        case "png":
            $multipartbody .= 'Content-Type: image/png'. "\r\n\r\n";
            break;
        case "jpg":
            $multipartbody .= 'Content-Type: image/jpg'. "\r\n\r\n";
            break;
        case "gif":
            $multipartbody .= 'Content-Type: image/gif'. "\r\n\r\n";
            break;
        case "jpeg":
            $multipartbody .= 'Content-Type: image/jpeg'. "\r\n\r\n";
            break;
        default:
            $multipartbody .= 'Content-Type: image/jpg'. "\r\n\r\n";
            break;
        }
        $multipartbody .= $bytes."\r\n";

        $key = "kind";
        $value = $kind;
        $multipartbody .= $MPboundary . "\r\n";
        $multipartbody .= 'content-disposition: form-data;name="'.$key."\r\n\r\n";
        $multipartbody .= $value . "\r\n";

        $multipartbody .= "\r\n". $endMPboundary;
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch , CURLOPT_POST, 1 );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $multipartbody );
        $header_array = array("Content-Type: multipart/form-data; boundary=$boundary" , "Expect: ");

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER , true);
        curl_setopt($ch, CURLINFO_HEADER_OUT , true);
        $info = curl_exec($ch);
        $headersize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curl_errno > 0) {
            ImageLog('ERROR',sprintf('act=postImage curl post data failed,curl_errno=%s,curl_error=%s', $curl_errno, $curl_error));
            return FALSE;
        }
        if ($code != 200) {
            ImageLog('ERROR', sprintf('act=postImage return code is %s', $code));
            return FALSE;
        }
        if (!empty($info)) {
            $info = substr($info ,$headersize);
            $info = json_decode($info,true);
            ImageLog('LOG', sprintf('act=postImage ret=%s,msg=%s', $info['ret'], $info['msg']));
            return $info;
        } else {
            return FALSE;
        }
    }
};


class ImageLogic {
    var $imgStg;
    var $need_thumb_sharp;

    public function init(){}

    public function __construct(){
        $this->imgStg = new ImageStg;
	$this->need_thumb_sharp = true;
    }
    /**
     * @param id * :字符串，如pic/a/0f/54/e3d6b02f47b3906a957f3d1aa750_800_598.jpg
     * @return : 成功返回array('width'=>int,'height'=>int),失败返回FALSE.
     */
    static public function getPictureInfo($id){
        $idInfo = ImageStg::parseImgId($id);
        if($idInfo == FALSE){
            return FALSE;
        }
        if(strcasecmp($idInfo['kind'],'pic') != 0){
            return FALSE;
        }
        $thumbType = $idInfo['thumb_type'];
        if(strcasecmp($thumbType,THUMB_TYPE_ORIG) == 0){
            $arr = array();
            $arr['width'] = $idInfo['width'];
            $arr['height'] = $idInfo['height'];
            return $arr;
        }
        if(!isset($GLOBALS['UPLOAD_PICTURE'][$thumbType])){
            return FALSE;
        }
        return ImageStg::calcPictureThumbInfo($idInfo['width'],$idInfo['height'],
            $GLOBALS['UPLOAD_PICTURE'][$thumbType]['width'],
            $GLOBALS['UPLOAD_PICTURE'][$thumbType]['height'],
            $GLOBALS['UPLOAD_PICTURE'][$thumbType]['scale']
        );
    }
    /**
     * 转成url
     */
    static public function getPictureUrl($key){
        $key = trim($key);
        if(strncasecmp($key,'http://',strlen('http://')) == 0){
            return $key;
        }

        $hostPart = self::getPictureHost($key);
        if(empty($key)){
            return  $hostPart . '/css/images/noimage.jpg';
        }
        $uri = self::convertKeyToUri($key);
        return $hostPart . $uri;
    }
    /**
     * @param @origImgId: 字符串，源图id,缩略图类型为_o.
     * @param @thumbType: 字符串，GLOBALS[UPLOAD_PICTURE]
     * @return :字符串.
     *
     */
    static public function getPictureThumbUrl2($origImgId,$thumbType){
        $thumbId = ImageStg::getThumbId($origImgId,$thumbType);
        return self::getPictureUrl($thumbId);
    }

    /**
     * 获取picture缩略图的url.
     * 逻辑如下:
     * 1) 如果thumbPath不为空,直接转化
     * 2) thumbPath为空，目前只处理'j'类型的所略图,如果是
     *   /home/work/webdata/pictures/_o/日期/随机数/13343.jpg转成
     *    j/日期/随机数/13343.jpg.
     * 3) 返回/css/images/goods/nopic_180.gif.
     *
     * @param thumbPath: 数据库中存储的路径，如f_pic_file,可能是空.
     * @param origPath: 数据库中存储的源图路径,不能为空.
     * @param thumbKey:'a','b','c','d'等thunbkey
     * @param :成功返回string类型的url，带host部分.失败返回FALSE.
     */
    static public function getPictureThumbUrl($thumbPath,$origPath,$thumbKey,$ctime = NULL){
        $thumbPath = trim($thumbPath);
        if(is_string($thumbPath) && !empty($thumbPath)){
            return self::getPictureUrl($thumbPath);
        }
		$NO_PIC_URL = $GLOBALS['GOODS_URL_PICTURE'][0] .
			'/css/images/goods/nopic_180.gif';
        return self::getPictureThumbUrl2($origPath,$thumbKey);
    }

     static public function getUrlOfGetPicture($key, $converter = '0'){
        $hostPart = self::getPictureHost($key);
        $retStr = $hostPart . "/css/getPicture.php?src=".base64_encode( $key )."&rs={$converter}";
        return $retStr;
    }
    static public function getUrlOfFilter($src,$filter_type,$reqSize){
        $hostPart = self::getPictureHost($src);
        return $hostPart
                   . '/css/filter.php?filter_type=' . $filter_type
                   . '&src=' . base64_encode($src)
                   . '&rs=' . $reqSize;
    }

    /**
     * 根据保存的图片返回Url的path部分.
     *   //1) /home/work/webdata/avatar_picture/YYYYmmDD/xx/xx/xx/sfdsfdsfdsfdsf.jpg
     *   //2) /css/images/0.gif
     *   //3) /css/picture/xx/xx/xx/nsdfdsfsdfds.jpg,t_dolphin_user_profile_ext
     *   //中user_id=500有这种形式.
     *   //4) 新的形式:不是/开头.
     * @param key: 保存在数据库表中记录的图片位置信息.
     * @return : uri的path部分,http://xxx/xxx.
     */
    static public function getAvatarUrlPath($key) {
        $key = trim($key);
        if(empty($key)){
            return  '/css/images/0.gif';
        }
        $uri = self::convertKeyToUri($key);
        return $uri;

    }
    static public function getAvatarUrl($key){
        $path = self::getAvatarUrlPath($key);
        return AVATAR_URL . $path;
    }

    /**
     * 将key转成uri.
    * /css/images/ = > /css/images/
     * (ap|pic)/_o|a|b|c/md5_w_h.ext => /ap|pic/_o|a|b/
     * @param key : local path.
     *
     */
    static function convertKeyToUri($key){
        if($key[0] == '/'){
            return $key;
        }
        return   '/' . $key;
    }
    /**
     *  将uri转成存在数据库表中的图片文件路径.
     * /pic/xxxx  => pic/xxxx
     * /ap/xxxx => ap/
     *
     */
    static function convertUriToKey($uri){
        $uri = ltrim($uri,'/');
        return $uri;
    }
    /**
     * 计算图片url host部分的下标.
     * @return 0~9的一个整数.
     */
    static public function computeUrlHostIdx($key){
        if (empty($key)) {
            return 0;
        }
        $val = intval($key[32],16);
        $idx =  $val % $GLOBALS['GOODS_URL_PICTURE_COUNT'];
        return $idx;
    }

    static public function getPictureHost($key) {
        $key = ltrim($key, '/');
        if (empty($key)) {
            return $GLOBALS['PICTURE_DOMAINS']['a'];
        }
        $remain = crc32($key) % 100;
		$remain = abs($remain);
		$hashKey = $GLOBALS['PICTURE_DOMAINS_ALLOCATION'][$remain];
		return $GLOBALS['PICTURE_DOMAINS'][$hashKey];
    }

    public function getPicByLocalPath($path){
        $bytes = $this->imgStg->getImage($path);
       if ($bytes == FALSE) {
           ImageLog('ERROR',sprintf('act=getPicByLocalPath err=getImage faled, id=%s', $path));
           return FALSE;
       }
       return $bytes;
    }

    /**
     * @author	baolinwei@meilishuo.com, poleon810500@gmail.com
     * @copyright meilishuo.com 2011-6-24
     * @param  $path: 文件路径
     * @return :成功返回string类型的id，失败返回FALSE.
     */
    public function uploadWebsiteImage($path,$compress = FALSE){
        ImageLog('LOG',sprintf('uploadwebsiteimage path=%s',$path));
        return $this->uploadImage($path,'img',$compress);
    }
    /*
     * @param $id : image id，字符串类型
     * @return : 识别成功返回字符串类型的url，含http:// ,失败返回FALSE。
     */
    static public function getWebsiteImageUrl($id){
        $hostPart = self::getPictureHost($id);
        $key = trim($id);
        if(empty($id)){
            return  FALSE;
        }
        if(strncasecmp($id,'http://',strlen('http://')) == 0){
            return $id;
        }
        $uri = self::convertKeyToUri($id);
        if($uri === FALSE){
            return FALSE;
        }
        return $hostPart . $uri;
    }
     /**
     * @param  $path: 文件路径
     * @param  $kind: 图片种类，支持的图片种类见shark.config.php
     * @return :成功返回string类型的id，失败返回FALSE.
     */
    public function uploadImage($path,$kind,$compress = FALSE){
        $origImgBytes = file_get_contents($path);
        if($origImgBytes === FALSE){
            ImageLog('ERROR', sprintf('act=uploadImage err=getContent path=%s kind=%s',$path,$kind));
            return FALSE;
        }
       $origImgId = $this->imgStg->saveOrigImage($origImgBytes,'jpg',$kind);
        if(!is_string($origImgId) || empty($origImgId)){
            return FALSE;
        }
        return $origImgId;
    }
    static public function getImageUrl($id){
        return self::getWebsiteImageUrl($id);
    }

    /**
     *
     * 执行文件上传，处理完返回一个包含上传成功或失败的文件信息数组，
     * @return Array
     * 其中：'file' 为生成的源图片id.
     *       'width' 为源图宽
     *       'height' 为源图高.
     *       'type' 为类型.
     *       'thumb' 是缩略图信息,['thumb']['a']取缩略图类型A的ID.
     */
    public function processUploadPicture($watermarkTag = '') {
        $arr = array();
        // note 附件数量
        $keys = array_keys($_FILES['attach']['name']);
        foreach ($keys AS $key) {
            // 处理一个上传的文件
            if (FALSE == $_FILES['attach']['name'][$key]) {
                continue;
            }
            $fileName = $_FILES['attach']['name'][$key];
            $tmp = $_FILES['attach']['tmp_name'][$key];
            if(!is_uploaded_file($tmp)){
                continue;
            }
            $extension = pathinfo($fileName,PATHINFO_EXTENSION);
            $result = $this->uploadPicture($tmp,$extension,$watermarkTag);
            unlink($tmp);
            $arr[$key] = $result;
        }
        return $arr;
    }

    /**
     * 上传图片.
     * @param tmp:本地临时文件.
     * @param extension: 图片扩展名.
     * @param watermarkTag:string,水印tag,见shark.config.php中$GLOBALS['IMG_STG_CFG']['WATERMARK'].
     * @return 成功返回array('file'=>图片id,'width'=>宽,'height'=>高,
     *         'thumb'=>array('a'=>thumb的id,'b'=>...).
     *         失败返回FALSE.
     */
    public function uploadPicture($tmp,$extension,$watermarkTag = ''){
        $fileContents = file_get_contents($tmp);
        if($fileContents === FALSE){
            ImageLog('ERROR',"act=uploadPicture err=readfile tmp=$tmp ext=$extension");
            return FALSE;
        }
        if ( strcasecmp($extension,'gif') == 0  && Image::isAnimatedGif($fileContents) ) {
            return $this->uploadPictureFromMagick($tmp);
        }
        $img = new ImageImagick();
        $img->loadFromMem($fileContents,$extension);
        return $this->uploadPictureFromImgObj($img,$watermarkTag);
    }

    public function uploadPictureSync($tmp,$extension,$watermarkTag = ''){
        $fileContents = file_get_contents($tmp);
        if($fileContents === FALSE){
            ImageLog('ERROR',"act=uploadPicture err=readfile tmp=$tmp ext=$extension");
            return FALSE;
        }
        if ( strcasecmp($extension,'gif') == 0  && Image::isAnimatedGif($fileContents) ) {
            return $this->uploadPictureFromMagick($tmp);
        }
        $img = new ImageImagick();
        $img->loadFromMem($fileContents,$extension);
        return $this->uploadPictureFromImgObjSync($img,$watermarkTag);
    }

    /**
     * @params:
     * $origUrl: string, url of original picture
     * $sType: string, 切割算法，具体参考wiki上的文档
            s表示缩放算法(包括是否裁剪和留白),s字段必须出现。数字表示支持的算法类型:
            0表示的算法:根据宽度或者高度进行等比例缩放,图片完整展现不剪裁,也不留白。
            1表示的算法:根据宽度进行等比例缩放(定宽),对超出给定高度的部分裁剪下半部分,缩放后高度小于给定高度,不留白;
            2表示的算法:根据高度进行等比例缩放(定高),超出给定宽度的部分只取图片中间部分。不留白;
            3表示的算法:自动根据原图宽高比以及给定期望宽高比进行选择,是按照算法1或者算法2进行缩放。
            4表示的算法:根据宽度进行等比例缩放,超出给定宽度的部分,裁剪上半部分。如果缩放之后小于给定高度则居中留白。
            5表示的算法:从图片中居中裁剪出最大的正方形,然后等比例缩放到指定宽高;
            6表示的算法:自动根据原图宽高比以及期望宽高比进行选择,最后超出部分都是截取保留中间部分。不留白;
            7表示的算法:按照宽度进行缩放,超出部分截取保留中间部分。不留白
     * $thumbWidth: int, 缩放后的宽度
     * $thumbHeight: int, 缩放后的高度
     * @return: string
     */
    public static function generateThumbUrl($origUrl, $sType, $thumbWidth, $thumbHeight, $sharpenFlag=false) {
        if (empty($origUrl)) {
            return '';
        }
        $urlArgs = parse_url($origUrl);
        $uri = ltrim($urlArgs['path'], '/');
        if (empty($uri)) {
            return '';
        }
        if ($sharpenFlag) {
            $amount = 150;
            $radius = 5;
            $threshold = 0;
            $token = $amount . $radius . $threshold . $thumbWidth . $thumbHeight . "meilishuonewyearhappy";
        }
        else {
            $token = $thumbWidth . $thumbHeight . "meilishuonewyearhappy";
        }
        $md5sum = md5($uri . $token);
        $eightM = substr($md5sum, 0, 8);
        $target = $origUrl . "_{$eightM}_{$sType}";
        $sharpenFlag && $target .= "_q0_{$amount}_{$radius}_{$threshold}";
        $target .= "_{$thumbWidth}_{$thumbHeight}.jpg";
        return $target;
    }
};

