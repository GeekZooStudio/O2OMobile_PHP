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

namespace Rest;

use \Auth;
use \Input;
use \Message;
use \Config;
use \Carbon\Carbon;

class MessageController extends BaseController {
    /**
     * 获取消息未读数 
     *
     * @return Response
     */
    public function postUnreadCount()
    {
        $user_id = $this->currentUser->id;
        $count = Message::where('user_id', $user_id)
                ->where('type', '>', 1)
                ->where('is_readed', 0)
                ->count();
        return $this->json(array('unread' => $count));
    }

    /**
     * 标记消息已读 
     *
     * @return Response
     */
    public function postRead()
    {
        $user_id = $this->currentUser->id;
        $message_id = Input::get('message', 0);
        $message = Message::find($message_id);
        if(!$message){
            return self::error(self::STATUS_BAD_REQUEST, '没有该消息');
        }
        $message->is_readed = 1;
        $message->save();
        return $this->json(array());
    }

    /**
     * 获取系统消息列表,不包括系统消息 
     *
     * @return Response
     */
    public function postList()
    {
        $o_user = Input::get('user', 0);
        $by_id = Input::get('by_id', 0);
        $by_no = Input::get('by_no', 1);
        $count = abs(Input::get('count', 10));

        $messages = Message::where('type', '>', 1)
                    ->where('user_id', $this->currentUser->id);
        $total = $messages->count();
        $messages = $messages->orderBy('created_at', 'DESC');
        
        $hasMore = 0;
        //按ID拉取
        if (Input::has('by_id') && Input::get('by_id') > 0) {
            $lastId = Input::get('by_id');
            $messages = $messages->where('id', '>=', $lastId)->take($count + 1)->get();
        
        //按页码
        } else {
            $by_no > 1 || $by_no = 1;
            $offset = $count * ($by_no - 1);
            $messages = $messages->skip($offset)->take($count + 1)->get();
        }
        if (count($messages) > $count) {
            $hasMore = 1;
            $messages->pop();//弹出最后一条
        }   

        $message_arr = array();
        foreach ($messages as $message) {
            $message_arr[] = $message->formatToApi();
        }
        return $this->json(array(
            'total' => $total, 
            'count' => count($message_arr),
            'more' => $hasMore, 
            'messages' => $message_arr
        ));
    }

    /**
     * 获取系统消息列表 
     *
     * @return Response
     */
    public function postSyslist()
    {
        $o_user = Input::get('user', 0);
        $by_id = Input::get('by_id', 0);
        $by_no = Input::get('by_no', 1);
        $count = abs(Input::get('count', 10));

        $messages = Message::where('type', 1)->where('created_at', '>', $this->currentUser->created_at);
        $total = $messages->count();
        $messages = $messages->orderBy('created_at', 'DESC');
        
        $hasMore = 0;
        //按ID拉取
        if (Input::has('by_id') && Input::get('by_id') > 0) {
            $lastId = Input::get('by_id');
            $messages = $messages->where('id', '>=', $lastId)->take($count + 1)->get();
        
        //按页码
        } else {
            $by_no > 1 || $by_no = 1;
            $offset = $count * ($by_no - 1);
            $messages = $messages->skip($offset)->take($count + 1)->get();
        }
        if (count($messages) > $count) {
            $hasMore = 1;
            $messages->pop();//弹出最后一条
        }   

        $message_arr = array();
        foreach ($messages as $message) {
            $message_arr[] = $message->formatToApi();
        }
        return $this->json(array(
            'total' => $total, 
            'count' => count($message_arr),
            'more' => $hasMore, 
            'messages' => $message_arr
        ));
    }
}