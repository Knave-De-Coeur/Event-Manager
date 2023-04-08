<?php
namespace app;

require_once __DIR__. '/src/utils/database.php';
require_once __DIR__. '/src/controllers/CityController.php';
require_once __DIR__. '/src/controllers/CategoryController.php';
//include 'src/utils/cache.php';
require 'vendor/autoload.php';
//
use Dotenv\Dotenv;
use src\controllers\CategoryController\CategoryController;
use src\controllers\CityController\CityController;
use src\utils\database as db;
//use src\utils\cache as cache;
//

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = (new db\Database());
//
//$cacheConn = (new cache\cache())->getConnection();
//echo $cacheConn . "\n";



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];

$id = null;
if (isset($uri[2])) {
    $id = (int) $uri[2];
}


$controller = null;

if ($uri[1] == "city" || $uri[1] == "cities") {
    $controller = new CityController($db, $requestMethod, $id);
} else if ($uri[1] == "category" || $uri[1] == "categories")   {
    $controller = new CategoryController($db, $requestMethod, $id);
} else {
    echo "TODO some message";
}

$controller->processRequest();

