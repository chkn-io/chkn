<?php

namespace App\View;
/**
 *
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 *
 *
 * Class View
 * This class set all the links for css and javascript
 * This class also set the content of the site and all the visible part of the website/system
 *
 */


class View {
    protected $_file;
    function Html_Objects($type,$value){
        $css = array();
        $js = array();
        switch($type){
            case 'css':
                $ex = explode(',',CSS_LIBRARY);
                for($x=0;$x<count($ex);$x++){
                    if($ex[0] != ""){
                        $path = DEFAULT_URL.'public/vendor/css/';
                        $file = $ex[$x];
                        if(file_exists('public/vendor/css/'.$file)){
                            $css[$x] = '<link href="'.$path.$file.'" rel="stylesheet" type="text/css">';
                        }else{
                            if(CSS_ERROR == 1){
                             $this->html_error('public/vendor/css/',$file);
                            }
                        }
                    }
                }
                $y = count($ex);
                $private_css="";
                for($x=0;$x<count($value);$x++){
                    $y++;
                    $path = DEFAULT_URL.'public/css/';
                    $file = $value[$x];
                    if(file_exists('public/css/'.$file)){
                        $private_css.=$value[$x].',';
                        $css[$y] = '<link href="'.$path.$file.'" rel="stylesheet" type="text/css">';
                    }else{
                        if(CSS_ERROR == 1){
                         $this->html_error('public/css/',$file);
                        }
                    }
                }
                define('PRIVATE_STYLESHEETS',substr($private_css,0,strlen($private_css) - 1));
                return implode("\n",$css);
                break;
            case 'js':
                $ex = explode(';',JS_LIBRARY);
                for($x=0;$x<count($ex);$x++){
                    if($ex[0] != ""){
                         $path = DEFAULT_URL.'public/vendor/js/';
                        $file = $ex[$x];
                        if(file_exists('public/vendor/js/'.$file)){
                            $js[$x] = '
                            <script type="text/javascript" src="'.$path.$file.'"></script>';
                        }else{
                            if(JS_ERROR == 1){
                                $this->html_error('public/vendor/js/',$file);
                            }
                        }
                    }
                }
                $y = count($ex);
                $private_js = "";
                for($x=0;$x<count($value);$x++){
                $y++;
                    $path = DEFAULT_URL.'public/js/';
                    $file = $value[$x];
                    if(file_exists('public/js/'.$file)){
                        $private_js.=$value[$x].',';
                        $js[$y] = '
                        <script type="text/javascript" src="'.$path.$file.'"></script>';
                    }else{
                        if(JS_ERROR == 1){
                            $this->html_error('public/js/',$file);
                        }
                    }
                }

                define('PRIVATE_SCRIPT',substr($private_js,0,strlen($private_js) - 1));
                return implode("\n",$js);
                break;
            case 'page':
                return file_get_contents('view/page/'.$value);
                break;
        }
    }

    function defaultVendors(){
        return implode("\n",$css);
    }


    private function html_error($path = '',$file = ''){
        echo '
        <p style="float:left;width:100%;border:1px solid red;background-color:#ff3333;padding:5pt;color:#fff;margin:0;z-index:9999">CHKN Error! The file '.$file.' not found at '.$path.'
        </p>';
    }
}
