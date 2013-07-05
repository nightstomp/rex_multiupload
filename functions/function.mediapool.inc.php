<?php
if(!function_exists('rex_multiupload_menu_insert')){
  function rex_multiupload_menu_insert($params)
  {
    $tmp = $params['subject'];
    $tmp[] = array("rex_multiupload","Multiupload");
    return $tmp;
  }
}

if(!function_exists('rex_multiupload_page_output')){
  function rex_multiupload_page_output($params)
  {
    $upload = new rex_mediapool_multiupload;
    $upload->setCallback("complete", "multiuploadEditFile");
    return $upload->createUploadForm();
  }
}
