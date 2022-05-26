<?php
use App\Helpers\UploadHelper;
use App\Helpers\encrypt_helper;
use App\Helpers\defaults;
use App\App\CSRFToken;



function seal($value = ""){
    $encrypt = encrypt_helper::encrypt($value);
    return base64_encode($encrypt);
}

function rseal($value = ""){
    $decode = base64_decode($value);
    $decrypt = encrypt_helper::decrypt($decode);
    return $decrypt;
}

function array_seal($array = array(),$field = ""){
    for($x=0;$x<count($array);$x++){
        $array[$x][$field] = $this->seal($array[$x][$field]);
    }
    return $array;
}

function locate($url){
    header('location:'.DEFAULT_URL.$url);
}

function response($info = [],$header=200){
    header("HTTP/1.0 ".$header);
    header("Content-Type:application/json");
    return json_encode($info);
}

function upload($file_location,$image,$image_name){
    if(UPLOAD == 1){
        UploadHelper::upload($file_location,$image,$image_name);
    }else{
        echo "UPLOAD is disabled";
    }
}

function encrypt($value = ''){
    if(ENCRYPTION == 1){
        $response = encrypt_helper::encrypt($value);
        return $response;
    }else{
        echo "ENCRYPTION is disabled";
        exit;
    }
}

function decrypt($value = ''){
    if(ENCRYPTION == 1){
        $response = encrypt_helper::decrypt($value);
        return $response;
    }else{
        echo "ENCRYPTION is disabled";
        exit;
    }
}

function download($filename,$file_location){
    if(DOWNLOAD == 1){
       download_helper::download($filename,$file_location);
    }else{
        echo "DOWNLOAD is disabled";
        exit;
    }
}

function defaults(){
    if(DEFAULTS == 1){
        return defaults::start();
    }else{
        echo "DEFAULT is disabled";
        exit;
    }
}


function d($data){
    if(is_null($data)){
        $str = "<i>NULL</i>";
    }elseif($data == ""){
        $str = "<i>Empty</i>";
    }elseif(is_array($data)){
        if(count($data) == 0){
            $str = "<i>Empty array.</i>";
        }else{
            $str = "<div>";
            if(is_array($data)){
                $str.='<span class="array">array('.count($data).'){</span>';
            }
            foreach ($data as $key => $value) {
                
                $t = gettype($value);
                $start="";
                $end="";
                if($t == "integer"){
                    $start = "int(";
                    $end = ")";
                }elseif($t == "string"){
                    $start = "string(".strlen($value).") ".'"';
                    $end = '"';
                }elseif($t == "double"){
                    $start = $t."(";
                    $end =')';
                    
                }
                if(is_array($data)){
                    $str .='<ul>';
                }

                $kt = gettype($key);
                if($kt == "string"){
                    $k = '"'.$key.'"';
                }else{
                    $k = $key;
                }
                $str .= "<li>
                            <span class='key'>".$k."</span> 
                            <span class='director'>=></span> 
                            <span class='start'>" .$start ."</span>
                            <span class='content'>". d($value) . "</span>
                            <span class='end'>".$end. "</span></li>";

                if(is_array($data)){
                    $str .='</ul>';
                }
                
            }

            if(is_array($data)){
                $str.='<span class="array">}</span>';
            }
            $str .= "</div>";
        }
    }elseif(is_resource($data)){
        while($arr = mysql_fetch_array($data)){
            $data_array[] = $arr;
        }
        $str = d($data_array);
    }elseif(is_object($data)){
        $str = d(get_object_vars($data));
    }elseif(is_bool($data)){
        $str = "<i>" . ($data ? "True" : "False") . "</i>";
    }else{
        $str = $data;
        $str = preg_replace("/\n/", "<br>\n", $str);
    }
    return $str;
}

function dnl($data){
    echo d($data) . "<br>\n";
}

function dd($data){
    echo '<style>
        ul{
            margin:0;
            padding:0;
            list-style:none;
        }

        div ul{
            padding-left:15pt;
        }

        div div{
            margin-left:15pt;
        }

        body{
            background-color:black;
            color:#fff;
        }
        .key{
            color:#ff6b15;
        }

        .content{
            color:yellow;
        }

        .director{
            color:#fff;
        }

        .start,.end{
            color:#16ade7;
        }

        .array{
            color:#fff;
        }
    </style>
    <body>';
    if(is_array($data)){
        echo dnl($data);
    }else{
        $t = gettype($data);
        $start="";
        $end="";
        if($t == "integer"){
            $start = "int(";
            $end = ")";
        }elseif($t == "string"){
            $start = "string(".strlen($data).") ".'"';
            $end = '"';
        }else{
            $start = $t."(";
            $end =')';
        }
        echo '<span class="start">'.$start.'</span><span class="content">'.$data.'</span><span class="end">'.$end.'</span>';
    }
    echo '</body>';
    exit;
}

function ddt($message = ""){
    echo "[" . date("Y/m/d H:i:s") . "]" . $message . "<br>\n";
}