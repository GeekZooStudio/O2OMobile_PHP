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
use \DB;
use \User;
use \Auth;
use \Input;
use \Comment;
use \Message;
use \Orders;
use \Carbon\Carbon;

class CommentController extends BaseController {
    /**
     * 发评论 
     *
     * @return Response
     */
    public function postSend()
    {
 
        $rule = array(
            'order_id' => 'required|integer',
            'rank' => 'integer|between:1,5',
            'content' => 'required',
        );
        if ($error = $this->validateInput($rule)) {
            return $error;
        }
        $s_user = $this->currentUser->id;
        $rank = Input::get('rank', 0);
        $order_id = Input::get('order_id', 0);
        $order = Orders::find($order_id);
        $comments_count = DB::table('comments')->where('o_id', '=', $order_id)->count();
        
        if($comments_count >= 2){
            return self::error(self::STATUS_BAD_REQUEST, '已评论过');
        }
        if (!$order) {
            return self::error(self::STATUS_BAD_REQUEST, '没有订单信息');
        }
        //
        if ($order->employer == $s_user) {  //雇主评论
            $o_user= $order->employee;
            $this->setHistory(Orders::OS_EMPLOYER_COMMENTED, $order->id, $order->employer); //
            DB::table('orders')
            ->where('id', $order_id)
            ->update(array('order_status' => Orders::OS_EMPLOYER_COMMENTED));

        }
        elseif ($order->employee == $s_user) { //雇员评论
            $o_user= $order->employer;
            $this->setHistory(Orders::OS_EMPLOYEE_COMMENTED, $order->id, $order->employee); //
            DB::table('orders')
            ->where('id', $order_id)
            ->update(array('order_status' => Orders::OS_EMPLOYEE_COMMENTED, 'employee' => $order->employee));
            
        }
        else {
            return self::error(self::STATUS_BAD_REQUEST, '你不能评论该订单!');
        }

        $user = User::find($s_user);
        $messages = array(
            'user_id'   =>  $o_user,   
            'content'   =>  '“'.$user->nickname.'”已评论你',
            'type'      =>  Message::ORDER,
            'order_id'  =>  $order_id,
            'is_readed' =>  Message::IS_NOT_READED,
            'is_pushed' =>  Message::STATUS_UNPUSHED,
        );

        $this->createMessage($messages, 1); //通知


        if($comments_count == 1){
            $this->setHistory(Orders::OS_FINISHED, $order->id, $s_user); //
            DB::table('orders')
            ->where('id', $order_id)
            ->update(array('order_status' => Orders::OS_FINISHED));
        }

        $this->updateRank($o_user, $rank);  //更新被评论者的评论数和好评率
        //
        $new_data = array(
            'category' => Comment::CATEGORY_ORDER,
            'o_id' => $order_id,
            's_user' => $s_user,
            'o_user' => $o_user,
            'content' => Input::get('content.text', ''),
            'rank' => intval($rank)
        );
        $comment = Comment::create($new_data);
        if (!$comment) {
            return self::error(self::STATUS_BAD_REQUEST, '评论失败!');
        }
        $orders = Orders::findOrFail($order_id);
        return $this->json(array('comment' => $comment->formatToApi(), 'order_info' => $orders->formatToApi()));
    }

    /**
     * 评论列表 
     *
     * @return Response
     */
    public function anyList()
    {
        $o_user = Input::get('user', 0);
        $by_id = Input::get('by_id', 1);
        $by_no = Input::get('by_no', 1);
        $count = abs(Input::get('count', 10));

        $user_info = User::find($o_user);
        if (!$user_info) {
            return self::error(self::STATUS_BAD_REQUEST, '用户不存在');
        }

        //初始化参数
        $hasMore = 0;
        //query comment
        $comments = Comment::where('o_user', $o_user);
        $total = $comments->count();
        $comments = $comments->orderBy('created_at', 'ASC');

        //按ID拉取
        if (Input::has('by_id') && Input::get('by_id') > 0) {
            $lastId = Input::get('by_id');
            $comments = $comments->where('id', '>=', $lastId)->take($count + 1)->get();
        
        //按页码
        } else {
            $by_no > 1 || $by_no = 1;
            $offset = $count * ($by_no - 1);
            $comments = $comments->skip($offset)->take($count + 1)->get();
        }
        if (count($comments) > $count) {
            $hasMore = 1;
            $comments->pop();//弹出最后一条
        }   

        $comment_arr = array();
        foreach ($comments as $comment) {
            $comment_arr[] = $comment->formatToApi();
        }
        return $this->json(array(
            'total' => $total, 
            'count' => count($comment_arr),
            'more' => $hasMore, 
            'comments' => $comment_arr
        ));
    }
}   