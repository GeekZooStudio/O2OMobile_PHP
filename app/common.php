<?php
/**
 * ----------------------------------------------
 * 全局函数库
 * ----------------------------------------------
 */
use \Carbon\Carbon;
const EARTH_RADIUS   =  6371; //地球半径，平均半径为6371km

 /**
 *计算某个经纬度的周围某段距离的正方形的四个点
 *
 *@param lon float 经度
 *@param lat float 纬度
 *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
 *@return array 正方形的四个点的经纬度坐标
 */
 function returnSquarePoint($lon, $lat,$distance = 10){

    $dlon =  2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
    $dlon = rad2deg($dlon);

    $dlat = $distance/EARTH_RADIUS;
    $dlat = rad2deg($dlat);

    return array(
                'left-top'=>array('lat'=>$lat + $dlat,'lon'=>$lon-$dlon),
                'right-top'=>array('lat'=>$lat + $dlat, 'lon'=>$lon + $dlon),
                'left-bottom'=>array('lat'=>$lat - $dlat, 'lon'=>$lon - $dlon),
                'right-bottom'=>array('lat'=>$lat - $dlat, 'lon'=>$lon + $dlon)
                );
 }




/**
 * 格式化location为API格式
 *
 * @param string $location json encoded
 *
 * @return array
 */
function apiLocation($location)
{
    if (is_string($location)) {
        $location = json_decode($location, true);
    }
    empty($location['lat']) || $location['lat'] = round($location['lat'], 6);
    empty($location['lon']) || $location['lon'] = round($location['lon'], 6);

    $location = array_merge(array('name' => '', 'lat' => '0', 'lon' => '0'), (array)$location);

    return $location;
}



/**
 * 调用一个控制器
 *
 * @param string $controller
 * @param string $action
 * @param array  $parameters
 *
 * @return object
 */
function call($controller, $action, $parameters = array())
{
    $app = app();
    $controller = $app->make($controller);
    return $controller->callAction($action, $parameters);
}

/**
 * 加密密码
 *
 * @param string $username
 * @param string $password
 *
 * @return string
 */
function hash_password($password)
{
    $secure_key = 'O2OMobile_Password_!@#';
    return md5(md5($secure_key) . md5($password));
}

/**
 * 检查当前url是否属于菜单中定义的模式，完成菜单激活状态
 *
 * @param array $pattern app/config/menu.php中的pattern
 *
 * @return boolean
 */
function is_current_model(array $pattern)
{
    foreach ($pattern as $ptn) {
        if (Request::is($ptn)) {
            return true;
        }
    }

    return false;
}

/**
 * 获取上传文件的路径
 *
 * @param string $
 * @param string $
 *
 * @return string
 */
function getFilePath($filename, $type = 'picture')
{
    $host = 'http://'.$_SERVER['HTTP_HOST'];
    $path = 'uploadFile';
    $url = $host . '/' . $path .'/'. $type . $filename;
    return $url;
}


/**
 * 获取文件拓展名
 *
 * @param string $filename
 *
 * @return string
 */
function fileExt($filename)
{
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    return empty($ext) ? '' : ".$ext";
}




/**
 * 转换为时间戳
 *
 * @param string $timeString
 *
 * @return integer
 */
function apiTime($timeString)
{
    return strtotime($timeString) < 0 ? '2014-01-01 00:00:00 +0800' : date('Y/m/d H:i:s O', strtotime($timeString));
}

/**
 * 移除数组中的null，bool转换为整型，以便于客户端JSON处理
 *
 * @param array $array
 * @return array
 */
function formatRestJson($array)
{
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if ($value === '' || is_null($value)) {
                unset($array[$key]); //移除空
            } else if (is_array($value)) {
                $value = formatRestJson($value);
                if(!empty($value)) {
                    $array[$key] = $value;
                } else {
                    unset($array[$key]);
                }
            } else if (is_bool($value)) {
                $array[$key] = intval($value); //bool转换为整型
            }
        }
    }

    return $array;
}

/**
 * 短信发送
 *
 * @param
 * @return bool
 */
function sms_send($mobile, $content) {
    $flag = 0;
    $argv = array(
        'sn'=> Config::get('sms.sn'),
        'pwd'=> strtoupper(md5(Config::get('sms.sn').Config::get('sms.pwd'))),
        'mobile'=> $mobile,
        'content'=> iconv("UTF-8", "gb2312//IGNORE", $content.Config::get('sms.sign_name')),
        'ext'=>'',
        'stime'=>'',
        'rrid'=>''
    );
    //http_build_query
    $params = '';
    foreach ($argv as $key=>$value) {
        if ($flag != 0) {
            $params .= "&";
            $flag = 1;
        }
        $params.= $key."="; $params.= urlencode($value);
        $flag = 1;
    }
    //
    $length = strlen($params);
    $fp = @fsockopen(Config::get('sms.host'), 8060, $errno, $errstr, 10);
    //构造post请求的头
    $header = "POST /webservice.asmx/mdSmsSend_u HTTP/1.1\r\n";
    $header .= "Host:".Config::get('sms.host')."\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: ".$length."\r\n";
    $header .= "Connection: Close\r\n\r\n";
    //添加post的字符串
    $header .= $params."\r\n";
    //发送post的数据
    fputs($fp, $header);
    $inheader = 1;
    while (!feof($fp)) {
        $line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据
        if ($inheader && ($line == "\n" || $line == "\r\n")) {
            $inheader = 0;
        }
        if ($inheader == 0) {
            // echo $line;
        }
    }
    preg_match('/<string xmlns=\"http:\/\/tempuri.org\/\">(.*)<\/string>/', $line, $str);
    $result = explode("-",$str[1]);
    if(count($result)>1) {
        //echo '发送失败返回值为:'.$line."请查看webservice返回值";
        return false;
    }
    return true;
}

/**
 * 生成随机数
 *
 * @param
 * @return string
 */
function get_randStr($len=6, $format='ALL') {
    switch($format) {
        case 'ALL':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
        case 'CHAR':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
        case 'NUMBER':
            $chars='0123456789';
            break;
        default :
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
    }
    mt_srand((double)microtime()*1000000*getmypid());
    $rand_str = "";
    while(strlen($rand_str) < $len) {
        $rand_str.= substr($chars,(mt_rand()%strlen($chars)), 1);
    }
    return $rand_str;
 }

/**
 * urldecode array
 *
 * @param array $array
 *
 * @return array
 */
if (!function_exists('multi_urldecode')) {
    function multi_urldecode(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $array[$key] = urldecode($value);
            } else if(is_array($value)) {
                $array[$key] = multi_urldecode($value);
            }
        }

        return $array;
    }
}


if (!function_exists('setLocation')) {
    /**
     * 设置坐标信息
     *
     * @param string|array $location
     * @param string       $ext
     *
     * @return json encoded data
     */
    function setLocation($location, $ext = 'all'){
        if (empty($location)) {
            return '';
        }

        if (is_string($location) && strpos($location, ',')) {
            list($location['lat'], $location['lon']) = explode(',', $location);
        }

        return json_encode(getAddressByLatLon($location['lat'], $location['lon'], $ext));
    }
}

if (!function_exists('getAddressByLatLon')) {
   
    function getLatLonByAddress($address)
    {
        $ak     = Config::get('3rdkey.baidu_map_ak','');
        $api    = "http://api.map.baidu.com/geocoder/v2/?address=%s&output=json&ak={$ak}";
        $url    = sprintf($api, $address);

        $result = getBaiduApi($url);

        if($result != 0){
            return array(
                    'name' => $address,
                    'lon' => $result['result']['location']['lng'],
                    'lat' => $result['result']['location']['lat'],
                );
        }else{
            return array(
                    'name' => $address,
                    'lon' => '0.00',
                    'lat' => '0.00',
                );
        }

    }


    function getAddressByLatLon($lat, $lon, $ext = 'all')
    {
        $ak     = Config::get('3rdkey.baidu_map_ak','');
        $lat    = floatval($lat);
        $lon    = floatval($lon);
        $api    = "http://api.map.baidu.com/geocoder/v2/?ak={$ak}&location=%s,%s&output=json&coordtype=gcj02ll";
        $url    = sprintf($api, $lat, $lon);

        $result = getBaiduApi($url);
        if($result == 0){
            return  array(
                 'name' => '',
                 'lat'  => $lat,
                 'lon'  => $lon,
                );
        }        

        if ($ext == 'province') {
            $address = $result['result']['addressComponent']['city'];
        } else if($ext == 'city_and_dis'){
            $address = $result['result']['addressComponent']['city'].$result['result']['addressComponent']['district'];
        }else{
            $address = $result['result']['formatted_address'];
        }
        if (stripos($address, '邮政编码')) {
            $address = strstr($address, '邮政编码:', true);
        }

        return  array(
                 'name' => $address,
                 'lat'  => $lat,
                 'lon'  => $lon,
                );
    }



    function getBaiduApi($url)
    {
        $url = urldecode($url);
        $ch = curl_init();
        $timeout = 30;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($ch);
        if($result === false)
        {
            return 0;
        }
        curl_close($ch);
        $backInfo = json_decode($result, true);
        return $backInfo;
    }


    function postApiUrl($url, $jsons)
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$jsons);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Content-Length: ' . strlen($jsons))
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 把请求要素按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param para 请求要素
     * @param sort 是否需要根据key值作升序排列
     * @param encode 是否需要URL编码
     * @return 拼接成的字符串
     */
    function createLinkString($para, $encode) {
        $sort = false;
        $linkString  = "";
        if ($sort){
            $para = argSort($para);
        }
        while (list ($key, $value) = each ($para)) {
            if ($encode){
                $value = urlencode($value);
            }
            $linkString.=$key."=".$value."&";
        }
        //去掉最后一个&字符
        $linkString = substr($linkString,0,count($linkString)-2);

        return $linkString;
    }

    function getParams4($memberId,$TerminalID,$key,$orderMoney, $transId, $returnUrl) {
        $pageUrl = "";
        $payId = "4010001";
        $tradeDate = date('YmdHis');
        $noticeType = "0";
        $keyType = "1";
        $commodityAmount = "1";
        $interfaceVersion = "4.0";
        $temp =  $memberId."|".$payId.'|'.$tradeDate.'|'.$transId.'|'.$orderMoney.'|'.$pageUrl.'|'.$returnUrl.'|'.$noticeType.'|'.$key;
        $signature = md5($temp);
        $commodityName = urlencode("宝付测试商品");   //需URL编码
        $userName = urlencode("baofoo");     //需URL编码
        $AdditionalInfo = urlencode("附加信息"); //需URL编码
        $pageUrl = urlencode($pageUrl); // 页面通知地址 ,需URL编码
        $returnUrl = urlencode($returnUrl); // 服务器通知地址 ,需URL编码

        $req = array();
        $req["PayID"]       = $payId;
        $req["MemberID"]    = $memberId;
        $req["TerminalID"]  = $TerminalID;
        $req["TradeDate"]   = $tradeDate;
        $req["OrderMoney"]  = $orderMoney;
        $req["TransId"]     = $transId;
        $req["ReturnUrl"]   = $returnUrl;
        $req["PageUrl"]     = $pageUrl;
        $req["KeyType"]     = $keyType;
        $req["Signature"]   = $signature;
        $req["CommodityName"] = $commodityName;
        $req["CommodityAmount"] = $commodityAmount;
        $req["UserName"]    = $userName;
        $req["AdditionalInfo"] = $AdditionalInfo;
        $req["InterfaceVersion"] = $interfaceVersion;
        $req["noticeType"]   = $noticeType;

        $s = createLinkString($req,false);        

        return $s;
    }



    /**
     * 获取邀请我的人 和 邀请我的人的上级
     *
     * @param int   $user_id
     *
     * @return id
     */
    function giveInvite($user_id)
    {


        $invite_uid = User::userinfo($user_id, 'invite_uid');

        if($invite_uid == 0){
            return true;
        }

        $user = User::find($invite_uid);

        $user->balance = $user->balance + 0.05;

        $user->save();


        $invite_two_uid = User::userinfo($invite_uid, 'invite_uid');

        if($invite_two_uid == 0){
            return true;
        }


        $users = User::find($invite_two_uid);

        $users->balance = $users->balance + 0.04;

        $users->save();


    }


    /**
     * 一次性返现逻辑
     *
     * @param int   $user_id
     *
     * @return id
     */
    function giveOneTimeInvite($user_id)
    {

        $count = DB::table('orders')->where('employer', $user_id)->count();

        if($count != 1){   //现在统计的是所有的订单不管成不成功都算一次
            return true;
        }

        $invite_uid = User::userinfo($user_id, 'invite_uid');

        if($invite_uid == 0){
            return true;
        }

        $user = User::find($invite_uid);

        $user->balance = $user->balance + 25;

        $user->save();


    }

    /**
     * 格式化时间
     *
     * @param int   $user_id
     * @return id
     */
    function format_created_at($created_at)
    {
        if (!is_int($created_at)) {
            $created_at = strtotime($created_at);
        }

        $seconds = time() - $created_at;
        $minutes = floor($seconds / 60);
        $hours = floor($seconds / (60 * 60));
        $days = floor($seconds / (60 * 60 * 24));
        //
        if ($seconds < 60) {
            return "刚刚";
        }
        elseif ($seconds < 120) {
            return "1分钟前";
        }
        elseif ($minutes < 60) {
            return $minutes . "分钟前";
        }
        elseif ($minutes < 120) {
            return "1小时前";
        }
        elseif ($hours < 24) {
            return $hours . "小时前";
        }
        elseif ($hours < 24 * 2) {
            return "1天前";
        }
        elseif ($days < 30) {
            return $days . "天前";
        }
        elseif ($days < 365) {
            return floor($days / 30) . "个月前";
        }
        else {
            return date("YYYY年mm月dd日", $created_at);
        }
    }

    /**
     * 获取未读消息数
     *
     * @param int   $user_id
     * @return int
     */
    function unread_message_count($user_id=0)
    {
        $unread_count = 0;
        if (Auth::check()) {
            $user_id = !$user_id ?  Auth::user()->id : $user_id;
            $unread_count = Message::where('user_id', $user_id)
                ->where('type', '>', 1)
                ->where('is_readed', 0)
                ->count();
        }
        return $unread_count;
    }



    /**
     * 获取域名
     *
     * @return string
     */
    function getDoman()
    {

        return 'http://' .$_SERVER['HTTP_HOST'];
    }


    /**
     * 往大c表里插一条 统计用
     *
     * @return string
     */
    function putBigc($orders)
    {

        $bigc = new Bigc;
        $bigc->user_id         = $orders->employee;
        $bigc->order_id        = $orders->id;
        $bigc->years           = date('Y', time());
        $bigc->month           = date('m', time());
        $bigc->times           = date('Y', time()).date('m', time());
        $bigc->save();
    }

}

/**
 * 发微信
 *
 * @param string $content
 *
 * @return boolean
 */
function send_wx($content = '')
{
    if (!$content) {
        return false;
    }
    if (is_string($content)) {
        $content = urlencode($content);
    }
    $tmpVar = file_get_contents('http://api.phpxuer.com/index.php?a=api/i/wx&fakeid=2643682040&msg='.$content);
    return true;
}

/**
 * 测试加参输出
 * @param string $var
 */
function dump($var = '')
{
    if (Input::get('pass', '') == '2014') {
        if (is_array($var)) {
            print_r($var);
        } else {
            var_dump($var);
        }
    }
}