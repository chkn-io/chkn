<?php
namespace App\Controller;

use App\App\App_Controller;
use App\Helpers\Helper;
use App\App\CSRFToken;


class Controller extends App_Controller{
	
    

    function maintenance(){
    	$this->maintenance->maintenance_page();
		exit;
    }

    function variable($_searchString, $_replacedString){
    	$this->pass_variable($_searchString, $_replacedString);
    }

    function array_var($key, $array){
    	$this->pass_array_var($key, $array);
    }
	
	function forceCSRF(){
		if(isset($_SESSION["CSRFToken"])){
			if(isset($_REQUEST["CSRFToken"])){
				$t = CSRFToken::validator($_REQUEST["CSRFToken"]);
				if($t != 1){
					$this->invalid_request();
					exit;
				}
			}else{
				$this->invalid_request();
				exit;
			}
		}else{			
			$this->invalid_request();
			exit;
		}
	}

}