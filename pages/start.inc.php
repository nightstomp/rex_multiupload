<div class="rex-addon-output">
  <h2 class="rex-hl2" style="font-size: 1em;">Quick Upload</h2>

  <div class="rex-addon-content">
  <p>
    Dies ist der "Quick-Upload Bereich." Der Multiuploader ist auch im Medienpool unter "Multiupload" zu finden. 
    Weitere Einstellungsmöglichkeiten erfährst Du unter "<a href="index.php?page=rex_multiupload&subpage=info">Informationen</a>"
  </p>
  
  <?php 
    $upload = new rex_mediapool_multiupload;
    // an dieser stelle könnten natürlich auch alle setter, getter und funktionen verwendet werden
    // für weitere Informationen bitte den Entwicklerbereich einsehen
    $upload->setCallback("complete", "multiuploadEditFile");
    echo $upload->createUploadForm(); 
  ?>
  </div>
</div>