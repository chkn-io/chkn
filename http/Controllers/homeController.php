<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;

class homeController extends Controller{
	public function home(){
		return scene("index","homepage/index")
			->title("Hello World")
			->show();
	}
}