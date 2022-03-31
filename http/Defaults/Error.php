<?php
namespace http\Defaults;

/**
 *
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 * Class Error
 * Holds the function that will load error page when there is no class or function found
 */
class chknError{
	public function error_page(){
      $template = file_get_contents(DEFAULT_URL.'view/defaults/error.tpl');
      $template = str_replace('[chkn:error]',DEFAULT_URL,$template);
      echo $template;
	}
}
