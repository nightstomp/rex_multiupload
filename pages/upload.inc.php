<?php

	$myself   = rex_request('page', 'string');
	
	if($REX['ADDON'][$myself]['debug_modus'])
	{
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
	}

	
    require_once $REX['INCLUDE_PATH'].'/addons/'.$myself.'/action/action.upload.php';
