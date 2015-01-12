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
use \Cache;
use \User;
use \Auth;
use \Input;
use \Config;
use \Client;
use \Withdraw;
use \Message;
use \ClientSession;
use \Applyservice;
use \UserCertity;
use \UserInvitecode;
use \Carbon\Carbon;
use \Illuminate\Database\Eloquent\Collection as Collection;

class UserController extends BaseController {

    //错误码
    const ERROR_MOBILE_NOT_EXISTS = 3;  //手机号不存在
    const ERROR_PASSWD_ERROR      = 6;   // 密码错误
    const ERROR_MOBILE_REPREAT    = 7;   // 用户名已经存在（注册）
    const ERROR_NICKNAME_REPREAT  = 10;  // 昵称已经存在（注册）
    const ERROR_CERTITY_REPREAT   = 11;  // 已经申请自由人

    /**
     * 用户详情
     * /user/get,/user/info/
     *
     * @return Response
     */
    public function getInfo()
    {
        $user = User::find(Input::get('uid'));
        if (empty($user)) {
            $user = array();
        } else {
            $user = $user->formatToApi();
        }

        return $this->json(array('user' => $user));
    }


    /**
     * 用户列表 跑腿服务
     *  周围排序
     *
     * @return Response
     */
    public function postList()
    {

        $location        = Input::get('location');
        $pageSize        = Input::get('count', 10);
        $service_type    = Input::get('service_type', 3);
        $sort_by         = Input::get('sort_by', self::TIME_DESC);
        $limit           = $pageSize + 1;
        $offset          = '';
        $hasMore         = 0;
        $total           = 0;
         $orderWhere = '`M`.services_id = '.$service_type.' AND `M`.user_id = `U`.id AND `U`.`role` = '.User::FREEMAN .' AND `U`.`status` = '.User::STATUS_OK ;        
        //按页码分页
        if (Input::has('by_no')) {
            $page = abs(Input::get('by_no', 1));
            $page > 1 || $page = 1;
            $offset = ' OFFSET '. $pageSize * ($page - 1);
        //按id分页，
        } elseif (Input::has('by_id')) {
            $lastId   = Input::get('by_id');
            if ($lastId > 0) {
                $orderWhere .= ' AND `M`.`id` < ' . $lastId;
            }
        }

        if($sort_by == self::PRICE_DESC){           //价格降序排列
            $orderBy = ' ORDER BY `M`.`price` DESC';
        }elseif($sort_by == self::PRICE_ASC){       //价格升序排列
            $orderBy = ' ORDER BY `M`.`price` ASC';
        }elseif($sort_by == self::LOCATION_ASC){    //按照距离有近到远排序
            $orderBy = " ORDER BY GetDistance({$location['lat']}, {$location['lon']}, `U`.`lat`, `U`.`lon`)  ASC";
        }elseif($sort_by == self::RANK_DESC){       //评价从高到低
            $orderBy = ' ORDER BY `U`.`comment_goodrate` DESC';
        }elseif($sort_by == self::RANK_ASC){        //评价从低到高
            $orderBy = ' ORDER BY `U`.`comment_goodrate` ASC';
        }

        $squares = returnSquarePoint($location['lon'], $location['lat']);
        // select b.id as id,a.id as fid,b.name as name,a.name as fname from 表名1 a inner join 表名1 b on a.id=b.uid
        $sql = "SELECT *
                     FROM `o2omobile_my_services` AS `M`
                     INNER JOIN `o2omobile_users` AS `U`
                     WHERE $orderWhere  AND `U`.`lat`<>0
                     AND `U`.deleted_at is NULL
                     $orderBy
                     LIMIT $limit
                     $offset";

        $userIds = DB::select($sql);
      // print_r($userIds);exit;
        $collection = new Collection;
        foreach ($userIds as $user) {
            $collection->add(with(new User)->fill((array)$user));
        }

        $users = array();
        if (!empty($collection)) {
            if (count($collection) > $pageSize) {
                $hasMore = 1;
                $collection->pop();//弹出最后一条
            }
            //格式化
            foreach ($collection as $user) {
                $users[] = $user->simpleFormatToApi();
            }
        }//endif

        $return = array(
                    'total'  => $total,
                    'count'  => count($users) < $pageSize ? count($users) : $pageSize,
                    'more'   => $hasMore,
                    'users' => $users,
                   );
        return $this->json($return);
    }



    /**
     * 获取用户资料
     *
     * @return Response
     */
    public function postProfile()
    {
        $user = User::find(Input::get('user'));
        if (empty($user)) {
            $user = array();
        } else {
            $user = $user->formatToApi();
        }

        return $this->json(array('user' => $user));
    }


    /**
     * 登录
     *
     * /user/signin
     *
     * @return Response
     */
    public function postSignin()
    {
        $mobile = Input::has('mobile_phone');
        $rule = array(
            'mobile_phone'    => 'required',
            'password' => 'required',
            'platform'      => 'required|in:ios,android',
        );
        if ($error = $this->validateInput($rule)) {
            return $error;
        }
        if (!User::where('mobile', Input::get('mobile_phone'))->first()) {
            return self::error(self::ERROR_MOBILE_NOT_EXISTS, '手机号码不存在');
        }

        $user = array(
            'mobile'    => Input::get('mobile_phone'),
            'password' => Input::get('password'),
        );

        if (!Auth::attempt($user)) {
            return self::error(self::ERROR_PASSWD_ERROR, '密码错误');
        }
        if (Auth::user()->locked()) {
            return self::error(self::STATUS_ACCOUNT_LOCKED, '请联系客服');
        }

        $session = ClientSession::setSession(Auth::user()->id);
        $user = Auth::getUser();
        //更新用户loactiton
        if ($location = Input::get('location')) {
            if (isset($location['lat']) && isset($location['lon'])) {
                $user->lat = $location['lat'];
                $user->lon = $location['lon'];
                $user->save();
            }
        }
        $this->currentUser = $user;
        $this->refreshClient($user->id);

        $resp = array(
            'sid'    => $session->session_id,
            'user'   => Auth::user()->formatToApi(),
            'config' => $this->getConfig(),
        );
        return $this->json($resp);
    }

    public function getTest()
    {
        Withdraw::balanceLog(Withdraw::CATE_REG, 59, 25, '注册奖励');
    }

    /**
     * 注册
     *
     * /user/signup
     * @return Response
     */
    public function postSignup()
    {
        $rule = array(
            'mobile_phone'  => 'required',
            'password'      => 'required|min:6',
            'nickname'      => 'required',
            'platform'      => 'required|in:ios,android',
        );
        if ($error = $this->validateInput($rule)) {
            return $error;
        }

        $nickname = trim(Input::get('nickname'));
        $mobile   = trim(Input::get('mobile_phone'));
        $invite_code = trim(Input::get('invite_code'));
        $password = hash_password(Input::get('password'));

        // 检查昵称重复
        if (User::where('nickname', $nickname)->first()) {
            return self::error(self::ERROR_NICKNAME_REPREAT, '昵称已经存在');
        }
        // 检查手机号重复
        if (User::where('mobile', $mobile)->first()) {
            return self::error(self::ERROR_MOBILE_REPREAT, '手机号码已经存在');
        }
        //检查邀请码
        if (!empty($invite_code)) {
            if (strlen($invite_code) != 6) {
                return self::error(self::STATUS_BAD_REQUEST, '无效的邀请码');
            }
            else {
                $res = UserInvitecode::where('invite_code', $invite_code)->first();
                if ($res) {
                    $invite_uid = $res->user_id;
                    unset($res);
                }
                else {
                    return self::error(self::STATUS_BAD_REQUEST, '邀请码不存在');
                }
            }
        }

        $balance = isset($invite_uid) ? 25 : 0;
        $insert_data = array(
            'mobile'     => $mobile,
            'nickname'   => $nickname,
            'password'   => $password,
            'role'       => User::NEWBEE,
            'balance'    => $balance
        );
        if ($location = Input::get('location')) {
            if (isset($location['lat']) && isset($location['lon'])) {
                $insert_data['lat'] = $location['lat'];
                $insert_data['lon'] = $location['lon'];
            }
        }
        if (isset($invite_uid) && !empty($invite_uid)) {
            $insert_data['invite_uid'] = $invite_uid;
        }
        $user = User::create($insert_data);
        if ($user && isset($invite_uid)) {
            Withdraw::balanceLog(Withdraw::CATE_REG, $user->id, $balance, '注册奖励');
        }

        $session = ClientSession::setSession($user->id);
        $this->currentUser = $user;
        $this->refreshClient($user->id);

        $resp = array(
            'sid'    => $session->session_id,
            'user'   => $user->formatToApi(),
            'config' => $this->getConfig(),
        );
        return $this->json($resp);
    }

    /**
     * 获取短信验证码
     *
     * /user/Verifycode
     * @return Response
     */
    public function postVerifycode() {
        $rule = array(
            'mobile_phone'  => 'required',
        );
        if ($error = $this->validateInput($rule)) {
            return $error;
        }
        // 检查手机号
        $mobile   = trim(Input::get('mobile_phone'));
        if (User::where('mobile', $mobile)->first()) {
            return self::error(self::ERROR_MOBILE_REPREAT, '手机号码已经存在');
        }
        // 获取短信码
        $verify_code = get_randStr(6, 'NUMBER');
        $sms_text = sprintf('欢迎加入O2OMobile，您的申请验证码为%s，我们将竭诚为您服务,5分钟有效。', $verify_code);
        $send_ok = sms_send($mobile, $sms_text);
        if (!$send_ok) {
            return self::error(self::STATUS_BAD_REQUEST, '短信发送失败!');
        }
        //把验证码存入cache
        Cache::put('verifycode_'.$mobile, array('verify_code' => $verify_code, 'time' => time()), 10);
        $resp = array(
            'verify_code' => $verify_code,
        );
        return $this->json($resp);
    }

    /**
     * 校验验证码
     *
     * /user/Verifycode
     * @return Response
     */
    public function postValidcode() {
        $rule = array(
            'mobile_phone'  => 'required',
            'verify_code' => 'required|min:6'
        );
        if ($error = $this->validateInput($rule)) {
            return $error;
        }
        $mobile = trim(Input::get('mobile_phone'));
        $verify_code = trim(Input::get('verify_code'));

        //从cache中获取验证码进行验证
        $sess_res = Cache::get('verifycode_'.$mobile);
        if ($sess_res) {
            //验证6位随机串
            if ($sess_res['verify_code'] != $verify_code) {
                return self::error(self::STATUS_BAD_REQUEST, '验证码输入错误!');
            }
            //验证时间有效有效性，5分钟,可配置
            if (time() > ($sess_res['time'] + 300)) {
                Cache::forget('verifycode_'.$mobile);
                return self::error(self::STATUS_BAD_REQUEST, '验证码已经过期!');
            }
            //验证后删除cache
            Cache::forget('verifycode_'.$mobile);
        }
        else {
            return self::error(self::STATUS_BAD_REQUEST, '验证码已经失效');
        }
        return $this->json(array());
    }

    /**
     * 获取邀请码
     *
     * @return json
     */
    public function postInviteCode()
    {
        $user = $this->currentUser;
        $userInvite = UserInvitecode::firstOrNew(array('user_id' => $user->id));
        if (!empty($userInvite->invite_code)) {
            $this_code = $userInvite->invite_code;
        }
        else {
            $invite_codes = $exist_code = array();
            //随机获取5个邀请码，取其中一个.
            for($i=0; $i < 5; $i++) {
                array_push($invite_codes, get_randStr(6));
            }
            if (count($invite_codes)) {
                $res = UserInvitecode::whereIn('invite_code', $invite_codes)->get();
                if ($res) {
                    foreach($res as $ic) {
                        array_push($exist_code, $ic->invite_codes);
                    }
                    foreach($invite_codes as $ic) {
                        if (!in_array($ic, $exist_code)) {
                            $this_code = $ic;
                            break;
                        }
                    }
                    unset($res, $exist_code);
                }
                else {
                    $this_code = array_pop($invite_codes);
                }
            }
            if (isset($this_code) && !empty($this_code)) {
                $userInvite->invite_code = $this_code;
                $userInvite->save();
            }
            else {
                return self::error(self::STATUS_BAD_REQUEST, '获取邀请码失败，请重新获取');
            }
        }
        return $this->json(array('invite_code' => $this_code));
    }

    /**
     * 申请认证自由人
     *
     * @return json
     */
    public function postCertify()
    {
        $rule = array(
            'name' => 'required',
            'bankcard' => 'required',
            'identity_card' => 'required',
            'gender' => 'in:0,1'
        );
        if ($error = $this->validateInput($rule)) {
            return $error;
        }

        $uid = $this->currentUser->id;
        $user = User::findOrFail($uid);
        if ($user->role == User::FREEMAN_INREVIEW || $user->role == User::FREEMAN) {
            return self::error(self::ERROR_CERTITY_REPREAT, '您已经申请过了');
        }

        //处理上传头像
        if (Input::hasFile('avatar')) {
            $fileStoragePath = public_path() . '/uploadFile/avatar';

            $dateFolder = date('/Y/md', time());  //日期作为目录
            $folder = $fileStoragePath . $dateFolder;
            if (!file_exists($folder) && !@mkdir($folder, 0777, true)) {
                return self::error(self::STATUS_BAD_REQUEST, 'Not Writeable Dir');
            }
            $file     = Input::file('avatar');
            $filename = $file->getClientOriginalName();
            $ext      = fileExt($filename);
            $localFile = md5($filename) . filesize($file) . $ext;
            $file->move($folder, $localFile);
            $avatar = $dateFolder .'/'. $localFile;
        }
        else {
            return self::error(self::STATUS_BAD_REQUEST, '请上传头像');
        }
        //update user
        $user->name = trim(Input::get('name'));
        $user->bankcard = trim(Input::get('bankcard'));
        $user->identity_card = trim(Input::get('identity_card'));
        $user->role = User::FREEMAN_INREVIEW;
        $user->gender = intval(Input::get('gender'));
        $user->avatar = $avatar;
        $user->save();

        return $this->json(array());
    }

    /**
     * 修改密码
     *
     * @return json
     */
    public function postChangePassword()
    {
        $rule = array(
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        );
        if ($error = $this->validateInput($rule)) {
            return $error;
        }

        $user = User::findOrFail(Input::get('uid'));
        //验证旧密码
        $credentials = array(
            'mobile'    => $user->mobile,
            'password' => Input::get('old_password'),
        );
        if (!Auth::attempt($credentials)) {
            return self::error(self::ERROR_PASSWD_ERROR, '当前密码错误');
        }

        $user->password = hash_password(Input::get('new_password'));
        $user->save();

        return $this->json(array('user' => $user->formatToApi()));
    }

    /**
     * 修改用户资料
     *
     * @return json
     */
    public function postChangeProfile()
    {
        $nickname = trim(Input::get('nickname', ''));
        $signature = trim(Input::get('signature', ''));
        $brief = trim(Input::get('brief', ''));
        if (empty($nickname) && empty($signature) && empty($brief)) {
            return self::error(self::STATUS_BAD_REQUEST, '请求参数错误');
        }

        $user = $this->currentUser;
        //
        if (!empty($nickname)) {
            $res = User::where('nickname', $nickname)->first();
            if ($res) {
                return self::error(self::ERROR_NICKNAME_REPREAT, '昵称已经存在');
            }
            $user->nickname = $nickname;
        }
        if (!empty($signature)) {
            $user->signature = $signature;
        }
        if (!empty($brief)) {
            $user->brief = $brief;
        }
        //save to database
        $user->save();
        return $this->json(array('user' => $user->formatToApi()));
    }

    /**
     * 修改用户头像
     *
     * @return json
     */
    public function postChangeAvatar()
    {
        $user = $this->currentUser;
        $fileStoragePath = public_path() . '/uploadFile/avatar';
        //处理上传头像
        if (Input::hasFile('avatar')) {
            $dateFolder = date('/Y/md', time());  //日期作为目录
            $folder = $fileStoragePath . $dateFolder;
            if (!file_exists($folder) && !@mkdir($folder, 0777, true)) {
                return self::error(self::STATUS_BAD_REQUEST, 'Not Writeable Dir');
            }
            $file     = Input::file('avatar');
            $filename = $file->getClientOriginalName();
            $ext      = fileExt($filename);
            $localFile = md5($filename) . filesize($file) . $ext;
            $file->move($folder, $localFile);
            $user->avatar = $dateFolder .'/'. $localFile;
        }
        else {
            return self::error(self::STATUS_BAD_REQUEST, '请求参数错误');
        }

        //save to database
        $user->save();
        return $this->json(array('user' => $user->formatToApi()));
    }

    protected function getConfig()
    {
        return array('push' => 1);
    }

    /**
     * 刷新用户client
     *
     * @param integer $userId
     * @return Client
     */
    protected function refreshClient($userId)
    {
        $client = Client::firstOrNew(array('user_id' => $userId));
        $client->uuid = Input::get('UUID', '');
        $client->version = Input::get('ver');
        $client->client_type = Input::get('platform', 'android');
        $client->token = Input::get('token', '');
        $client->save();

        return $client;
    }

    /**
     * 用户余额
     *
     *
     * @return Response
     */
    public function postBalance()
    {
        $user = User::find(Input::get('uid'));

        return $this->json(array('balance' => $user->balance));
    }


    /**
     * 认证更多服务
     *
     *
     * @return Response
     */
    public function postApplyService()
    {
        $user_id                         = Input::get('uid');
        $service_type_id                 = Input::get('service_type_id');
        $firstclass_service_category_id  = Input::get('firstclass_service_category_id');
        $secondclass_service_category_id = Input::get('secondclass_service_category_id');

        $check = DB::table('apply_service')->where('user_id', $user_id)
                        ->where('service_type_id', $service_type_id)
                        ->where('firstclass_service_category_id', $firstclass_service_category_id)
                        ->where('secondclass_service_category_id', $secondclass_service_category_id)
                        ->where('state', '!=', '2')
                        ->get();

        if(!empty($check)){
            return self::error(self::STATUS_BAD_REQUEST, '申请重复');
        }

        $applyservice                                    = new Applyservice;
        $applyservice->user_id                           = $user_id;
        $applyservice->service_type_id                   = $service_type_id;
        $applyservice->firstclass_service_category_id    = $firstclass_service_category_id;
        $applyservice->secondclass_service_category_id   = $secondclass_service_category_id;
        $applyservice->save();
        return $this->json(array());
    }


    /**
     * 退出登录 清除session和token
     *
     *
     * @return Response
     */
    public function postSignout()
    {

        $user_id                         = Input::get('uid');
        $this->refreshClient($user_id);
        ClientSession::setSession($user_id);
        return $this->json(array());
    }




}