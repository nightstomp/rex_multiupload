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
 
if(!function_exists('rex_multiupload_header_add'))
{
  function rex_multiupload_header_add($params) {

    if (is_array($params) && count($params)>2) {
      foreach($params as $key => $val) {
        if($key !== 'subject' && $key !== 'extension_point') {
        $params['subject'] .= "\n".$val;
        }
      }
    }

    return $params['subject'];
  }
}
