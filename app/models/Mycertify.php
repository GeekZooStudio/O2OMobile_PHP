<?php

class Mycertify extends Eloquent {

    protected $table        = 'my_certify';
    public    $timestamps   = false;
    protected $guarded      = array();

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
            'user_id'               => $this->user_id,
            'certify_id'              => $this->certify_id,  
        );
    }

    // /**
    //  * 用户关系
    //  * 订单状态操作者
    //  * @return Object
    //  */
    // public function user()
    // {
    //     return $this->belongsTo('User');//user_id
    // }
    

    /**
     * 认证转换为html
     *
     * @return string
     */
    public function certifyToHtml()
    {
        if(!$this->certify_id){
            return '<font color="grey">无</font>';
        }
        $certifys = Certify::find($this->certify_id);
        if(empty($certifys)){
            return '<font color="grey">无此认证</font>';
        }
        $class = 'badge-default';
        $text = $certifys->name;

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