<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;
use App\Helpers\Helper;
use App\Database\DB;

class index extends Controller{
	public function index_page(){
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
		$this->body("homepage/index");

		$this->show();
	}
}

