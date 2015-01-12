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
use \Tender;
use \Config;
use \Withdraw;
use \ClientSession;
use \Carbon\Carbon;
use \Illuminate\Database\Eloquent\Collection as Collection;

class WithdrawController extends BaseController {


    /**
     * 提现列表
     *
     * @return json
     */
    public function postList()
    {
        $uid             = Input::get('uid');
        $pageSize        = Input::get('count', 10);
        $limit           = $pageSize + 1;
        $offset          = '';
        $hasMore         = 0;
        $orderWhere = '`W`.`user_id` = '.$uid.' AND `W`.`category` = 0';
        $total = 0;
        //按页码分页
        if (Input::has('by_no')) {
            $page = abs(Input::get('by_no', 1));
            $page > 1 || $page = 1;
            $offset = ' OFFSET '. $pageSize * ($page - 1);
        //按id分页，
        } elseif (Input::has('by_id')) {
            $lastId   = Input::get('by_id');
            if ($lastId > 0) {
                $orderWhere .= ' AND `W`.`id` < ' . $lastId;
            }
        }

        $sql = "SELECT *
                FROM `o2omobile_withdraw` AS `W`
                WHERE $orderWhere  AND `W`.deleted_at is NULL
                ORDER BY `W`.`created_at` DESC
                LIMIT $limit
                $offset";

        $withdrawids = DB::select($sql);

        $collection = new Collection;
        foreach ($withdrawids as $withdraw) {
            $collection->add(with(new Withdraw)->fill((array)$withdraw));
        }
        $withdraws = array();
        if (!empty($collection)) {
            if (count($collection) > $pageSize) {
                $hasMore = 1;
                $collection->pop();//弹出最后一条
            }
            //格式化
            foreach ($collection as $withdraw) {
                $withdraws[] = $withdraw->formatToApi();
            }
        }//endif

        $return = array(
                    'total'      => $total,
                    'count'      => count($withdraws) < $pageSize ? count($withdraws) : $pageSize,
                    'more'       => $hasMore,
                    'withdraws'  => $withdraws,
                   );

        return $this->json($return);
    }


    /**
     * 提现
     *
     * @return json
     */
    public function postMoney()
    {

        $user_id         = Input::get('uid');
        $amount          = Input::get('amount');
        $user            = User::find($user_id);
        if($amount < 200){
            return self::error(self::STATUS_BAD_REQUEST, '提现金额不能小于200元');
        }
        if($user->balance < $amount){
            return self::error(self::STATUS_BAD_REQUEST, '余额不足');
        }
        $user->balance   = $user->balance - $amount;
        $user->save();
        $withdraw = new Withdraw;
        $withdraw->user_id  =  $user_id;
        $withdraw->amount   =  $amount;
        $withdraw->save();
        return $this->json(array());
    }


}