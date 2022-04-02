<?php
namespace App\Route;
use App\App\Request;
use App\App\Loader;
use App\App\CSRFToken;


class Router extends Loader{
	protected $controller;
	protected $_url;
	public $session = array();
	//error handler function

	public function Url_Controller(){
		

		
		$url = $_SERVER[ 'REQUEST_URI' ];
		
		$_REQUEST["url"] = $url;
		
		$url = trim($url, '/');
		$url = filter_var($url, FILTER_SANITIZE_URL);

		if(LOCAL == 1){
			 $url = isset($_GET['url']) ? $_GET['url'] : null;
		}

		
		$this->_url = explode('/', $url);
		
		if(LOCAL == 1){
			if($this->_url[0] == ""){
				$this->url[0] = "index";
			}
		}


		$this->controller =  $this->_url[0].'Controller';
		$this->load_page_controller($this->controller);
		
		if($this->controller == "Controller"){
			$this->controller = "index";
		}

		if(class_exists("http\Controllers\\".$this->controller)){
			if(CSRF == 1){
				if(isset($_REQUEST["CSRFToken"])){
					$t = CSRFToken::validator($_REQUEST["CSRFToken"]);
					if($t != 1){
						$this->invalid_request();
					}
				}else{
					if(count($_REQUEST) > 2){
						CSRFToken::init();
						$this->invalid_request();
					}else{
						CSRFToken::init();
					}
				}
			}

			$r = $_REQUEST;
			if($_FILES){
				$f = $_FILES;
			}else{
				$f = "";
			}

			$maintenance = explode(",",MAINTENANCE);
			if(MAINTENANCE_CLASS == 1){
				for($x=0;$x<count($maintenance);$x++){
					if(trim($maintenance[$x]) == trim($this->controller)){
						$this->maintenance();
						exit;
					}
				}
			}
		
			$namespace = '\http\Controllers\\';
			$class = $namespace.$this->controller;
			$page = new $class;
			$url_count = count($this->_url);
			

			if($url_count == 1){	
				if($this->_url[0] == ""){
					$page = new $class;
					$page->index_page(new Request($r,$f));
				}else{
				
					if(method_exists($class,$this->_url[0])){
						if(strlen(APPLICATION_KEY) != 43){
							echo "APPLICATION KEY Not Yet Updated";
						}else{
							$page_url = $this->_url[0];
							$post = array();
							$request["message"] = 'CHKN Framework Request';
							$page->$page_url(new Request($r,$f));
						}
					}else{
						$this->chknError();
					}
				}
			}elseif($url_count == 2){
				$page_url = $this->_url[1];
				$post = array();
				$request["message"] = 'CHKN Framework Request';

				if(method_exists($class,$this->_url[1])){
					if(strlen(APPLICATION_KEY) != 43){
						echo "APPLICATION KEY Not Yet Updated";
					}else{
						$page->$page_url(new Request($r,$f));
					}
				}else{
					$this->chknError();
				}
			}elseif($url_count > 2){
				if($this->_url[1] == "[path]"){
					$page_url = $this->_url[3];
					$cont = $this->_url[2].'Controller';
					$this->load_page_controller($cont);
					$page = new $cont;
					$count = 4;
				}else{
					if($this->_url[2] == "[path]"){
						$page_url = $this->_url[4];
						$cont = $this->_url[3].'Controller';
						$this->load_page_controller($cont);
						$page = new $cont;
						$count = 5;		
					}else{
						$page_url = $this->_url[1];
						$count = 2;
						$cont = $this->controller;
					}
				}
				
				$get = array();
				$y=0;
				for($x=$count;$x<$url_count;$x++){
					$get[$y] = $this->_url[$x];
					$y++;
				}
				
				$request["get"] = $get;
				$post = array();
				$request["message"] = 'CHKN Framework Request';
				if(method_exists($namespace.$cont,$page_url)){
					if(strlen(APPLICATION_KEY) != 43){
						echo "APPLICATION KEY Not Yet Updated";
					}else{
						$page->$page_url(new Request($r,$f));
					}
				}else{
					$this->chknError();

				}
			}
		}else{
		/**
		* Load error if no class found
		*/
			$this->chknError();
		}
	}

	
	private function invalid_request(){
		header("HTTP/1.0 401");
		$file = file_get_contents("view/defaults/invalid_request.tpl");
		echo $file;
		exit;
	}

	
}


