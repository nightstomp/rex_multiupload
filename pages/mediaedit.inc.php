<?php
    $file = htmlspecialchars(rex_request('file', 'string'));

    if($file) {
        $ooPic = OOMedia::getMediaByFileName($file);
        if(is_object($ooPic)){
            print_r($ooPic);
        }
    }
?>