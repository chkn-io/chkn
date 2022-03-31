<?php
namespace App\Route;
use App\App\Request;
use App\App\App_Loader;
use App\App\CSRFToken;
use App\Controller\chknConsole;
/**
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 *
 *
 * Class Loader
 * This class controls the url set by the user
 * It will divide the url into 4 parts (host/class_name/function_name/parameter)
 * It will load all the Libraries needed on a specific class
 * This class also holds the maintenance class that will be loaded once a class who wish to execute is defined as maintenance(lib/Settings.php)
 * This class also holds the error class that will be loaded once an error on loading class,method and pages occur.
 *
 * Note: This class must be left off-hand. This class is the core class of this framework. Any changes on this class will cause fatal error.
 */

class Router extends App_Loader{
	protected $controller;
	protected $_url;
	public $session = array();
	//error handler function

	public function Url_Controller(){
		session_start();
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/');
		$url = filter_var($url, FILTER_SANITIZE_URL);
		$this->load_app();
		$this->load_library();
		$this->_url = explode('/', $url);
		$this->controller =  $this->_url[0].'Controller';
		$this->load_page_controller($this->controller);
		$this->load_default();
		$this->load_helper();

		
		if($this->_url[0] == "console"){
			if(CONSOLE == 1){
				$console = new CHKNConsole;
				if(isset($this->_url[1])){
					$page = $this->_url[1];
					if(isset($this->_url[2])){
						$console->$page($this->_url[2]);
					}else{
						$console->$page();
					}
				}else{
					unset($_SESSION["console_collection"]);
					$this->collect("Welcome to CHKN Console.","success");
					$_SESSION["console_trigger"] = 1;
					$console->console();
				}
			}else{
				$this->chknError();
			}
			
		}else{

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
				if($this->_url[0] != ""){
					$this->collect("Initialized `".$this->_url[0]."Controller`","success");
				}else{
					$this->collect("Initialized `index`","success");
				}
				if($url_count == 1){	
					if($this->_url[0] == ""){
						$page = new $class;
						$page->index_page(new Request($r,$f));
					}else{
						if(method_exists($class,$this->_url[0])){
							if(strlen(APPLICATION_KEY) != 43){
								echo "APPLICATION KEY Not Yet Updated";
								$this->collect("Application KEY not yet updated","error");
							}else{
								$this->collect("Index loaded successfully","success");
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
							$this->collect("Application KEY not yet updated","error");
						}else{

							$this->collect("Method `".$this->_url[1]."` on Class `".$this->_url[0]."Controller` loaded successfully","success");
								$page->$page_url(new Request($r,$f));
						}
					}else{
						$this->collect("Undefined method `".$this->_url[1]."` on `".$this->_url[0]."Controller`","error");
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
							$this->collect("Application KEY not yet updated","error");
						}else{

							$this->collect("Method `".$this->_url[1]."` on Class `".$this->_url[0]."Controller` loaded successfully","success");
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
				if($this->controller != "publicController"){
					$this->collect("`".$this->_url[0]."Controller` Not Found! Check the Controller's File or Class Name.","error");
				}

			}
		}
	}

	private function invalid_request(){
		header("HTTP/1.0 401");
		$file = file_get_contents("view/defaults/invalid_request.tpl");
		echo $file;
		exit;
	}
}
