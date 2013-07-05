<?php

  $myself   = rex_request('page', 'string');
  
  if($REX["ADDON"][$myself]["settings"]["SELECT"]["php_debug"])
  {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
  }
  
  require_once $REX['INCLUDE_PATH'].'/addons/'.$myself.'/action/action.upload.php';
