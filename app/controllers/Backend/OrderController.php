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
use \Orders;
use \Request;
use \Response;
use \Redirect;
use \Validator;
use \AdminLog;
class OrderController extends BaseController {
    /**
     * 订单管理
     *
     * @return Response
     */
    public function getIndex() {         
        $orders = Orders::orderBy('id', 'DESC')->where(function($query){
            
            // //订单ID
            $order_id = intval(Input::get('order_id'));
            if (empty($order_id)) {
                $query->where('id', $order_id);
            }

            //订单ID
            $order_sn = Input::get('order_sn', 0);
            if ($order_sn) {
                $query->where('order_sn', 'like', "%$order_sn%");
            }

            //状态
            $order_status = Input::get('order_status',0);
            if ($order_status) {
                $order_status == 'un' ? 0 : $order_status;
                $query->where('order_status', $order_status);
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
                    $query->whereIn('employer', $users);
                }
                
            }
        })->paginate(15);
        // print_r($users);exit;
        //关键字搜索用户时用
        if (Request::wantsJson()) {
            return $orders->getCollection()->toArray();
        }

        return View::make('Backend.pages.order')->withOrders($orders);
    }


    public function getDetails($id) {
        $order = Orders::findOrFail($id);
        $location = json_decode($order->location);
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        return View::make('Backend.pages.order-details')->withOrder($order)->withLocation($location)->withHost($host);
    }

}