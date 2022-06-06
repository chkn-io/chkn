# CHKN3
PHP Framework for developing lightweight server-side application. 


## Installation
1. Download the package using composer.
   * `composer create-project chkn/core folder_name`
2. Install Application Key
   * `php chkn key:generate`
3. Change the rootfolder of the app. It must be the name of your main folder.
   * `php chkn root folder_name`
4. Database Configuration
    * Changing Database Connection = `php chkn db:connection __Connection__`
    * Changing Database Host = `php chkn db:host __host__`
    * Changing Database Name = `php chkn db:name __name__`
    * Changing Database Charset = `php chkn db:charset __charset__`
    * Changing Database Username = `php chkn db:username __username__`
    * Changing Database Password = `php chkn db:password __password__`


## Package
Default classes on CHKN Framework are grouped and compiled inside `Drive:ROOT_FOLDER/config/Package/package.conf`


* **Query Building Class** - *Set value to 0 to disable*
```
QUERY_BUILDER=1
```

* **Encryption and Decryption Class** - *Set value to 0 to disable*
```
ENCRYPTION=1
```

* **Download Class** - *Set value to 0 to disable*
```
DOWNLOAD=1
```

* **Upload Class** - *Set value to 0 to disable*
```
UPLOAD=1
```

* **Session Class** - *Set value to 0 to disable*
```
SESSION=1
```

* **Maintenance Class** - *Set value to 0 to disable*
```
MAINTENANCE_CLASS=1
```

* **Page Not Found Class** - *Display a Page Not Found Page if class or method was not found. Set value to 0 to disable*
```
PAGE_NOT_FOUND=1
```

* **CSRF Token Class** - *Set value to 0 to disable*
```
CSRF=1
```




## Vendor Styles and Scripts

Include a global styles and scripts on the application. 

* **Include Global CSS** - *Go to `config/vendors/stylesheet.conf`*
```
/**Set your App's Global Stylesheet/Libraries
/**Include every file after a new line
/**Save the stylesheet inside public/vendor/css/
/**Don't include the file extension

/**Start
bootstrap.min
fontawesome.min
fontawesome-all
/**End
```

* **Include Global Scripts** - *Go to `config/vendors/scripts.conf`*
```
/**Set your App's Global JavaScript/Libraries
/**Include every file after a new line
/**Save the scripts inside public/vendor/js/
/**Don't include the file extension

/**Start
jquery
bootstrap.min
fontawesome.min
/**End
```


## CONTROLLERS AND PAGES
As what we know about Controllers, they are the one actually responsible on what must be displayed inside a View and what must be requested inside the Model. In CHKN Framework , Controllers are the most important file on your system. Controller is the place where you have to declare what will be your template to be use, the page that will display inside your template, the page title, the page stylesheet and scripts and the place where you will becreating a request in the Model.

### Creating a Controller
* You have to create a PHP file inside the folder Http/Controller/. The File name must be the name of the Controller you want, followed by the word Controller. Example: homeController.php
* php create:controller __controller_name__

Every Controller will contain the following codes.
```php
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



```

* **scene("template","page content")** - *Calling the the page template. 'index' is pertaining the the template file located at view/template/index.tpl*
* **->title()** - *Set the title of the page.*
* **->css()** - *Including stylesheets inside the page. CSS Files are stored at public/css.*
* **->js()** - *Including scripts inside the page. Script Files are stored at public/js.*
* **->show()** - *Compile the settings above and display the page.*


## Routing
In CHKN Framework, navigating to a page means navigating to a Controller. As you created a page you must also create a Controller that will hold all the request or processes of that page
### Navigation through Links
Most of the times, links are most likely use when navigating from one page to another. Exactly what you can do in CHKN . On creating link location you must follow the exact format. [chkn:path]controllerName/methodName

```html
<a href= "[chkn:path]controllerName/methodName">Link Name < /a>
```

## Templating Tool
### Passing values to your template
Data processed by controller can be pass inside your template using the variable() chain method of the scene() function.
```php
//your controller 
<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;

class homeController extends Controller{
	public function home(){
		return scene("index","homepage/index")
			->title("Hello World")->bind([
				"variable_name"=>"value"
			])
			->show();
	}
} 
```
```html 
<body> 
<p> $variable_name < /p> 
</body>
```
### Passing array on your template
Array processed by controller can be pass inside your template using the $this-> array_var() method.
```php
//your controller 
$array = [
     0=> ["name"=> "Ben","age"=> "25"],
     1=> ["name"=> "Anna","age"=> "24"]
];

namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;

class homeController extends Controller{
	public function home(){
		return scene("index","homepage/index")
			->title("Hello World")
			->bind([
				"variable_name"=>$array
			])
			->show();
	}
} 
```

### Populating assigned array on your template using foreach
Assigned array variable on the Controller can be populated using the CHKN Framework Templating Tool. Developers can use the foreach as shown below.

<span style="color:red">Note: You can address the specific field on the array by enclosing the field name by {[ ]}</span>

```html
//on your template or page 
<body> 
#foreach( $array_name ){ 
{{ 
< p> Name: {[name]} < /p> 
< p> Age: {[age]} < /p> 
}} 
#endforeach 
</body>

```

### The if Condition
You can also set a conditional statement out of the assigned variables.
```php
//your controller 
<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;

class homeController extends Controller{
	public function home(){
		return scene("index","homepage/index")
			->title("Hello World")
			->bind([
				"type"=>"active"
			])
			->show();
	}
} 
```

```html
//on your template or page 
//include double quote(“”) on the variable 
# if( “$type” == “active” ){ 
{{ 
<p> Value if True </p> 
}} 
} else{ 
{{ 
<p> Value if False </p> 
}} 
#}

```
### The For Loop
You can easily create a looping statement such as FOR LOOP in CHKN Framework using its Templating Tool. Take note that Comparison Operator to be used must be enclosed with square bracket []


```html
//on your template or page 
#for($x=0;$x[<]$data;$x++ ){ 
{{ 
<p> Hello World {[x]} </p> 
}} 
#endfor
```


## Forms and CSRF Token
Including csrf field on forms will enable a CSRF token for every form request. This will tighten the security against CSRF or Cross-Site Request Forgery.
```html
<form method="post" action="controller/method">
	[form:csrf]
	<label>Name</label><br>
	<input type="text" name="name"><br>
	<label>Address</label><br>
	<input type="text" name="address"><br>
	<label>Age</label><br>
	<input type="number" name="age"><br>
	<button>Submit</button>
</form>
```

## Fetching Request
```php
public function fetchData(Request $r){
	$name = $r->name;
	$address = $r->address;
	$age = $r->age;
}
```

## QUERY BUILDER
On CHKN Framework, Database operations such as Create, Read, Update and Delete are handle using the QUERY BUILDER. 
Add the Class DB on your Controller Class
```php
use App\Database\DB;
```


### Select Query
On CHKN Query Builder, Select Operation is executed using `DB::select()`;
#### Select All
```php
public function select(Request $r){
	$data = DB::select("table")->fetch();
}
```

#### Select By Table Field
```php
public function select(Request $r){
	$data = DB::select("table")
			->where("table_field","=","value")
			->fetch();
}
```

#### Select with JOIN
```php
public function select(Request $r){
	$data = DB::select("table")
			->join("other_table","table.field","=","other_table.field")
			->where("table.field","=","value")
			->fetch();
}
```

#### Select with Limit
```php
public function select(Request $r){
	$data = DB::select("table")
			->limit(1)
			->fetch();
}
```
#### Select By Order
```php
public function select(Request $r){
	DB::select("table")
		->orderBy("table_field","ASC")
		->fetch();
}
```

### Insert Query
On CHKN Query Builder, Insert Operation is executed using `DB::insert()`;
#### INSERT
```php
public function insert(Request $r){
	$data = DB::insert("table")
			->field("field_name","value")
			->field("field_name","value")
			->field("field_name","value")
			->execute();
}
```

### Delete Query
On CHKN Query Builder, Delete Operation is executed using `DB::delete()`;
#### DELETE
```php
public function delete(Request $r){
	$data = DB::delete("table")
			->where("field_name","=","value")
			->execute();
}
```

### Update Query
On CHKN Query Builder, Update Operation is executed using `DB::update()`;
#### UPDATE
```php
public function update(Request $r){
	$data = DB::update("table")
			->field("field_name","value")
			->field("field_name","value")
			->field("field_name","value")
			->where("field_name","=","value")
			->execute();
}
```

### Query
On CHKN Query Builder, You can create your own query using `DB::query()`;
#### QUERY
```php
public function query(Request $r){
	$data = DB::query("SELECT * FROM table WHERE field={field}")
			->bind("field","value of field")
			->execute();
}
```

## Sessions
The functions `session_start()` is already included in the framework's core. All the functions for handling Session are included in the class Session.

### Saving Session
For saving session, use the method put().
```php
// Include the class Session
use App\App\Session;


// In a method
Session::put("session_name","session_value");

```

### Deleting Session
For deleting session, use the method clear().
```php
// Include the class Session
use App\App\Session;


// In a method
Session::clear("session_name");

```

### Reading stored Sessions
For reading stored sessions, use the method get().
```php
// Include the class Session
use App\App\Session;


// In a method
Session::get("session_name");

```

### Checking if Session exists
For checking the session's existence, use the method check().
```php
// Include the class Session
use App\App\Session;


// In a method
Session::check("session_name");

```
### Helpers
Helpers are functions added to the core of PHP Framework to help you speed and homogenise repetitive tasks on development. 


### Encryption and Decryption
Encrypting and Decrypting String in the Controller

- Encrypt
```php

// In a method
encrypt("string to encrypt");

// base64
seal("string to encrypt");

// Selected column in an array (base64)
array_seal($array,$key);

```

- Decrypt
```php

// In a method
decrypt("encrypted string");

// base64
rseal("encrypted string");

```

### Dump and Die
PHP Debugging using CHKN Framework Dump and Die.

```php
// Dump data in the application
dd($var);

// Dump date time in the application
ddt($message);
```


### Upload File
Upload Files on your application

```php
upload($file_location,$image,$image_name);
```

### Download File
Upload Files on your application

```php
download($filename,$file_location)
```

### Redirect Page
Redirect from one page to another

```php

locate("location");
```

### JSON Response
Throw a JSON Response on an HTTP Request

```php
echo response(["message"=>"success"],200);

```



## Modules
In CHKN Framework, you can define objects globally thru Modules. The App's Modules are located and can be created inside `http/Module` folder. CHKN Modules works like an ordinary Controller, but you can access it any existing Controllers inside `http/Controllers`
```php
<?php
// Module
namespace http\Module;
use App\Controller\Controller;
use App\Database\DB;
class Module extends Controller{
    public static function sample(){
	return "Hello World"
    }
}

//Controller
<?php
namespace http\Controllers;

use App\Controller\Controller;
use App\App\Request;
use http\Module\Module;

// Include the Module Class
use http\Module\Module;

class sampleController extends Controller{
    public function home(Request $r){
	// It will display Hello World in sample/home
	echo Module::sample();
    }
}

```
