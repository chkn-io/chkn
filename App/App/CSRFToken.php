<?php
namespace App\App;
class CSRFToken{
	public static function init(){
		$string = str_shuffle("abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP");
		$_SESSION["CSRFToken"] = $string;
		$_SESSION["CSRF"] = $string;
	}

	public static function validator($form = ""){
		if(isset($_SESSION["CSRFToken"])){
			if($form === $_SESSION["CSRFToken"]){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	
}
	