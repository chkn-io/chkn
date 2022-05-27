<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;
use App\Database\DB;

class homeController extends Controller{
	public function home(){
		return scene("index","homepage/index")
			->title("Hello World")
			->show();
	}
}