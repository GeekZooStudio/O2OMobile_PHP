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

namespace Backend;

use \DB;
use \User;
use \View;
use \Bigc;
use \Input;
use \Redirect;
use \Request;
use \Certify;
use \Message;
use \Mycertify;
use \Service;
use \MyService;
use \Applyservice;
use \Response;
use \Validator;
use \AdminLog;
class UserController extends BaseController {

    public function getAll() {
        $users = User::orderBy('id', 'DESC')->where(function($query){
            //不检索后台管理员账号
            $query->where('role', '<', User::ROLE_ADMIN);
            //状态
            if (!is_null(Input::get('status'))) {
                $query->whereStatus(Input::get('status'));
            }
            //角色
            $role = Input::get('role',0);
            if ($role) {
                $query->whereRole($role);
            }
            //时间
            $regTimeRange = Input::get('time_range',0);
            if ($regTimeRange) {
                $times = explode(' - ', $regTimeRange);
                $query->whereBetween('created_at', $times);
            }
            //关键字,昵称和手机号
            $keyword = Input::get('keyword',0);
            if ($keyword) {
                $query->whereRaw("(CONCAT(`nickname`,`mobile`) LIKE '%{$keyword}%')");
            }

        })->paginate(15);
        //
        return View::make('Backend.pages.user-all')->withUsers($users);
    }

    public function getInfo($id)
    {
        $user = User::find($id);
        if (!$user) {
            exit('no user!');
        }

        $sql = "SELECT `years`, `month`, `times`, count(*) AS count
                 FROM `o2omobile_bigc`
                 WHERE `user_id` = $id
                 GROUP BY `times` ORDER BY `times` DESC";
        $bigc = DB::select($sql);
        $data = array();
        foreach ($bigc as $key => $value) {
            $data[$value->years][$value->month] = $value->count;
        }
        return View::make('Backend.pages.user-info')->withUser($user)->withData($data);
    }


    //添加认证
    public function getCertify($id) {

        $users       = User::find($id);
        $certifys    = DB::table('certify')->where('is_valid', '=', self::IS_VALID)->get();
        $my_certifys = Mycertify::orderBy('id', 'ASC')->where('user_id', $id)->paginate(15);
        return View::make('Backend.pages.mycertify-new')
            ->withUsers($users)->withCertifys($certifys)->withMy_certifys($my_certifys);
    }

    //添加服务
    public function getServices($id) {

        $users    = User::find($id);
        $services = DB::table('services')->where('parent_id', '0')->get();
        $my_services = MyService::orderBy('id', 'ASC')->where('user_id', $id)->paginate(15);
        return View::make('Backend.pages.myservice-new')
            ->withUsers($users)->withServices($services)->withMy_services($my_services);
    }

    //申请自由人
    public function getFreeman() {
        $users = User::orderBy('id', 'DESC')->where(function($query){
            $query->where('role', User::FREEMAN_INREVIEW);
            //时间
            $regTimeRange = Input::get('time_range');
            if (!empty($regTimeRange)) {
                $times = explode(' - ', $regTimeRange);
                $query->whereBetween('created_at', $times);
            }
            //关键字,昵称和手机号
            $keyword = Input::get('keyword',0);
            if ($keyword) {
                $query->whereRaw("(CONCAT(`name`,`nickname`,`mobile`) LIKE '%{$keyword}%')");
            }
        })->paginate(15);
        //
        return View::make('Backend.pages.freeman-new')
            ->withUsers($users);
    }


    //申请自由人通过
    public function getFreemanok($id) {
        $res = DB::table('users')
            ->where('id', $id)
            ->update(array('role' => User::FREEMAN));
        //成功推送消息
        if ($res) {
            $message = array(
                'user_id' => $id,
                'type' => Message::OTHER,
                'content' => '您申请自由人通过'
            );
            Message::createMessage($message, true);
        }
        AdminLog::log($id, "为“".User::userinfo($id)."”进行申请自由人成功的操作");
        return Redirect::back()->withMessage('操作成功！');
    }

    //申请自由人不通过
    public function getFreemanno($id) {
        $res = DB::table('users')
            ->where('id', $id)
            ->update(array('role' => User::NEWBEE));
        //成功推送消息
        if ($res) {
            $message = array(
                'user_id' => $id,
                'type' => Message::OTHER,
                'content' => '您申请自由人未通过'
            );
            Message::createMessage($message, true);
        }
        AdminLog::log($id, "为“".User::userinfo($id)."”进行申请自由人失败的操作");
        return Redirect::back()->withMessage('操作成功！');
    }



    //申请服务
    public function getApplyservice() {

        $applyservices = Applyservice::orderBy('id', 'DESC')->where(function($query){
            //状态
            $state = Input::get('state');
            if (!empty($state)) {
                $state == 'un' ? 0 : $state;
                $query->where('state', $state);
            }

            //时间
            $regTimeRange = Input::get('time_range');
            if (!empty($regTimeRange)) {
                $times = explode(' - ', $regTimeRange);
                $query->whereBetween('created_at', $times);
            }

            //关键字
            $keyword = Input::get('keyword');
            if (!empty($keyword)) {
                $users = DB::table('users')->where('nickname', 'like', "%$keyword%")->lists('id');
                if(!empty($users)){
                    $query->whereIn('user_id', $users);
                }

            }
        })->paginate(15);

        return View::make('Backend.pages.user-apply-service')
            ->withApplyservices($applyservices);
    }



    /**
     * 认证服务处理
     *
     * @return Response
     */
    public function getApplyresult() {

        $id                 = Input::get('id');
        $state              = Input::get('state');
        $note               = Input::get('note', '');

        $applyservices          = Applyservice::findOrFail($id);
        $applyservices->state   = $state;
        $applyservices->note    = $note;
        $applyservices->save();
        $services = Service::find($applyservices->service_type_id);
        // print_r($services);exit;
        if( $state == Applyservice::APPLY_FAILED ){
            $note   = '申请《'.$services->name.'》服务失败，原因“'.$note.'”';
            AdminLog::log($id, "为".User::userinfo($applyservices->user_id)."进行申请服务失败的操作");
        }elseif( $state == Applyservice::APPLY_SUCC ){
            $note = '申请《'.$services->name.'》服务成功';
            $user_id     = $applyservices->user_id;
            $services_id = $applyservices->service_type_id;

            $check = DB::table('my_services')->where('user_id', $user_id)
                        ->where('services_id', $services_id)
                        ->get();
            if(empty($check)){
                $myservice = new MyService;
                $myservice->user_id           = $user_id;
                $myservice->services_id       = $services_id;
                $myservice->save();
            }
            AdminLog::log($id, "为".User::userinfo($applyservices->user_id)."进行申请服务成功的操作");
        }

        $message = array(
            'user_id' => $applyservices->user_id,
            'type'    => Message::OTHER,
            'content' => $note,
        );

        Message::createMessage($message, true);

        return Redirect::back()->withMessage('操作成功！');
    }

    /**
     * 禁用或者启用用户
     *
     * @param integer $id
     * @param integer $status 1,0
     *
     * @return Response
     */
    public function getUpdateStatus($id, $status)
    {
        $user = User::findOrFail($id);
        if ($status == User::STATUS_OK) {
            $user->status = User::STATUS_OK;
        } else {
            $user->status = User::STATUS_DISABLED;
        }
        $user->save();

        return Redirect::back()->withMessage('更新成功！');
    }

    /**
     * 删除用户
     *
     * @param  integer $id
     *
     * @return Response
     */
    public function getDelete($id)
    {
        User::findOrFail($id)->delete();

        return Redirect::back()->withMessage('删除成功！');
    }



    /**
     * 大C统计
     *
     * @param  integer $id
     *
     * @return Response
     */
    public function getBigc() {
        $users = User::orderBy('id', 'DESC')->where(function($query){
            //不检索后台管理员账号
            $query->where('role', '<', User::ROLE_ADMIN);
            //状态
            if (!is_null(Input::get('status'))) {
                $query->whereStatus(Input::get('status'));
            }
            //角色
            $role = Input::get('role',0);
            if ($role) {
                $query->whereRole($role);
            }
            //时间
            $regTimeRange = Input::get('time_range',0);
            if ($regTimeRange) {
                $times = explode(' - ', $regTimeRange);
                $query->whereBetween('created_at', $times);
            }
            //关键字,昵称和手机号
            $keyword = Input::get('keyword',0);
            if ($keyword) {
                $query->whereRaw("(CONCAT(`nickname`,`mobile`) LIKE '%{$keyword}%')");
            }

        })->paginate(15);
        //
        return View::make('Backend.pages.user-bigc')->withUsers($users);
    }

}