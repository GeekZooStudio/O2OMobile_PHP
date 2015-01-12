<?php

class Client extends Eloquent {

    const PUSH_SWITCH_ON  = 1; //推送开关：开
    const PUSH_SWITCH_OFF = 0;//推送开关：关

    //设备
    const TYPE_IOS       = 'ios';     //ios
    const TYPE_AOS       = 'android'; //安卓


    protected $table    = 'client';
    public  $timestamps = true;
    protected $guarded  = array();

    public function user()
    {
        return $this->belongsTo('User')->withTrashed();
    }

    /**
     * 获取IOS设备
     *
     * @return Builder
     */
    public function scopeIos()
    {
        return $this->where('client_type', self::TYPE_IOS);
    }

    /**
     * 获取AOS设备
     *
     * @return Builder
     */
    public function scopeAos()
    {
        return $this->where('client_type', self::TYPE_AOS);
    }
}