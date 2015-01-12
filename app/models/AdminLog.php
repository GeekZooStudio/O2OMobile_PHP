<?php

use \Request;
use \Carbon\Carbon;

class AdminLog extends Eloquent {
    protected $table      = 'log';
    public  $timestamps   = true;
    protected $softDelete = true;
    protected $guarded    = array();
    

    /**
     * 获取用户
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 记录日志
     *
     * @param mixed  $objectId 
     * @param string $action   
     *
     * @example
     *
     * Admin::log($user->id, '创建用户');
     *
     * @return void
     */
    public static function log($objectId, $action)
    {
        if (!is_array($objectId)) {
            $objectId = array($objectId);
        }
        $objectId = join(',', $objectId);
        $data = array(
                 'user_id'   => Auth::user()->id,
                 'object_id' => $objectId,
                 'action'    => strip_tags($action),
                 'ip'        => Request::getClientIp(),
                );

        return static::create($data);
    }
}