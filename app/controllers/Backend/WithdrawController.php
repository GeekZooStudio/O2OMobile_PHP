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
use \Withdraw;
use \Message;
use \Request;
use \Response;
use \Redirect;
use \Validator;
use \AdminLog;
class WithdrawController extends BaseController {

    /**
     * 提现管理
     *
     * @return Response
     */
    public function getIndex() {
        $withdraws = Withdraw::orderBy('id', 'DESC')->where('category', '0')->where(function($query){
            
            $state = Input::get('state',0);
            if ($state) {
                $state == 'un' ? 0 : $state;
                $query->where('state', $state);
            }

            //时间
            $regTimeRange = Input::get('time_range',0);
            if ($regTimeRange) {
                $times = explode(' - ', $regTimeRange);
                $query->whereBetween('created_at', $times);
            }

            //关键字
            $keyword = Input::get('keyword',0);
            if ($keyword) {
                $users = DB::table('users')->where('nickname', 'like', "%$keyword%")->lists('id');
                if(!empty($users)){
                    $query->whereIn('user_id', $users);
                }
                
            }
        })->paginate(15);
      
        //关键字搜索用户时用
        if (Request::wantsJson()) {
            return $withdraws->getCollection()->toArray();
        }

        return View::make('Backend.pages.withdraw')->withWithdraws($withdraws);
    }


    /**
     * 提现处理
     *
     * @return Response
     */
    public function getMoney() {

        $id                 = Input::get('id');
        $state              = Input::get('state');
        $note               = Input::get('note', '');

        $withdraws          = Withdraw::findOrFail($id);
        $withdraws->state   = $state;
        $withdraws->note    = $note;
        $withdraws->save();

        if($state == Withdraw::WITHDRAW_FAILED){ //提现失败时给用户发信息
            $note   = '提现失败，原因“'.$note.'”';
            //把钱打给用户
            $user   =  User::find($withdraws->user_id);   
            $user->balance   =  $user->balance + $withdraws->amount;
            $user->save();
            AdminLog::log($id, "为“".User::userinfo($withdraws->user_id)."”进行提现失败操作");
        }elseif($state == Withdraw::WITHDRAW_SUCC){
            $note = '提现成功';
            AdminLog::log($id, "为“".User::userinfo($withdraws->user_id)."”进行提现成功的操作");
        }

        $message = array(
            'user_id' => $withdraws->user_id,
            'type'    => Message::OTHER,
            'content' => $note,
        );

        Message::createMessage($message, true);

        
        return Redirect::back()->withMessage('操作成功！');
    }


}