<?php
namespace app;

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Exception;
use Src\Utils\Cache as cache;
use Src\Utils\Database as db;
use Src\Models\City as City;
use Src\Models\Category as Category;
use Src\controllers\CategoryController as CategoryController;
use Src\controllers\CityController as CityController;
use Src\controllers\EventController as EventController;
use Src\Models\Response as Response;
use stdClass;

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

$db = new db();
$cache = new Cache();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];

$id = null;
if (isset($uri[2])) {
    $id = (int) $uri[2];
}

if ($uri[1] == "city" || $uri[1] == "cities") {
    $controller = new CityController($db, $cache, $requestMethod, $id, null);
} elseif ($uri[1] == "category" || $uri[1] == "categories")   {
    $controller = new CategoryController($db, $cache, $requestMethod, $id, null);
} elseif ($uri[1] == "event" || $uri[1] == "events") {
    $city = new City($db);
    $category = new Category($db);
    $controller = new EventController($db, $cache, $requestMethod, $id, $city, $category);
} else {
    $controller = new EventController(null, null, null, null, null, null);
    $response = new Response(
        404,
        "something went wrong",
        new stdClass(),
        "page not found."
    );
    $controller->processResponse($response);
    exit(0);
}

try {
    $controller->processRequest();
} catch(Exception $e) {
    $response = new Response(
        500,
        "something went wrong",
        $e->getMessage(),
        null
    );
    $controller->processResponse($response);
}

