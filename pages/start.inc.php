<div class="rex-addon-output">
  <h2 class="rex-hl2" style="font-size: 1em;">Quick Upload</h2>

  <div class="rex-addon-content">
	<p>
		Dies ist der "Quick-Upload Bereich." Der Multiuploader ist auch im Medienpool unter "Multiupload" zu finden. 
		Weitere Einstellungsmöglichkeiten erfährst Du unter "<a href="index.php?page=rex_multiupload&subpage=info">Informationen</a>"
	</p>
	
	<?php 
	  $upload = new rex_mediapool_multiupload;
	  $upload->setValue(); // diese zeile kann auskommentiert werden, dann greift die "Globale Einstellnung", ansonsten Parameter setzen => siehe "Information"
	  echo $upload->createUploadForm(); 
	?>
  </div>
</div>