<div class="rex-addon-output">
  <h2 class="rex-hl2" style="font-size: 1em;">Multiupload</h2>

  <div class="rex-addon-content">
      <p>Das Multiupload-Addon besitzt 2 Einstellungsmöglichkeiten (siehe config.inc.php für maxConnections und Säuberung der Liste). Es integriert sich nahtlos in den Medienpool und liefert die Möglichkeit, 
	  mehrere Dateien gleichzeitig hochzuladen. Mit modernen Browsern ist es mittels HTML5 sogar möglich, vom Desktop per Drag and Drop Dateien abzulegen. Diese werden sofort hochgeladen.
	  Teste es einfach, in dem du z.B. vom Desktop eine Datei auf "Dateien auswählen" ziehst - Steve Jobs würde magisch sagen!.<br /><br />
	  Das Multiupload-Addon beachtet die in der master.inc.php angegebenen Blocked-Extensions, und <strong>sychronisiert die Dateien direkt in die ausgewählte Medienpoolkategorie</strong>.
	  Die Dateien durchlaufen alle Redaxofunktionen (rex_mediapool_filename und rex_mediapool_syncFile). 100%-ige Kompatibilität ist dadurch gewährleistet.
	</p>
	  
	  <p>Vorteile dieses AddOn's gegenüber den verfügbaren auf Redaxo.org</p>
	  <ul>
		<li>Es funktioniert tatsächlich ;-)</li>
		<li>Nutz kein Flash - erspart diverse Probleme (100MB Limit, crossdomain.xml, Cookieproblem etc.)</li>
		<li>Funktioniert ohne Plugins, benötigt nur JavaScript (ansonsten Fallback)</li>
		<li>Lässt sich per Permission für jeden User zu- und abschalten</li>
		<li>Kann Drag&Drop (zieh mal eine Datei vom Desktop direkt in's Fenster)</li>
		<li>Nutzt in modernen Browsern HTML5-Upload-Methoden</li>
		<li>Funktioniert in IE6 - IE10, Firefox 3-7 Beta, Safari, Chrome, Opera 10.6</li>
		<li>Basiert auf http://valums.com/ajax-upload/ | gut dokumentiert und hat diverse Einstellungsmöglichkeiten</li>
		<li>Ist OpenSource und wird von mir gepflegt</li>
		<li>
			<strong>
				Und der Kracher: Kein Uploadlimit der Dateigröße. Moderne Browser senden und (moderne) Server empfangen die Daten im RAW-Format, 
				dadurch ist kein Flashseitiges "chunking" nötig und macht jede kostenpflichtige Software sinnlos. Ein Server mit Standard-PHP Einstellung unterstützt diese Methoden.<br />
				Das Script erkennt, ob der Server diese Funktionen versteht, wenn nicht, dann greift die php.ini und behandelt es wie einen normalen Upload!
			</strong>
		</li>
	  </ul>
	  
	  <p>&copy; 2011 Hirbod Mirjavadi (info@nightstomp.com)</p>
  </div>
</div>