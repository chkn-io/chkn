<?php
namespace App\Helpers;

use App\Model\Model;
use App\Database\DB;
use PDO;
/**
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 * Class defaults
 */
 class defaults{
    public $model;
    private static $db;
    static function start(){
        $encryption_key_256bit = base64_encode(openssl_random_pseudo_bytes(32));
        $def = '
                <div id="homepage">
                    <div class="container">
                    <div class="row">
                            <div class="col-md-8">
                                <h1>Application Status</h1>
                            <hr>';
        $def.='';
        if(strlen(APPLICATION_KEY) != 43){
            $def.='
                <div class="con salt_error">
                <h3> <i class="fa fa-password"></i> Application Key: Not yet updated</h3>
                <p>Application Key: <span class="app-key">'.$encryption_key_256bit.'</span></p>
                <p>To Change it, go to config/app.conf</p>
                </div>';
        }else{
            $def.='<div class="con salt_success">
                <h3>  <i class="fa fa-user"> </i> Application Key: Installed</h3>
                </div>';
        }
        if(QUERY_BUILDER == 1){
            
             $database_response = self::db_conn();
            if($database_response[0] == 'Database Connection Error'){
                $def.=
                    '<div class="clearfix"></div><div class="con database_error">
                    <h3><i class="fa fa-database"></i> Database Status: Not Found</h3>
                    <p>You can use our default Database on public/data/sample.sql.</p>
                    <br>
                    <p>To fix this error, go to config/database.conf</p>
                    </div>';
            }else{
                $def.=
                    '<div class="con database_success">
                    <h3> <i class="fa fa-database"></i> Database Status: Ready</h3>
                    </div>';
            }
        }
       

        $def.='</div>
                <div class="col-md-4">
                    <h1>Active External Files</h1>
                    <hr>
                        <div class="col-md-12 other-config">
                        <h3>Stylesheet</h3>';
               $css_lib = explode(',',CSS_LIBRARY);  
               $checker = 0;
               if($css_lib[0] != ""){
                $checker++;
                 for($x=0;$x<count($css_lib);$x++){
                $def.='<li>'.$css_lib[$x].'.css</li>';
               }   
               }
                  
               $css_lib = explode(',',PRIVATE_STYLESHEETS);  
               if($css_lib[0] != ""){
                $checker++;
                for($x=0;$x<count($css_lib);$x++){
                $def.='<li>'.$css_lib[$x].'.css</li>';
               }  
                }

                if($checker == 0){
                        $def.='<li>No CSS File used</li>';
                    }
        $def.='</div>
 
                     <div class="col-md-12">
                        <h3>Script</h3>';
                        $js_lib = explode(';',JS_LIBRARY);  
                        $checker = 0;
                        if($js_lib[0] != ""){
                            $checker++;
                            for($x=0;$x<count($js_lib);$x++){
                            $def.='<li>'.$js_lib[$x].'.js</li>';
                           } 
                        }
                           

                    $js_lib = explode(',',PRIVATE_SCRIPT);  
                    if($js_lib[0] != ""){
                        $checker++;
                        for($x=0;$x<count($js_lib);$x++){
                        $def.='<li>'.$js_lib[$x].'.js</li>';
                       } 
                    }

                    if($checker == 0){
                        $def.='<li>No JS File used</li>';
                    }
                           
        $def.='</div>

                     <div class="col-md-12 other-config">
                        <h3>Other Libraries</h3>';
                        $js_lib = explode(';',CHKN_ASSETS);  
                        $checker = 0;
                        if($js_lib[0] != ""){
                            $checker++;
                            for($x=0;$x<count($js_lib);$x++){
                            $def.='<li>'.$js_lib[$x].'</li>';
                           } 
                        }

                        if($checker == 0){
                            $def.='<li>No Library used</li>';
                        }
                           

                 
        $def.=' </div>
                            ';
        $def.='</div>
        </div>
    </div>
    </div>
<br><br><br>';

        return $def;

    }

    private static function db_conn(){
		
        try {
            self::$db = new PDO(DB_CONNECTION.":host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASSWORD);
            return  [0=>"success"];
        }catch(PDOException $e){
            $error = array();
            $error[0] = 'Database Connection Error';
            $error[1] = $e->getMessage();
            return $error;
        }
    }
}