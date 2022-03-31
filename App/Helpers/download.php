<?php
namespace App\Helpers;
/**
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 * Settings Page
 * Class download_helper
 */
class download_helper {
    public static function download($filename,$file_location){
        $address = 'public/'.$file_location.$filename;
        if (file_exists($address)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($address));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($address));
            ob_clean();
            flush();
            readfile($address);
            exit;
        }else{
        }
    }

}