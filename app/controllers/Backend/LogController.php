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


use \View;
use \Input;
use \Redirect;
use \Validator;
use \AdminLog;
class LogController extends BaseController {

    /**
     * 列表
     *
     * @return Object
     */
    public function getAll()
    {

        // $logs = AdminLog::orderBy('id', 'DESC')->where(function($query){
        $logs = AdminLog::with('user')->where(function($query){
            //行为
            if ($action = Input::get('action')) {
                $query->where('action', 'like', "%{$action}%");
            }

            //用户
            if ($userId = Input::get('user_id')) {
                $query->where('user_id', $userId);
            }

            //时间
            $regTimeRange = Input::get('time_range');
            if (!empty($regTimeRange)) {
                $times = explode(' - ', $regTimeRange);
                $query->whereBetween('created_at', $times);
            }

        })->orderBy('created_at', 'DESC')->paginate(20);

        return View::make('Backend.pages.log-all')->withLogs($logs);
    }

    /**
     * 删除
     *
     * @param integer|array $id 
     *
     * @return Response
     */
    public function anyDelete($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        AdminLog::whereIn('id', $id)->delete();

        return Redirect::back()->withMessage('删除成功！');
    
    }

}