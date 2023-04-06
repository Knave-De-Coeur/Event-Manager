<?php
namespace app;

include 'src/utils/database.php';
include 'src/utils/cache.php';
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use src\utils\database as db;
use src\utils\cache as cache;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo $_ENV['DB_HOST'] . "\n";

$dbConnection = (new db\database())->getConnection();

$cacheConn = (new cache\cache())->getConnection();
echo $cacheConn . "\n";