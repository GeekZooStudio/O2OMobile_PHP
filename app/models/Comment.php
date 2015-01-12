<?php

class Comment extends Eloquent {
    const CATEGORY_ORDER = 0; //订单
    const CATEGORY_OTHER = 1; //其它

    protected $table    = 'comments';
    public  $timestamps = true;
    protected $softDelete   = true;
    protected $guarded  = array();

    /**
     * 格式化API格式
     *
     * @return array
     */
    public function formatToApi() {
        return array(
            'id' => $this->id,
            'user' => $this->sendUser->simpleFormatToApi(),
            'rank' => $this->rank,
            'content' => array('text' => $this->content),
            'created_at' => apiTime($this->created_at)
        );
    }


    /**
     * 获取评论
     *
     * @return Object
     */
    public static function getCommnets($o_id, $s_user, $key = 'content'){
        $contents = DB::table('comments')->where('o_id', $o_id)->where('s_user', $s_user)->lists($key);
        if(!empty($contents)){
            return $contents[0];
        }
        return '暂无';
    }


    /**
     * 发送者
     *
     * @return Object
     */
    public function sendUser()
    {
        return $this->belongsTo('User', 's_user');
    }

    /**
     * 发送者
     *
     * @return Object
     */
    public function receiveUser()
    {
        return $this->belongsTo('User', 'o_user');
    }
}