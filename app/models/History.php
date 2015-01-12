<?php

class History extends Eloquent {

    protected $table        = 'history';
    public    $timestamps   = false;
    protected $softDelete   = false;
    protected $guarded      = array();
    

    //订单动作
    const OA_PUBLISH             = 0; //发起订单
    const OA_KNOCK_DOWN          = 1; //拍板成交
    const OA_WORK_DONE           = 2; //活已干完
    const OA_PAY                 = 3; //付款
    const OA_PAY_CONFIRM         = 4; //确认付款
    const OA_COMMENT             = 5; //评价
    const OA_CANCEL              = 6; //取消订单


    /**
     * 获取API格式数据  
     *
     * @return array
     */
    public function formatToApi()
    {
        // 每次都返回全部的历史状态 排序 并 标注
        return array(
                'id'                 => $this->id,
                'user'               => $this->user->simpleFormatToApi(),
                'order_action'       => $this->order_status,          //订单状态
                'active'             => $this->active,                  // 是否完成
                'note'               => $this->note,                  // 取消订单原因
                'created_at'         => apiTime($this->created_at),   //创建时间
               );

        // return $this->user;
    }



    /**
     * 用户关系
     * 订单状态操作者
     * @return Object
     */
    public function user()
    {
        return $this->belongsTo('User');//user_id
    }
    

    /**
     * 状态转换为html
     *
     * @return string
     */
    public function statusToHtml()
    {
        switch ($this->status) {
            case self::STATUS_NOCHECK:
                $class = 'badge-default';
                $text = '未审核';
                break;
            case self::STATUS_OK:
                $class = 'badge-success';
                $text = '已通过';
                break;
            case self::STATUS_DISABLED:
                $class = 'badge-danger';
                $text = '未通过';
                break;
        }

        return "<span class=\"badge $class\">$text</span>";
    }
}