<?php
require_once("App/Exception/catch.php");
error_reporting(E_ALL);
ini_set("display_errors", 0);
register_shutdown_function('fatalErrorShutdownHandler');
function fatalErrorShutdownHandler(){
	$last_error = error_get_last();
	$catch = new Catcher;
	$catch->errorHandler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
}
