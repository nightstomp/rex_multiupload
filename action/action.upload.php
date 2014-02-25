<?php

/**
 * REX Multiupload - Multi Upload Utility
 *
 * @link https://github.com/nightstomp/rex_multiupload
 *
 * @author info[at]nightstomp.com Hirbod Mirjavadi
 *
 * @package redaxo4.3.x, redaxo4.4.x, redaxo4.5.x, redaxo4.6.x
 * @version 3.1.0
 */

$myself = 'rex_multiupload';
if($REX["ADDON"][$myself]["settings"]["SELECT"]["php_debug"])
{
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
}

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        if(!$temp){
            $temp = fopen("php://temp", "wb");
        }
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()){
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10737418240;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10737418240){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    private function checkServerSettings(){
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        /*if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }*/
    }

    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        global $REX;

        if (!is_writable($uploadDirectory)){
            return array('error' => "Fehler: Upload-Verzeichnis hat keine Schreibrechte.");
        }

        if (!$this->file){
            return array('error' => 'Fehler: Es wurden keine Dateien hochgeladen.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'Fehler: Die Datei ist leer');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'Fehler: Die Datei ist zu groß');
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());

        if(!isset($pathinfo['extension'])){
            $pathinfo['extension'] = '';
        }
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'Fehler: Die Datei hat eine ungültige Endung, verboten sind: '. $these . '.');
        }

        if(!$replaceOldFile){
            $final_name = rex_mediapool_filename($filename . '.' . $ext);
        }

        $arrFolder = explode('/', $uploadDirectory);
        if ($uploadDirectory != $REX['HTDOCS_PATH'].'files/') {
            $final_name = $filename . '.' . $ext;
        }

        if ($this->file->save($uploadDirectory . $final_name)){
            if ($uploadDirectory == $REX['HTDOCS_PATH'].'files/') {
                rex_mediapool_syncFile($final_name, rex_get('mediaCat', 'int'), '');

                rex_set_session('media[rex_file_category]', rex_get('mediaCat', 'int'));

                return array('success'=>true, 'filename' => ''.$final_name.'', 'mediaCatId' => rex_get('mediaCat', 'int'), 'fileId' => OOMedia::getMediaByFileName($final_name)->getId(), 'originalname' => ''.$filename.'.'.$ext.'', 'timestamp' => time());
            } else {
                return array('success'=>true, 'filename' => ''.$final_name.'', 'mediaCatId' => 'none', 'fileId' => 'none', 'originalname' => ''.$final_name.'', 'timestamp' => time());
            }
        } else {
            return array('error'=> 'Die Datei konnte nicht gespeichert werden.' .
            'Der Upload wurde abgebrochen, oder es handelt sich um einen internen Fehler');
        }

    }
}

// security proof // die() if not logged in
if(isset($REX['USER']) AND is_object($REX['USER']) AND ($REX['USER']->hasPerm('rex_multiupload[]') OR $REX['USER']->isAdmin())){
    // redaxo array without dots, strip them out
    $blockedExt = str_replace('.', '', $REX['MEDIAPOOL']['BLOCKED_EXTENSIONS']);
    $allowedExtensions = $blockedExt;

    $upload_folder = rex_request('upload_folder', 'string', 'files/');

    // max file size in bytes
    //$sizeLimit = 10 * 1024 * 1024;
    $sizeLimit = '10737418240';
    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
    $result = $uploader->handleUpload($REX['HTDOCS_PATH'].$upload_folder);

    // to pass data through iframe you will need to encode all html tags
    echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
} else {
    die('ACCESS DENIED');
}


