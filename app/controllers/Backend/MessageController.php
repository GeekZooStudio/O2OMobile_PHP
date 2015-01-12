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
use \User;
use \Message;
use \Redirect;
use \Request;
use \Response;
use \Validator;
use \AdminLog;
use \Config;

class MessageController extends BaseController {
    /**
     * 消息列表
     *
     * @return Response
     */
    public function getIndex() {
        $sort = Input::get('sort');
        if (!empty($sort)) {
            list($sortField, $by) = explode('-', $sort);
        } else {
            $sortField = 'id';
            $by = 'DESC';
        }
        $messages = Message::orderBy($sortField, $by)->where(function($query){
            $query->where('type', '!=', 2);
            //关键字搜索
            $keyword = Input::get('keyword');
            if (!empty($keyword)) {
                $query->where('content', 'like', "%{$keyword}%");
            }
        })->paginate(15);

        return View::make('Backend.pages.message-index')->withMessages($messages);
    }

    /**
     * 创建
     *
     * @return Response
     */
    public function getNew()
    {
        return View::make('Backend.pages.message-new');
    }

    /**
     * 创建
     *
     * @return Response
     */
    public function postCreate()
    {
        //验证
        $validator = Validator::make(Input::all(), Config::get('rules.message-create'));
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $message = new Message;
        $targetUser = array();
        foreach(explode(',', Input::get('target_user', '')) as $item) {
            if(!empty($item)) $targetUser[] = trim($item); 
        }     
        $url = Input::get('url', '');
        $message->content     = strip_tags(Input::get('message'));
        $message->type        = intval(Input::get('action'));
        $message->url        = $url;
        if (count($targetUser)) {
            $message->user_id = $targetUser[0];
        }
        $message->save();


        return Redirect::back()->withMessage('创建成功！' . link_to(url('admin/message/all'), '查看消息列表'));
    }

    /**
     * 删除
     *
     * @param array|integer $id 
     *
     * @return Reponse
     */
    public function anyDelete($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        Message::whereIn('id', $id)->delete();
        //AdminLog::log($id, '删除消息');
        return Redirect::back()->withMessage('删除成功！');
    }

    /**
     * 删除
     *
     * @param array|integer $id 
     *
     * @return Reponse
     */
    public function anyDoMore()
    {
        $ids = Input::get('id');
        if (is_array($ids) && count($ids)) {
            Message::whereIn('id', $ids)->delete();
        }

        return Redirect::back()->withMessage('批量删除成功');
    }
}