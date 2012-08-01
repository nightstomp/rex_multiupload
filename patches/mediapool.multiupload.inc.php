<?php

/**
 * REX Multiupload - Multi Upload Utlility
 *
 * @link https://github.com/nightstomp/rex_multiupload
 *
 * @author info[at]nightstomp.com Hirbod Mirjavadi
 *
 * @package redaxo4.3.x
 * @version 2.0.4
*/ 
  
  $upload = new rex_mediapool_multiupload;
  $upload->setCallback("complete", "multiuploadEditFile");
  echo $upload->createUploadForm();
?>