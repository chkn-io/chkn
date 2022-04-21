<?php
namespace App\Helpers;


use App\Helpers\UploadHelper;
use App\Helpers\encrypt_helper;
use App\Helpers\defaults;
class Helper {
	
    public static function upload($file_location,$image,$image_name){
        if(UPLOAD == 1){
            UploadHelper::upload($file_location,$image,$image_name);
        }else{
            echo "UPLOAD is disabled";
        }
	}

    public static function encrypt($value = ''){
        if(ENCRYPTION == 1){
            $response = encrypt_helper::encrypt($value);
            return $response;
        }else{
            echo "ENCRYPTION is disabled";
            exit;
        }
        
    }

    public static function decrypt($value = ''){
        if(ENCRYPTION == 1){
            $response = encrypt_helper::decrypt($value);
            return $response;
        }else{
            echo "ENCRYPTION is disabled";
            exit;
        }
    }

    public static function download($filename,$file_location){
        if(DOWNLOAD == 1){
           download_helper::download($filename,$file_location);
        }else{
            echo "DOWNLOAD is disabled";
            exit;
        }
    }

    public static function defaults(){
        if(DEFAULTS == 1){
            return defaults::start();
        }else{
            echo "DEFAULT is disabled";
            exit;
        }
    }
}