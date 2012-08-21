<?php

/**
 * REX Multiupload - Multi Upload Utlility
 *
 * @link https://github.com/nightstomp/rex_multiupload
 *
 * @author info[at]nightstomp.com Hirbod Mirjavadi
 *
 * @package redaxo4.3.x, redaxo4.4.x
 * @version 2.2.1
 */
 
if(!function_exists('rex_multiupload_menu_insert')){
  function rex_multiupload_menu_insert($params)
  {
    $tmp = $params['subject'];
    $tmp[] = array("rex_multiupload","Multiupload");
    return $tmp;
  }
}
