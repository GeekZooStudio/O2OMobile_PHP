<?php

use \Carbon\Carbon;

class ClientSession extends Eloquent {

    const TYPE_CATEGORY = 1;
    const TYPE_HOMEPAGE = 2;

    protected $table    = 'client_session';
    public  $timestamps = false;
    protected $guarded  = array();

    public function getDates()
    {
        return array('expired_at');
    }

    /**
     * 创建session
     *
     * @param integer $uid
     * 
     * @return ClientSession
     */
    public static function setSession($uid) 
    {
        $session = self::firstOrNew(array('user_id' => $uid));
        $session->session_id = "sess_".uniqid();
        $session->expired_at = Carbon::now()->addMonth();
        $session->save();

        return $session;
    }

    /**
     * 会话是否已经过期     
     *
     * @return boolean 
     */
    public function isExpired()
    {
        return Carbon::now() > $this->expired_at;
    }
}