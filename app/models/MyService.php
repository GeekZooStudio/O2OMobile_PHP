<?php

class MyService extends Eloquent {

    protected $table        = 'my_services';
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
        return array(
                'id'                 => $this->id,
                'price'              => $this->price,
                'service_type'       => $this->servicess->formatToApi(),          //
               );

        // return $this->user;
    }



    /**
     * 服务
     *
     * @return Object
     */
    public function servicess()
    {
        // print_r($this->belongsTo('Service', 'id'));exit;
        return $this->belongsTo('Service', 'services_id');//user_id
    }



    /**
     * 我的服务转换为html
     *
     * @return string
     */
    public function myserviceToHtml()
    {
        if(!$this->services_id){
            return '<font color="grey">无</font>';
        }
        $services = Service::find($this->services_id);
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