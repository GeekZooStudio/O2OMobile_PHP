<?php

class Applyservice extends Eloquent {

    protected $table        = 'apply_service';
    public    $timestamps   = true;
    protected $softDelete   = true;
    protected $guarded      = array();
    

    const PROCESSING          = 0; //处理中
    const APPLY_SUCC          = 1; //申请成功
    const APPLY_FAILED        = 2; //申请失败

    /**
     * 获取API格式数据  
     *
     * @return array
     */
    public function formatToApi()
    {
        // print_r($this);exit;
        return array(
                'id'                 => $this->id,
                'price'              => $this->price,
                'service_type'       => $this->servicess->formatToApi(),          //
               );

        // return $this->user;
    }



    /**
     * 用户转换为html
     *
     * @return string
     */
    public function userToHtml()
    {

        if(!$this->user_id){
            return '<font color="grey">无</font>';
        }
        $user = User::find($this->user_id);
        if(empty($user)){
            return '<font color="grey">无此用户</font>';
        }
        // print_r($user);exit;
        $class = 'badge-success';
        $text = $user->nickname;

        return "<span class=\"badge $class\">$text</span>";
    }

    /**
     * 一级类目转换为html
     *
     * @return string
     */
    public function oneToHtml()
    {
        if(!$this->firstclass_service_category_id){
           return '<font color="grey">无</font>'; 
        }
        $service = Service::find($this->firstclass_service_category_id);
        if(empty($service)){
            return '<font color="grey">无此服务</font>';
        }
        $class = 'badge-success';
        $text = $service->name;

        return "<span class=\"badge $class\">$text</span>";
    }

    /**
     * 二级类目转换为html
     *
     * @return string
     */
    public function twoToHtml()
    {
        if(!$this->secondclass_service_category_id){
           return '<font color="grey">无</font>'; 
        }
        $service = Service::find($this->secondclass_service_category_id);
        if(empty($service)){
            return '<font color="grey">无此服务</font>';
        }
        $class = 'badge-warning';
        $text = $service->name;

        return "<span class=\"badge $class\">$text</span>";
    }


    /**
     * 服务
     * 
     * @return Object
     */
    public function servicess()
    {
        // print_r($this->belongsTo('Service', 'id'));exit;
        return $this->belongsTo('Service', 'services_type_id');//user_id
    }
    


    /**
     * 我的服务转换为html
     *
     * @return string
     */
    public function serviceToHtml()
    {
        if(!$this->service_type_id){
            return '<font color="grey">无</font>';
        }
// return $this->services_type_id;exit;
        $services = Service::find($this->service_type_id);
        // print_r($services->name);exit;
        if(empty($services)){
            return '<font color="grey">无此服务</font>';
        }
        $class = 'badge-default';
        $text = $services->name;

        return "<span class=\"badge $class\">$text</span>";
    }


    /**
     * 状态转换为html
     *
     * @return string
     */
    public function stateToHtml()
    {

        switch ($this->state) {
            case self::PROCESSING:
                $class = 'badge-default';
                $text = '处理中';
                break;
            case self::APPLY_SUCC:
                $class = 'badge-success';
                $text = '申请成功';
                break;
            case self::APPLY_FAILED:
                $class = 'badge-danger';
                $text = '申请失败';
                break;
        }

        return "<span class=\"badge $class\">$text</span>";
    }
}