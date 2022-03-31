<?php
namespace App\Controller;

use App\App\App_Controller;
use App\Helpers\Helpers;
use App\App\CSRFToken;
use App\Helpers\defaults;


class Controller extends App_Controller{
	function template($temp = ''){
		$this->path('view/template/'.$temp.".tpl");
		$this->assign('DEFAULT_PATH',DEFAULT_URL);
	}

	function con_interface($temp = ''){
		$this->path('view/defaults/'.$temp.".tpl");
		$this->assign('DEFAULT_PATH',DEFAULT_URL);
	}

	function title($title = ''){
		$this->assign('chkn:title',$title);
	}

	function css($css = ''){
		$this->assign('chkn:style',$this->view->Html_Objects('css',
            $css
		));
	}

	function js($js = ''){
		$this->assign('chkn:script',$this->view->Html_Objects('js',
			$js
		));
	}

	function body($path = ''){
		$data = $this->view->Html_Objects('page',$path.".cvf");
		$this->assign('chkn:body',$data);
	}

	function chkn_status(){
		$data = Helpers::defaults();
		$this->assign('chkn:body',$data);
	}


	function show(){
		if(isset($_SESSION["CSRF"])){
			$this->assign('form:csrf','<input type="hidden" name="CSRFToken" value="'.$_SESSION["CSRF"].'">');
        	$this->assign('chkn:csrf',$_SESSION["CSRF"]);
		}
		
		$this->dispose();
	}

    function locate($url){
        header('location:'.DEFAULT_URL.$url);
    }

    function httpRequest($request){
    	$this->post = $request["post"];
    	$this->get = $request["get"];
    }

    function get($index){
    	return $this->get[$index];
    }

    function post($index,$position = 0){
    	return $this->post[$index][$position];

    }

    function response($info = [],$header=200){
    	header("HTTP/1.0 ".$header);
        header("Content-Type:application/json");
    	return json_encode($info);
    }

    function chknError(){
        $this->error->error_page();
    }

    function maintenance(){
    	$this->maintenance->maintenance_page();
    }

    function variable($_searchString, $_replacedString){
    	$this->pass_variable($_searchString, $_replacedString);
    }

    function array_var($key, $array){
    	$this->pass_array_var($key, $array);
    }

    function seal($value = ""){
    	$encrypt = Helper::encrypt($value);
    	return base64_encode($encrypt);
    }

    function rseal($value = ""){
    	$decode = base64_decode($value);
    	$decrypt = Helper::decrypt($decode);
    	return $decrypt;
    }

    function array_seal($array = array(),$field = ""){
    	for($x=0;$x<count($array);$x++){
    		$array[$x][$field] = $this->seal($array[$x][$field]);
    	}
    	return $array;
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