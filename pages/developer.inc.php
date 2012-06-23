<div class="rex-addon-output">
  <h2 class="rex-hl2" style="font-size: 1em;">REX Multiupload DevCenter - Für Entwickler</h2>
  
  <div class="rex-addon-content">
     <h3>Intro</h3>
     <p class="rex-tx1">Bevor ich mit der Materie anfange, hier ein kurzer Hinwes: Der Multiuploader ist ab sofort in der Lage, unbegrenzt eingebunden zu werden.
       Man ist nicht mehr auf einen einzigen Upload-Button pro Seite beschränkt. Die komplette interne Geschichte läuft vollautomatisch, 
       es muss nichts angepasst werden!</p>
     
     <h3>Out of the Box - Standard-Einstellung</h3>
     
     <p class="rex-tx1">Mit folgendem Code kann der Multiuploader in AddOns verwendet werden:</p>
<?php
$code1 ='<?php
if(OOAddon::isAvailable("rex_multiupload"))
{
  $upload = new rex_mediapool_multiupload;
  echo $uploader->createUploadForm();
  
} else {
  echo rex_warning(\'"rex_multiupload" Addon benötigt!\');
}
?>';
  
$code2 = '<?php 
if(OOAddon::isAvailable("rex_multiupload"))
{
 $upload = new rex_mediapool_multiupload;
 $upload->setValue(
   $sync = true, 
   $clear_auto = true, 
   $clear_fin = true, 
   $sim_uploads = 5,
   $js_debug = false, 
   $foot = true
  );
  
 echo $upload->createUploadForm();

 /**
 * Für alle Werte sind bereits Standards definiert, es kommt also zu keiner Fehlermeldung
 * wenn eine $VAR nicht gesetzt wurde. Reihenfolge beachten!
 
   $sync = Kategoriesync? boolean(true/false)
   $clear_auto = Uploadliste automatisch bereinigen? boolean(true/false)
   $clear_fin = Datei nach Upload aus Warteschlange entfernen? boolean(true/false)
   $sim_uploads = Gleichzeitige Uploads (5 wird empfohlen/default) int(1 bis 50)
   $js_debug = JavaScript Debug einschalten? (Firebug) boolean(true/false)
   $foot = Fußnote mit Erklärung unter Uploadbutton einblenden boolean(true/false)
 
 */
 
} else {
 echo rex_warning(\'"rex_multiupload" Addon benötigt!\');
}
?>';

$code3 = '<?php 
if(OOAddon::isAvailable("rex_multiupload"))
{
 $upload = new rex_mediapool_multiupload;

 // setter function um kategorie sync anzuzeigen (boolean: true/false)
 $upload->setSyncCat(true);
 
 // setter function für automatische listensäuberung (boolean: true/false)
 $upload->setClearUploadsAutomatically(true);
 
 // setter function um datei nach upload aus der liste zu entfernen (boolean: true/false)
 $upload->setClearFileAfterFinish(true);
 
 // setter function um maximalwert für gleichzeitige uploads zu setzen (int)
 $upload->setSimultanUploads(5);
 
 // setter function um JS-Debugging zu aktivieren (Firebug Output, boolean: true/false)
 $upload->setJSDebug(false);
 
 // setter function um fußnoten information auszugeben
 $upload->setFootnote(true);
 
 // setter function um redaxo-addon-markup mit auszugeben (boolean: true/false)
 // Hinweis: Wird setMarkup() auf false gesetzt, wird setSyncCat & setFootnote 
 // automatisch auf false gesetzt. Um dennoch den Kategorie-Sync auszugeben,
 // bitte das Beispiel "Nackte Ausgabe" lesen.
 $upload->setMarkup(true);
 
 
 // Ausgabe des Uploaders   
 echo $upload->createUploadForm();


 
} else {
 echo rex_warning(\'"rex_multiupload" Addon benötigt!\');
}
?>';

$code4 = '<?php 
if(OOAddon::isAvailable("rex_multiupload"))
{
 $upload = new rex_mediapool_multiupload;
 $upload->setMarkup(false);
 //echo $upload->getMediaCats(); // kann auskommentiert werden, falls nötig. echo nicht vergessen
 echo $upload->createUploadForm();

 /**
 * Wird setMarkup() auf "false" gestellt, so werden folgende setValue() Werte ignoriert:
 
   $sync = Kategoriesync? boolean(true/false)
   $foot = Fußnote mit Erklärung unter Uploadbutton einblenden boolean(true/false)
  
   Zurückgegeben wird der reine, simple Markup, welcher für\'s JavaScript/CSS notwendig ist.
   Alle anderen Output Schachtelungen werden unterdrückt. Der Kategorie-Sync wird ebenfalls 
   ausgeblendet. Um dennoch den Kategorie-Sync anzuzeigen, muss man sich an der 
   Methode getMediaCats() bedienen. Die Ausgabe erzeugt man mit echo $objekt->getMediaCats();
   
   Es ist nicht notwendig IDs oder Klassen an das select zu geben, der Multiuploader 
   erkennt seine Instanz automatisch und bindet die korrekte SELECT_ID an den JavaScript-Selector.
   
   Beispiel:
   $upload = new rex_mediapool_multiupload;
   $upload->setMarkup(false);
   echo $upload->getMediaCats();
   echo $upload->createUploadForm();
   
   
 */
 
} else {
 echo rex_warning(\'"rex_multiupload" Addon benötigt!\');
}
?>';

$code5 = '<?php 
if(OOAddon::isAvailable("rex_multiupload"))
{
 $upload = new rex_mediapool_multiupload;
 $upload->setCallback("upload", "uploadCallback");
 $upload->setCallback("submit", "submitCallback");
 $upload->setCallback("progress", "progressCallback");
 $upload->setCallback("complete", "completeCallback");
 $upload->setCallback("cancel", "cancelCallback");
 
 echo $upload->createUploadForm();

 /**
 ** Registriert die JavaScript-Callbacks
 ** Selbstverständlich können auch in Verbindung mit den Callbacks weiterhin 
 ** alle Methoden wie setValue(), getMediaCats(), setXYZ..() oder setMarkup() verwendet werden
 */
 
} else {
 echo rex_warning(\'"rex_multiupload" Addon benötigt!\');
}
?>';
?>
  
  <p class="rex-tx1">Mit der Aufrufmethode unten beschrieben bezieht der Multiupload die Parameter aus dem Bereich "Einstellungen". Möchtest Du eigene Einstellungen verwenden, musst du die setValue() Methode aufrufen. (nächstes Beispiel)
  </p>
  
  
  <?php rex_highlight_string($code1); ?>
  <h3>Erweiterte Konfiguration</h3>
  <p class="rex-tx1">Möchte man den Multiupload Addon-/Modulabhängig steuern, muss man sich der setValue() Methode bedienen.
    Mit setValue() kann das AddOn praktisch beliebig eingestellt werden. Alle erwarteten Werte sind boolean, d.h. true oder false.
    Ausnahme ist $sim_uploads, hier muss ein INT-Wert übergeben werden (eine Zahl zwischen 1 bis 50). Die Erklärung findet sich unten im Code.
  </p>
  <?php rex_highlight_string($code2); ?>
  
  
  <h3>Setter Methoden</h3>
  <p class="rex-tx1">
    Da es teilweise schwer ist, sich die Reihenfolge für "setValue()" zu merken, gibt es für jede Einstellung einen eigenen Setter.
    Alle Setter können nach Lust und Laune miteinander kombiniert werden. Die Nutzung einer Settermethode überschreibt immer
    die setValue() Einstellung für die aktuelle Config. Zur Übersichtlichkeit wird empfohlen, die Methoden nicht zu vermischen. Wird eine Settermethode 
    für eine bestimmte Config nicht genutzt, greift automatisch die Config aus "Einstellungen" für die Funktion.
  </p>
  <?php rex_highlight_string($code3); ?>
  
  
  <h3>Nackte Ausgabe</h3>
  <p class="rex-tx1">Perfekt für AddOns oder Module, ohne Addon-Markup. Hier bedient man sich der setMarkup() Methode und stellt den Wert auf "false"
    Die Deaktivierung des Markups schaltet den Kategoriesync und die Fußnote ab. Um ein Markupfreien Kategoriesync zu erhalten, holt man sich die
    Rückgabe mit der Methode getMediaCats(). Die Position der Ausführung spielt dabei keine Rolle. Erklärung findet sich unten
  </p>
  <?php rex_highlight_string($code4); ?>
  
  
  <h2 class="rex-hl2">Für Profis</h2><br />
  <h3>Wer macht das xForm-AddOn? ;)</h3>
  
  <p class="rex-tx1">
    Richtig interessant wird es jetzt. Die JavaScript-Callbacks. Mit den oben genannten Methoden habe ich schon viele Einstellungsmöglichkeiten implementiert,
    jedoch richtig spannend wird das ganze, wenn man selbst bestimmen will, was möglich ist. Die beste Manipulation / Weiterverarbeitung läuft mit JavaScript. 
    Dafür steht die Methode "setCallback()" zur Verfügung. Es gibt fünf Callback-Typen. <br />
    <ul>
      <li>"upload" (bei Upload einer Datei)</li>
      <li>"submit" (direkt nach Auswahl einer Datei)</li>
      <li>"progress" (bei der Verarbeitung der Datei)</li>
      <li>"complete" (Datei wurde erfolgreich hochgeladen)</li>
      <li>"cancel" (Dateiupload wurde abgebrochen / ist fehlgeschlagen).</li>
    </ul>
    Die ganzen Callbacks feuern für jede Datei in der Warteschlange einzeln, d.h. man hat absolute Kontrolle darüber, was man hat/macht.<br />
    Mit den Callbacks ist man in der Lage, richtige Module / AddOns zu schreiben, in dem man mit den Rückgabewerten die Weiterverarbeitung macht (z.B. Dateiname in ein Inputfeld setzen, oder mit AJAX in die Datenbank jagen u.v.m.)<br /><br />
    Es können theoretisch alle Callbacks pro Objekt-Instanz gleichzeitig geaddet werden. Keine Doppelungen, es darf pro $objekt z.B. nur ein mal setCallback('complete, 'function') gesetzt werden. Wird ein weiteres mal gesetzt, wird der erste Wert überschrieben. Die Registrierung des Callbacks muss vor der createUploadForm() Methode erfolgen. Damit die Länge dieses Manuals nicht Überhand nimmt, eine kurze Erklärung:<br /><br />
    Jeder Callback hat eine bestimmte Rückgabe. Damit die Rückgaben registriert werden, muss deine JavaScript Funktion einige Parameter erwarten.
    Folgende Rückgaben gibt es: (JavaScript) <br />
    <ul>
      <li>"function uploadCallback(filename, xhr){ ... }"
        <ul>
          <li>Feuert direkt beim Upload - Return "fileName" und "xhr"</li>
        </ul>
      </li>
      
      <li>"function submitCallback(filename){ ... }"
        <ul>
          <li>Deine Funktion erhält sofort nach Auswahl den Dateinamen</li>
        </ul>
      </li>
      
      <li>"function progressCallback(fileName, loaded, total){ ... }"
        <ul>
          <li>Dauercallback, bis Prozess beendet ist. Schickt non-stop filename, current uploaded, totalsize zurück. Damit kann man alle möglichen Berechnungen machen.
            Kann extrem Rechenintensiv werden!</li>
        </ul>
      </li>
      
      
      <li>"function cancelCallback(filename){ ... }"
        <ul>
          <li>Dateiname des fehlgeschlagenen / abgebrochenen Uploads</li>
        </ul>
      </li>
        
      <li>"function completeCallback(json){ ... }" - Der wichtigste Callback.<br />
        Schickt ein JSON-Objekt mit jeder Menge Informationen zurück. Mittels "json.objectname" kannst du die Rückgaben abrufen:<br />
        <ul>
          <li>json.success (immer true, ansonsten feuert completeCallback gar nicht)</li>
          <li>json.filename (finale, von Redaxo umgewandelter Dateiname. Die Rückgabe wurde exakt so in den Mediepool gesynced)</li>
          <li>json.mediaCatId (ID der Medienpool-Kategorie, in die gesynced wurde)</li>
          <li>json.originalname (Gibt den echten Namen zurück [brauch man das?])</li>
          <li>json.timestamp (Gibt einen Timestamp der Fertigstellung zurück)</li>
        </ul>
      </li>
    </ul>
    Damit die Callbacks greifen, muss natürlich eine .JS oder ein &lt;script&gt;..functions..&lt;/script&gt; irgendwo im Backend, auf der Seite oder 
    in der Moduleingabe vorbereitet werden. Wichtig: Beim Methodenaufruf im PHP darf der Funktionsname der Callbacks KEINE KLAMMERN() oder sonstige Parameter enthalten.
    Es darf nur der reine Funktionsname übergeben werden. Lediglich eure JavaScripts müssen für die Parameter-Rückgabe vorbereitet werden
  </p>
  
  <?php rex_highlight_string($code5); ?>
  
  <p>Bei Fragen wendet euch bitte im Forum oder direkt bei <a href="http://www.github.com/nightstomp/redaxo_multiupload">GitHub</a></p>
  <p>&copy; 2011-2012 Hirbod Mirjavadi (info@nightstomp.com)</p>
  
  </div>
</div>