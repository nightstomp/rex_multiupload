<?php

// ADDON IDENTIFIER AUS GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$myself = rex_request('addonname','string');
$myself_required = "rex_multiupload"; 
$force_rename = rex_request('force_rename','int');

if(!$force_rename) {
	$install_url = '?page=addon&addonname='.$myself.'&install=1';
} else {
	$install_url = '?page=addon&addonname='.$myself_required.'&install=1';
}


if($force_rename){
	if(rename(dirname(__FILE__), $REX['INCLUDE_PATH'].'/addons/'.$myself_required)) {
		header("Location: ". $install_url);
		exit;
	} else {
		$REX['ADDON']['installmsg'][$myself] = 'Das AddOn konnte aufgrund fehlender Rechte das Verzeichnis nicht selbst umbenennen. Bitte führe diesen Schritt manuell durch.';
		$REX['ADDON']['install'][$myself] = 0;
		$do_install = false;
	}
}

// INSTALL CONDITIONS
////////////////////////////////////////////////////////////////////////////////
$requiered_REX = '4.3.1';
$requiered_PHP = 5;
$do_install = true;


// CHECK NAME
if ($myself != $myself_required)
{
	$REX['ADDON']['installmsg'][$myself] = 'Du hast vermutlich das AddOn von Github geladen und den Ordner nicht wie in der README beschrieben umbenannt. Das Verzeichnis muss zwingend in '.$myself_required.' umbenannt werden, aktuell lautet das Verzeichnis jedoch '.$myself.'. <a href="'.$install_url.'&force_rename=1">Korrektur automatisch vornehmen?</a>';
	$REX['ADDON']['install'][$myself] = 0;
	$do_install = false;
}

// CHECK REDAXO VERSION
////////////////////////////////////////////////////////////////////////////////
$this_REX = $REX['VERSION'].'.'.$REX['SUBVERSION'].'.'.$REX['MINORVERSION'];
$patch_REX = str_replace('.', '_', $this_REX);

if(version_compare($this_REX, $requiered_REX, '<'))
{
	$REX['ADDON']['installmsg'][$myself] = 'Dieses Addon ben&ouml;tigt Redaxo Version '.$requiered_REX.' oder h&ouml;her.';
	$REX['ADDON']['install'][$myself] = 0;
	$do_install = false;
}

// CHECK PHP VERSION
////////////////////////////////////////////////////////////////////////////////
if (intval(PHP_VERSION) < $requiered_PHP)
{
	$REX['ADDON']['installmsg'][$myself] = 'Dieses Addon ben&ouml;tigt mind. PHP '.$requiered_PHP.'!';
	$REX['ADDON']['install'][$myself] = 0;
	$do_install = false;
}



// DO INSTALL
////////////////////////////////////////////////////////////////////////////////
if ($do_install)
{
	$REX['ADDON']['install'][$myself] = 1;
}
