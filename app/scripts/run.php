<?php
/* 运行PHP值守进程
 * 
 * @author Joy <anzhengchao@gmail.com>
 *
 */
// $pid  = posix_getpid(); //取得主进程ID
// $user = posix_getlogin(); //取得用户名
// $pid = pcntl_fork(); //创建子进程
// echo $pid;exit;
date_default_timezone_set('Asia/Chongqing');

//php 可执行文件
$phpbin = 'php';

if (substr(php_sapi_name(), 0, 3) !== 'cli') {
    die("hack");
}
if (empty($argv[2])) {
    echo "参数错误\n";exit;
}

$command = basename($argv[2]);

$max_num_workers = empty($argv[3]) ? 1 : intval($argv[3]);
if ($max_num_workers < 1 || $max_num_workers > 10) {
    echo "最小进程数1 最大进程数10\n";exit;
}

$exec = "$phpbin ".dirname(dirname(__DIR__))."/artisan $command";

// 运行数量计数器
$run_num_workers = 0;
 
 for($i = 1; $i <= $max_num_workers; $i++) {//note $i 活着的子进程数
    $pid = pcntl_fork();//note 创建子进程
    if($pid == -1) {//note 创建子进程失败
        // TODO 日志
    } else if ($pid > 0) {
        if ($i >= $max_num_workers) {            //note 如果子进程数已达上限 暂停
            pcntl_wait($status, WUNTRACED); //note WUNTRACED 子进程已经退出并且其状态未报告时返回
            $i--;
        }
    } else {
        // function shutdown_func() {
        //  unset($_ENV);
        // }
        // register_shutdown_function('shutdown_func');
        exec($exec);
        exit();
    }
}