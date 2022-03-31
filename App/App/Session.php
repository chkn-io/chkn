<?php
namespace App\App;
class Session{
	static function put($name,$value){
		$_SESSION[$name] = $value;
	}

	static function get($name){
		return $_SESSION[$name];
	}

	static function check($name){
		if(isset($_SESSION[$name])){
			return true;
		}else{
			return false;
		}
	}

	static function clear($name){
		unset($_SESSION[$name]);
	}

}