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
use \Input;
use \Service;
use \Certify;
use \MyService;
use \Redirect;
use \Request;
use \Response;
use \Validator;
use \AdminLog;
class MyserviceController extends BaseController {
    

    // 添加我的服务
    public function postAdd() {
        $user_id    = Input::get('user_id', '');
        $services_id = Input::get('services_id', '');
        $my_services = DB::table('my_services')->where('user_id', '=', $user_id)->where('services_id', '=', $services_id)->get();
        if(!empty($my_services)){
            return Redirect::back()->withMessage('重复添加')->withColor('danger');
        }
        $myservice = new MyService;
        $myservice->user_id           = Input::get('user_id', '');
        $myservice->services_id       = Input::get('services_id', '');
        $myservice->save();
        AdminLog::log($myservice->id, "添加“".User::userinfo($myservice->user_id)."”的认证");
        return Redirect::back()->withMessage('创建成功！');
    }

    // 删除我的服务
    public function getDel($id) {
        $myservice = MyService::find($id);
        DB::table('my_services')->where('id', $id)->delete();
        AdminLog::log($id, "删除“".User::userinfo($myservice->user_id)."”的服务");
        return Redirect::back()->withMessage('取消成功！');
    }

}