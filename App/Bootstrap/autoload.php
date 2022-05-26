<?php
namespace App\Bootstrap;

use App\App\App;
// use App\Exception\Catcher;

session_start();


date_default_timezone_set('Asia/Manila');

require_once('App/App/App.php');
$app = new App;



// App Components
require_once('App/App/App_Controller.php');
require_once('App/App/Session.php');
require_once('App/App/App_Model.php');
require_once('App/App/Request.php');
require_once('App/App/Loader.php');
require_once('App/Route/Router.php');

// Defaults
if(PAGE_NOT_FOUND == 1){
    require_once('App/View/Error.php');
}

if(MAINTENANCE_CLASS == 1){
    require_once('App/View/Maintenance.php');
}

// App Libraries

if(QUERY_BUILDER == 1){
    require_once('App/Model/Model.php');
    require_once('App/Database/DB.php');
}

require_once('App/View/View.php');
require_once('App/Controller/Controller.php');
require_once('App/Controller/Auth.php');
require_once('App/App/CSRFToken.php');


// Modules
if(MODULE == 1){
    foreach(glob("http/Module/*.php") as $filename){
      include $filename;
    }
}

require_once('App/Template/CHKNTemplate.php');

$ex = explode(";",CHKN_ASSETS);
foreach($ex as $value){
    if(file_exists('App/Assets/'.$value)){
        if($value != ""){
            require_once('App/Assets/'.$value);
        }
    }
}
// Helpers
require_once('App/Helpers/helper.php');
require_once('App/Helpers/encryption.php');
require_once('App/Helpers/upload.php');
require_once('App/Helpers/download.php');
require_once('App/Helpers/default.php');


require_once('http/Controllers/index.php');




