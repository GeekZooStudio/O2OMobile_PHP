<?php

use Illuminate\Support\Facades\Redirect;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//admin
Route::controller('admin/auth', '\Backend\AuthController');
Route::group(array('prefix' => '/admin', 'before' => 'auth.admin'), function(){
    Route::get('/', '\Backend\HomeController@index');
    Route::controller('order', '\Backend\OrderController');  //订单
    Route::controller('user', '\Backend\UserController');  //用户管理
    Route::controller('service', '\Backend\ServiceController');  //服务管理
    Route::controller('myservice', '\Backend\MyserviceController');  //我的服务
    Route::controller('certify', '\Backend\CertifyController');  //认证管理
    Route::controller('mycertify', '\Backend\MycertifyController');  //我的认证
    Route::controller('message', '\Backend\MessageController');  //消息管理
    Route::controller('permission', '\Backend\PermissionController');  //权限管理
    Route::controller('withdraw', '\Backend\WithdrawController');  //提现
    Route::controller('log',       '\Backend\LogController');  //后台操作日志
    Route::controller('system',    '\Backend\SystemController');  //系统
    Route::controller('report', '\Backend\ReportController');  //举报 投诉
    Route::controller('feedback', '\Backend\FeedbackController');  //意见反馈
});

// //API
Route::group(array('prefix' => '/api'), function(){
	Route::controller('user', '\Rest\UserController');		//用户
	Route::controller('order', '\Rest\OrderController');	//订单操作相关
    Route::controller('orderlist', '\Rest\OrderlistController');  //订单列表
    Route::controller('myservice', '\Rest\MyserviceController');  //我的服务
    Route::controller('servicetype', '\Rest\ServicetypeController');  //服务分类
    Route::controller('comment', '\Rest\CommentController');  //评论
    Route::controller('report', '\Rest\ReportController');  //举报 投诉
    Route::controller('message', '\Rest\MessageController');  //消息
    Route::controller('location', '\Rest\LocationController');  //位置
    Route::controller('withdraw', '\Rest\WithdrawController');  //提现
    Route::controller('servicecategory', '\Rest\ServicecategoryController');  //服务类目
    Route::controller('feedback', '\Rest\FeedbackController');  //意见反馈
});

//front
Route::get('/', '\Frontend\IndexController@index');