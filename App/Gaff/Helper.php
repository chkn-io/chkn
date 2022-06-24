<?php
use App\App\App_Controller as app;
use App\App\CSRFToken;

class Template{
    private static $css;
    private static $js;
    static function scene($template="",$cvf=""){
        app::path('view/template/'.$template.".tpl");
        app::assign('DEFAULT_PATH',DEFAULT_URL);
     
        $data = app::$view->Html_Objects('page',$cvf.".cvf");
        app::assign('chkn:body',$data);
        return new static;
     }
    
    static function title($title=""){
        app::assign('chkn:title',$title);
        return new static;
    }
    static function css($css=[]){
        app::assign('chkn:style',app::$view->Html_Objects('css',
            $css
		));
        self::$css = true;
        return new static;
    }

    static function js($js=[]){
        app::assign('chkn:script',app::$view->Html_Objects('js',
            $js
        ));
        self::$js = true;
        return new static;
    }

    static function bind($data = ""){
        foreach ($data as $key => $value) {
            if(is_array($value)){
    	        app::pass_array_var($key, $value);
            }else{
                app::pass_variable($key,$value);
            }
        }
        return new static;
    }

   
    static function show(){
        if(isset($_SESSION["CSRFToken"])){
			app::assign('form:csrf','<input type="hidden" name="CSRFToken" value="'.$_SESSION["CSRFToken"].'">');
        	app::assign('chkn:csrf',$_SESSION["CSRFToken"]);
		}
        if(!self::$css){
            app::assign('chkn:style',app::$view->Html_Objects('css',[]));
        }
        if(!self::$js){
            app::assign('chkn:script',app::$view->Html_Objects('js',[]));
        }
		app::dispose();
    }
}

function scene($template,$cvf){
    return Template::scene($template,$cvf);
}

function forceCSRF(){
    if(isset($_SESSION["CSRFToken"])){
        if(isset($_REQUEST["CSRFToken"])){
            $t = CSRFToken::validator($_REQUEST["CSRFToken"]);
            if($t != 1){
                invalid_request();
                exit;
            }
        }else{
            invalid_request();
            exit;
        }
    }else{			
        invalid_request();
        exit;
    }
}

function invalid_request(){
    header("HTTP/1.0 401");
    $file = file_get_contents("view/defaults/invalid_request.tpl");
    echo $file;
    exit;
}