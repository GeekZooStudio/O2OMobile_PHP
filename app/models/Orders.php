<?php

class Orders extends Eloquent {

    //订单状态
    const OS_PUBLISHED          = 0; //客户发单
    const OS_KNOCK_DOWN         = 1; //已确认接单
    const OS_WORK_DONE          = 2; //工作完成
    const OS_PAYED              = 3; //已付款
    const OS_PAY_CONFORMED      = 4; //付款已确认
    const OS_EMPLOYEE_COMMENTED = 5; //雇员已评价
    const OS_EMPLOYER_COMMENTED = 6; //雇主已评价
    const OS_FINISHED           = 7; //订单结束
    const OS_CANCELED           = 8; //订单取消


    //我发布的订单状态
    const PUBLISHED_ORDER_UNDONE = 0; //未完成
    const PUBLISHED_ORDER_DONE   = 1; //已完成
    const PUBLISHED_ORDER_ALL    = 2; //全部


    //我接收的订单状态
    const TAKED_ORDER_TENDER     = 0; //已竞标
    const TAKED_ORDER_UNDONE     = 1; //未完成
    const TAKED_ORDER_DONE       = 2; //已完成
    const TAKED_ORDER_ALL        = 3; //全部


    //支付方式
    const PAY_ONLINE             = 0; //在线支付
    const PAY_OFFLINE            = 1; //线下支付


    protected $table        = 'orders';
    public    $timestamps   = true;
    protected $softDelete   = true;
    protected $guarded      = array();
    

    /**
     * 获取API格式数据  
     *
     * @return array
     */
    public function formatToApi()
    {

        if($this->employee){ 
            $employeeInfo = $this->employeeInfo->simpleFormatToApi();            
        }else{
            $employeeInfo = NULL;
        }

     
        if($this->employer)
        {
            $employerInfo = $this->employerInfo->simpleFormatToApi();
        }else{
            $employerInfo = NULL;
        }

        $path = 'http://'.$_SERVER['HTTP_HOST'];
        if(!empty($this->voice)){
            $voice = getFilePath($this->voice, 'voice');
        }else{
            $voice = $this->voice;
        }
    
        $employerComment = $this->employerComment($this->employer, $this->id);
        $employeeComment = $this->employeeComment($this->employee, $this->id);
        // print_r($employerComment);exit;
        // 雇主的评论
        if($employerComment){
            $this->ercomment_id = $employerComment->id;
            $employer_comment = $this->ercommentInfo->formatToApi();
            
        }else{
            $employer_comment = NULL;
        }
        // 雇员的评论
        if($employeeComment){
            $this->eecomment_id = $employeeComment->id;
            $employee_comment = $this->eecommentInfo->formatToApi();
            
        }else{
           $employee_comment = NULL; 
        }

        if($this->serviceInfo){ 
            $service_type = $this->serviceInfo->formatToApi();            
        }else{
            $service_type = NULL;
        }

        return array(
                'id'                 => $this->id,
                'order_sn'           => $this->order_sn,
                'order_status'       => $this->order_status,            // 订单状态
                'pay_code'           => $this->pay_code,                // 支付方式
                'employer'           => $employerInfo,     // 雇主信息
                "employee"           => $employeeInfo,                  // 雇员信息
                'offer_price'        => $this->offer_price,             // 初始价格
                'appointment_time'   => $this->appointment_time, // 预约时间
                'transaction_price'  => $this->transaction_price,       // 成交价格
                'service_type'       => $service_type,            // 服务类型
                'location'           => apiLocation($this->location),   // 服务地点
                'rank'               => $this->rank,   
                'accept_time'        => apiTime($this->accept_time),             //接单时间
                'push_number'        => $this->push_number,                 //推送个数
                'duration'           => $this->duration,                    // 音频长度 秒
                'content'            => array('text' => $this->text, 'voice' => $voice),                // 服务描述
                'employer_comment'   => $employer_comment,                // 雇主评价
                'employee_comment'   => $employee_comment,             // 雇员评价
                'created_at'         => apiTime($this->created_at),   //创建时间
               );

        // return $this->user;
    }



    /**
     * 用户关系
     * 雇主
     * @return Object
     */
    public function employerInfo()
    {
        return $this->belongsTo('User', 'employer');
    }

    /**
     * 用户关系
     * 雇员
     * @return Object
     */
    public function employeeInfo()
    {
        return $this->belongsTo('User', 'employee');
    }



    /**
     * 用户关系
     * 雇员
     * @return Object
     */
    public function employerComment($user_id, $order_id)
    {

        $comments = DB::table('comments')->where('s_user', $user_id)->where('o_id', $order_id)->get();
        if(!empty($comments)){
            // $comment = $comments[0];
            return $comments[0];
        }
        return 0;
    }


    /**
     * 用户关系
     * 雇员
     * @return Object
     */
    public function employeeComment($user_id, $order_id)
    {

        $comments = DB::table('comments')->where('s_user', $user_id)->where('o_id', $order_id)->get();
        if(!empty($comments)){
            // $comment = $comments[0];
            return $comments[0];
        }
        return 0;
    }


    /**
     * 
     * 雇主的评论
     * @return Object
     */
    public function ercommentInfo()
    {
        return $this->belongsTo('Comment', 'ercomment_id');
    }

    /**
     * 
     * 雇员的评论
     * @return Object
     */
    public function eecommentInfo()
    {
        return $this->belongsTo('Comment', 'eecomment_id');
    }


    /**
     * 服务
     * 
     * @return Object
     */
    public function serviceInfo()
    {
        return $this->belongsTo('Service', 'service_type');//service_type
    }



    /**
     * 状态转换为html 前台
     *
     * @return string
     */
    public static function orderStatus($order_status)
    {  

       
        
        if($order_status == self::OS_PUBLISHED){
            $html = '<em class="red">未接单</em>';
        }elseif($order_status == self::OS_KNOCK_DOWN){
            $html = '<em class="green">已接单</em>';
        }elseif($order_status == self::OS_WORK_DONE){
            $html = '<em class="green">工作完成</em>';
        }elseif($order_status == self::OS_PAYED){
            $html = '<em class="green">已付款</em>';
        }elseif($order_status == self::OS_PAY_CONFORMED){
            $html = '<em class="green">付款已确认</em>';
        }elseif($order_status == self::OS_CANCELED){
            $html = '订单取消';
        }
        return $html;
    }


    /**
     * 状态转换为html 订单评论状态 前台
     *
     * @return string
     */
    public static function orderCommentStatus($order_id, $employer, $employee)
    {  

        $comments_count = DB::table('comments')->where('o_id', '=', $order_id)->count();
        
        if($comments_count == 2){
            return '双方已评论';
        }
        if($comments_count == 0){
            return '对方未评论';
        }
        if($comments_count == 1){
            
            $employer_count = DB::table('comments')
                        ->where('o_id', '=', $order_id)
                        ->where('s_user', '=', $employer)->count();
            $employee_count = DB::table('comments')
                        ->where('o_id', '=', $order_id)
                        ->where('s_user', '=', $employee)->count();

        }

        if($employer_count){
            return '对方未评论';
        }

        if($employee_count){
            return '对方已评论';
        }   

    }


    /**
     * 状态转换为html 我的评论状态 前台
     *
     * @return string
     */
    public static function orderMyStatus($order_id, $employer)
    {  

        $employer_count = DB::table('comments')
                        ->where('o_id', '=', $order_id)
                        ->where('s_user', '=', $employer)->count();

        if($employer_count){
            return '查看';
        }

        return '<a class="green" href="/order/details/'.$order_id.'#comment">评论</a>';  

    }


    /**
     * 状态转换为html 后台
     *
     * @return string
     */
    public function statusToHtml()
    {
        switch ($this->order_status) {
            case self::OS_PUBLISHED:
                $class = 'badge-default';
                $text = '客户发单';
                break;
            case self::OS_KNOCK_DOWN:
                $class = 'badge-default';
                $text = '已确认接单';
                break;
            case self::OS_WORK_DONE:
                $class = 'badge-default';
                $text = '工作完成';
                break;
            case self::OS_PAYED:
                $class = 'badge-default';
                $text = '已付款';
                break;
            case self::OS_PAY_CONFORMED:
                $class = 'badge-default';
                $text = '付款已确认';
                break;
            case self::OS_EMPLOYEE_COMMENTED:
                $class = 'badge-default';
                $text = '雇员已评价';
                break;
            case self::OS_EMPLOYER_COMMENTED:
                $class = 'badge-default';
                $text = '雇主已评价';
                break;
            case self::OS_FINISHED:
                $class = 'badge-success';
                $text = '订单结束';
                break;
            case self::OS_CANCELED:
                $class = 'badge-danger';
                $text = '订单取消';
                break;
        }

        return "<span class=\"badge $class\">$text</span>";
    }


    /**
     * 服务类型转换为html
     *
     * @return string
     */
    public function serviceToHtml()
    {

        if(!$this->service_type){
            return '<font color="grey">无</font>';
        }
        $services = Service::find($this->service_type);
        if(empty($services)){
            return '<font color="grey">无此服务</font>';
        }
        $class = 'badge-default';
        $text = $services->name;

        return "<span class=\"badge $class\">$text</span>";
    }

    /**
     * 雇主转换为html
     *
     * @return string
     */
    public function employerToHtml()
    {
        $user = User::find($this->employer);
        $class = 'badge-success';
        if (!empty($user)) 
        {
            $text = $user->nickname;
        }
        else
        {
            $text = "已删除";
        }
        

        return "<span class=\"badge $class\">$text</span>";
    }


    /**
     * 雇员转换为html
     *
     * @return string
     */
    public function employeeToHtml()
    {
        if(!$this->employee){
            return "<span class=badge-default>无人接单</span>";
        }
        $user = User::find($this->employee);
        if(empty($user)){
            return '<font color="grey">无此用户</font>';
        }
        $class = 'badge-danger';
        $text = $user->nickname;

        return "<span class=\"badge $class\">$text</span>";
    }

    
}