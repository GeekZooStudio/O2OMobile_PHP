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
use \View;
use \Input;
use \Config;
use \Certify;
use \Mycertify;
use \Validator;
use \Redirect;
use \AdminLog;

class CertifyController extends BaseController {
    
    /**
     * 认证列表
     *
     * @return Response
     */
    public function getAll() {
        $certifys = Certify::orderBy('id','DESC')->paginate(10);
        return View::make('Backend.pages.certify-all')
                ->with('certifys', $certifys);
    }



    /**
     * 创建服务
     *
     * @return Response
     */
    public function getNew() {

        $services = DB::table('services')->get();
        return View::make('Backend.pages.certify-new')->withServices($services);
    }


    /**
     * 创建
     *
     * @return Response
     */
    public function postCreate()
    {

        $validator = Validator::make(Input::all(), Config::get('rules.certify'));
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $certify = new Certify;
        $certify->name        = Input::get('name');
        $certify->save();
        AdminLog::log($certify->id, "创建认证");
        return Redirect::back()->withMessage('创建成功！');
    }



    /**
     * 编辑认证
     *
     * @return Response
     */
    public function getEdit($id)
    {
        $certify = Certify::findOrFail($id);
        AdminLog::log($id, "编辑服务");
        return View::make('Backend.pages.certify-edit')->withCertify($certify);
    }



    /**
     * 创建
     *
     * @return Response
     */
    public function postUpdate()
    {


        $validator = Validator::make(Input::all(), Config::get('rules.certify'));
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = Input::get('id');
    	$name = Input::get('name');

        $certify = Certify::findOrFail($id);
        $certify->name        = Input::get('name');
        $certify->save();
        AdminLog::log($certify->id, "编辑认证");
        return Redirect::back()->withMessage('更新成功！');
    }


    /**
     * 删除认证
     *
     * @param  integer $id
     * @return Response
     */
    public function anyDelete($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }
        Certify::whereIn('id', $id)->delete();
        Mycertify::where("certify_id", $id)->delete();
        AdminLog::log($id, "删除认证");
        return Redirect::back()->withMessage('删除成功！');
    }


}