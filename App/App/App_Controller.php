<?php
namespace App\App;

use App\View\View;
use App\View\Error;
use App\View\maintenance;
use App\Gaff\Template;


class App_Controller{
	public static $assignedValues = [];
  public static $variable = [];
  public static $array_var = [];
	public static $tpl;
  public static $view;
  public static $tem_tool;

  protected $post;
   protected $get;

    /**
     * @param string $_path
     * A function that get the requested template
     */

    function __construct(){
        self::$view = new View;
        
        self::$tem_tool = new Template;
    }

	public static function path($_path = ''){
		if(!empty($_path)){
			if(file_exists($_path)){
				self::$tpl = file_get_contents($_path);	
			}else{
				Error::error_page();
			}
		}
  }
  
  function invalid_request(){
		header("HTTP/1.0 401");
		$file = file_get_contents("view/defaults/invalid_request.tpl");
		echo $file;
		exit;
	}

    /**
     * @param $_searchString
     * @param $_replacedString
     * This function is responsible for replacing variables with {} to its defined values
     */
    
	public static function assign($_searchString, $_replacedString){
		if(!empty($_searchString)){
			self::$assignedValues[strtolower($_searchString)] = $_replacedString;
		}
	}


  /**
     * @param $_searchString
     * @param $_replacedString
     * This function is responsible for replacing variables with {} to its defined values
     */
  static function pass_variable($_searchString, $_replacedString){
    if(!empty($_searchString)){
      self::$variable[$_searchString] = $_replacedString;
    }
  }

  static function pass_array_var($key, $array){
    self::$array_var[$key] = $array;
  }

    /**
     *This function executes the requested page(template,page,css,js,etc.)
     */

	static function dispose(){
		if(count(self::$assignedValues) > 0){
			foreach(self::$assignedValues as $key => $value){
				self::$tpl = str_replace('['.$key.']',$value,self::$tpl);
			}

      foreach(self::$variable as $key => $value){
        self::$tpl = str_replace('$'.$key.'',$value,self::$tpl);
      }

			self::$tpl = str_replace('[chkn:path]',DEFAULT_URL,self::$tpl);	
      preg_match('/#if(.*?)#}/s', self::$tpl,$result);
      if(count($result) != 0){
        $response = self::$tem_tool->if_condition($result);
        self::$tpl = str_replace($result[0], eval($response), self::$tpl);
      }

      preg_match('/#for(\\(.*?)#endfor/s', self::$tpl,$result);
      if(count($result) != 0){
       $return = self::$tem_tool->forloop($result);
        self::$tpl = str_replace($result[0], $return, self::$tpl);
      } 

      preg_match('/#foreach(\\(.*?)#endforeach/s', self::$tpl,$result);

      if(count($result) != 0){
        $return = self::$tem_tool->foreachs(self::$tpl,$result,self::$array_var);
        self::$tpl = str_replace($result[0], $return, self::$tpl);
      }

      echo self::$tpl;
		}
	}


}