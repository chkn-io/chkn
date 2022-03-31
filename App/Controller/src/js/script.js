$(document).click(function(){
	$(".console-input").focus();
});
var logs = {
};
var timestamp = "";
setInterval(function(){
	var currentDate = new Date(),
      day = currentDate.getDate(),
      month = currentDate.getMonth() + 1,
      year = currentDate.getFullYear();
        hours = currentDate.getHours(),
      minutes = currentDate.getMinutes();
      seconds = currentDate.getSeconds();

	if (minutes < 10) {
	 minutes = "0" + minutes;
  }

  if (seconds < 10) {
	 seconds = "0" + seconds;
  }


	var suffix = "AM";
	if (hours >= 12) {
    suffix = "PM";
    hours = hours - 12;
	}
	if (hours == 0) {
	 hours = 12;
	}

	 if (hours < 10) {
	 hours = "0" + hours;
  }
      timestamp = day + "/" + month + "/" + year + " " + hours + ":" + minutes +":"+ seconds +" " + suffix + " |";
      $("#console p").html(timestamp);
},500);
setInterval(function(){
	$.ajax({
		url:"console/check_trigger/",
		type:"GET",
		success:function(e){
			if(e == 1){
				$.ajax({
					url:"console/console_push",
					type:"GET",
					dataType:"JSON",
					success:function(e){
						var counter = 0;
						$(e.data).each(function(){
							var sclass = "";
							if(e.data[counter].status == "success"){
								sclass = "success";
							}else if(e.data[counter].status == "info"){
								sclass = "info";
							}else{
								sclass = "error";
							}
							$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="'+sclass+'"> '+e.data[counter].message+'</span></li>');
							counter++;
						});
						// $("#container").animate({ scrollTop: $('#container').prop("scrollHeight")}, 1000);
					}
				});
			}
		}
	});
},1000);
var count = 0;
$(document).keydown(function(e){
    if (e.keyCode == 40) { 
       $(".console-input").val(logs[count]);
       count = count - 1;
       if(count < 0){
       	count = (Object.keys(logs).length);
       }
    }
});

$(document).keydown(function(e){
    if (e.keyCode == 38) { 
       $(".console-input").val(logs[count]);
       count = count + 1;
       if(count > Object.keys(logs).length){
       	count = 0;
       }
    }
});
$(".console-input").keypress(function(e) {
    if(e.which == 13) {
    	var val = $(this).val();
        
        var obj = $("#container #console-con");
        count = (Object.keys(logs).length);
		logs[count] = val;
		if(val != ""){

        	$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> Me: <span class="command">'+val+'</span></li>');
			if(val == "chkn clear"){
	        	obj.html("");	

	        }
	        else if(val.includes("chkn status -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Display Database Connection and Application Key Status</span></li>');
	        }
	        else if(val.includes("chkn styles -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> List of Global Styles Used.</span></li>');
	        }
	        else if(val.includes("chkn scripts -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> List of Global Scripts Used.</span></li>');
	        }
	        else if(val.includes("chkn libraries -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> List of Global Libraries Used.</span></li>');
	        }
	        else if(val.includes("-status")){
	        	var raw = val.replace(" -status","");
	        	raw = raw.replace("chkn ","");
	        	if(raw != "styles" && raw != "scripts" && raw != "libraries" && raw != "status"){
		        	getValues(raw);
	        	}else{
	        		obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="error">Invalid -status command on '+raw+'</span></li>');
	        	}
	        }else if(val.includes("chkn rootfolder -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Root Folder</span></li>');
	        }
	        else if(val.includes("chkn styles")){
	        	getValues("styles");
	        }
	        else if(val.includes("chkn scripts")){
	        	getValues("scripts");
	        }
	        else if(val.includes("chkn libraries")){
	        	getValues("libraries");
	        }
	        else if(val.includes("chkn status")){
	        	getValues("status");
	        }
	        else if(val.includes("chkn clear -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Clear the Console Page</span></li>');
	        }
	        else if(val.includes("chkn restart -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Restart the Console Page</span></li>');
	        }
	        else if(val.includes("chkn db -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Database Configuration. Must follow the following format (Database Host,Database Name, Database Username and Database Password)</span></li>');
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Example localhost,sample_db_name,sample_username,sample_password</span></li>');
	        	
	        }
	        
	        else if(val.includes("chkn dbconnection -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Database Connection. By default it is `mysql`</span></li>');
	        }
	        else if(val.includes("chkn dbhost -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Database Host</span></li>');
	        }
	        else if(val.includes("chkn dbname -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Database Host</span></li>');
	        }
	        else if(val.includes("chkn dbcharset -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Database Charset</span></li>');
	        }
	        else if(val.includes("chkn dbusername -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Database Username</span></li>');
	        }
	        else if(val.includes("chkn dbpassword -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Database Password</span></li>');
	        }
	        else if(val.includes("chkn console -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Turn OFF/ON console by changing its value. Change value to 0 to OFF and 1 to ON.</span></li>');
	        }
	        else if(val.includes("chkn rootfolder -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Root Folder</span></li>');
	        }
	        else if(val.includes("chkn install key -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Automate Application Key setup on the Application</span></li>');
	        }
	        else if(val.includes("chkn clear key -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Clear Application Key</span></li>');
	        }
	        else if(val.includes("chkn local -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Local. Change value to 1 if you are working locally and 0 if not.</span></li>');
	        }
	        else if(val.includes("chkn csserror -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s CSS ERROR display function status. Change the value to 1 if your want to display css errors and 0 if not.</span></li>');
	        }
	        else if(val.includes("chkn jserror -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the Application\'s Script ERROR display function status. Change the value to 1 if your want to display js errors and 0 if not.</span></li>');
	        }
	        else if(val.includes("chkn imagesize -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the upload limit for Images.</span></li>');
	        }
	        else if(val.includes("chkn filesize -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Change the upload limit for Files.</span></li>');
	        }
	        else if(val.includes("chkn auth create -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Automate Authentication Page. </span></li>');
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> This function will automatically create a table `chkn_auth` on your database and setup a primary account(Username:admin, Password:admin) </span></li>');
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Controller and Pages for the Authentication Function will be automatically created too.</span></li>');
	        }
	        else if(val.includes("chkn defineglobal -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Define new Constants for the Application (CONSTANT_NAME,contant_value).</span></li>');
	        }
	        else if(val.includes("chkn create controller -help")){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="command"> Create New Controller in the System</span></li>');
	        	obj.append('<li><span class="time">'+timestamp+'</span> <span class="command"> Note: Controller\'s name must start with a small letter.</span></li>');
	        	obj.append('<li><span class="time">'+timestamp+'</span> <span class="command"> A view folder will also be generated together with the Controller.</span></li>');
	        }	
	        else if(val == "chkn"){
	        	obj.append('<li><span class="time">'+timestamp+'</span> System: <span class="info">Please enter the Command `chkn help` for the list of CHKN Commands</span></li>');
	        }else if(val == "chkn help"){
	        	obj.append('<li>'+

	        				'<span class="time">'+timestamp+'</span> System: <span class="info"> CHKN Console Commands'+
	        				'<p><span class="divider"></span></p>'+ 
							'<p><span>clear</span><span>Clears the Screen</span></p>'+   
							'<p><span>restart</span><span>Restart the Console</span></p>'+   
							'<p><span>create controller</span><span>Create New Controller</span></p>'+  
							
							'<p><span class="divider"></span></p>'+
							'<p><span>status</span><span>Application Status</span></p>'+
							'<p><span>styles</span><span>Global Styles used</span></p>'+
							'<p><span>scripts</span><span>Global Scripts used</span></p>'+
							'<p><span>libraries</span><span>Global Libraries used</span></p>'+
							'<p><span class="divider"></span></p>'+        		
							
							'<p><span>db</span><span>Change the Database Configuration. Must follow the following format (Database Host,Database Name, Database Username and Database Password)</span></p>'+
							'<p><span>dbconnection</span><span>Change the Database Connection. By default it is `mysql` </span></p>'+    
							'<p><span>dbhost</span><span> Change the Database Host</span></p>'+   
							'<p><span>dbname</span><span> Change the Database Name</span></p>'+   
							'<p><span>dbcharset</span><span> Change the Database Charset</span></p>'+
							'<p><span>dbusername</span><span> Change the Database Username</span></p>'+
							'<p><span>dbpassword</span><span> Change the Database Password</span></p>'+   
							'<p><span class="divider"></span></p>'+       
							
							'<p><span>console</span><span> Turn OFF/ON console by changing its value. Change value to 0 to OFF and 1 to ONN.</span></p>'+   
							'<p><span>rootfolder</span><span> Change the Application\'s Root Folder</span></p>'+ 
							'<p><span>install key</span><span> Change the Application\'s Key</span></p>'+ 
							'<p><span>clear key</span><span> Clear the Application\'s Key</span></p>'+   	  
							'<p><span>local</span><span> Change the Application\'s Local. Change value to 1 if you are working locally and 0 if not.</span></p>'+   
							'<p><span>csserror</span><span> Change the Application\'s CSS ERROR display function status. Change the value to 1 if your want to display css errors and 0 if not.</span></p>'+   	  	  		 
	        				'<p><span>jserror</span><span> Change the Application\'s Script ERROR display function status. Change the value to 1 if your want to display js errors and 0 if not.</span></p>'+  
							'<p><span>imagesize</span><span> Change the upload limit for Images.</span></p>'+   
							'<p><span>filesize</span><span> Change the upload limit for Files.</span></p>'+  
							'<p><span class="divider"></span></p>'+ 
							'<p><span>auth create</span><span>Automate Authentication Page</span></p>'+
							'<p><span>defineglobal</span><span>DEFINE NEW CONSTANTS. (CONSTANT_NAME,constant_value)</span></p>'+  
							
							'<p><span class="divider"></span></p>'+ 
							'<p><span>command -help</span><span>Display command information</span></p>'+
							'<p><span>command -status</span><span>Display command status</span></p>'+  
	        		'</li>');
	        }else if(val.includes("chkn create controller")){
	        	var raw = val.replace("chkn create controller","");
	        	if(raw == ""){
	        		$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">Undefined value for CREATE CONTROLLER command `'+val+'`</span></li>');
	        	}else{
	        		raw = raw.replace(" ","");
	        		$.ajax({
	        			url:"console/create_controller/"+raw,
	        			type:"GET",
	        			dataType:"JSON",
	        			success:function(e){
	        				if(e.type == "error"){
	        					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">'+e.message+'</span></li>');
	        				}else{
	        					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="success">'+e.message+'</span></li>');
	        				}
	        			}
	        		});
	        	}
	        }else if(val.includes("chkn defineglobal")){
	        	var raw = val.replace("chkn defineglobal ","");
	        	if(raw == ""){
	        		$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">Undefined value for DEFINEGLOBAL command `'+val+'`</span></li>');
	        	}else{
	        		var split = raw.split(",");
	        		if(split.length != 2){
	        			$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">Expects at least 2 Parameters in DEFINEGLOBAL command, '+split.length+' given.</span></li>');
	        		}else{

	        		}
	        		$.ajax({
	        			url:"console/defineglobal/",
	        			type:"POST",
	        			data:{"globals":split[0],"values":split[1]},
	        			dataType:"JSON",
	        			success:function(e){
	        				if(e.type == "error"){
	        					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">'+e.message+'</span></li>');
	        				}else{
	        					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="success">'+e.message+'</span></li>');
	        				}
	        			}
	        		});
	        	}
	        }else if(val.includes("chkn dbconnection")){
	        	dbconfig(val,"DB_CONNECTION","DBCONNECTION","dbconnection");
	        }else if(val.includes("chkn dbhost")){
	        	dbconfig(val,"DB_HOST","DBHOST","dbhost");
	        }else if(val.includes("chkn dbname")){
	        	dbconfig(val,"DB_NAME","DBNAME","dbname");
	        }else if(val.includes("chkn dbcharset")){
	        	dbconfig(val,"DB_CHARSET","DBCHARSET","dbcharset");
	        }else if(val.includes("chkn dbusername")){
	        	dbconfig(val,"DB_USER","DBUSERNAME","dbusername");
	        }else if(val.includes("chkn dbpassword")){
	        	dbconfig(val,"DB_PASSWORD","DBPASSWORD","dbpassword");
	        }else if(val == "chkn restart"){
	        	window.location = "";
	        }else if(val.includes("chkn db")){
	        	var raw = val.replace("chkn db","");
				raw = raw.replace(" ","");
	        	if(raw == ""){
	        		$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">Undefined value for DB command `'+val+'`</span></li>');
	        	}else{
	        		$.ajax({
	        			url:"console/db/",
	        			data:{
	        				"config_string":raw
	        			},
	        			type:"POST",
	        			dataType:"JSON",
	        			success:function(e){
	        				if(e.type == "error"){
	        					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">'+e.message+'</span></li>');
	        				}else{
	        					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="success">'+e.message+'</span></li>');
	        				}
	        			}
	        		});
	        	}
	        }
	        
	        else if(val.includes("chkn rootfolder")){
	        	appconfig(val,"ROOT_FOLDER","ROOTFOLDER","rootfolder");
	        }else if(val.includes("chkn local")){
	        	appconfig(val,"LOCAL","LOCAL","local");
	        }else if(val.includes("chkn console")){
	        	appconfig(val,"CONSOLE","CONSOLE","console");
	        }else if(val.includes("chkn csserror")){
	        	appconfig(val,"CSS_ERROR","CSSERROR","csserror");
	        }else if(val.includes("chkn csserror")){
	        	appconfig(val,"JS_ERROR","JSERROR","jserror");
	        }else if(val.includes("chkn imagesize")){
	        	appconfig(val,"DEFAULT_IMAGE_SIZE","IMAGESIZE","imagesize");
	        }else if(val.includes("chkn filesize")){
	        	appconfig(val,"DEFAULT_FILE_SIZE","FILESIZE","filesize");
	    	}else if(val.includes("chkn auth create")){
	        	$.ajax({
	        		url:"console/checkdatabase/",
	        		type:"POST",
	        		dataType:"JSON",
	        		beforeSend:function(){
	        			obj.append('<li><span class="time">'+timestamp+'</span> <span class="command">Checking database connection...</span></li>');
	        		},
	        		success:function(e){
	        			if(e.type ==  "success"){
							obj.append('<li><span class="time">'+timestamp+'</span> <span class="success">Database Found!</span></li>');
							$.ajax({
								url:"console/createtable/",
				        		type:"POST",
				        		dataType:"JSON",
				        		beforeSend:function(){
				        			obj.append('<li><span class="time">'+timestamp+'</span> <span class="command">Create authentication table...</span></li>');
				        		},
				        		success:function(e){
	        						if(e.type ==  "success"){
										obj.append('<li><span class="time">'+timestamp+'</span> <span class="success">Table successfully created!</span></li>');
										$.ajax({
	        								url:"console/createauthcontroller/",
	        								dataType:"JSON",
							        		beforeSend:function(){
							        			obj.append('<li><span class="time">'+timestamp+'</span> <span class="command">Creating AuthController.php...</span></li>');
							        		},
							        		success:function(e){
							        			if(e.type ==  "success"){
													obj.append('<li><span class="time">'+timestamp+'</span> <span class="success">Table successfully created!</span></li>');
				        						}else{
				        							
				        						}						
							        		}
	        							});
	        						}else{
										obj.append('<li><span class="time">'+timestamp+'</span> <span class="success">Table already exist!</span></li>');
	        						}
				        		}
							});
	        			}else{
	        				obj.append('<li><span class="time">'+timestamp+'</span> <span class="error">Database Not Found!</span></li>');
	        			}
	        		}
	        	});


	        }
	        else if(val == "chkn install key"){
	        	$.ajax({
	        		url:"console/installKey/",
	        		type:"GET",
	        		dataType:"JSON",
	        		success:function(e){
	        			if(e.type == "error"){
	    					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">'+e.message+'</span></li>');
	    				}else{
	    					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="success">'+e.message+'</span></li>');
	    				}
	        		}
	        	});

	        }else if(val == "chkn clear key"){
	        	appconfig("_blank_","APPLICATION_KEY","APPLICATION_KEY","clear key");
	        }else{
	        	$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">Unknown CHKN Command `'+val+'`</span></li>');
	        }
	        $(this).val("");
	        $("#container").animate({ scrollTop: $('#container').prop("scrollHeight")}, 1000);
		}
        
    }
});


function dbconfig(val,defaults,commands,command2){
	var raw = val.replace("chkn "+command2,"");
	raw = raw.replace(" ","");
	if(raw != ""){
		$.ajax({
    		url:"console/dbconfig/"+raw,
    		type:"POST",
    		data:{
    			"defaults":defaults,
    			"commands":commands
    		},
    		dataType:"JSON",
    		success:function(e){
    			if(e.type == "error"){
					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">'+e.message+'</span></li>');
				}else{
					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="success">'+e.message+'</span></li>');
				}
    		}
    	});
	}else{
		$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">Undefined value for '+command2+' command `'+val+'`</span></li>');
	}
}

function getValues(command){
	if(command != ""){
		$.ajax({
			url:"console/getValues/",
			type:"POST",
			data:{"command":command},
			dataType:"JSON",
			success:function(e){
				$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="info">'+e.value+'</span></li>');
			}
		});
	}else{
		$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">Undefined value for command</span></li>');
	}
}

function appconfig(val,defaults,commands,command2){
	var raw = val.replace("chkn "+command2,"");
	raw = raw.replace(" ","");
	if(raw != ""){
		$.ajax({
    		url:"console/appconfig/"+raw,
    		type:"POST",
    		data:{
    			"defaults":defaults,
    			"commands":commands
    		},
    		dataType:"JSON",
    		success:function(e){
    			if(e.type == "error"){
					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">'+e.message+'</span></li>');
				}else{
					$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="success">'+e.message+'</span></li>');
				}

				if(command2 == "console"){
					location.reload();
				}
    		}
    	});
	}else{
		$("#container #console-con").append('<li><span class="time">'+timestamp+'</span> System: <span class="error">Undefined value for '+command2+' command `'+val+'`</span></li>');
	}
}

$(".console-input").focus();