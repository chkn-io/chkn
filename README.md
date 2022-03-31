# CHKN3
PHP Framework for developing lightweight server-side application. 


## Installation
1. Download and copy the CHKN3 Files on your local server.
2. Open the CHKN console by navigating to http://localhost/chkn/console
   * Enter the command chkn -help for the list of commands.
   * You need to include the word chkn before the command.
3. Change the rootfolder of the app. It must be the name of your main folder.
    * Use `chkn rootfolder` command
4. Application Key Installation. 
    * Use `chkn install key` command
5. Database Configuration
    * Use `chkn db -help` command for the syntax.


## Package
Default classes on CHKN Framework are grouped and compiled inside Drive://xampp/htdocs/rootfolder/config/Package/package.conf  


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

* **DEFAULT Class** - *For displaying the status of the site using `$this->chkn_default`. Set value to 0 to disable*
```
DEFAULTS=1
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
* On the CHKN Console. Simply enter `chkn create controller ___name of controller___`.

Every Controller will contain the following codes.
```php
<?php
use App\App\Request;
class sampleController extends Controller{
     public function sample_page(Request $r){
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
	$this->show();
     }
}


```

* **$this->template('index')** - *Calling the the page template. 'index' is pertaining the the template file located at view/template/index.tpl*
* **$this->title();** - *Set the title of the page.*
* **$this->css(array());** - *Including stylesheets inside the page. CSS Files are stored at public/css. In addition, CSS Files are called without the .css file extension*
* **$this->js(array());** - *Including scripts inside the page. Script Files are stored at public/js. In addition, Script Files are called without the .js file extension*
* **$this->body('homepage/index')** - *Including the page content inside the template. This method will only going to fetch .cvf files*
* **$this->show()** - *Compile the settings above and display the page.*


## Routing
In CHKN Framework, navigating to a page means navigating to a Controller. As you created a page you must also create a Controller that will hold all the request or processes of that page
### Navigation through Links
Most of the times, links are most likely use when navigating from one page to another. Exactly what you can do in CHKN . On creating link location you must follow the exact format. [chkn:path]controllerName/methodName

```html
<a href= "[chkn:path]controllerName/methodName">Link Name < /a>
```

## Templating Tool
### Passing values to your template
Data processed by controller can be pass inside your template using the $this-> variable() method.
```php
//your controller 
$this-> variable( “variable_name”, ”variable_value” ); 
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
$array = array( 
     0=> array( 
        "name"=> "Ben", 
        "age"=> "25"),
	  
     1=> array( 
	"name"=> "Anna", 
	"age"=> "24") 
); 

$this->array_var( “array_name”, $array);

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
$this->variable( “type”, ”active” ); 
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

### Select Query
On CHKN Query Builder, Select Operation is executed using `$this->CRUD->select()`;
#### Select All
```php
public function select(Request $r){
	$data = $this->CRUD->select("table")->fetch();
}
```

#### Select By Table Field
```php
public function select(Request $r){
	$data = $this->CRUD->select("table")
			->where("table_field","=","value")
			->fetch();
}
```

#### Select with JOIN
```php
public function select(Request $r){
	$data = $this->CRUD->select("table")
			->join("other_table","table.field","=","other_table.field")
			->where("table.field","=","value")
			->fetch();
}
```

#### Select with Limit
```php
public function select(Request $r){
	$data = $this->CRUD->select("table")
			->limit(1)
			->fetch();
}
```
#### Select By Order
```php
public function select(Request $r){
	$data = $this->CRUD->select("table")
			->orderBy("table_field","ASC")
			->fetch();
}
```

### Insert Query
On CHKN Query Builder, Insert Operation is executed using `$this->CRUD->insert()`;
#### INSERT
```php
public function insert(Request $r){
	$data = $this->CRUD->insert("table")
			->field("field_name","value")
			->field("field_name","value")
			->field("field_name","value")
			->execute();
}
```

### Delete Query
On CHKN Query Builder, Delete Operation is executed using `$this->CRUD->delete()`;
#### DELETE
```php
public function delete(Request $r){
	$data = $this->CRUD->delete("table")
			->where("field_name","=","value")
			->execute();
}
```

### Update Query
On CHKN Query Builder, Update Operation is executed using `$this->CRUD->update()`;
#### UPDATE
```php
public function update(Request $r){
	$data = $this->CRUD->update("table")
			->field("field_name","value")
			->field("field_name","value")
			->field("field_name","value")
			->where("field_name","=","value")
			->execute();
}
```

### Query
On CHKN Query Builder, You can create your own query using `$this->CRUD->query()`;
#### QUERY
```php
public function query(Request $r){
	$data = $this->CRUD->query("SELECT * FROM table WHERE field={field}")
			->bind("field","value of field")
			->execute();
}
```
