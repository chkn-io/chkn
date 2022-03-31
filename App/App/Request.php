<?php
namespace App\App;
class Request
{
    public $request;
    public $request_status;
    public $who_error;
    public function __construct($request,$files){
        $this->httpRequest($request,$files);
        $this->request = $request;
    }

    public function check($cons = ["*"=>"not null"]){
        if(isset($cons["*"])){
            if($cons["*"] == "not null"){
                 $checker = 0;
                 $who = "";
                foreach ($this->request as $key => $value) {
                    if($value == ""){
                        $checker++;
                        $who.=$key."|";
                    }
                }
                
            }
        }else{
            $checker = 0;
            $who = "";
            foreach ($cons as $value) {
                if($this->$value == ""){
                    $checker++;
                    $who.=$value."|";
                }
            }
        }


        $who = substr($who,0,(strlen($who)-1));
        if($checker == 0){
            $this->request_status = 1;
            $this->who_error = [];
        }else{
            $this->request_status = 0;
            $this->who_error = explode("|",$who);
        }
        return $this;

    }

    public function isset($cons = []){
        $checker = 0;
        $who = "";
        foreach ($cons as $key => $value) {
            if(!isset($this->$value)){
                $checker++;
                $who.=$value."|";
            }
        }

        $who = substr($who,0,(strlen($who)-1));
        if($checker == 0){
            $this->request_status = 1;
            $this->who_error = [];
        }else{
            $this->request_status = 0;
            $this->who_error = explode("|",$who);
        }
        return $this;
    }

    public function status(){
        return $this->request_status;
    }

    public function who(){
        return $this->who_error;
    }

    
    private function httpRequest($request,$files){
        if($files){
            $request = array_merge($request,$files);
        }else{
            $request = $request;
        }
        if(count($request) != 0){
             $get = explode("/",$request["url"]);
            if($get[0] == ""){
                $this->class = "index";
            }elseif(count($get) == 1){
                $this->class = $get[0];
            }else{
                $this->class = $get[0];
                $this->method = $get[1];
            }

            for($x=2;$x<count($get);$x++){
                $c = "g".($x-1);
                $this->$c = $get[$x];
            }

            foreach ($request as $key => $value) {
                if($key != "url"){
                $this->$key = $value;
                }
            }
        }
       

    }
    
   
}
