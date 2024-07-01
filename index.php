<?php

require_once realpath("vendor/autoload.php");
$config = require_once realpath('app/Configs/config.php');

use App\Controllers\Controller;


$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$controller = new Controller($config['api_key']);

if ($requestUri == '/' && $requestMethod == 'GET') {
    $controller->fetchData();
    
} else {
    header("HTTP/1.0 404 Not Found");
    exit;
}