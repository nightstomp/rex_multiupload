<?php

// ADDON IDENTIFIER AUS ORDNERNAMEN ABLEITEN
////////////////////////////////////////////////////////////////////////////////
$myself = 'rex_multiupload';
$myroot = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/';


// Debug-Modus einschalten? (true / false)
$REX['ADDON'][$myself]['debug_modus'] = false;



// SETTINGS
////////////////////////////////////////////////////////////////////////////////

// soll die uploadliste erfolgreiche uploads automatisch aus der liste löschen?
// fehlerhafte werden zur Information in der Liste behalten und müssen manuell entfernt werden
// erwartet boolean (true / false)
$REX['ADDON'][$myself]['clear_uploadlist_automatically'] = true;

// Gleichzeitige Uploads. Ich empfehle keinen Wert über 6, kann zu Problemen führen
// Viele Server unterstützen nicht mehr als 10 gleichzeitige Verbindungen.
// erwartet INTEGER.
$REX['ADDON'][$myself]['upload_simultaneously'] = 3;


// SETTINGS ENDE
////////////////////////////////////////////////////////////////////////////////


// ERROR_REPORTING
////////////////////////////////////////////////////////////////////////////////
//ini_set('error_reporting', 'E_ALL');
//ini_set('display_errors', 'On');


// ADDON VERSION
////////////////////////////////////////////////////////////////////////////////
$Revision = '';
$REX['ADDON'][$myself]['VERSION'] = array
(
'VERSION'      => 1,
'MINORVERSION' => 3,
'SUBVERSION'   => 1
);

// ADDON REX COMMONS
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON']['rxid'][$myself] = '938';
$REX['ADDON']['page'][$myself] = $myself;
$REX['ADDON']['name'][$myself] = 'REX Multiupload';
$REX['ADDON']['version'][$myself] = implode('.', $REX['ADDON'][$myself]['VERSION']);
$REX['ADDON']['author'][$myself] = 'Hirbod Mirjavadi';
$REX['ADDON']['supportpage'][$myself] = 'www.redaxo.org/de/forum/addons-f30/update-rex-multiupload-no-flash-html5-no-uploadlimit-t17253.html';
$REX['ADDON']['perm'][$myself] = $myself.'[]';
$REX['PERM'][] = $myself.'[]';
$this_REX       = $REX['VERSION'].'.'.$REX['SUBVERSION'].'.'.$REX['MINORVERSION'];
$patch_REX      = str_replace('.', '_', $this_REX);

// AUTO INCLUDE FUNCTIONS & CLASSES
////////////////////////////////////////////////////////////////////////////////
if ($REX['REDAXO'])
{
  foreach (glob($myroot.'functions/function.*.inc.php') as $include)
  {
    require_once $include;
  }
  
}

if(is_object($REX['USER']) AND ($REX['USER']->hasPerm('rex_multiupload[]') OR $REX['USER']->isAdmin()))
{
  $_REX_HACK_OPENER = "";
  $_REX_HACK_OPENER = rex_request('opener_input_field', 'string');
  
	// BACKEND CSS
	////////////////////////////////////////////////////////////////////////////////
	$header = '
				<link rel="stylesheet" type="text/css" href="../files/addons/'.$myself.'/fileuploader.css" media="screen, projection, print" />
				<script type="text/javascript">var lastMediaPoolOpener = "'.$_REX_HACK_OPENER.'";</script>
				<script type="text/javascript" src="../files/addons/'.$myself.'/fileuploader.js"></script>
			';
	// Für jede Seite registrieren, damit eventuell auch andere Addons mittels API-Aufruf die Multiuploadform
	// einblenden können
	rex_register_extension('PAGE_HEADER', 'rex_multiupload_header_add',array($header));

  
	// Wert steht auf rex_request, da der Medienpool teilweise per $_GET Parameter sendet, teilweise per $_POST
	if ($REX['REDAXO'] && rex_request('page', 'string') == 'mediapool') {
	  $REX['PAGES']['mediapool']->getPage()->setPath($REX['INCLUDE_PATH'].'/addons/rex_multiupload/patches/mediapool_'.$patch_REX.'.inc.php');
	  rex_register_extension('PAGE_MEDIAPOOL_MENU', 'rex_multiupload_menu_insert'); // in die Medienpool-Navi einklinken
	}
}

