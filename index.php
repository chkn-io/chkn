<?php

use App\Route\Router;

require_once("App/Exception/Exception.php");
require_once("App/Bootstrap/autoload.php");

$loader = new Router();
$loader->Url_Controller();


