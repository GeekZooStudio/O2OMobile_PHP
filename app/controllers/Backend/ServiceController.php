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
use \Service;
use \Validator;
use \Redirect;
use \AdminLog;
class ServiceController extends BaseController {
    /**
     * 服务列表
     *
     * @return Response
     */
    public function getAll() {
        $services = Service::where('parent_id', 0)->orderBy('id','ASC')->paginate(20);
        return View::make('Backend.pages.service-all')
                ->with('services', $services);
    }

    /**
     * 创建服务
     *
     * @return Response
     */
    public function getNew() {

        $services = DB::table('services')->get();
        return View::make('Backend.pages.service-new')->withServices($services);
    }

    /**
     * 创建
     *
     * @return Response
     */
    public function postCreate()
    {

        $parent_id = Input::get('parent_id', 0);
        if($parent_id){
            $validator = Validator::make(Input::all(), Config::get('rules.serviceChild'));
        }else{
            $validator = Validator::make(Input::all(), Config::get('rules.service'));
        }
        
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $service = new Service;
        $service->parent_id   = $parent_id;
        $service->name        = Input::get('name');
        $service->desc        = Input::get('desc', '');
        $service->imgurl      = Input::get('imgurl');
        $service->usort       = intval(Input::get('usort', 0));
        $service->save();
        AdminLog::log($service->id, "创建服务");
        return Redirect::back()->withMessage('创建成功！');
    }


    /**
     * 查看子类
     *
     * @return Response
     */
    public function getLookchild()
    {

        $id       =  Input::get('id');
        $services =  DB::table('services')->where('parent_id', $id)->paginate(20);
        $ser      =  Service::find($id);

        // $pser     =  Service::find($ser->parent_id);
        if($ser->parent_id){
            $isparent = 1;
        }else{
            $isparent = 0;
        }
        return View::make('Backend.pages.service-look-child')->withServices($services)->withSer($ser)->withIsparent($isparent);
    }


    /**
     * 添加子类
     *
     * @return Response
     */
    public function getAddchild()
    {

        $id       =  Input::get('id');
        $services =  DB::table('services')->where('parent_id', $id)->get();
        $ser      =  Service::find($id);
        return View::make('Backend.pages.service-add-child')->withServices($services)->withId($id)->withSer($ser);
    }

    
    /**
     * 编辑服务
     *
     * @return Response
     */
    public function getEdit($id)
    {
        $service = Service::findOrFail($id);
        return View::make('Backend.pages.service-edit')->withService($service);
    }


    /**
     * 编辑服务
     *
     * @return Response
     */
    public function postUpdate()
    {


        $validator = Validator::make(Input::all(), Config::get('rules.service'));
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = Input::get('id');
        $service = Service::findOrFail($id);
        $service->parent_id   = Input::get('parent_id', 0);
        $service->name        = Input::get('name');
        $service->desc        = Input::get('desc', '');
        $service->imgurl      = Input::get('imgurl');
        $service->usort       = Input::get('usort', 0);
        $service->save();
        AdminLog::log($service->id, "编辑服务");
        return Redirect::back()->withMessage('更新成功！');
    }



    /**
     * 删除服务
     *
     * @param  integer $id
     * @return Response
     */
    public function anyDelete($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }
        $services =  DB::table('services')->where('parent_id', $id)->get();
        if(!empty($services)){
            return Redirect::back()->withMessage('请先清空下级的所有服务')->withColor('danger');
        }
        Service::whereIn('id', $id)->delete();
        AdminLog::log($id, "删除服务");
        return Redirect::back()->withMessage('删除成功！');
    }   
}