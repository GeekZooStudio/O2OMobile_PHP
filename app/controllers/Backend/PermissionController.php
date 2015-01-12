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
use \PermissionGroup;
use \Validator;
use \Config;
use \View;
use \Input;
use \Redirect;
use \AdminLog;
class PermissionController extends BaseController {
    /**
     * 管理员列表
     *
     * @return Response
     */
    public function getUser() {
        $users = User::with('permissionGroup')->orderBy('created_at', 'ASC')->where(function($query){
            //不检索后台管理员账号
            $query->where('role', User::ROLE_ADMIN);
            $group = intval(Input::get('group'));
            if (!empty($group)) {
                $group_id = ($group == 100) ? 0 : $group;
                $query->where('group_id', $group_id);
            }
            //关键字,昵称和手机号
            $keyword = Input::get('keyword');
            if (!empty($keyword)) {
                $query->where('username', 'like', "%{$keyword}%");
            }
        })->paginate(15);
        //
        $groups = PermissionGroup::orderBy('id', 'DESC')->get();

        return View::make('Backend.pages.permission-user')
                ->withUsers($users)
                ->withGroups($groups);
    }

    /**
     * 新建管理员
     *
     * @return Response
     */
    public function getUserNew()
    {
        $groups = PermissionGroup::orderBy('id', 'DESC')->get();
        return View::make('Backend.pages.permission-user-new')->with('groups', $groups);
    }

    /**
     * 创建管理员
     *
     * @return Response
     */
    public function postUserCreate()
    {
        $id = intval(Input::get('id'));
        $rules = Config::get('rules.permission-user-create');
        $password = Input::get('password');
        if ($id && empty($password)) {
            unset($rules['password'], $rules['rePassword']);
        }
        //验证
        $validator = Validator::make(Input::all(), $rules);
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        if(!$id)  {
            $user = new User;
            $user->role = User::ROLE_ADMIN;
            $user->password = hash_password(Input::get('password'));
            $msg = '创建成功';
        }
        else {
            $user = User::findOrFail($id);
            $password = Input::get('password');
            if (!empty($password)) {
                $user->password = hash_password($password);
            }
            $msg = '编辑成功';
        }
        $user->username = trim(Input::get('username'));
        $user->group_id = intval(Input::get('group_id'));
        $user->save();

        return Redirect::back()->withMessage($msg);
    }

    /**
     * 编辑管理员
     *
     * @return Response
     */
    public function getUserEdit($id)
    {
        $user = User::findOrFail($id);
        $groups = PermissionGroup::orderBy('id', 'DESC')->get();

        return View::make('Backend.pages.permission-user-edit')
                ->with('groups', $groups)
                ->with('user', $user);
    }

    /**
     * 删除管理员
     *
     * @return Response
     */
    public function getUserDelete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
        }    
        return Redirect::back()->withMessage('删除成功');
    }

    /**
     * 权限组列表
     *
     * @return Response
     */
    public function getGroup()
    {
        $sort = input::get('sort');
        if (!empty($sort)) {
            $sort = str_replace('-', ' ', $sort);
        }
        else {
            $sort = 'id ASC';
        }
        $groups = PermissionGroup::orderByRaw($sort)->where(function($query){
            //关键字,昵称和手机号
            $keyword = Input::get('keyword');
            if (!empty($keyword)) {
                $query->where('name', 'like', "%{$keyword}%");
            }
        })->paginate(15);
        //
        return View::make('Backend.pages.permission-group')->withGroups($groups);
    }

    /**
     * 新建权限组
     *
     * @return Response
     */
    public function getGroupNew()
    {
        return View::make('Backend.pages.permission-group-new');
    }

    /**
     * 创建权限组
     *
     * @return Response
     */
    public function postGroupCreate()
    {
        $validator = Validator::make(Input::all(), Config::get('rules.permission-group-create'));
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
      
        $name = trim(Input::get('name'));
        $permissions =   Input::get('permission');
        if (!is_array($permissions)) {
            $permissions = array();
        }
        else {
            $permissions = array_unique($permissions);
        }
        $id = intval(Input::get('id'));
        if(!$id)  {
            $permissionGroup = new PermissionGroup;
            $msg = '创建成功';
        }
        else {
            $permissionGroup = User::findOrFail($id);
            $msg = '编辑成功';
        }
        $permissionGroup->name = $name;
        $permissionGroup->permissions = serialize($permissions);
        $permissionGroup->save();

        return Redirect::back()->withMessage($msg);
    }

    /**
     * 编辑权限组
     *
     * @return Response
     */
    public function getGroupEdit($id)
    {
        $permissionGroup = PermissionGroup::findOrFail($id);
        $permissionGroup->permissions = unserialize($permissionGroup->permissions);

        return View::make('Backend.pages.permission-group-edit')->with('permissionGroup', $permissionGroup);
    }

    /**
     * 删除权限组
     *
     * @return Response
     */
    public function getGroupDelete($id)
    {
        $permissionGroup = PermissionGroup::find($id);
        if ($permissionGroup) {
            $res = $permissionGroup->delete();
            if ($res) {
                //删除成功，清除管理员所属组
                DB::table('users')->where('group_id', $id)->where('role', User::ROLE_ADMIN)
                    ->update(array('group_id' => 0));
            }
        }
        return Redirect::back()->withMessage('删除成功');
    }

}