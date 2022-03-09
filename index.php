<?php 


namespace app;
/*header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: *");*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include routing from library
require_once "libraries/Routing.php";
use app\library\Routing as routing; 

\session_start();

//initialize app
$app = new routing();

$app->route('/', function ($parts,$request,$headers) {
    ob_start();
    include("web/index.php");
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
});

$app->route('/api', function ($parts,$request,$headers) {
  
    header('Content-Type: application/json');
    header("HTTP/1.1 200 OK");
    //return \json_encode($controller->login($parts,$request));
    return 'falan boyle gibi';
});



$app->route('/api/request/*', function ($parts,$request,$headers) {
    require_once "controllers/System.php";
    $controller = new controllers\System($headers); 
    //we are in request route
    //this route is for only system requests
    //this why we will wait return from system controller
    header('Content-Type: application/json');
    header("HTTP/1.1 200 OK");
    return \json_encode($controller->handleRequest($parts,$request));
});

//client huge data requests
$app->route('/api/table/*', function ($parts,$request,$headers) {
    require_once "controllers/System.php";
    $controller = new controllers\System($headers); 
    //we are in request route
    //this route is for only system requests
    //this why we will wait return from system controller
    header('Content-Type: application/json');
    header("HTTP/1.1 200 OK");
    return \json_encode($controller->handleTableRequest($parts,$request));
});

//client query data requests
$app->route('/api/query/*', function ($parts,$request,$headers) {
    require_once "controllers/System.php";
    $controller = new controllers\System($headers); 
    //we are in request route
    //this route is for only system requests
    //this why we will wait return from system controller
    header('Content-Type: application/json');
    header("HTTP/1.1 200 OK");
    return \json_encode($controller->handleQueryRequest($parts,$request));
});

$app->route('/api/login', function ($parts,$request,$headers) {
    /*require_once "controllers/System.php";
    $controller = new controllers\System($headers); 
    //we are in request route
    //this route is for only system requests
    //this why we will wait return from system controller*/
    header('Content-Type: application/json');
    header("HTTP/1.1 200 OK");
    //return \json_encode($controller->login($parts,$request));
    return 'falan boyle gibi';
});

$app->route('/err404', function () {
    header("HTTP/1.0 404 Not Found");
    return "Not Available";
});

$action = $_SERVER['REQUEST_URI'];
$app->dispatch($action);
