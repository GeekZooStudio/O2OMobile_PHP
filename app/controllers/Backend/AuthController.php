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

use \Auth;
use \View;
use \User;
use \Input;
use \Response;
use \Redirect;

class AuthController extends BaseController {
    /**
     * 登录
     *
     * @return Response
     */
    public function getLogin() {
        if (Auth::check()) {
            if (Auth::user()->role != User::ROLE_ADMIN) {
                Auth::logout();
                return Redirect::to('/admin/auth/login');
            }
            else {
                return Redirect::to('admin');
            }
        }

        return View::make('Backend.pages.login');
    }

    /**
     * 执行登录操作
     *
     * @return Response
     */
    public function postLogin()
    {
        $user = array(
            'username' => Input::get('username'),
            'password'   => Input::get('password'),
        );
        /*if (filter_var($username = Input::get('username'), FILTER_VALIDATE_EMAIL)) {
            $user['email'] = Input::get('username');
            unset($user['username']);
        }*/
        if (Auth::attempt($user, Input::get('remember'))) {
            return Response::json(
                array(
                    'login_status' => 'success',
                    'redirect_url' => url('admin'),
                )
            );
        }
        //返回登陆错误
        return Response::json(
            array(
                'login_status' => 'invalid',
            )
        );
    }

    /**
     * 注销登录 
     *
     * @return Response
     */
    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('/admin/auth/login')
            ->with('message', '你已经成功注销当前登录.');
    }
}