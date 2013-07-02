<?php

// GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$myself     = rex_request('page', 'string');
$faceless   = rex_request('faceless', 'string');
$subpage    = rex_request('subpage', 'string');

// Build Subnavigation 
$subpages = array(
  array('','Quick Upload'),
  array('settings', 'Einstellungen'),
  array('developer', 'Für Entwickler'),
  array('info','Informationen')
);

if(is_object($REX['USER']) AND ($REX['USER']->hasPerm('rex_multiupload[settings]') OR $REX['USER']->isAdmin())){
  //all fine
} else {
  unset($subpages[1]);
}

if(is_object($REX['USER']) AND$REX['USER']->isAdmin()){
  //all fine
} else {
  unset($subpages[2]);
}

if($faceless != 1)
{
  // REX BACKEND LAYOUT TOP
  require $REX['INCLUDE_PATH'] . '/layout/top.php';
  
  // TITLE & SUBPAGE NAVIGATION
  rex_title($REX['ADDON']['name'][$myself].' <span class="addonversion">'.$REX['ADDON']['version'][$myself].'</span>', $subpages);

  // INCLUDE REQUESTED SUBPAGE
  if(!$subpage) {
    $subpage = 'start';  /* DEFAULT SUBPAGE */
  }
  
  require $REX['INCLUDE_PATH'] . '/addons/'.$myself.'/pages/'.$subpage.'.inc.php';

  
  // REX BACKEND LAYOUT BOTTOM
  require $REX['INCLUDE_PATH'] . '/layout/bottom.php';
  
} else {
  require $REX['INCLUDE_PATH'] . '/addons/'.$myself.'/pages/'.$subpage.'.inc.php';
}
