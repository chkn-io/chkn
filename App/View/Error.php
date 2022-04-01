<?php
namespace App\View;
class Error{
	public static function error_page(){
            $template = file_get_contents('view\defaults\error.tpl');
            $template = str_replace('[chkn:error]',DEFAULT_URL,$template);
            echo $template;
	}
}
