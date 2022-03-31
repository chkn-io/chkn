<?php
namespace App\Exception;
class Catcher{
	public function errorHandler($type,$message,$file,$line){
		if($type != ""){
			$html = '<style>
					.chkn_handler{
						width:100%;
						float:left;
						position:absolute;
						background-color:#fff;
						border:1px solid #ccc;
					}

					.chkn_handler_header{
						padding:10pt;
						float:left;
						width:97.5%;
						text-transform:uppercase;
					}
					.chkn_handler_header p{
						padding:10pt;
						margin:0;
					}
					.chkn_handler_error{
						background-image:linear-gradient(to bottom, #ff3019 0%,#cf0404 100%);
						color:white;
					}
					.chkn_handler_warning{
						background-image:linear-gradient(to bottom, #ffa84c 0%,#ff7b0d 100%);;
						color:black;
					}
					.chkn_handler_content{
						width:97.5%;
						padding:10pt;
					}
				</style>';
		$html .= '<div class="chkn_handler">';
		$html .= $this->header($type);
		$html .= '<div class="chkn_handler_content">';
		$html .= '
				<p><b>Message:</b> '.$message.'</p>
				<p><b>File Located:</b> '.$file.'</p>
				<p><b>Line:</b> '.$line.'</p>
		';		
		$html.='</div>';
		$html .=  '</div>';

		echo $html;
		}	
	}
		

	public function header($type){
		$html = '<div class="chkn_handler_header">';
		switch($type){
			case 1:
				$html.='<p class="chkn_handler_error">E_ERROR - Fatal run-time errors</p>';
			break;
			case 2:
				$html.='<p class="chkn_handler_warning">E_WARNING - Run-time warnings (non-fatal errors)</p>';
			break;
			case 4:
				$html.='<p class="chkn_handler_error">E_PARSE - Compile-time parse errors</p>';
			break;
			case 8:
				$html.='<p class="chkn_handler_warning">E_NOTICE - Run-time notices</p>';
			break;
			case 16:
				$html.='<p class="chkn_handler_error">E_CORE_ERROR - Fatal errors that occur during PHP\'s initial startup</p>';
			break;
			case 32:
				$html.='<p class="chkn_handler_warning">E_CORE_WARNING  - Warnings (non-fatal errors) that occur during PHP\'s initial startup</p>';
			break;
			case 64:
				$html.='<p class="chkn_handler_error">E_COMPILE_ERROR  - Fatal compile-time errors</p>';
			break;
			case 128:
				$html.='<p class="chkn_handler_warning">E_COMPILE_WARNING - Compile-time warnings (non-fatal errors)</p>';
			break;
			case 256:
				$html.='<p class="chkn_handler_error">E_USER_ERROR - User-generated error message</p>';
			break;
			case 512:
				$html.='<p class="chkn_handler_warning">E_USER_WARNING - User-generated warning message</p>';
			break;
			case 1024:
				$html.='<p class="chkn_handler_warning">E_USER_NOTICE  - User-generated notice message</p>';
			break;
			case 2048:
				$html.='<p class="chkn_handler_error">E_STRICT - Enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code.</p>';
			break;
			case 4096:
				$html.='<p class="chkn_handler_error">E_RECOVERABLE_ERROR - Catchable fatal error</p>';
			break;
			case 8192:
				$html.='<p class="chkn_handler_warning">E_DEPRECATED - Run-time notices</p>';
			break;
			case 16348:
				$html.='<p class="chkn_handler_warning">E_USER_DEPRECATED - User-generated warning message</p>';
			break;
			case 32767:
				$html.='<p class="chkn_handler_error">E_ALL - All errors and warnings, as supported, except of level E_STRICT prior to PHP 5.4.0.</p>';
			break;
		}

		$html.='</div>';
		return $html;	
	}
}
