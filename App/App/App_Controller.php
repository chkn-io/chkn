<?php
namespace App\App;

use App\View\View;
use App\Template\CHKNTemplate;
use App\Controller\Auth;

use http\Defaults\chknError;
use http\Defaults\maintenance;

class App_Controller{
	public $assignedValues = array();
  public $variable = array();
  public $array_var = array();
	public $tpl;
  public $view;
  public $error;
  public $maintenance;
  public $tem_tool;

  protected $post;
   protected $get;

    /**
     * @param string $_path
     * A function that get the requested template
     */

    function __construct(){
        $this->view = new View;
        
        if(PAGE_NOT_FOUND == 1){
           $this->error = new chknError;
        }
        if(MAINTENANCE_CLASS == 1){
           $this->maintenance = new maintenance;
        }

        $this->tem_tool = new CHKNTemplate;
    }

	function path($_path = ''){
		if(!empty($_path)){
			if(file_exists($_path)){
				$this->tpl = file_get_contents($_path);	
			}else{
				$this->chknError();
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
    
	function assign($_searchString, $_replacedString){
		if(!empty($_searchString)){
			$this->assignedValues[strtolower($_searchString)] = $_replacedString;
		}
	}


  /**
     * @param $_searchString
     * @param $_replacedString
     * This function is responsible for replacing variables with {} to its defined values
     */
  function pass_variable($_searchString, $_replacedString){
    if(!empty($_searchString)){
      $this->variable[$_searchString] = $_replacedString;
    }
  }

  function pass_array_var($key, $array){
    $this->array_var[$key] = $array;
  }

    /**
     *This function executes the requested page(template,page,css,js,etc.)
     */

	function dispose(){
		if(count($this->assignedValues) > 0){
			foreach($this->assignedValues as $key => $value){
				$this->tpl = str_replace('['.$key.']',$value,$this->tpl);
			}

      foreach($this->variable as $key => $value){
        $this->tpl = str_replace('$'.$key.'',$value,$this->tpl);
      }

			$this->tpl = str_replace('[chkn:path]',DEFAULT_URL,$this->tpl);	
      preg_match('/#if(.*?)#}/s', $this->tpl,$result);
      if(count($result) != 0){
        $response = $this->tem_tool->if_condition($result);
        $this->tpl = str_replace($result[0], eval($response), $this->tpl);
      }

      preg_match('/#for(\\(.*?)#endfor/s', $this->tpl,$result);
      if(count($result) != 0){
       $return = $this->tem_tool->forloop($result);
        $this->tpl = str_replace($result[0], $return, $this->tpl);
      } 

      preg_match('/#foreach(\\(.*?)#endforeach/s', $this->tpl,$result);
      if(count($result) != 0){
        $return = $this->tem_tool->foreachs($this->tpl,$result,$this->array_var);
        
        $this->tpl = str_replace($result[0], $return, $this->tpl);
      }
      echo $this->tpl;
		}
	}


}