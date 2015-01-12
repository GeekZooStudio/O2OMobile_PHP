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
use \PermissionGroup;
use \View;
use \User;
use \Request;
use \Config;
use \Response;
use \Redirect;

class BaseController extends \Controller {

	const IS_UNVALID        = 0; //无效
    const IS_VALID          = 1; //有效

    //用户权限
    protected $userPermission;

    public function __construct() {
        if (!Request::is('*auth/log*')) {
            if (Auth::check()) {
                if (Auth::user()->role != User::ROLE_ADMIN) {
                    header('Location:'.url('/admin/auth/login'));
                    exit;
                }
            }

            !is_null($this->userPermission) || $this->userPermission = $this->getPermissionGroup(Auth::user()->group_id);
            $this->beforeFilter('@auth');
            //获取后台左侧功能菜单
            View::share('menus', $this->getMenu());
        }
    }

    /**
     * 获取用户权限组
     *
     * @return array
     */
    protected function getPermissionGroup($group_id)
    {
        $permissions = array();
        //find
        $permissionGroup = PermissionGroup::find($group_id);
        if ($permissionGroup) {
            if ($permissionGroup->permissions != '*') {
                $permissions = unserialize($permissionGroup->permissions);
            }
            else {
                $permissions = '*';
            }
        }
        return $permissions;
    }

    /**
     * 权限检查
     *
     * @return Response
     */
    public function auth()
    {
        //如果是管理员直接返回
        if ($this->userPermission == '*') {
            return;
        }
        //非管理员,做权限判断
        $permission_arr = array();
        foreach ($this->userPermission as $uvp) {
            $permission_arr = array_merge($permission_arr, explode('|', $uvp));
        }
        $url_path = str_replace('admin/', '', Request::path());
        $url_arr = explode('/', $url_path);
        if (count($url_arr) < 2) {
            //首页不做权限判断
            return;
        }
        if (in_array($url_arr[0].'.*', $permission_arr) || in_array(implode('.', $url_arr), $permission_arr)) {
            return;
        }
        //App::abort(404);
        exit('permission deny');
    }

    /**
     * 获取菜单
     *
     * @return array
     */
    protected function getMenu()
    {
        $menu = Config::get('menu');
        if ($this->userPermission == '*') {
            return $menu;
        }
        //非管理员权限，需要过滤菜单项
        foreach ($menu as $key => $val) {
            if (isset($val['permission'])) {
                if ($val['permission'] == '*') {
                    continue;
                }
                else {
                    if(!in_array($val['permission'], $this->userPermission)) {
                        unset($menu[$key]);
                    }
                }
            }
            elseif (count($val['submenu'])) {
                foreach ($val['submenu'] as $sKey => $sVal) {
                    if(!in_array($sVal['permission'], $this->userPermission)) {
                        unset($menu[$key]['submenu'][$sKey]);
                    }
                }
                //如果子功能没有了，则删除父菜单
                if (!count($menu[$key]['submenu'])) {
                    unset($menu[$key]);
                }
            }
        }
        return $menu;
    }
}