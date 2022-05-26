<?php
namespace App\Controller;

use App\App\App_Controller;
use App\Helpers\Helper;
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
		$data = Helper::defaults();
		$this->assign('chkn:body',$data);
	}


	function show(){
		if(isset($_SESSION["CSRF"])){
			$this->assign('form:csrf','<input type="hidden" name="CSRFToken" value="'.$_SESSION["CSRF"].'">');
        	$this->assign('chkn:csrf',$_SESSION["CSRF"]);
		}
		
		$this->dispose();
	}

    function chknError(){
        $this->error->error_page();
		exit;
    }

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