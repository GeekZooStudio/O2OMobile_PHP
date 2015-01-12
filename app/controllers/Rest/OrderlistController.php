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
use \Orders;
use \Tender;
use \Config;
use \Client;
use \ClientSession;
use \Carbon\Carbon;
use \Illuminate\Database\Eloquent\Collection as Collection;

class OrderlistController extends BaseController {


    /**
     * 发布的订单列表
     *
     * @return json
     */
    public function postPublished()
    {

        $user_id         = Input::get('uid');
        $pageSize        = Input::get('count', 10);
        $published_order = Input::get('published_order', Orders::PUBLISHED_ORDER_ALL);
        $limit           = $pageSize + 1;
        $offset          = '';
        $hasMore         = 0;
        $total           = 0;
        $orderWhere = '`O`.`employer` = '.$user_id;

        //按页码分页
        if (Input::has('by_no')) {

            $page = abs(Input::get('by_no', 1));
            $page > 1 || $page = 1;
            $offset = ' OFFSET '. $pageSize * ($page - 1);
        //按id分页，
        } elseif (Input::has('by_id')) {
            $lastId   = Input::get('by_id');
            if ($lastId > 0) {
                $orderWhere .= ' AND `O`.`id` < ' . $lastId;
            }
        }


        if($published_order == Orders::PUBLISHED_ORDER_UNDONE){  //未完成
            $orderWhere .= ' AND (`O`.`order_status` < ' . Orders::OS_PAY_CONFORMED.' OR `O`.`order_status` = ' . Orders::OS_CANCELED. ')';
        }elseif($published_order == Orders::PUBLISHED_ORDER_DONE){ //已完成
            $orderWhere .= ' AND `O`.`order_status` >= ' . Orders::OS_PAY_CONFORMED .' AND `O`.`order_status` != ' . Orders::OS_CANCELED;
        }elseif($published_order == Orders::PUBLISHED_ORDER_ALL){  //全部

        }


        $sql = "SELECT *
                FROM `o2omobile_orders` AS `O`
                WHERE $orderWhere  AND `O`.deleted_at is NULL
                ORDER BY `O`.`created_at` DESC
                LIMIT $limit
                $offset";

        $orderIds = DB::select($sql);

        $collection = new Collection;
        foreach ($orderIds as $order) {
            $collection->add(with(new Orders)->fill((array)$order));
        }

        $orders = array();
        if (!empty($collection)) {
            if (count($collection) > $pageSize) {
                $hasMore = 1;
                $collection->pop();//弹出最后一条
            }
            //格式化
            foreach ($collection as $order) {
                $orders[] = $order->formatToApi();
            }
        }//endif
        $return = array(
                    'total'  => $total,
                    'count'  => count($orders) < $pageSize ? count($orders) : $pageSize,
                    'more'   => $hasMore,
                    'orders' => $orders,
                   );

        return $this->json($return);
    }



    /**
     * 接收的订单列表
     *
     * @return json
     */
    public function postReceived()
    {
        $user_id         = Input::get('uid');
        $pageSize        = Input::get('count', 10);
        $taked_order     = Input::get('taked_order', Orders::TAKED_ORDER_ALL);
        $limit           = $pageSize + 1;
        $offset          = '';
        $hasMore         = 0;
        $total           = 0;
        $orderWhere = '`O`.`employee` = '.$user_id;
        //按页码分页
        if (Input::has('by_no')) {
            $page = abs(Input::get('by_no', 1));
            $page > 1 || $page = 1;
            $offset = ' OFFSET '. $pageSize * ($page - 1);
        //按id分页，
        } elseif (Input::has('by_id')) {
            $lastId   = Input::get('by_id');
            if ($lastId > 0) {
                $orderWhere .= ' AND `O`.`id` < ' . $lastId;
            }
        }


        if($taked_order == Orders::TAKED_ORDER_TENDER){  //已接单
            $orderWhere .= ' AND `O`.`order_status` = ' . Orders::OS_KNOCK_DOWN;
        }elseif($taked_order == Orders::TAKED_ORDER_UNDONE){ //未完成
            $orderWhere .= ' AND (`O`.`order_status` < ' . Orders::OS_PAY_CONFORMED.' OR `O`.`order_status` = ' . Orders::OS_CANCELED. ')';
        }elseif($taked_order == Orders::TAKED_ORDER_DONE){  //已完成
            $orderWhere .= ' AND `O`.`order_status` >= ' . Orders::OS_PAY_CONFORMED .' AND `O`.`order_status` != ' . Orders::OS_CANCELED;
        }elseif($taked_order == Orders::TAKED_ORDER_ALL){  //全部

        }


        $sql = "SELECT *
                FROM `o2omobile_orders` AS `O`
                WHERE $orderWhere  AND `O`.deleted_at is NULL
                ORDER BY `O`.`created_at` DESC
                LIMIT $limit
                $offset";

        $orderIds = DB::select($sql);        

        $collection = new Collection;
        foreach ($orderIds as $order) {
            $collection->add(with(new Orders)->fill((array)$order));
        }

        $orders = array();
        if (!empty($collection)) {
            if (count($collection) > $pageSize) {
                $hasMore = 1;
                $collection->pop();//弹出最后一条
            }
            //格式化
            foreach ($collection as $order) {
                $orders[] = $order->formatToApi();
            }
        }//endif
        $return = array(
                    'total'  => $total,
                    'count'  => count($orders) < $pageSize ? count($orders) : $pageSize,
                    'more'   => $hasMore,
                    'orders' => $orders,
                   );

        return $this->json($return);
    }



    /**
     * 周围订单列表
     *
     * @return json
     */
    public function postAround()
    {
        $location        = Input::get('location');
        $pageSize        = Input::get('count', 10);
        $sort_by         = Input::get('sort_by', self::TIME_DESC);
        $limit           = $pageSize + 1;
        $offset          = '';
        $hasMore         = 0;
        $total           = 0;
        $orderWhere = '`O`.`order_status` = '.Orders::OS_PUBLISHED;
        //按页码分页
        if (Input::has('by_no')) {
            $page = abs(Input::get('by_no', 1));
            $page > 1 || $page = 1;
            $offset = ' OFFSET '. $pageSize * ($page - 1);
        //按id分页，
        } elseif (Input::has('by_id')) {
            $lastId   = Input::get('by_id');
            if ($lastId > 0) {
                $orderWhere .= ' AND `O`.`id` < ' . $lastId;
            }
        }

        if($sort_by == self::PRICE_DESC){           //价格降序排列
            $orderBy = ' ORDER BY `O`.`offer_price` DESC';
        }elseif($sort_by == self::PRICE_ASC){       //价格升序排列
            $orderBy = ' ORDER BY `O`.`offer_price` ASC';
        }elseif($sort_by == self::TIME_DESC){       //时间排序
            $orderBy = ' ORDER BY `O`.`created_at` DESC';
        }elseif($sort_by == self::LOCATION_ASC){    //按照距离有近到远排序
            $orderBy = " ORDER BY GetDistance({$location['lat']}, {$location['lon']}, `O`.`lat`, `O`.`lon`)  ASC";
        }elseif($sort_by == self::RANK_DESC){       //评价从高到低
            $orderBy = ' ORDER BY `O`.`rank` DESC';
        }elseif($sort_by == self::RANK_ASC){        //评价从低到高
            $orderBy = ' ORDER BY `O`.`rank` ASC';
        }

        $squares = returnSquarePoint($location['lon'], $location['lat']);

        $sql = "SELECT *
                     FROM `o2omobile_orders` AS `O`
                     WHERE $orderWhere  AND `O`.`lat`<>0
                     AND `O`.`lat`>{$squares['right-bottom']['lat']}
                     AND `O`.`lat`<{$squares['left-top']['lat']}
                     AND `O`.`lon`>{$squares['left-top']['lon']}
                     AND `O`.`lon`<{$squares['right-bottom']['lon']}
                     AND `O`.deleted_at is NULL
                     $orderBy
                     LIMIT $limit
                     $offset";
        $orderIds = DB::select($sql);
        // print_r($orderIds);exit;
        $collection = new Collection;
        foreach ($orderIds as $order) {
            $collection->add(with(new Orders)->fill((array)$order));
        }

        $orders = array();
        if (!empty($collection)) {
            if (count($collection) > $pageSize) {
                $hasMore = 1;
                $collection->pop();//弹出最后一条
            }
            //格式化
            foreach ($collection as $order) {
                $orders[] = $order->formatToApi();
            }
        }//endif

        $return = array(
                    'total'  => $total,
                    'count'  => count($orders) < $pageSize ? count($orders) : $pageSize,
                    'more'   => $hasMore,
                    'orders' => $orders,
                   );
        return $this->json($return);
    }


}