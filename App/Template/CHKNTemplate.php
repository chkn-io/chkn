<?php
namespace App\Template;
class CHKNTemplate{
	public function if_condition($source = array()){
		$return = "";
        $condition = str_replace('{{',"return '",$source[0]);  
        $condition = str_replace('}}',"';",$condition);  
        $condition = str_replace('#if', 'if', $condition);
        $condition = str_replace('#}', '}', $condition);

        return $condition;
	}

	public function forloop($source = array()){
		$return = "";
        $condition = str_replace('{{',"\$return.= ('",$source[0]);  
        $condition = str_replace('}}',"');",$condition);   
        $condition = str_replace('#endfor', '}', $condition);
        $condition = str_replace('[<]', '<', $condition);
        $condition = str_replace('[>]', '>', $condition);
        $condition = str_replace('[<=]', '<=', $condition);
        $condition = str_replace('[>=]', '>=', $condition);
        $condition = str_replace('[==]', '==', $condition);
        $condition = str_replace('[!=]', '!=', $condition);
        $condition = str_replace('#for', 'for', $condition);
        $condition = str_replace('{[', '\'.$', $condition);
        $condition = str_replace(']}', '.\'', $condition);
        $condition = eval($condition);
        return $return;
	}

	public function foreachs($template = "",$result = array(),$array_var=""){
		preg_match('/foreach((.*?)){/s', $template,$var);
        $source = str_replace('($', '', $var[2]);
        $source = str_replace(')', '', $source);
        eval('$$source = $array_var[$source];');
        eval('$v = $$source;');
        preg_match('/{{(.*?)}}/s', $result[1],$datas);
        $return = "";
        $string = str_replace("#foreach($".$source.")", "foreach($".$source." as \$key => \$value)", $result[0]);
        $string = str_replace('{{',"\$return.='",$string);  
        $string = str_replace('}}',"';",$string); 
        $string = str_replace('{[', '\'.$value["', $string);
        
        $string = str_replace(']}', '"].\'', $string);

        $string = str_replace('#endforeach', '}', $string);
        preg_match("/if(\\(.*?)\\){/s", $string,$conditions);
        if(count($conditions) != 0){
          $con = str_replace("if('.","if(",$conditions[0]);
          $con = str_replace("\"].'","\"]",$con);
          $string = str_replace($conditions[0],$con,$string);
        }
        preg_match("/elseif(\\(.*?)\\){/s", $string,$conditions);
        if(count($conditions) != 0){
          $con = str_replace("elseif('.","elseif(",$conditions[0]);
          $con = str_replace("\"].'","\"]",$con);
          $string = str_replace($conditions[0],$con,$string);
        }
        eval($string);
        return $return;
	}

	
}

