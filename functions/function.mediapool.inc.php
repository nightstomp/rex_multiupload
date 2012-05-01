<?php
function rex_multiupload_menu_insert($params)
{
  $tmp = $params['subject'];
  $tmp[] = array("rex_multiupload","Multiupload");
  return $tmp;
}