<?php
namespace App\Controller;
use App\Database\DB;
use App\App\Session;
use App\Helpers\Helpers;

class Auth extends Controller
{
	static function check($data,$redirect_url){
        $keys = array_keys($data);
    	if(Session::get("auth")){
    		if(Session::get("auth")[$keys[0]] != $data[$keys[0]]){
				header("location:".$redirect_url);
			}
    	}else{
            header("location:".$redirect_url);
    	}
    }

    static function user($data){
        $auth = array();
        $array_val = null;
        if(Session::get("auth")){
            foreach ($data as $value) {
             $auth[$value] = Session::get("auth")[$value];
            }
        }
        return $auth;
    }

    static function login($table,$data){
		$keys = (array_keys($data));
		$users = DB::select($table)
    		->where($keys[0],'=',$data[$keys[0]])
    		->fetch();

    	if(count($data) != 0){
            if(count($users) != 0){
                $decrypt_password = Helpers::decrypt($users[0][$keys[1]]);
                if($decrypt_password == $data[$keys[1]]){
                    Session::put("auth",$users[0]);
                    Session::put("auth_message",["message" => "Authentication Success","status"=>"success"]);
                    Session::put('lauth','success');
                    if(isset($data["url"])){
                        if(isset($data["url"]["success"])){
                            echo $data["url"]["success"];
                            header("location:".$data["url"]["success"]);
                        }else{
                            return array("message"=>"Success","status"=>"success");
                        }
                    }else{
                        return array("message"=>"Success","status"=>"success");
                    }
    
                }else{
                    Session::put("auth_message",["message" => "Wrong Username or Password","status"=>"error"]);
                    if(isset($data["url"])){
                        if(isset($data["url"]["failed"])){
                            header("location:".$data["url"]["failed"]);
                        }else{
                            return array("message"=>"Incorrect Password","status"=>"error");
                        }
                    }else{
                        return array("message"=>"Incorrect Password","status"=>"error");
                    }
                }
            }else{
                Session::put("auth_message",["message" => "Wrong Username or Password","status"=>"error"]);
                    if(isset($data["url"])){
                        if(isset($data["url"]["failed"])){
                            header("location:".$data["url"]["failed"]);
                        }else{
                            return array("message"=>"Incorrect Password","status"=>"error");
                        }
                    }else{
                        return array("message"=>"Incorrect Password","status"=>"error");
                    }
            }
        }else{
            if(isset($data["url"])){
                Session::put("auth_message",["message" => "Error","status"=>"error"]);
                if(isset($data["url"]["failed"])){
                    header("localtion:".$data["url"]["failed"]);
                }else{
                    return array("message"=>"Incorrect Username","status"=>"error");
                }
            }else{
                return array("message"=>"Incorrect Username","status"=>"error");
            }
        }

    }

    static function register($table,$data){
        if (isset($data["password"])) {
            $data["password"] = Helper::encrypt($data["password"]);
        }

        try {
            $query = "INSERT INTO ".$table;

            $columns = "(";

            $values = "(";

            foreach ($data as $key => $value) {
                if(is_array($value)){
                    break;
                }
               $columns = $columns . $key .",";

               $values = $values."'".$value."',";
            }

            $columns = rtrim($columns,",");

            $columns = $columns .")";

            $values = rtrim($values,",");

            $values = $values .")";

            $query = $query ." ". $columns."VALUES".$values;

            $users = DB::query($query)
                    ->execute();

            $users = DB::select($table)
                    ->where('id','=',DB::lastInsertId())
                    ->fetch();

            Session::put("auth",$users[0]);

            if(isset($data["url"]["success"])){
               Session::put("auth_message",["message" => "success"]);
               header("location".$data["url"]["success"]);
            }else{
                Session::put("auth_message",["message" => "success"]);
                return 1;
            }
        } catch (Exception $e) {
            if(isset($data["url"]["failed"])){
               Session::put("auth_message",["message" => "SQL Error"]);
               Session::put("hasError",1);
               header("location".$data["url"]["failed"]);
            }else{
                Session::put("auth_message",["message" => "SQL Error"]);
                Session::put("hasError",1);
                return 0;
            }
        }
        
    }

    static function message(){
        if (Session::check("auth_message")) {
            return Session::get("auth_message")["message"];   
        }
        
    }

    static function role(){
        return Session::get("auth")["role"];
    }

    static function isSuccess(){
         if (Session::check("auth_message")) {
            if (Session::get("auth_message")["message"] == "success") {
                return true;
            }else{
                return false;
            }
         }else{
            return Null;
         }
    }

    static function hasError(){
        return Session::get("auth_error");
    }

    static function logout($url = ""){
        Session::clear("auth");
        Session::clear("auth_message");
        Session::clear("lauth");
        if($url == ""){
            return ["message"=>"Logout","status"=>1];
        }else{
            header("location:".$url);
        }
    }
}
