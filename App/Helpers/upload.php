<?php
namespace App\Helpers;

class UploadHelper {
    static function  upload($file_location,$file,$file_name){
        if(isset($file)){
            if($file['type'] == 'image/jpeg' || $file['type'] == 'image/png' || $file['type'] == 'image/gif' || $file['type'] == 'image/jpg'){
                if($file['size'] < DEFAULT_IMAGE_SIZE){
                    move_uploaded_file($file['tmp_name'],'public/'.$file_location.$file_name);
                }else{
                    return 'Image size is too big!';
                }
            }
            if($file['type'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                || $file['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                || $file['type']=='application/vnd.openxmlformats-officedocument.presentationml.presentation'
                || $file['type']=='application/msword'
                || $file['type'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.template'
                || $file['type'] == 'application/vnd.ms-word.document.macroEnabled.12'
                || $file['type'] == 'application/vnd.ms-word.template.macroEnabled.12'
                || $file['type'] == 'application/vnd.ms-excel'
                || $file['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.template'
                || $file['type'] == 'application/vnd.ms-excel.sheet.macroEnabled.12'
                || $file['type'] == 'application/vnd.ms-excel.template.macroEnabled.12'
                || $file['type'] == 'application/vnd.ms-excel.addin.macroEnabled.12'
                || $file['type'] == 'application/vnd.ms-excel.sheet.binary.macroEnabled.12'
                || $file['type'] == 'application/vnd.ms-powerpoint'
                || $file['type'] == 'application/vnd.openxmlformats-officedocument.presentationml.template'
                || $file['type'] == 'application/vnd.openxmlformats-officedocument.presentationml.slideshow'
                || $file['type'] == 'application/vnd.ms-powerpoint.addin.macroEnabled.12'
                || $file['type'] == 'application/vnd.ms-powerpoint.presentation.macroEnabled.12'
                || $file['type'] == 'application/vnd.ms-powerpoint.template.macroEnabled.12'
                || $file['type'] == 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12'
                || $file['type'] == 'application/pdf'
                || $file['type'] == 'text/plain'
            ){
                if($file['size'] < DEFAULT_FILE_SIZE){
                    move_uploaded_file($file['tmp_name'],'public/'.$file_location.$file_name);
                }else{
                    return 'File size is too big!';
                }
            }
        }

    }

}