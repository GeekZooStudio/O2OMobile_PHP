<?php
//左侧菜单配置
return array(
    /**
     * ------------------------
     * 控制面板首页
     * ------------------------
     */
    array(
        'name'       => '首页',
        'icon'       => 'entypo-gauge',
        'url'        => 'admin',
        'pattern'    => array('admin/'),
        'permission' => '*',
        'submenu'    => array(),               
    ),

    /**
     * ------------------------
     * 服务管理
     * ------------------------
     */
    array(
      'name'       => '服务管理',
      'pattern'    => array('admin/service/*'),
      'icon'       => 'entypo-rss',
      'url'        => 'admin/service/all',
      'permission' => 'service.*',
      'submenu'    => array(),               
    ),


    /**
     * ------------------------
     * 认证管理
     * ------------------------
     */
    array(
      'name'       => '认证管理',
      'pattern'    => array('admin/certify/*'),
      'icon'       => 'entypo-rss',
      'url'        => 'admin/certify/all',
      'permission' => 'certify.*',
      'submenu'    => array(),               
    ),


    /**
     * ------------------------
     * 用户管理
     * ------------------------
     */
    array(
        'name'     => '用户管理',
        'icon'     => 'entypo-user',
        'pattern' => array('admin/user/*'),
        'submenu' => array(
            array(
                'name'   => '所有用户',
                'url'    => 'admin/user/all',
                'permission' => 'user.all|user.new|user.edit|user.delete|user.certify|user.certify|user.services',
            ),
            array(
                'name'   => '自由人申请',
                'url'    => 'admin/user/freeman',
                'permission' => 'user.freeman|user.freemanok|user.freemanno',
            ),
            array(
                'name'   => '服务申请',
                'url'    => 'admin/user/applyservice',
                'permission' => 'user.applyservice|user.applyresult',
            ),
        ),
    ),


    /**
     * ------------------------
     * 权限管理
     * ------------------------
     */
    array(
        'name'       => '权限管理',
        'icon'       => 'entypo-lamp',
        'pattern'    => array('admin/permission/*'),
        'submenu'    => array(
            array(
                'name'   => '管理员',
                'url'    => 'admin/permission/user',
                'permission' => 'permission.user|permission.user-new|permission.user-delete|permission.user-edit|permission.user-create',
            ),
            array(
                'name'   => '权限组',
                'url'    => 'admin/permission/group',
                'permission' => 'permission.group|permission.group-new|permission.group-edit|permission.group-create|permission.group-delete',
            ),
        ),               
    ),



    /**
     * ------------------------
     * 订单列表
     * ------------------------
     */
    array(
        'name'       => '订单列表',
        'icon'       => 'entypo-clipboard',
        'url'        => 'admin/order',
        'pattern'    => array('admin/order/*'),
        'permission' => 'order.*',
        'submenu'    => array(),               
    ),

    /**
     * ------------------------
     * 提现列表
     * ------------------------
     */
    array(
        'name'       => '提现列表',
        'icon'       => 'entypo-publish',
        'url'        => 'admin/withdraw',
        'pattern'    => array('admin/withdraw/*'),
        'permission' => 'withdraw.*',
        'submenu'    => array(),               
    ),


    /**
     * ------------------------
     * 消息管理
     * ------------------------
     */
    array(
        'name'       => '消息推送',
        'icon'       => 'entypo-paper-plane',
        'url'        => 'admin/message',
        'pattern'    => array('admin/message/*'),
        'permission' => 'message.*',
        'submenu'    => array(),               
    ),


    /**
     * ------------------------
     * 意见反馈
     * ------------------------
     */
    array(
        'name'       => '意见反馈',
        'icon'       => 'entypo-phone',
        'url'        => 'admin/feedback',
        'pattern'    => array('admin/feedback/*'),
        'permission' => 'feedback.*',
        'submenu'    => array(),               
    ),


    /**
     * ------------------------
     * 投诉举报
     * ------------------------
     */
    array(
        'name'       => '投诉举报',
        'icon'       => 'entypo-thumbs-down',
        'url'        => 'admin/report',
        'pattern'    => array('admin/report/*'),
        'permission' => 'report.*',
        'submenu'    => array(),               
    ),

    
    /**
     * ------------------------
     * 系统管理
     * ------------------------
     */
    array(
        'name'     => '系统管理',
        'icon'     => 'entypo-cog',
        'pattern' => array('admin/system/*', 'admin/log/*'),
        'submenu' => array(
            array(
             'name'   => '开通城市 TODO',
             'url'    => 'admin',
             'permission' => 'system.activate-city',
            ),

            // admin/system/activate-city

            array(
             'name'   => '短消息设置 TODO',
             'url'    => 'admin',
             'permission' => 'system.msg',
            ),

            // admin/system/msg

            // array(
            //  'name'   => '数据备份',
            //  'url'    => 'admin/system/backup',
            //  'permission' => 'system.backup',
            // ),
            array(
             'name'   => '操作日志',
             'url'    => 'admin/log/all',
             'permission' => 'log.all',
            ),
        ),               
    ),
);
