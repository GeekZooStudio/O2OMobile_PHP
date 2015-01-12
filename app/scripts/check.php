<?php
include __DIR__ . '/base.php'; 
echo 99;
$stomp = new Stomp(Config::get('global.activemq_host'));
