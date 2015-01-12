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
use \Feedback;
use \Request;
use \Response;
use \Redirect;
use \Validator;
use \AdminLog;
class FeedbackController extends BaseController {

    /**
     * 意见反馈
     *
     * @return Response
     */
    public function getIndex() {
        $feedbacks = Feedback::orderBy('id', 'DESC')->where(function($query){

            //时间
            $regTimeRange = Input::get('time_range');
            if (!empty($regTimeRange)) {
                $times = explode(' - ', $regTimeRange);
                $query->whereBetween('created_at', $times);
            }

            //
            $text = Input::get('text');
            if (!empty($text)) {
                $query->where("text", "LIKE", "%$text%");
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
        // print_r($users);exit;
        //关键字搜索用户时用
        if (Request::wantsJson()) {
            return $feedbacks->getCollection()->toArray();
        }

        return View::make('Backend.pages.feedback')->withFeedbacks($feedbacks);
    }


}