<?php
namespace App\Console;

require_once("App/App/App_Model.php");
require_once("App/Model/Model.php");
require_once("App/Database/DB.php");


use App\Database\DB;
use App\Helpers\encrypt_helper;
use PDO;

class Console{
    function __construct($argv){
        if(count($argv) <= 1){
            $this->response("Invalid CHKN Command","Error");
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
                        $this->response("Invalid CHKN Command. Undefined value for CREATE CONTROLLER.","Error");
                    }
                break;

                case "styles":
                    $this->styles();
                break;

                case "db:connection":
                    if(isset($argv[2])){
                        $this->dbconfig("DB_CONNECTION",$argv[2]);
                    }else{
                        $this->response("Invalid CHKN Command. Undefined value for DB_CONNECTION.","Error");
                    }
                break;

                case "db:host":
                    if(isset($argv[2])){
                        $this->dbconfig("DB_HOST",$argv[2]);
                    }else{
                        $this->response("Invalid CHKN Command. Undefined value for DB_HOST.","Error");
                    }
                break;

                case "db:name":
                    if(isset($argv[2])){
                        $this->dbconfig("DB_NAME",$argv[2]);
                    }else{
                        $this->response("Invalid CHKN Command. Undefined value for DB_NAME.","Error");
                    }
                break;

                case "db:username":
                    if(isset($argv[2])){
                        $this->dbconfig("DB_USER",$argv[2]);
                    }else{
                        $this->response("Invalid CHKN Command. Undefined value for DB_USER.","Error");
                    }
                break;

                case "db:password":
                    if(isset($argv[2])){
                        $this->dbconfig("DB_PASSWORD",$argv[2]);
                    }else{
                        $this->dbconfig("DB_PASSWORD","");
                    }
                break;

                case "db:charset":
                    if(isset($argv[2])){
                        $this->dbconfig("DB_CHARSET",$argv[2]);
                    }else{
                        $this->response("Invalid CHKN Command. Undefined value for DB_CHARSET.","Error");
                    }
                break;

                case "constant:add":
                    if(isset($argv[2],$argv[3])){
                        $this->defineConstant($argv[2],$argv[3]);
                    }else{
                        $this->response("Invalid CHKN Command.","Error");
                    }
                break;

                case "constant:update":
                    if(isset($argv[2],$argv[3])){
                        $this->updateConstant($argv[2],$argv[3]);
                    }else{
                        $this->response("Invalid CHKN Command.","Error");
                    }
                break;

                case "constant:delete":
                    if(isset($argv[2])){
                        $this->deleteConstant($argv[2]);
                    }else{
                        $this->response("Invalid CHKN Command.","Error");
                    }
                break;

                case "auth:create":
                    $this->AuthCreate();
                break;

                default:
                    $this->response("Invalid CHKN Command. Unknown command is executed.","Error");
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
        file_put_contents($path, $content);
    }

    private function response($message,$type){
        $a = "";
        if($type == "Error"){
            $a = "\e[0;31m";
        }else if($type == "Success"){
            $a = "\e[0;32m";
        }else if($type == "Info"){
            $a = "\e[1;30m";
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
				// echo $this->response(["message"=>$controller."Controller.php is already exist","type"=>"error"],200);
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

    private function dbconfig($field="",$value=""){
        $path = "config/database.conf";
        $file = fopen($path,"r");
        $content = file_get_contents($path);
        while(!feof($file)){
            $string = fgets($file);
            if (stripos($string, $field) !== false) {
                $content = str_replace($string,$field."=".$value."\r\n",$content);
            }
        }
        file_put_contents($path, $content);
        $this->response("$field is successfully updated","Success");
    }

    private function defineConstant($field="",$value=""){
        if(str_replace(" ", "", $field) != ""){
			$path = "config/user.conf";
			$file = fopen($path,"r");
			$content = file_get_contents($path);
			$ind = 0;
			if($ind == 0){
				$new = $field."=".$value;
				$new_content = $content."\r\n".$new;
			    file_put_contents($path, $new_content);
				$this->response($field." is successfully defined.","Success");
			}
		}else{ 
		}
    }

    private function updateConstant($field="",$value=""){
        if(str_replace(" ", "", $field) != ""){
			$path = "config/user.conf";
			$file = fopen($path,"r");
			$content = file_get_contents($path);
			$ind = 0;
			while(!feof($file)){
				$string = fgets($file);
				if(stripos($string, $field) !== false){
					$content = str_replace($string,$field."=".$value."\r\n",$content);
					$ind++;
				}
			}
            file_put_contents($path, $content);
            $this->response("CONSTANT ".$field." is successfully updated!","Success");
		}else{
			$this->response("Invalid CHKN Command.","Error");
		}
    }

    private function deleteConstant($field=""){
        if(str_replace(" ", "", $field) != ""){
			$path = "config/user.conf";
			$file = fopen($path,"r");
			$content = file_get_contents($path);
			$ind = 0;
			while(!feof($file)){
				$string = fgets($file);
				if(stripos($string, $field) !== false){
					$content = str_replace($string,"",$content);
					$ind++;
				}
			}
            file_put_contents($path, $content);
            $this->response("CONSTANT ".$field." is successfully deleted!","Success");
		}else{
			$this->response("Invalid CHKN Command.","Error");
		}
    }

    private function authCreate(){
        error_reporting(0);
        register_shutdown_function([$this,'fatalErrorShutdownHandler']);
        $this->loadConfig();
        if($this->checkdatabase() !== false){
            if($this->createtable() !== false){
                $this->createauthcontroller();
            }
        }
    }

    public function fatalErrorShutdownHandler(){
        $last_error = error_get_last();
        if(isset($last_error)){
            $this->response("Incorrect database configuration. Check your database connection.","Error");
        }
    }

    private function loadConfig(){
        $file = fopen("config/database.conf","r");
        $app = array();
        while(!feof($file)){
           $string = trim(fgets($file));

            if($string != ""){
                $ex = explode("=",$string);
              
                if($ex[0] != ""){
                    $app[$ex[0]] = $ex[1];
                }elseif($ex[0] != array()){
                    $app[$ex[0]] = $ex[1];
                }
            }
        }
        
        foreach($app as $key => $value){
            define($key,$value);
        }

        $file = fopen("config/app.conf","r");
        $app = array();
        $string="";
        while(!feof($file)){
            $string .= fgets($file)."newLine;";
        }

        $ex = explode("newLine;",$string);
        $ex = array_filter($ex);

        $def_array = array();
        for($x=0;$x<count($ex);$x++){
            $pos = strstr($ex[$x],"/*");
            if($pos == ""){
                $vex = explode("=",$ex[$x]);
                if(isset($vex[1])){
                    $def_array[$vex[0]] = $vex[1];
                }
            }
        }

        $defines = array_filter($def_array);
        foreach ($defines as $key => $value) {
            define($key,$value);
        }
    }

    
    private function db_conn(){
       $this->response("Checking Database Connection...","Info");
        $this->db = new PDO(DB_CONNECTION.":host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASSWORD);
        return  [0=>"success"];
	}
    private function checkdatabase(){
		$db = $this->db_conn();
        if($db[0] == "success"){
            $this->response("Database found!","Success");
        }else{
            $this->response("Database not found!","Error");
        }
	}

    public function createtable(){
        require_once("App/Helpers/encryption.php");
        $this->response("Creating Database...","Info");
			$query = 'CREATE table chkn_auth(
		     id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
		     username VARCHAR( 100 ) NOT NULL, 
		     password VARCHAR( 250 ) NOT NULL,
		     role VARCHAR( 50 ) NOT NULL,
		     date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);';
	     	$stmt = $this->db->prepare($query);
            $stmt->execute();

            $query = "INSERT INTO chkn_auth (username,password,role)
                VALUES(:username,:password,:role)
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue("username","admin");
            $stmt->bindValue("password",encrypt_helper::encrypt("admin"));
            $stmt->bindValue("role","admin");
            $stmt->execute();

            $this->response("The Database is successfully created.","Success");
		
	}

    public function createauthcontroller(){
        $this->response("Creating Auth Controller...","Info");
		if(!file_exists("http/Controllers/AuthController.php")){
			$cont = fopen("http/Controllers/AuthController.php", "w");
			$content = '<?php
    namespace http\Controllers;
    
    use App\Controller\Controller;
    use App\App\Request;
    use App\App\Session;
    use App\Database\DB;
    use App\Controller\Auth;
    use App\Helpers\Helpers;
    
    class AuthController extends Controller{
        public function auth(){
            //Call Controller Template
            $this->template("auth");
    
            //Change Page Title
            $this->title("");
    
            //Page Style
            $this->css([
            ]);
    
            //Page Script
            $this->js([]);
    
            //Page Content
            $this->body("auth/index");
            $this->variable("auth_message","");
            if(Session::check("auth_message")){
                $this->variable("auth_message",Session::get("auth_message")["status"]);
            }
    
            //App Status
            //$this->chkn_status();
    
            $this->show();
            Session::clear("auth_message");
        }
    
        public function login(Request $r){
            if($r->isset(["username","password"])->status()){
                if($r->check(["username","password"])){
                    Auth::login("chkn_auth",[
                        "username"=>$r->username,
                        "password"=>$r->password,
                        "url"=>[
                            //Redirect when true
                            "success"=>"../",
                            //Redirect when false
                            "failed"=>"auth"
                        ]
                    ]);
                }else{
                    $this->locate("auth");
                    Session::put("auth_status","error");
                }
            }else{
                $this->locate("auth");
                Session::put("auth_status","error");
            }
        }
    
        public function logout(){
            Auth::logout("auth");
        }
    }
';
				
				fwrite($cont, $content);
				fclose($cont); 

                $this->response("AuthController.php is successfully created.","Success");

                $this->response("Creating Auth Page...","Info");
				if (!file_exists('view/page/auth')) {
				    mkdir('view/page/auth', 0777, true);
				    if(!file_exists("view/page/auth/index.cvf")){
				    	$page = fopen("view/page/auth/index.cvf", "w");
				    	$pcontent = '
<div class="col-md-5 login-container">
	<div class="wrapper"> 
		#if("$auth_message" == "error"){
			{{
				<p class="alert alert-danger text-center">Incorrect Username or Password</p>
			}}
		}elseif("$auth_message" == "success"){
			{{
				<p class="alert alert-danger text-center">Authentication success. Please wait...</p>
			}}
		}else{

		#}
		<form method="post" action="[chkn:path]auth/login">
			[form:csrf]
			<div class="form-group">
				<label>Username</label>
				<input class="form-control" type="text" name="username" placeholder="Enter your username">
			</div>

			<div class="form-group">
				<label>Password</label>
				<input class="form-control" type="password" name="password" placeholder="Enter your password">
			</div>

			<div class="form-group">
				<button class="btn btn-primary">Submit</button>
			</div>
		</form>
	</div>
	
</div>

';
						fwrite($page, $pcontent);
						fclose($page); 
				    }
				}
                
        $this->response("Authentication view is successfully created.","Success");

        $this->response("Creating Auth Template...","Info");
			    if(!file_exists("view/template/auth.tpl")){
			    	$temp = fopen("view/template/auth.tpl", "w");
			    	$tempcontent = '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" >
    <title>[chkn:title]</title>
    <meta content="[chkn:csrf]" name="csrf-token">
    <link rel="icon" href="[chkn:path]public/images/icon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    [chkn:style]
    <style>
	body{
		background-image: linear-gradient(180deg,#045c14,#32ea0c);
		background-attachment: 	fixed;
		height:100%;
	}
	.login-container{
		float:none;
		margin:auto;
	}

	div.login-container .wrapper{
		background-color:white;
		width:100%;
		float:left;
		padding:20pt;
		border-radius:5px;
		margin-top:20%;
	}

	header,footer{
		display:none;
	}

</style>

</head>
<body>
    <div id="content">
	[chkn:body]               
    </div>
	[chkn:script]
</body>

</html>
';
					fwrite($temp, $tempcontent);
					fclose($temp); 
			    }

                
                $this->response("Auth Template is created successfully","Success");
			}else{
				$this->response("AuthController.php is already exist","Error");
			}
	}
}