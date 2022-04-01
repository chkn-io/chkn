<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;
use http\Module\Module;
use App\Database\DB;

class index extends Controller{
	public function index_page(Request $r){
		//Call index template
		$this->template('index');
		//set default title
		$this->title('CHKN Framework');
		//set css
		$this->css(array(
		));
		//set js
		$this->js(array(
		));
		$this->body('homepage/index');
		$stmt = DB::select("records")->fetch();

		print_r($stmt);
		$this->show();

	}
}
