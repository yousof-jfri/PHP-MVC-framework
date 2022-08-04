<?php

require '../vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler('Core\Errors::errorHandler'); 
set_exception_handler('Core\Errors::exceptionHandler');

$router = require("../App/Router.php");

$url = $_SERVER["QUERY_STRING"];

$router->dispatch($url);
