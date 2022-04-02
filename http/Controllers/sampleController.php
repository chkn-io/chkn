<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;
use App\Helpers\Helper;

class sampleController extends Controller{
	public function sample(){
		//Call Controller Template
		$this->template("index");

		//Change Page Title
		$this->title("");

		//Page Style
		$this->css([
		]);

		//Page Script
		$this->js([]);

		//Page Content
		$this->body("sample/index");

		//App Status
		//$this->chkn_status();

		$this->show();
	}
	
}

