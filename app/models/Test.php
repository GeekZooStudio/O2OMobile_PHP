<?php

class Test extends Eloquent {

    protected $table    = 'test';
    public  $timestamps = false;
    protected $guarded  = array();

    // /**
    //  * 获取API格式数据  
    //  *
    //  * @return array
    //  */
    // public function formatToApi()
    // {
    //     return array(
    //         'id'        => $this->id,
    //         'user'      => $this->user_id,
    //         'order_id'  => $this->order_id,
    //         'content'   => array('text' => $this->content),
    //         'created_at'=> apiTime($this->created_at)
    //     );
    // }
}