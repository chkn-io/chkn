<?php

use App\Route\Router;

require_once("App/Exception/Exception.php");
require_once("App/Bootstrap/autoload.php");

$router = new Router();
$router->Url_Controller();


