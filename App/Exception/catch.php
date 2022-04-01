<?php
class Catcher{
	public function errorHandler($type,$message,$file,$line){
		// clean the output buffer
		if($type != ""){
			ob_clean();
			$head = $this->header($type);
			$html = file_get_contents("view/defaults/exception/exception.tpl");
			$html = str_replace("{ERROR_TYPE}",$head["message"],$html);
			$html = str_replace("{DEFAULT_URL}",DEFAULT_URL,$html);
			$html = str_replace("{LINE_NUMBER}",$line,$html);
			$message = explode("\n",$message);

			if($head["type"] == "error"){
				$html = str_replace("BACKCOLOR","rgb(247, 76, 76);",$html);
			}else{
				$html = str_replace("{BACKCOLOR}","rgb(255, 170, 59);",$html);
			}
			$html = str_replace("{MESSAGE}",$message[0],$html);

			$h = "";
			foreach($message as $key => $value){
				$h .= '<li>'.$value.'</li>';
			}
			$html = str_replace("{ERROR_DETAILS}",$h,$html);
			$html = str_replace("{FILE}",$file,$html);
			$html = str_replace("{LINE}",$line,$html);

			echo $html;
		}	
	}
		

	public function header($type){
		switch($type){
			case 1:
				$html=["message"=>'E_ERROR - Fatal run-time errors',"type"=>"error"];
			break;
			case 2:
				$html=["message"=>'E_WARNING - Run-time warnings (non-fatal errors)',"type"=>"warning"];
			break;
			case 4:
				$html=["message"=>'E_PARSE - Compile-time parse errors',"type"=>"error"];
			break;
			case 8:
				$html=["message"=>'E_NOTICE - Run-time notices',"type"=>"warning"];
			break;
			case 16:
				$html=["message"=>'E_CORE_ERROR - Fatal errors that occur during PHP\'s initial startup',"type"=>"error"];
			break;
			case 32:
				$html=["message"=>'E_CORE_WARNING  - Warnings (non-fatal errors) that occur during PHP\'s initial startup',"type"=>"warning"];
			break;
			case 64:
				$html=["message"=>'E_COMPILE_ERROR  - Fatal compile-time errors',"type"=>"error"];
			break;
			case 128:
				$html=["message"=>'E_COMPILE_WARNING - Compile-time warnings (non-fatal errors)',"type"=>"warning"];
			break;
			case 256:
				$html=["message"=>'E_USER_ERROR - User-generated error message',"type"=>"error"];
			break;
			case 512:
				$html=["message"=>'E_USER_WARNING - User-generated warning message',"type"=>"warning"];
			break;
			case 1024:
				$html=["message"=>'E_USER_NOTICE  - User-generated notice message',"type"=>"warning"];
			break;
			case 2048:
				$html=["message"=>'E_STRICT - Enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code',"type"=>"error"];
			break;
			case 4096:
				$html=["message"=>'E_RECOVERABLE_ERROR - Catchable fatal error',"type"=>"error"];
			break;
			case 8192:
				$html=["message"=>'E_DEPRECATED - Run-time notices',"type"=>"warning"];
			break;
			case 16348:
				$html=["message"=>'E_USER_DEPRECATED - User-generated warning message',"type"=>"warning"];
			break;
			case 32767:
				$html=["message"=>'E_ALL - All errors and warnings, as supported, except of level E_STRICT prior to PHP 5.4.0.',"type"=>"error"];
			break;
		}

		return $html;	
	}
}
