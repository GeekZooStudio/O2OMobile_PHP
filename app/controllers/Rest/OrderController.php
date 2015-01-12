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
use \Test;
use \DB;
use \User;
use \Auth;
use \Input;
use \Orders;
use \Log;
use \Service;
use \History;
use \Tender;
use \Config;
use \Client;
use \Message;
use \Comment;
use \ClientSession;
use \Carbon\Carbon;
use \Illuminate\Support\Facades\URL as URL;
use \Illuminate\Database\Eloquent\Collection as Collection;
class OrderController extends BaseController {

    //错误码
    const ERROR_ORDER_ALREADY_ACCEPT          = 13;  //此订单已经被接
    const ERROR_CANT_ACCEPT_ORDER_YOURSELF    = 14;  //不能接自己的订单
    const ERROR_ORDER_ALREADY_ACCEPT_FOR_YOU  = 15;  //你已经接下此订单
    const ERROR_THIS_ORDER_NOT_FOR_YOU        = 16;  //此订单不属于你
    const ERROR_CANT_ACCEPT_ORDER_NOT_FREEMAN = 17; //不是自由人不能接单
    const ERROR_YOU_CANT_APPLY_THIS_SERVICE   = 18; //你没有申请此服务
    

    // 李秋实  发单人 雇主
    // 王贵生  接单人 雇员

    /**
     * 订单详情
     *
     * @return json
     */
    public function postInfo()
    {
        // $test = $this->currentUser->id;
        // print_r($test);exit;
        $order_id = Input::get('order_id', 0);

        $order = Orders::find($order_id);

        if (empty($order)) {
            return $this->json(array('order_info' => array()));
        }
        return $this->json(array('order_info' => $order->formatToApi()));
    }


    /**
     * 发布订单 （李秋实发布订单）
     *
     * @return Response
     */
    public function postPublish()
    {

        $order = new Orders;
        $order->employer            = Input::get('uid', '');
        $order->employee            = Input::get('employee', '');
        $order->text                = Input::get('content.text', '');
        $order->duration            = Input::get('duration', 0);
        $order->lon                 = Input::get('location.lon', '');   //经度
        $order->lat                 = Input::get('location.lat', '');   //纬度
        $order->appointment_time    = Input::get('start_time', ''); //预约时间
        $order->offer_price         = Input::get('offer_price', '');  //约定价格
        $order->transaction_price   = Input::get('offer_price', '');  //最终成交价  暂时 ＝ 预定价格
        $order->service_type        = Input::get('service_type_id', '');

        $order->location = json_encode(Input::get('location', array()));

        $order->default_receiver_id    = Input::get('default_receiver_id', 0);   //指定服务人 有就只推送给这个人 没有就推送给附近的人

        $fileStoragePath = public_path() . '/uploadFile/voice';
        //处理上传音频
        if (Input::hasFile('voice')) {
            $dateFolder = date('/Y/m/d', time());  //日期作为目录
            $folder = $fileStoragePath . $dateFolder;
            if (!file_exists($folder) && !@mkdir($folder, 0777, true)) {
                return self::error(self::STATUS_UNKNOWN_ERROR, 'Not Writeable Dir');
            }
            $file     = Input::file('voice');
            $filename = $file->getClientOriginalName();
            $ext      = fileExt($filename);
            $localFile = md5($filename) . filesize($file) . $ext;
            $file->move($folder, $localFile);
            $order->voice = $dateFolder .'/'. $localFile;
        }

        $order->save();
        $order_sn = $date = date('YmdHis').$order->id;

        if($order->default_receiver_id != 0){  //有默认接单人推送个数1人
            $push_number = 1;
        }else{
            $push_number = rand(30, 100);
        }
        //更新一次为了更新订单号 
        DB::table('orders')
            ->where('id', $order->id)
            ->update(array('order_sn' => $order_sn, 'push_number' => $push_number));

        if($order->default_receiver_id != 0){  //有默认接单人就发给这个人
            $user = User::find($order->employer);
            $messages = array(
                'user_id'   =>  $order->default_receiver_id,   
                'content'   =>  '“'.$user->nickname.'”请求你帮忙',
                'type'      =>  Message::ORDER,
                'order_id'  =>  $order->id,
                'is_readed' =>  Message::IS_NOT_READED,
                'is_pushed' =>  Message::STATUS_UNPUSHED,
            );

            $this->createMessage($messages, 1); //通知 传1是发两个push
        }

        $order->order_sn     = $order_sn;
        $order->order_status = Orders::OS_PUBLISHED;
        $order->push_number  = $push_number;
        $this->setHistory(Orders::OS_PUBLISHED, $order->id, $order->employer); //创建到订单状态历史表
        return $this->json(array('order_info' => $order->formatToApi()));

    }


    /**
     * 取消订单 （李秋实和王贵生接单）
     * 李秋实在别人未接单(<1)时可以取消
     * 王贵生在接单后完成前(2> =>1)时可以取消
     * @return json
     */
    public function postCancel()
    {
        $order_id = Input::get('order_id');
        $user_id = Input::get('uid');
        // print_r($order_id);exit;
        $order = Orders::findOrFail($order_id);

        if($order->employer != $user_id && $order->employee != $user_id){
            return self::error(self::ERROR_THIS_ORDER_NOT_FOR_YOU, '此订单不符合要求');
        }

        if($order->employer == $user_id && $order->order_status >= Orders::OS_KNOCK_DOWN){
            return self::error(self::ERROR_THIS_ORDER_NOT_FOR_YOU, '此订单不符合要求');
        }

        if($order->employee == $user_id && ($order->order_status < Orders::OS_KNOCK_DOWN || $order->order_status >= Orders::OS_WORK_DONE)){
            return self::error(self::ERROR_THIS_ORDER_NOT_FOR_YOU, '此订单不符合要求');
        }

        if($order->employee == $user_id){
            $user = User::find($order->employee);
            $messages = array(
                'user_id'   =>  $order->employer,   
                'content'   =>  '“'.$user->nickname.'”取消了订单',
                'type'      =>  Message::ORDER,
                'order_id'  =>  $order_id,
                'is_readed' =>  Message::IS_NOT_READED,
                'is_pushed' =>  Message::STATUS_UNPUSHED,
            );

            $this->createMessage($messages, 1); //通知 传1是发两个push
        }

        DB::table('orders')
            ->where('id', $order_id)
            ->update(array('order_status' => Orders::OS_CANCELED));
        $order->order_status = Orders::OS_CANCELED;
        $this->setHistory(Orders::OS_CANCELED, $order_id, $user_id); //创建到订单状态历史表
        return $this->json(array('order_info' => $order->formatToApi()));
    }


    /**
     * 接单  （王贵生接单）
     *
     * @return json
     */
    public function postAccept()
    {
        $order_id = Input::get('order_id');
        $user_id = Input::get('uid');
        // print_r($order_id);exit;
        $order = Orders::findOrFail($order_id);
        $user = User::find($user_id);
        $my_services = DB::table('my_services')->where('user_id', $user_id)->lists('services_id');
        if($user->role != User::FREEMAN){
            return self::error(self::ERROR_CANT_ACCEPT_ORDER_NOT_FREEMAN, '不是自由人不能接订单');
        }
        if(!in_array($order->service_type, $my_services)){
            return self::error(self::ERROR_YOU_CANT_APPLY_THIS_SERVICE, '你没有申请此服务');
        }
        if($order->employer == $user_id){
            return self::error(self::ERROR_CANT_ACCEPT_ORDER_YOURSELF, '不能接自己的订单');
        }
        if($order->employee == $user_id){
            return self::error(self::ERROR_ORDER_ALREADY_ACCEPT_FOR_YOU, '你已经接下此订单');
        }
        if($order->order_status >= Orders::OS_KNOCK_DOWN){
            // return self::error(self::ERROR_ORDER_ALREADY_ACCEPT, '你来晚了');
            $order->order_status = Orders::OS_KNOCK_DOWN;
            return $this->jsons(array('order_info' => $order->formatToApi()), 0, self::ERROR_ORDER_ALREADY_ACCEPT, '订单已经被接');
        }
        $accept_time = date('Y/m/d H:i:s', time());
        DB::table('orders')
            ->where('id', $order_id)
            ->update(array('order_status' => Orders::OS_KNOCK_DOWN, 'employee' => $user_id, 'accept_time' => $accept_time));
        $order->order_status = Orders::OS_KNOCK_DOWN;
        $order->employee = $user_id;
        $order->accept_time = $accept_time;
        $this->setHistory(Orders::OS_KNOCK_DOWN, $order_id, $user_id); //创建到订单状态历史表

        $messages = array(
                'user_id'   =>  $order->employer,   
                'content'   =>  '“'.$user->nickname.'”接受了你的订单',
                'type'      =>  Message::ORDER,
                'order_id'  =>  $order_id,
                'is_readed' =>  Message::IS_NOT_READED,
                'is_pushed' =>  Message::STATUS_UNPUSHED,
            );

        $this->createMessage($messages, 1); //通知 传1是发两个push

        return $this->json(array('order_info' => $order->formatToApi()));
    }


    /**
     * 活已干完 （王贵生确认完成）
     *
     * @return json
     */
    public function postWorkDone()
    {
        $order_id           = Input::get('order_id');
        $user_id            = Input::get('uid');
        $order              = Orders::findOrFail($order_id);
        $transaction_price  = Input::get('transaction_price', $order->offer_price);
        $user = User::find($order->employee);
        if($order->employee != $user_id){
            return self::error(self::ERROR_THIS_ORDER_NOT_FOR_YOU, '此订单不属于你');
        }

        if($order->order_status > Orders::OS_WORK_DONE){
            return self::error(self::ERROR_THIS_ORDER_NOT_FOR_YOU, '此订单不符合要求');
        }

        DB::table('orders')
            ->where('id', $order_id)
            ->update(array('order_status' => Orders::OS_WORK_DONE, 'transaction_price' => $transaction_price));
        $order->order_status = Orders::OS_WORK_DONE;
        $order->transaction_price = $transaction_price;
        $this->setHistory(Orders::OS_WORK_DONE, $order_id, $user_id); //创建到订单状态历史表

        $messages = array(
                'user_id'   =>  $order->employer,   
                'content'   =>  '“'.$user->nickname.'”完成了工作',
                'type'      =>  Message::ORDER,
                'order_id'  =>  $order_id,
                'is_readed' =>  Message::IS_NOT_READED,
                'is_pushed' =>  Message::STATUS_UNPUSHED,
            );

        $this->createMessage($messages, 1); //通知 传1是发两个push

        return $this->json(array('order_info' => $order->formatToApi()));
    }


    /**
     * 请求支付 （李秋实确认支付方式并且去支付）
     *
     * @return json
     */
    public function postPay()
    {
        $order_id           = Input::get('order_id');
        $user_id            = Input::get('uid');
        $pay_code           = Input::get('pay_code');

        $order = Orders::findOrFail($order_id);
        $user = User::find($order->employer);
        if($order->employer != $user_id){
            return self::error(self::ERROR_THIS_ORDER_NOT_FOR_YOU, '此订单不属于你');
        }
        if($pay_code == Orders::PAY_ONLINE){  //线上支付
            $jsonArray = array('order_info' => $order->formatToApi());
            return $this->json($jsonArray);
        }

        DB::table('orders')
            ->where('id', $order_id)
            ->update(array('order_status' => Orders::OS_PAYED, 'pay_code' => Orders::PAY_OFFLINE));
        $order->order_status = Orders::OS_PAYED;
        $this->setHistory(Orders::OS_PAYED, $order_id, $user_id); //创建到订单状态历史表

        $messages = array(
                'user_id'   =>  $order->employee,   
                'content'   =>  '“'.$user->nickname.'”给你线下支付',
                'type'      =>  Message::ORDER,
                'order_id'  =>  $order_id,
                'is_readed' =>  Message::IS_NOT_READED,
                'is_pushed' =>  Message::STATUS_UNPUSHED,
            );

        $this->createMessage($messages, 1); //通知 传1是发两个push

        return $this->json(array('order_info' => $order->formatToApi()));
    }


    /**
     * 确认支付 （王贵生确认李秋实已付款）
     *
     * @return json
     */
    public function postConfirmPay()
    {
        $order_id           = Input::get('order_id');
        $user_id            = Input::get('uid');

        $order = Orders::findOrFail($order_id);
        $user = User::find($order->employee);
        if($order->employee != $user_id){
            return self::error(self::ERROR_THIS_ORDER_NOT_FOR_YOU, '此订单不属于你');
        }

        DB::table('orders')
            ->where('id', $order_id)
            ->update(array('order_status' => Orders::OS_PAY_CONFORMED));
        $order->order_status = Orders::OS_PAY_CONFORMED;
        $this->setHistory(Orders::OS_PAY_CONFORMED, $order_id, $user_id); //创建到订单状态历史表

        $messages = array(
                'user_id'   =>  $order->employer,   
                'content'   =>  '“'.$user->nickname.'”已确认你的支付',
                'type'      =>  Message::ORDER,
                'order_id'  =>  $order_id,
                'is_readed' =>  Message::IS_NOT_READED,
                'is_pushed' =>  Message::STATUS_UNPUSHED,
            );

        $this->createMessage($messages, 1); //通知 传1是发两个push
        giveInvite($order->employee);  //订单提成邀请奖励  雇员的
        giveOneTimeInvite($order->employer);  //一次性返现奖励  雇主的
        putBigc($order);  //往大c统计表里插一条数据  雇员的
        return $this->json(array('order_info' => $order->formatToApi()));
    }


    /**
     * 订单历史 列表
     *
     * @return json
     */
    public function postHistory()
    {
        $order_id           = Input::get('order_id');
        $user_id            = Input::get('uid');
        $historyids = DB::table('history')->where('order_id', '=', $order_id)->orderBy('id', 'ASC')->get();        
        $arr = array(0,1,2,3,4,5,6,7);        
        $collection = new Collection;
        foreach ($historyids as $history) {            
            if(in_array($history->order_status, $arr)){
                unset($arr[$history->order_status]);
            }
            $collection->add(with(new History)->fill((array)$history));
        }        
        $historys = array();
        $count = count($collection);
        $i=0;
        if (!empty($collection)) {
            foreach ($collection as $history) {
                $history->active = 1;
                $historys[] = $history->formatToApi();
                $i++;         
            }            
            foreach ($arr as $key => $value) {
                $historys[$i]['order_action'] = $value;
                $historys[$i]['active'] = 0;
                $i++;
            }
            
        }//endif
        return $this->json(array('history' => $historys));
    }


    
}