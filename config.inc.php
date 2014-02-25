<?php

/**
 * REX Multiupload - Multi Upload Utility
 *
 * @link https://github.com/nightstomp/rex_multiupload
 *
 * @author info[at]nightstomp.com Hirbod Mirjavadi
 *
 * @package redaxo4.3.x, redaxo4.4.x, redaxo4.5.x, redaxo4.6.x
 * @version 3.1.0
 */



// ADDON IDENTIFIER
////////////////////////////////////////////////////////////////////////////////
$myself = 'rex_multiupload';
$myroot = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/';


// ADDON VERSION
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON'][$myself]['VERSION'] = array
(
'VERSION'      => 3,
'MINORVERSION' => 1,
'SUBVERSION'   => 0
);


define('REX_HAS_CONTENT_OUTPUT_EP', '4.5b1.0');
define('REX_CURRENT_VERSION', $REX['VERSION'].'.'.$REX['SUBVERSION'].'.'.$REX['MINORVERSION']);



// ADDON REX COMMONS
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON']['rxid'][$myself] = '938';
$REX['ADDON']['page'][$myself] = $myself;
$REX['ADDON']['name'][$myself] = 'Rex Multiupload';
$REX['ADDON']['version'][$myself] = implode('.', $REX['ADDON'][$myself]['VERSION']);
$REX['ADDON']['author'][$myself] = 'Hirbod Mirjavadi';
$REX['ADDON']['supportpage'][$myself] = 'www.redaxo.org/de/forum/addons-f30/update-rex-multiupload-no-flash-html5-no-uploadlimit-t17253.html';
$REX['ADDON']['perm'][$myself] = $myself.'[]';
$REX['PERM'][] = $myself.'[settings]';
$REX['PERM'][] = $myself.'[]';


$this_REX       = $REX['VERSION'].'.'.$REX['SUBVERSION'].'.'.$REX['MINORVERSION'];
$patch_REX      = str_replace('.', '_', $this_REX);


// SETTINGS - BITTE NUR UNTER "EINSTELLUNGEN" IM ADDON EDITIEREN!
////////////////////////////////////////////////////////////////////////////////
 
// --- DYN
$REX["ADDON"]["rex_multiupload"]["settings"]["folder"] = 'files/';
$REX["ADDON"]["rex_multiupload"]["settings"]["SELECT"] = array (
  'sync_cats' => '1',
  'instant_upload_start' => '1',
  'upload_simultaneously' => '5',
  'clear_uploadlist_automatically' => '1',
  'clear_file_after_finish' => '1',
  'show_footnote' => '1',
  'php_debug' => '0',
  'javascript_debug' => '0',
);
// --- /DYN


// SETTINGS ENDE
////////////////////////////////////////////////////////////////////////////////



// AUTO INCLUDE FUNCTIONS & CLASSES
////////////////////////////////////////////////////////////////////////////////
if ($REX['REDAXO'])
{
  $pattern = $myroot.'functions/function.*.inc.php';
  $include_files = glob($pattern);
  if(is_array($include_files) && count($include_files) > 0){
     foreach ($include_files as $include)
     {
       require_once $include;
     }
  }
  
  $pattern = $myroot.'classes/*.class.php';
  $include_files = glob($pattern);

  if(is_array($include_files) && count($include_files) > 0){
     foreach ($include_files as $include)
     {
       require_once $include;
     }
  }
  
  if (!function_exists('mime_content_type') && !function_exists('finfo_open')) {
    require_once $myroot.'functions/compatfunction.mime_content_type.inc.php';
  }
}


// SOME PATCHES
////////////////////////////////////////////////////////////////////////////////

if(isset($REX['USER']) AND is_object($REX['USER']) AND ($REX['USER']->hasPerm('rex_multiupload[]') OR $REX['USER']->isAdmin()))
{
  $_REX_HACK_OPENER = "";
  $_REX_HACK_OPENER = rex_request('opener_input_field', 'string');
      
  // BACKEND CSS
  //////////////////////////////////////////////////////////////////////////////
  $header =
    PHP_EOL.'  '.
    PHP_EOL.'  <!-- '.$myself.' -->'.
    PHP_EOL.'  <link rel="stylesheet" type="text/css" href="../files/addons/'.$myself.'/fileuploader.css" media="screen, projection, print" />'.
    PHP_EOL.'  <script type="text/javascript">var lastMediaPoolOpener = "'.$_REX_HACK_OPENER.'";</script>'.
    PHP_EOL.'  <script type="text/javascript" src="../files/addons/'.$myself.'/fileuploader.js"></script>'.
    PHP_EOL.'  <!-- ###MULTIUPLOAD_EP_REPLACE### -->'.
    PHP_EOL.'  <!-- /'.$myself.' -->'.PHP_EOL;
  $header_func = 'return $params[\'subject\'].\''.$header.'\';';

  rex_register_extension('PAGE_HEADER', create_function('$params',$header_func));
  
  // Wert steht auf rex_request, da der Medienpool teilweise per $_GET Parameter sendet, teilweise per $_POST
  if ($REX['REDAXO'] && rex_request('page', 'string') == 'mediapool') {

    rex_register_extension('PAGE_MEDIAPOOL_MENU', 'rex_multiupload_menu_insert'); // in die Medienpool-Navi einklinken


    if (version_compare(REX_CURRENT_VERSION, REX_HAS_CONTENT_OUTPUT_EP) < 0) {
      $REX['PAGES']['mediapool']->getPage()->setPath($REX['INCLUDE_PATH'].'/addons/rex_multiupload/patches/mediapool_'.$patch_REX.'.inc.php');
    } else {
      if(rex_request('subpage', 'string') == 'rex_multiupload') {
        rex_register_extension('PAGE_MEDIAPOOL_OUTPUT', 'rex_multiupload_page_output'); // in die Medienpool-Navi einklinken
      }
    }

  }
}

