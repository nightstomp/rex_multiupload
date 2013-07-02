<?php

// ADDON PARAMETER AUS URL HOLEN
////////////////////////////////////////////////////////////////////////////////
$myself    = rex_request('page'   , 'string');
$subpage   = rex_request('subpage', 'string');
$minorpage = rex_request('minorpage', 'string');
$func      = rex_request('func'   , 'string');

// ADDON RELEVANTES AUS $REX HOLEN
////////////////////////////////////////////////////////////////////////////////
$myREX = $REX['ADDON'][$myself];


if(is_object($REX['USER']) AND ($REX['USER']->hasPerm('rex_multiupload[settings]') OR $REX['USER']->isAdmin())){
  //all fine
} else {
  die('Keine Rechte diesen Bereich zu verändern!');
}


// FORMULAR PARAMETER SPEICHERN
////////////////////////////////////////////////////////////////////////////////
if ($func == 'savesettings')
{
  $content = '';
  foreach($_GET as $key => $val)
  {
    if(!in_array($key,array('page','subpage','minorpage','func','submit','PHPSESSID')))
    {
      $myREX['settings'][$key] = $val;
      if(is_array($val))
      {
        $content .= '$REX["ADDON"]["'.$myself.'"]["settings"]["'.$key.'"] = '.var_export($val,true).';'."\n";
      }
      else
      {
        if(is_numeric($val))
        {
          $content .= '$REX["ADDON"]["'.$myself.'"]["settings"]["'.$key.'"] = '.$val.';'."\n";
        }
        else
        {
          $content .= '$REX["ADDON"]["'.$myself.'"]["settings"]["'.$key.'"] = \''.$val.'\';'."\n";
        }
      }
    }
  }

  $file = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/config.inc.php';
  rex_replace_dynamic_contents($file, $content);
  echo rex_info('Einstellungen wurden gespeichert.');
}



// SELECT BOX
////////////////////////////////////////////////////////////////////////////////

$id = "instant_upload_start";                                   // ID dieser Select Box
$tmp = new rex_select();                                      // rex_select Objekt initialisieren
$tmp->setSize(1);                                             // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('nein',0);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('ja',1);
$tmp->setSelected($myREX['settings']['SELECT'][$id]);         // gespeicherte Werte einsetzen
$select5 = $tmp->get();


$id = "upload_simultaneously";                                       // ID dieser Select Box
$tmp = new rex_select();                                      // rex_select Objekt initialisieren
$tmp->setSize(1);                                             // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('1',1); 
$tmp->addOption('2',2);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('3',3);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('4',4);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('5',5);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('6',6);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('7',7); 
$tmp->addOption('8',8);
$tmp->addOption('9',9);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('10',10);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->setSelected($myREX['settings']['SELECT'][$id]);         // gespeicherte Werte einsetzen
$select1 = $tmp->get();                                        // HTML in Variable speichern


$id = "clear_uploadlist_automatically";                                   // ID dieser Select Box
$tmp = new rex_select();                                      // rex_select Objekt initialisieren
$tmp->setSize(1);                                             // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('nein',0);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('ja',1);
$tmp->setSelected($myREX['settings']['SELECT'][$id]);         // gespeicherte Werte einsetzen
$select2 = $tmp->get();   

$id = "clear_file_after_finish";                                   // ID dieser Select Box
$tmp = new rex_select();                                      // rex_select Objekt initialisieren
$tmp->setSize(1);                                             // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('nein',0);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('ja',1);
$tmp->setSelected($myREX['settings']['SELECT'][$id]);         // gespeicherte Werte einsetzen
$select6 = $tmp->get();

$id = "sync_cats";                                   // ID dieser Select Box
$tmp = new rex_select();                                      // rex_select Objekt initialisieren
$tmp->setSize(1);                                             // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('nein',0);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('ja',1);
$tmp->setSelected($myREX['settings']['SELECT'][$id]);         // gespeicherte Werte einsetzen
$select7 = $tmp->get();

$id = "show_footnote";                                   // ID dieser Select Box
$tmp = new rex_select();                                      // rex_select Objekt initialisieren
$tmp->setSize(1);                                             // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('nein',0);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('ja',1);
$tmp->setSelected($myREX['settings']['SELECT'][$id]);         // gespeicherte Werte einsetzen
$select8 = $tmp->get();

$id = "php_debug";                                                        // ID dieser Select Box
$tmp = new rex_select();                                      // rex_select Objekt initialisieren
$tmp->setSize(1);                                             // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('nein',0);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('ja',1);
$tmp->setSelected($myREX['settings']['SELECT'][$id]);         // gespeicherte Werte einsetzen
$select3 = $tmp->get();   

$id = "javascript_debug";                                       // ID dieser Select Box
$tmp = new rex_select();                                      // rex_select Objekt initialisieren
$tmp->setSize(1);                                             // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('nein',0);                                    // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('ja',1);
$tmp->setSelected($myREX['settings']['SELECT'][$id]);         // gespeicherte Werte einsetzen
$select4 = $tmp->get();   



echo '
<div class="rex-addon-output">
  <div class="rex-form">

  <form action="index.php" method="get" id="settings">
    <input type="hidden" name="page" value="'.$myself.'" />
    <input type="hidden" name="subpage" value="'.$subpage.'" />
    <input type="hidden" name="func" value="savesettings" />
    <input type="hidden" name="folder" value="'.stripslashes($REX["ADDON"]["rex_multiupload"]["settings"]["folder"]).'" />

        <fieldset class="rex-form-col-1">
          <legend>Einstellungen für Rex Multiupload</legend>
          <div class="rex-form-wrapper">
            
            <div class="rex-form-row">
                <p class="rex-form-col-a rex-form-select">
                  <label for="select">Kategoriesync anzeigen?</label>
                  '.$select7.'
                </p>
              </div><!-- .rex-form-row -->
            
            <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-select">
                <label for="select">Uploads nach Auswahl sofort starten?</label>
                '.$select5.'
              </p>
            </div><!-- .rex-form-row -->
            
            
            <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-select">
                <label for="select">Gleichzeitige Uploads?</label>
                '.$select1.'
              </p>
            </div><!-- .rex-form-row -->


          <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-select">
                <label for="select">Uploadliste automatisch bereinigen?</label>
                '.$select2.'
              </p>
            </div><!-- .rex-form-row -->
      
            <div class="rex-form-row">
                <p class="rex-form-col-a rex-form-select">
                  <label for="select">Datei nach Upload automatisch aus Liste entfernen?</label>
                  '.$select6.'
                </p>
              </div><!-- .rex-form-row -->
              
              <div class="rex-form-row">
                  <p class="rex-form-col-a rex-form-select">
                    <label for="select">Fußnote anzeigen?</label>
                    '.$select8.'
                  </p>
                </div><!-- .rex-form-row -->
              
            <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-select">
                <label for="select">PHP Error Reporting aktivieren? (ACHTUNG: NUR TESTWEISE)</label>
                '.$select3.'
              </p>
            </div><!-- .rex-form-row -->
      
            <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-select">
                <label for="select">Javascript Debugging aktivieren? (Outputs in Firebug)</label>
                '.$select4.'
              </p>
            </div><!-- .rex-form-row -->

            <div class="rex-form-row rex-form-element-v2">
              <p class="rex-form-submit">
                <input class="rex-form-submit" type="submit" id="submit" name="submit" value="Einstellungen speichern" />
              </p>
            </div><!-- .rex-form-row -->

          </div><!-- .rex-form-wrapper -->
        </fieldset>
  </form>

  </div><!-- .rex-form -->
</div><!-- .rex-addon-output -->
';
