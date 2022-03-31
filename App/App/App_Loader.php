<?php

namespace App\App;
use App\App\App;
use http\Defaults\chknError;


class App_Loader{
	protected $controller;
	protected $_url;
	public $session = array();
	//error handler function


    protected function load_app(){
    	if(file_exists('App/App/App.php')){
	      	include 'App/App/App.php';
	    }

    	$app = new App;

	    if(file_exists('App/App/App_Controller.php')){
	      	include 'App/App/App_Controller.php';
	    }
	    if(SESSION == 1){
	    	if(file_exists('App/App/Session.php')){
		      	include 'App/App/Session.php';
		    }
	    }
	    
	    if(QUERY_BUILDER == 1){
	    	if(file_exists('App/App/App_Model.php')){
		      	include 'App/App/App_Model.php';
		    }

			
	    }

	    if(file_exists('App/App/Request.php')){
	      	include 'App/App/Request.php';
	    }
    }

    /**
     * Loads all the library
     * DOMPDF - Generates and Converts HTML into PDF Document
     * PHPExcel - Generates an Excel File
     * ReCaptcha - Creates a picture captcha for form security
     */
	protected function load_library(){
		if(QUERY_BUILDER == 1){
			if(file_exists('App/Model/Model.php')){
		      include 'App/Model/Model.php';
		    }

			if(file_exists('App/Database/DB.php')){
				include 'App/Database/DB.php';
			}
		}
      
      if(file_exists('App/View/View.php')){
      	include 'App/View/View.php';
      }
      if(file_exists('App/Controller/Controller.php')){
      	include 'App/Controller/Controller.php';
      }
      if(file_exists('App/Controller/Console.php')){
      	include 'App/Controller/Console.php';
      }
      if(file_exists('App/Controller/Auth.php')){
      	include 'App/Controller/Auth.php';
      }
    
   		 if(CSRF == 1){
	    	if(file_exists('App/App/CSRFToken.php')){
		      	include 'App/App/CSRFToken.php';
		    }
	    }
      
      if(MODULE == 1){
	      foreach(glob("http/Module/*.php") as $filename){
			include $filename;
     	  }
      }

      if(file_exists('App/Template/CHKNTemplate.php')){
      	include 'App/Template/CHKNTemplate.php';
      }

      $ex = explode(";",CHKN_ASSETS);
      foreach($ex as $value){
      	if(file_exists('App/Assets/'.$value)){
      		if($value != ""){
      			include 'App/Assets/'.$value;
      		}
      	}
      }
    }
    /**
     * Load the global_helper class
     */
	protected function load_helper(){
        if(file_exists('App/Helpers/helper.php')){
	      	include 'App/Helpers/helper.php';
	     }

	     if(ENCRYPTION == 1){
	     	if(file_exists('App/Helpers/encryption.php')){
		      	include 'App/Helpers/encryption.php';
		     }
	     }
	    
	     if(UPLOAD == 1){
	     	if(file_exists('App/Helpers/upload.php')){
		      	include 'App/Helpers/upload.php';
		    }
	     }
	     
	     if(DOWNLOAD == 1){
	     	if(file_exists('App/Helpers/download.php')){
		      	include 'App/Helpers/download.php';
		     }	
	     }
	     if(DEFAULTS == 1){
	     	if(file_exists('App/Helpers/default.php')){
		      	include 'App/Helpers/default.php';
		     }
	     }
	     
	     
	     
	}
    /**
     * @param $controller
     * Loads a controller which name is base on the value passed by this function
     */
	protected function load_page_controller($controller){
		if(file_exists('http/Controllers/'.$controller.'.php')){
			require_once('http/Controllers/'.$controller.'.php');
		}
	}
    /**
     * Loads all the default pages of the Framework
     * index - Load the default class that has the highest priority
     * error - Load the error class that notify the user that there is problem accessing a specific class or method or page
     * maintenance - Load the maintenance class that notify the user that the accessed page is under construction or maintenance
     */
	protected function load_default(){
		if(file_exists('http/Controllers/index.php')){
			require_once('http/Controllers/index.php');
		}
		if(PAGE_NOT_FOUND == 1){
			if(file_exists('http/Defaults/Error.php')){
				require_once('http/Defaults/Error.php');
			}
		}
		
		if(MAINTENANCE_CLASS == 1){
			if(file_exists('http/Defaults/Maintenance.php')){
				require_once('http/Defaults/Maintenance.php');
			}
		}
		
	}

	protected function chknError(){
		if(PAGE_NOT_FOUND == 1){
			$error = new chknError();
			$error->error_page();
		}
		
	}
	
	protected function maintenance(){
		if(MAINTENANCE_CLASS == 1){
			$maintenance = new maintenance();
			$maintenance->maintenance_page();
		}
	}

	protected function collect($value = "",$status=""){
		if(isset($_SESSION["console_collection"])){
			$count = count($_SESSION["console_collection"]);
			$_SESSION["console_collection"][$count]["message"] = $value;
			$_SESSION["console_trigger"] = 1;
			if($value == "Initialized Controller"){
				$_SESSION["console_collection"][$count]["message"] = "Initialized Index Controller";
			}
			$_SESSION["console_collection"][$count]["status"] = $status;
		}else{
			$_SESSION["console_collection"][0]["message"] = $value;
			$_SESSION["console_collection"][0]["status"] = $status;
			$_SESSION["console_trigger"] = 1;
			if($value == "Initialized Controller"){
				$_SESSION["console_collection"][$count]["message"] = "Initialized Index Controller";
			}

		}
		
	}

}