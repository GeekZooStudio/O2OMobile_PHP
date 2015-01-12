<?php
/**
 * 各种验证规则 
 */

return array(

    /**
     * 后台添加服务
     */
    'service' => array(
        'name' => 'required|min:2,max:8',
        'imgurl'  => 'required|url',
    ),

    /**
     * 后台添加服务
     */
    'serviceChild' => array(
        'name' => 'required|min:2,max:8',
    ),


    /**
     * 后台添加认证
     */
    'certify' => array(
        'name' => 'required',
    ),

    /**
     * 后台创建权限组
     */
    'permission-group-create' => array(
        'name' => 'required',
        //'permission' => 'required',
    ),

    /**
     * 后台创建管理员
     */
    'permission-user-create' => array(
        'username' => 'required',
        'group_id' => 'required|numeric',
        'password' => 'required|min:6,max:16',
        'rePassword' => 'required|same:password',
    ),

    /**
     * 后台创建消息
     */
    'message-create' => array(
        'message' => 'required',
        'action'  => 'required|numeric'
    ),
);