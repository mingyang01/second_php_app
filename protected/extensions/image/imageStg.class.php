<?php
//require_once(CORE_LIB_PATH."common.func.php"); //common functions
//require_once(CORE_LIB_PATH."pdoDB_pic.class.php");
//require_once(CORE_LIB_PATH."pdoDB_pic.class.php");
//require_once(CORE_LIB_PATH."TimeHelper.class.php");
//require_once(CORE_LIB_PATH."imggen/imageGenClient.class.php");

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

//require_once(CORE_LIB_PATH . 'Image.class.php');
//require_once(CORE_LIB_PATH . 'imageFilter.class.php');
//require_once(CORE_LIB_PATH . 'ImageImagick.class.php');
//require_once(CORE_LIB_PATH . 'commFun.class.php');
//loadLib('Image', false);
//loadLib('ImageImagick', false);
//loadLib('imageFilter', false);
require_once 'Image.class.php';
require_once 'ImageImagick.class.php';
require_once 'imageFilter.class.php';
require_once 'service.inc.php';

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
     * 功能和uploadAvatar类似，调用的是imagemagick库,对gif格式的图片进行处理
     * @param tmp ,php保存的临时文件路径.
     * @param fileName :用户上传的本地文件名.
     * @return
     */
    public function uploadAvatar($tmp,$fileName,$mode = 0){
        ImageLog('LOG',sprintf('upload avatar filename=%s ',$filename));
        $logStr = sprintf('act=avatar:upload tmp=%s file=%s',$tmp,$fileName);
        $img = new Image();
        $img->load($tmp);
        if(!$img->isLoaded()){
            ImageLog('ERROR',sprintf('%s err=load_tmp',$logStr));
            return FALSE;
        }
        $fileext = $img->getExt();
        unlink($tmp);
        $width = $img->getWidth();
        $height = $img->getHeight();
        if ($mode == 0) {
	        if($width >= 500) {
	            $scale = 500 / $width;
	        } else if($height >= 500){
	            $scale = 500 / $height;
	        } else if($width < 128 && $height < 128){
	            $scale = 128 / $width;
	        } else {
	            $scale = 1;
	        }
        }
        else {
        	$scale = 950/ $width;
        }
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);

        if ( FALSE === $img->resizeTo($newImageWidth,$newImageHeight) ) {
            ImageLog('ERROR','%s err=resizeTo ',$logStr);
            return FALSE;
        }
        $newImgBytes = $img->getContent();
        if($newImgBytes === FALSE){
            ImageLog('ERROR','%s err=get_content ',$logStr);
            return FALSE;
        }

        $newImgId = $this->imgStg->saveOrigImage($newImgBytes,$fileext,'tmp');
        if($newImgId === FALSE){
            ImageLog('ERROR','%s err=save ',$logStr);
            return FALSE;
        }

        $large_image_relative_location = self::getAvatarUrl( $newImgId);

        $large_photo_exists = "<img src=\"{$large_image_relative_location}\""
                                ." alt=\"Large Image\"/>";

        $_SESSION['large_image_absolute_location'] = $newImgId;
        $_SESSION['large_photo_exists'] = $large_photo_exists;
        $_SESSION['large_image_relative_location'] = $large_image_relative_location;

        $avatar_info['current_large_image_width'] = $newImageWidth;
        $avatar_info['current_large_image_height'] = $newImageHeight;
        if ($mode == 0){
            $avatar_info['thumb_height'] = DEFAULT_AVATAR_HEIGHT;
            $avatar_info['thumb_width'] = DEFAULT_AVATAR_WIDTH;
        }
        else {
            $avatar_info['thumb_height'] = 250;
            $avatar_info['thumb_width'] = 950;
        }
        $avatar_info['aspectRatio'] = DEFAULT_AVATAR_HEIGHT / DEFAULT_AVATAR_WIDTH;
        $avatar_info['large_image_location'] = $large_image_relative_location;
        $avatar_info['large_photo_exists'] = $large_photo_exists;
        $avatar_info['large_image_absolute_location'] = $newImgId;
        ImageLog('LOG',sprintf('%s id=%s ',$logStr,$newImgId));

        return $avatar_info;
    }

    public function fetchAvatarFromOutsite($userid, $avatarUrl, $timeout=3){
        $avatarUrl = urldecode($avatarUrl);
        $logStr =  sprintf('act=fetch_outsite_avatar uid=%d url=%s',
                           $userid,$avatarUrl);
        //$num = rand() % count($GLOBALS['IMAGE_SERVICE']['SERVERS']);
        //$domain = $GLOBALS['IMAGE_SERVICE']['SERVERS'][$num];
        //$process_url = $domain . $GLOBALS['IMAGE_SERVICE']['PIC_GETPICID'] . "url=" . $avatarUrl . "&kind=ap&ext=true";
        $uri = $GLOBALS['IMAGE_SERVICE']['PIC_GETPICID'] . "url=" . $avatarUrl . "&kind=ap&ext=true";
        $process_url = ComposeImgServiceReqUrl($uri, IMAGE_SERVICE_SET_IMAGE);
        $ch = curl_init($process_url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $info = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($curl_errno > 0) {
            ImageLog('ERROR',sprintf('%s failed to receive image data,curl_errno=%s,curl_error=%s',$logStr, $curl_errno, $curl_error));
            return FALSE;
        }
        if ($code != 200) {
            ImageLog('ERROR', sprintf('%s failed to download image return code is %s', $logStr, $code));
            return FALSE;
        }
        if (!isset($info)) {
            ImageLog('ERROR', sprintf('%s get data fail', $logStr));
            return FALSE;
        }
        $info = json_decode($info, true);
        if ($info['ret'] != 0) {
            ImageLog('ERROR', sprintf('%s get data fail ret=%s', $logStr,strval($info['ret'])));
            return FALSE;
        }
        if (!isset($info['data']['n_pic_file'])) {
            ImageLog('ERROR',sprintf('%s get n_pic_file failed', $logStr));
            return FALSE;
        }
        $origAvatarKey =  $info['data']['n_pic_file'];
        ImageLog('LOG', sprintf('%s n_pic_file=%s', $logStr, $info['data']['n_pic_file']));
        $AVATAR_THUMBS = array(
            'a' => array(128,128),
            'b' => array(64,64),
            'c' => array(32,32),
            'd' => array(16,16),
            'e' => array(180,180),
            'f' => array(48,48),
            'g' => array(28,28),
            'h' => array(200,200)
        );
        $thumbIds = array();
	$updateData = array();
        foreach($AVATAR_THUMBS as $thumbType => $thumbSize){
            $thumbId = str_replace('/_o/','/' . $thumbType . '/',$origAvatarKey);
            $thumbIds[$thumbType] = $thumbId;
            if ( $thumbType !='f' && $thumbType !='g'  && $thumbType != 'h' )//不将这两中缩略图的信息存到数据库中
                $updateData['avatar_' . $thumbType] = $thumbIds[$thumbType];
        }
        $result_str = implode(',',$thumbIds);
        //avatar 创建成功，将头像路径添加到用户信息
        $updateData['user_id'] = $userid;
        $updateData['is_uploaded'] = 1;
        $isSucc = userModel::getInstance()->updateUserExtInfo1($updateData);
        if($isSucc === TRUE) {
            ImageLog('LOG',sprintf('%s result=succeed ids=%s',
                     $logStr, $result_str));
            return TRUE;
        }
        ImageLog('ERROR',sprintf('%s err=save_db ids=%s', $logStr, $result_str));
        return FALSE;
    }

	static public function savePicture($url, $thumb = 'f') {
		//通过拾宝器接口，下载，存取图片
		$url = $GLOBALS['IMAGE_SERVICE']['PIC_GETPICID']."url=".$url;
		$url = ComposeImgServiceReqUrl($url, IMAGE_SERVICE_DOWNLOAD_IMAGE);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 11);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$picId = curl_exec($ch);//拾宝器返回picid
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		$code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
		if (200 != $code) {
			$logError = new zx_log('savePicture_error', 'normal');
			$logError->w_log("err=fail_save_picture for url: $url");
			$logError->w_log(print_r($code, TRUE));
			$logError->w_log("errno: $curl_errno, error: $curl_error");
			return FALSE;
		}
		importer('corelib.MlsStorageService');
		$columns_names = "n_pic_file";
    	$sql = "SELECT $columns_names FROM t_picture WHERE picid = " . $picId;
    	$result = MlsStorageService::GetQueryData("t_picture", array(), $columns_names, $sql);
		if (!empty($result[0]['n_pic_file'])) {
			$uri = str_replace('/_o/', '/f/', $result[0]['n_pic_file']);
			return $uri;
		}
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
     * 上传推图片.
     *
     * @param img[in]: gd图片资源
     * @return 成功返回array('file'=>图片id,'width'=>宽,'height'=>高,
     *         'thumb'=>array('a'=>thumb的id,'b'=>...).
     *         失败返回FALSE.

     */
    public function uploadPictureFromGD($img){
        $imgObj = new Image();
        $imgObj->attach($img,'jpg');
        return $this->uploadPictureFromImgObj($imgObj);
    }
    public function uploadPictureFromImgObj($img,$watermarkTag = ''){
        $result = array();
        if(!$img->isLoaded()){
            ImageLog('ERROR',sprintf('act=uploadPic:load_img err=fail '));
            return FALSE;
        }
        $fileext = $img->getExt();
        if(!isValidImageExt($fileext)){
            $fileext = 'jpg';
        }
        $origImgBytes = $img->getContent($fileext,90,TRUE);
        if($origImgBytes === FALSE){
            ImageLog('ERROR',sprintf('act=uploadPic:get_content err=fail'));
            return FALSE;
        }
        $width = $img->getWidth();
        $height = $img->getHeight();
        $origImgId = $this->imgStg->saveOrigImage($origImgBytes,$fileext,'pic');
        if(!is_string($origImgId) || empty($origImgId)){
            ImageLog('ERROR',sprintf('act=uploadPic:save err=fail '));
            return FALSE;
        }
        $imgBytesBeforeWatermark = NULL;
        if(!empty($watermarkTag)) {
            $origImgId = ImageStg::genFilterImgId($origImgId,$watermarkTag);
        }
        ImageLog('LOG',sprintf('act=uploadPic:save id=%s', $origImgId));
        $result['file'] = $origImgId;
        $result['width'] = $width;
        $result['height'] = $height;
	$result['size'] = strlen($origImgBytes);
        // 文件类型允许时,缩略生成jpeg
        $thumb_info = $GLOBALS['UPLOAD_PICTURE'];
        foreach ($thumb_info AS $thumb_key => $thumb) {
            $result['thumb'][$thumb_key] = ImageStg::getThumbId($origImgId,$thumb_key);
        }
        return $result;
    }
    public function uploadPictureFromImgObjSync($img,$watermarkTag = ''){
        $result = array();
        if(!$img->isLoaded()){
            ImageLog('ERROR',sprintf('act=uploadPic:load_img err=fail '));
            return FALSE;
        }
        $fileext = $img->getExt();
        if(!isValidImageExt($fileext)){
            $fileext = 'jpg';
        }

        $origImgBytes = $img->getContent($fileext);
        if($origImgBytes === FALSE){
            ImageLog('ERROR',sprintf('act=uploadPic:get_content err=fail'));
            return FALSE;
        }
        $width = $img->getWidth();
        $height = $img->getHeight();
        $origImgId = $this->imgStg->saveOrigImage($origImgBytes,$fileext,'pic');
        if(!is_string($origImgId) || empty($origImgId)){
            ImageLog('ERROR',sprintf('act=uploadPic:save err=fail '));
            return FALSE;
        }
               $imgBytesBeforeWatermark = NULL;
        if(!empty($watermarkTag)) {
           $origImgId = ImageStg::genFilterImgId($origImgId,$watermarkTag);
        }

        ImageLog('LOG',sprintf('act=uploadPictureFromImgObjSync :save id=%s',$origImgId));
        $result['file'] = $origImgId;
        $result['width'] = $width;
        $result['height'] = $height;
	$result['size'] = strlen($origImgBytes);

        // 文件类型允许时,缩略生成jpeg
        $thumb_info = $GLOBALS['UPLOAD_PICTURE'];
	if (isset($GLOBALS['UPLOAD_PICTURE_CRAWLER_SIZES'])) {
	    $crawler_sizes = $GLOBALS['UPLOAD_PICTURE_CRAWLER_SIZES'];
	    $crawler_arr = explode(",", $crawler_sizes);
	    foreach ($crawler_arr AS $thumb_key) {
		$thumb = $thumb_info[$thumb_key];
		$thumbImgId = ImageStg::getThumbId($origImgId,$thumb_key);
		//$thumbImgBytes = $this->createImgFromOrigImg($thumbImgId,$img);
                $result['thumb'][$thumb_key] = $thumbImgId;
            }
	}
	else {
	    foreach ($thumb_info AS $thumb_key => $thumb) {
		$thumbImgId = ImageStg::getThumbId($origImgId,$thumb_key);
		//$thumbImgBytes = $this->createImgFromOrigImg($thumbImgId,$img);
                $result['thumb'][$thumb_key] = $thumbImgId;
            }
	}
        return $result;
    }

    //功能和uploadPictureFromImgObj类似，使用的是imagemagick库
    public function uploadPictureFromMagick($tmpFile){
        $result = array();
        $img = new ImageImagick();
        $img->load($tmpFile);
        if ( !$img->isLoaded() ) {
            ImageLog('ERROR',sprintf('act=uploadPic:load_img err=fail '));
            return FALSE;
        }
        $fileext = 'gif';
        unlink($tmpFile);

        $origImgBytes = $img->getContent(NULL,90,TRUE);
        if($origImgBytes === FALSE){
            ImageLog('ERROR',sprintf('act=uploadPic:get_content err=fail'));
            return FALSE;
        }
        $width = $img->getWidth();
        $height = $img->getHeight();
        $origImgId = $this->imgStg->saveOrigImage($origImgBytes,$fileext,'pic');
        if(!is_string($origImgId) || empty($origImgId)){
            ImageLog('ERROR',sprintf('act=uploadPic:save err=fail '));
            return FALSE;
        }
        ImageLog('LOG',sprintf('act=uploadPictureFromMagick:save id=%s',
            $origImgId));
        $result['file'] = $origImgId;
        $result['width'] = $width;
        $result['height'] = $height;
	$result['size'] = strlen($origImgBytes);
        // 文件类型允许时,缩略生成jpeg
        $thumb_info = $GLOBALS['UPLOAD_PICTURE'];
        foreach ($thumb_info AS $thumb_key => $thumb) {
            $result['thumb'][$thumb_key] =
                ImageStg::getThumbId($origImgId,$thumb_key);
        }
        importer("corelib.task");
        $taskHelper = new task();
        $taskHelper->setFile("./createPicThumb.php");
        $taskHelper->setParm(array($origImgId));
        $taskHelper->taskRun();
        return $result;
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
     * 功能和saveAvatarThumb类似，只对gif格式的图像进行处理，使用的是imagemagick库
     * 用户选择修改缩略图.
     * @param thumbInfo : array ,key有'orig_image_key','x1','y1','x2','y2','w','h'.
     * @return bool ,成功返回TRUE,失败返回FALSE.
     */
    public function saveAvatarThumb($thumbInfo,$needUpdateDb,$mode = 0){
        if (!empty($thumbInfo['user_id'])) {
            $user_id = $thumbInfo['user_id'];
            $orig_image_path = $thumbInfo['orig_image_key'];
            $logStr = sprintf('act=avatar:save uid=%d tmpId=%s',$user_id, $orig_image_path);
        }
        if (isset($thumbInfo['group_id'])) {
            $group_id = $thumbInfo['group_id'];
            $mode = $thumbInfo['mode'];
            $orig_image_path = $thumbInfo['orig_image_key'];
            $logStr = sprintf('act=avatar:save gid=%d tmpId=%s',$group_id, $orig_image_path);
        }

        //Get the new coordinates to crop the image.
        $x1 = intval($thumbInfo["x1"]);
        $y1 = intval($thumbInfo["y1"]);
        $x2 = intval($thumbInfo["x2"]);
        $y2 = intval($thumbInfo["y2"]);
        $w = intval($thumbInfo["w"]);
        $h = intval($thumbInfo["h"]);
        if($w == 0 || $h == 0){
            ImageLog('ERROR',sprintf('%s err=input w=%d h=%d',$logStr,$w,$h));
            return FALSE;
        }
        //Scale the image to the thumb_width set above
        //$scale = DEFAULT_AVATAR_WIDTH / $w;
        $origImgBytes = $this->imgStg->getImage($orig_image_path);

        if($origImgBytes === FALSE){
            ImageLog('ERROR',sprintf('%s err=getOrig',$logStr));
            return FALSE;
        }

        $path_parts = pathinfo($orig_image_path);
        $ext = $path_parts['extension'];

        //$ext = 'gif';
        $img = new Image();
        if( !$img->loadFromMem($origImgBytes,$ext) ){
            ImageLog('ERROR',sprintf('%s err=loadImg',$logStr));
            return FALSE;
        }
        $origImg = $img->cloneMyself();
        if($origImg === FALSE){
            ImageLog('ERROR',sprintf('%s err=cloneMyself',$logStr));
            return FALSE;
        }

        if (!$origImg->crop($x1, $y1, $w, $h)){
            ImageLog('ERROR',sprintf('%s err=crop',$logStr));
            return FALSE;
        }

        $origBytes = $origImg->getContent();
        if($origBytes === FALSE){
            ImageLog('ERROR',sprintf('%s err=getContent',$logStr));
            return FALSE;
        }

        if (!empty($thumbInfo['user_id'])) {
            $origId = $this->imgStg->saveOrigImage($origBytes,$ext,'ap');
        }
        elseif (!empty($thumbInfo['group_id'])) {
            $origId = $this->imgStg->saveOrigImage($origBytes,$ext,'glogo');
        }
        elseif (!empty($thumbInfo['mode'])){
            $origId = $this->imgStg->saveOrigImage($origBytes,$ext,'glogo');
        }
        if($origId === FALSE){
            ImageLog('ERROR',sprintf('%s err=save_orig %s', $logStr));
            return FALSE;
        }
        $AVATAR_THUMBS = array(
            'a' => array(128,128),
            'b' => array(64,64),
            'c' => array(32,32),
            'd' => array(16,16),
            'e' => array(180,180),
            'f' => array(48,48),
            'g' => array(28,28),
            'h' => array(200,200)
        );
        $thumbIds = array();
        $updateData = array();
        foreach($AVATAR_THUMBS as $thumbType => $size){
            $thumbIds[$thumbType] = FALSE;
            $a_bytes = $origImg->getContent();
            if($a_bytes === FALSE){
                ImageLog('ERROR',sprintf('%s err=getContent',$logStr));
                return FALSE;
            }
            if ($mode == 0) {
                $thumbIds[$thumbType] = ImageStg::getThumbId($origId, $thumbType);
            }
            unset($a_bytes);
            if($thumbIds[$thumbType] === FALSE){
                ImageLog('ERROR',sprintf('%s err=save_thumb type=%s %s'), $thumbType,$logStr);
                $thumbIds[$thumbType] = '';
            }
            if ( $thumbType !='f' &&  $thumbType !='g'  && $thumbType != 'h')//不将这两中缩略图的信息存到数据库中
                $updateData['avatar_' . $thumbType] = $thumbIds[$thumbType];
        }
        unset($img);
        unset($origImg);

        $result_str = implode('@_SPLIT_@',$thumbIds);
        if($needUpdateDb !== TRUE){
            return $result_str;
        }
        //avatar 创建成功，将头像路径添加到用户信息
        if (isset($user_id)) {
            $updateData['user_id'] = $user_id;
            $updateData['is_uploaded'] = 1;
            $isSucc = userModel::getInstance()->updateUserExtInfo1($updateData);
        }
        elseif (isset($group_id) && ($mode) == 0) {
            $updateData['group_id'] = $group_id;
            $updateData['is_uploaded'] = 1;
            $isSucc = TopicGroupModel::getInstance()->changeGroupImage($updateData);
            if(!empty($updateData['avatar_a']) && $updateData['avatar_a'] != "glogo/a/24/3a/029fd92ab2a12e07e04a57460a69_180_180.jpg"){
                $iData = array();
                $iData['n_pic_file'] = preg_replace('/\/a\//','/_o/',$updateData['avatar_a']);
                importer('corelib.imageStg');
                $img = new ImageLogic();
                $saveResult = $img->uploadPictureFromGlogo($iData['n_pic_file']);
                preg_match('/_[0-9]*_/',$iData['n_pic_file'], $w);
                preg_match('/_[0-9]*\./',$iData['n_pic_file'], $h);
                $find = array('_','.');
                $iData['nwidth'] = str_replace($find,'',$w[0]);
                $iData['nheight'] = str_replace($find,'',$h[0]);
                $iData['size'] = strlen($origBytes);
                $iData['authorid'] = -1;
                $iData['n_pic_file'] = $saveResult['file'];
                $picId = pictureModel::getInstance()->insertPicture($iData);
                $updateData['picid'] = $picId;
                $picIdIsSucc = TopicGroupModel::getInstance()->changePicId($updateData);
            }

            return TRUE;
        }
        elseif (isset($group_id) && ($mode) == 1) {
            $updateData['group_id'] = $group_id;
            $updateData['is_uploaded'] = 1;
            $updateData['avatar_a'] = $origId;
            $picMode = 'header_path';
            $isSucc = TopicGroupModel::getInstance()->changeGroupImage($updateData, $picMode);
            return TRUE;
        }
        if($isSucc === TRUE) {
            ImageLog('LOG',sprintf('%s result=succeed ids=%s',
                $logStr, $result_str));
            return TRUE;
        }

        ImageLog('ERROR',sprintf('%s err=save_db ids=%s', $logStr,$result_str));
        return FALSE;
    }

    /**
     * @params:
     * $origUrl: string, url of original picture
     * $sType: string, 切割算法，具体参考wiki上的文档
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

