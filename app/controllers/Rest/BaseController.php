<?php 
//
//       _/_/_/                      _/            _/_/_/_/_/
//    _/          _/_/      _/_/    _/  _/              _/      _/_/      _/_/
//   _/  _/_/  _/_/_/_/  _/_/_/_/  _/_/              _/      _/    _/  _/    _/
//  _/    _/  _/        _/        _/  _/          _/        _/    _/  _/    _/
//   _/_/_/    _/_/_/    _/_/_/  _/    _/      _/_/_/_/_/    _/_/      _/_/
//
//
//  Copyright (c) 2015-2016, Geek Zoo Studio
//  http://www.geek-zoo.com
//
//
//  Permission is hereby granted, free of charge, to any person obtaining a
//  copy of this software and associated documentation files (the "Software"),
//  to deal in the Software without restriction, including without limitation
//  the rights to use, copy, modify, merge, publish, distribute, sublicense,
//  and/or sell copies of the Software, and to permit persons to whom the
//  Software is furnished to do so, subject to the following conditions:
//
//  The above copyright notice and this permission notice shall be included in
//  all copies or substantial portions of the Software.
//
//  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
//  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
//  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
//  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
//  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
//  FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
//  IN THE SOFTWARE.
//

namespace Rest;
use \DB;
use \Log;
use \App;
use \User;
use \Input;
use \Config;
use \History;
use \Message;
use \Request;
use \Response;
use \Validator;
use \ClientSession;
use \Carbon\Carbon;

/**
 * API控制器基类
 * 
 * @author  yanan <yanan.pan@gmail.com>
 * @version 1.0
 */
class BaseController extends \Controller {
    // 错误码
    const STATUS_OK             = 0;    // 正常
    const STATUS_UNKNOWN_ERROR  = 500;  // 内部错误
    const STATUS_BAD_REQUEST    = 101;  // 错误的请求
    const STATUS_AUTH_FAIL      = 401;  // APPKEY与APPSEC认证失败
    const STATUS_AUTH_EXPIRED   = 1;    // 授权过期
    const STATUS_NOT_FOUND      = 404;  // 页面不存在  
    const STATUS_ACCOUNT_LOCKED = 600;  // 账号被锁定 
    const STATUS_EMPTY_DATA     = 201;  // 操作成功，空数据

    //错误消息
    protected static $errorMsg = array(
        self::STATUS_OK             => '成功',
        self::STATUS_UNKNOWN_ERROR  => '服务器内部错误',
        self::STATUS_BAD_REQUEST    => '错误的请求',
        self::STATUS_AUTH_FAIL      => '认证失败',
        self::STATUS_AUTH_EXPIRED   => '授权过期',
        self::STATUS_NOT_FOUND      => '数据未找到',
        self::STATUS_EMPTY_DATA     => '操作成功，数据不存在！',
        self::STATUS_ACCOUNT_LOCKED => '您的账号已被锁定',
    );


    //筛选
    const PRICE_DESC        = 0; //价格降序排列
    const PRICE_ASC         = 1; //价格升序排列
    const TIME_DESC         = 2; //时间排序
    const LOCATION_ASC      = 3; //按照距离有近到远排序
    const RANK_DESC         = 4; //评价从高到低
    const RANK_ASC          = 5; //评价从低到高


    //验证规则
    protected $reqRules = array(
       'UUID'       => 'required',
       'push_token' => 'required',
       'platform'   => 'required',
       'os'         => 'required',
       'APPID'      => 'required|min:6',
       'APPKEY'     => 'required|min:32',
    );

    protected $currentUser;
    protected $session;
    protected $time_start = 0;

    /**
     * 初始化，注册错误处理函数
     */
    public function __construct()
    {
        date_default_timezone_set('PRC');
        $this->time_start = microtime(true);
        if (App::environment() != 'production') {
            Log::info('request: '. Request::url().' with input:' . json_encode(Input::all()));
        }

        //从配置中取出需要登录的API
        $requireLoginApis = Config::get('rest.api_required_login');
        foreach ($requireLoginApis as $api) {
            if (Request::is("*{$api}*")) {
                $this->beforeFilter('@auth');
            }
        }

        $input = Input::all();
        $json = Input::get('json');
        if (!empty($json)) {
            $jsonData = @json_decode($json, true);
            !is_array($jsonData) || $input = array_merge($input, $jsonData);
        }

        $input = multi_urldecode($input);
        Request::merge($input);
        $uid = Input::get('uid');
        $sid = Input::get('sid');
        if (!empty($uid) && !empty($sid)) {
            //$this->currentUser = User::whereId(Input::get('uid'))->remember(60)->first();
            $this->currentUser = User::whereId(Input::get('uid'))->first();
            if ($this->currentUser && $this->currentUser->locked()) {
                return self::error(self::STATUS_ACCOUNT_LOCKED, '账户被锁定');
            }
        }
    }

    /**
     * 基本认证
     *
     * @return object
     */
    public function auth()
    {

        $uid = Input::get('uid');
        $sid = Input::get('sid');

        if (empty($uid) || empty($sid)) {
            return self::error(self::STATUS_AUTH_EXPIRED);
        }

        $session = ClientSession::where('user_id', $uid)->where('session_id', $sid)->first();
        // print_r($session);exit;
        if (!$session || $session->isExpired()) {
            return self::error(self::STATUS_AUTH_EXPIRED);
        }

    }



    /**
     * 验证输入
     *
     * @param array $rule 
     *
     * @return response
     */
    public function validateInput($rules)
    {
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return self::error(self::STATUS_BAD_REQUEST, strip_tags(join('、', $validator->messages()->all())));
        } else {
            return '';
        }
    }

    /**
     * 公用response接口
     *
     * @param array $data 
     *
     * @return object
     */
    public function json(array $data)
    {
        $data['succeed'] = 1;
        $data = array_map('formatRestJson', $data);
        $data['time_usage'] = substr(microtime(true) - $this->time_start, 0, 6) . ' s';
        
        return Response::json($data);
    }   

    /**
     * 返回错误
     *
     * @param string  $error
     * @param integer $code  
     *
     * @return object
     */
    public static function error($code, $error = '')
    {
        if (isset(self::$errorMsg[$code])) {
            $error = self::$errorMsg[$code] . (empty($error) ? '' : ": [$error]");
        }

        return Response::json([
            'succeed'    => 0,
            'error_code' => $code,
            'error_desc' => $error,
        ]);
    }


    /**
     * 返回错误
     *
     * @param string  $error
     * @param integer $code  
     *
     * @return object
     */
    public static function jsons(array $data, $succeed, $code, $error)
    {
        $data['succeed'] = 0;
        $data['error_code'] = $code;
        $data['error_desc'] = $error;
        $data = array_map('formatRestJson', $data);
        // $data['time_usage'] = substr(microtime(true) - $this->time_start, 0, 6) . ' s';
        
        return Response::json($data);
    }


    /**
     * 每次操作更新订单历史记录
     *
     * @return 
     */
    public function setHistory($order_status, $order_id, $user_id, $note = '')
    {

        $historyids = DB::table('history')->where('order_id', $order_id)->where('order_status', $order_status)->get();
        if(!empty($historyids)){
            return self::error(self::STATUS_BAD_REQUEST, '不能重复操作');
        }
        $history = new History;
        $history->order_id           = $order_id;
        $history->user_id            = $user_id;
        $history->order_status       = $order_status;
        $history->note               = $note;
        // print_r($history);exit;
        $history->save();
        
    }


    /**
     * 每次评论更新用户的被评论数和被评论级别
     *
     * @return 
     */
    public function updateRank($user_id, $rank)
    {

        $comments = DB::table('comments')->where('o_user', $user_id)->sum('rank');

        $users = User::find($user_id);

        $new = round((($comments + $rank) / ($users->comment_count + 1) / 5), 2);

        DB::table('users')
            ->where('id', $user_id)
            ->update(array('comment_count' => $users->comment_count + 1, 'comment_goodrate' => $new));
        
    }


    /**
     * 每次做完操作后存到message 再读出来推送
     *
     * @return 
     */
    public function createMessage($messages, $type = 0)
    {
        $message = new Message;
        $message->user_id     = $messages['user_id'];
        $message->content     = $messages['content'];
        $message->type        = $messages['type'];
        $message->order_id    = $messages['order_id'];
        $message->is_readed   = $messages['is_readed'];

        $message->save();
        
        $client = DB::table('client')->where('user_id', $messages['user_id'])->first();
    }


    
    
}
