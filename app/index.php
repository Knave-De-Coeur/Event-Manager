<?php
namespace app;

require_once __DIR__. '/src/utils/database.php';
require_once __DIR__. '/src/controllers/CityController.php';
require_once __DIR__. '/src/controllers/CategoryController.php';
require_once __DIR__. '/src/controllers/EventController.php';
//include 'src/utils/cache.php';
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use src\controllers\CategoryController as CategoryController;
use src\controllers\CityController as CityController;
use src\controllers\EventController as EventController;
use src\models\Response as Response;
use src\utils\database as db;
use src\models\City as City;
use src\models\Category as Category;
//use src\utils\cache as cache;

header("Content-Type: application/json; charset=UTF-8");
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
} else {
    header("Access-Control-Allow-Origin: *");
}

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 3600');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
} else {
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = (new db());
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
    $controller = new CityController($db, $requestMethod, $id, null);
} else if ($uri[1] == "category" || $uri[1] == "categories")   {
    $controller = new CategoryController($db, $requestMethod, $id, null);
} else if ($uri[1] == "event" || $uri[1] == "events") {
    $city = new City($db);
    $category = new Category($db);
    $controller = new EventController($db, $requestMethod, $id, $city, $category);
}else {
    echo "TODO some message";
}

try {
    $controller->processRequest();
} catch(\Exception $e) {
    $response = new Response(
        code: 500,
        msg: "something went wrong",
        body: $e->getMessage(),
        errorMsg: null,
    );
    $controller->processResponse($response);
}

