<?php

class Withdraw extends Eloquent {

    protected $table        = 'withdraw';
    public    $timestamps   = true;
    protected $softDelete   = true;
    protected $guarded      = array();
    
    const PROCESSING             = 0; //处理中
    const WITHDRAW_SUCC          = 1; //提现成功
    const WITHDRAW_FAILED        = 2; //提现失败

    const CATE_WITHDRAW      = 0; //提现
    const CATE_REG           = 1; //注册奖励
    const CATE_INVITE        = 2; //邀请用户奖励
    const CATE_TICHENG       = 3; //提成

    /**
     * 获取API格式数据  
     *
     * @return array
     */
    public function formatToApi()
    {

        return array(
                'id'                 => $this->id,
                'amount'             => $this->amount,
                'state'              => $this->state,
                'created_at'         => apiTime($this->created_at)
               );
    }

    /**
     * 余额变更日志，推送
     *
     * @return boolean
     */
    public static function balanceLog($category, $user_id, $amount, $note='', $is_push=true)
    {
        if (empty($user_id) || empty($amount)) {
            return false;
        }
        //记录日志
        $withdraw = new Withdraw;
        $withdraw->category = $category;
        $withdraw->user_id = $user_id;
        $withdraw->amount = $amount;
        $withdraw->note = $note;
        $withdraw->state = 1;
        if ($withdraw->save()) {
            $message = array(
                'user_id' => $withdraw->user_id,
                'type'    => Message::OTHER,
                'content' => $withdraw->note . ',金额:' . $withdraw->amount . '元',
            );

            Message::createMessage($message, $is_push);
        }
        return true;
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
        $class = 'badge-success';
        $text = $user->nickname;

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
            case self::WITHDRAW_SUCC:
                $class = 'badge-success';
                $text = '提现成功';
                break;
            case self::WITHDRAW_FAILED:
                $class = 'badge-danger';
                $text = '提现失败';
                break;
        }

        return "<span class=\"badge $class\">$text</span>";
    }
}