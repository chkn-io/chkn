<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;
use App\Helpers\Helper;
use App\Database\DB;

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

	public function create(Request $r){
		DB::insert("records")
			->field("first_name",$r->first_name)
			->field("last_name",$r->last_name)
			->execute();
	}
}

