<?php

class Certify extends Eloquent {

    protected $table        = 'certify';
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
            'name'               => $this->name,
            // 'ename'              => $this->ename,  
        );
    }

    /**
     * 获取认证信息
     *
     * @return object
     */
    public static function getInfo($id, $key='name'){
        if(!$id){
            return '<font color="grey">无此认证</font>';
        }
        $certify = Certify::find($id);
        if(empty($certify)){
            return '<font color="grey">无此认证</font>';
        }
        return $certify->$key;
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