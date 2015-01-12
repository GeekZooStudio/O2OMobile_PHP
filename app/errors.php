<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

//以下内容只在产品环境下有效
$error = function(Exception $exception, $code = 500)
{
    Log::error($exception);
    if (App::runningInConsole() || Request::is('api/*')) {
        return Response::json(array(
                                'succeed'    => 0,
                                'error_code' => $code,
                                'error_desc' => '处理失败',
                               ));
    } else {
        return View::make($code);
    }
};

// 一般错误
App::error(function($exception) use ($error){
    return $error($exception, 500);
});

// 404
App::missing(function($exception) use ($error){
    return $error($exception, 404);
});

// 服务器内部错误
App::fatal(function($exception) use ($error){
    return $error($exception, 500);
});

//模型未找到
App::error(function(ModelNotFoundException $exception) use ($error){
    return $error($exception, 404);
});