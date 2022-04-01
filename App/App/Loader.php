<?php

namespace App\App;
use App\View\Error;
use App\View\Maintenance;


class Loader{
	protected function load_page_controller($controller){
		if(file_exists('http/Controllers/'.$controller.'.php')){
			require_once('http/Controllers/'.$controller.'.php');
		}
	}

	protected function chknError(){
		if(PAGE_NOT_FOUND == 1){
			$error = new Error();
			$error->error_page();
		}
	}
	
	protected function maintenance(){
		if(MAINTENANCE_CLASS == 1){
			$maintenance = new Maintenance();
			$maintenance->maintenance_page();
		}
	}
}