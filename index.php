<?php

require_once realpath("vendor/autoload.php");

use Dotenv\Dotenv;
use App\Controllers\Controller;

// setup environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$controller = new Controller();

if ($requestUri == '/' && $requestMethod == 'GET') {
    $controller->fetchData();
    
} else {
    header("HTTP/1.0 404 Not Found");
    exit;
}