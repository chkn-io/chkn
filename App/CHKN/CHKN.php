<?php
namespace App\CHKN;
class CHKN{
    function __construct($argv){
        if(count($argv) <= 1){
            $this->response("Invalid CHKN Command","error");
        }else{
            switch($argv[1]){
                case "roast":
                    if(isset($argv[2])){
                        if(strpos($argv[2],"--port") !== false){
                            $port = str_replace("--port=","",$argv[2]);
                            $this->serve($port);
                        }else{
                            $this->response("Invalid CHKN Command","Error");
                        }
                    }else{
                        $this->serve(8000);
                    }
                break;
                case "key:generate":
                    $this->installKey();
                break;
                case "key":
                    $this->showKey();
                break;
                case "css:error":
                    if(isset($argv[2])){
                        // $ref = $argv[2] < 2 && $argv[2] >= 0 ? (bool)$argv[2] : $argv[2];
                        if($argv[2] !=0 && $argv[2] != 1){
                            $this->response("Invalid CHKN Command. CHKN_ERROR value must be 0 or 1.","Error");
                        }else{
                            $this->changeAppConfigValue("CSS_ERROR",$argv[2]);
                            $this->response("CSS_ERROR is successfully set to {$argv[2]}","Success");
                        }
                        
                    }else{
                        $this->response("Invalid CHKN Command","Error");
                    }
                    
                break;
                case "js:error":
                    if($argv[2] !=0 && $argv[2] != 1){
                        $this->response("Invalid CHKN Command. CHKN_ERROR value must be 0 or 1.","Error");
                    }else{
                        $this->changeAppConfigValue("JS_ERROR",$argv[2]);
                        $this->response("JS_ERROR is successfully set to {$argv[2]}","Success");
                    }
                break;

                case "create:controller":
                    if(isset($argv[2])){
                        $this->createController($argv[2]);
                    }else{
                        $this->response("Invalid CHKN Command. Undefined value for CREATE CONTROLLER.");
                    }
                break;

                case "styles":
                    $this->styles();
                break;
            }
        }
    }
    private function serve($port){
        shell_exec("php -S localhost:".$port);
    }

    private function installKey(){
        $encryption_key_256bit = base64_encode(openssl_random_pseudo_bytes(32));
        $this->changeAppConfigValue("APPLICATION_KEY",$encryption_key_256bit);
        $this->response("Application Key is successfully installed","Success");
    }

    private function showKey(){
		$path = "config/app.conf";
        $file = fopen($path,"r");
        $content = file_get_contents($path);
        while(!feof($file)){
            $string = fgets($file);
            if (stripos($string, "APPLICATION_KEY") !== false) {
                echo str_replace("APPLICATION_KEY=","",$string);
            }
        }
    }

    private function changeAppConfigValue($field,$value){
        $path = "config/app.conf";
        $file = fopen($path,"r");
        $content = file_get_contents($path);
        while(!feof($file)){
            $string = fgets($file);
            if (stripos($string, $field) !== false) {
                $content = str_replace($string,$field."=".$value."\r\n",$content);
            }
        }
        // echo $encryption_key_256bit;
        file_put_contents($path, $content);
    }

    private function response($message,$type){
        if($type == "Error"){
            $a = "\e[0;31m";
        }else if($type == "Success"){
            $a = "\e[0;32m";
        }
        echo $a.$type.":".$message."\e[0m\n";
    }

    private function createController($ctrler){
        $controller = str_replace(" ","",$ctrler);
		if($controller == ""){
            $this->response("Invalid CHKN Command. Undefined value for CREATE CONTROLLER.");
		}else{
			if(!file_exists("http/Controllers/".$controller."Controller.php")){
				$cont = fopen("http/Controllers/".$controller."Controller.php", "w");
				$content = '<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;
use App\Helpers\Helper;

class '.$controller.'Controller extends Controller{
	public function '.$controller.'(){
		//Call Controller Template
		$this->template("index");

		//Change Page Title
		$this->title("");

		//Page Style
		$this->css([
		]);

		//Page Script
		$this->js([]);

		//Page Content
		$this->body("'.$controller.'/index");

		//App Status
		//$this->chkn_status();

		$this->show();
	}
}

';
				
				fwrite($cont, $content);
				fclose($cont); 


				if (!file_exists('view/page/'.$controller)) {
				    mkdir('view/page/'.$controller, 0777, true);
				    if(!file_exists("view/page/".$controller."/index.cvf")){
				    	$page = fopen("view/page/".$controller."/index.cvf", "w");
				    	$pcontent = '
<div class="clearfix"></div>
	<div class="col-md-12">
		<h1 class="text-center">This is the index page of '.$controller.'Controller.</h1>
	</div>	
<hr>';


						fwrite($page, $pcontent);
						fclose($page); 
				    }
				}

                $this->response($controller."Controller.php successfully created","Success");
			}else{
				echo $this->response(["message"=>$controller."Controller.php is already exist","type"=>"error"],200);
                $this->response($controller."Controller.php is already exist","Error");
			}
		}
    }

    private function styles(){
        $css_lib = explode(',',CSS_LIBRARY);  
            $checker = 0;
            if($css_lib[0] != ""){
            $checker++;
                for($x=0;$x<count($css_lib);$x++){
            echo $css_lib[$x].'.css';
            }
        }
    }
}