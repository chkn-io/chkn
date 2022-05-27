<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;

class sampleController extends Controller{
	public function sample(){
        return scene("index","sample/index")
			->title("")
			->show();
	}
}

