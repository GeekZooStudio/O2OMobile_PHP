<?php
use \Client;

class Message extends Eloquent {

    const SYSTEM      = 1; //系统消息
    const ORDER       = 2; //订单消息
    const OTHER       = 3; //其它个人消息
    const WITHDRAW    = 4; //提现

    const STATUS_OK       = 1; //已推送
    const STATUS_UNPUSHED = 0; //未推送

    const IS_READED       = 1; //已读
    const IS_NOT_READED   = 0; //未读

    protected $table      = 'message';
    public  $timestamps   = true;
    protected $softDelete = true;
    protected $guarded    = array();

    /**
     * 关联用户
     *
     * @return Builder
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    /**
     * 格式化成API所需要的样式
     *
     * @return array
     */
    public function formatToApi()
    {
        $return_arr = array(
            "id"         => $this->id,
            "type"       => $this->type,
            "url"        => $this->url,
            "content"    => $this->content,
            "is_readed"  => $this->is_readed,
            "created_at" => apiTime($this->created_at),
        );
        if ($this->type != self::SYSTEM) {
            $return_arr['user'] = $this->user->simpleFormatToApi();
            $return_arr['order_id'] = $this->order_id;
        }

        return $return_arr;
    }



    /**
     * 创建消息，静态方法
     *
     * @param integer $deviceType
     * @return array
     */
    public static function createMessage($msg, $is_push=false)
    {
        if (!is_array($msg) || empty($msg['type']) || empty($msg['content'])) {
            return false;
        }

        $message = new Message;
        $message->content     = $msg['content'];
        $message->type        = $msg['type'];
        if (isset($msg['user_id'])) {
            $message->user_id = $msg['user_id'];
        }
        if (isset($msg['order_id'])) {
            $message->order_id = $msg['order_id'];
        }
        if (isset($msg['is_readed'])) {
            $message->is_readed = $msg['is_readed'];
        }
        if ($is_push) {
            $message->is_pushed = self::STATUS_OK;
        }
        $message->save();
        return $message;  
    }

    /**
     * 状态转换为html
     *
     * @return string
     */
    public function statusToHtml()
    {
        switch ($this->is_pushed) {
            case self::STATUS_OK:
                $class = 'badge-success';
                $text = '已推送';
                break;
            default:
                $class = 'badge-default';
                $text = '未推送';
                break;
        }

        return "<span class=\"badge $class\">$text</span>";
    }
}