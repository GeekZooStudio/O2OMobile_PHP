<?php

return array(
    /**
     * 需要登录才能访问的API
     */
    'api_required_login' => array(
        'user/info',
        'user/change-profile',
        'user/change-password',
        'user/change-avatar',
        'user/certify',
        'user/balance',
        'user/invite-code',
        'comment/send',
        'comment/list',
        'message/unread-count',
        'message/list',
        'message/read',
        'order/publish',
        'order/info',
        'order/accept',
        'order/work-done',
        'order/pay',
        'order/confirm-pay',
        'order/history',
        'orderlist/published',
        'orderlist/received',
        'myservice/list',
        'myservice/modify',
        'Withdraw/list',
        'Withdraw/money',
    ), 
);