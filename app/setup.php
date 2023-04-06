<?php
$redis = new Redis();
$redis->connect("redis");
$redis->auth('secret_redis_password');
$redis->select(0);

$dsn      = 'mysql:dbname=events;host=mysql';
$user     = 'alex';
$password = 'alexsecret';
$pdo      = new PDO($dsn, $user, $password);

//return [$redis, $pdo];