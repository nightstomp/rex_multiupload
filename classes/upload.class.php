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
 
if(!class_exists('rex_mediapool_multiupload')) {
  class rex_mediapool_multiupload {
  
      /**
      * Vars
      */
      public $myself = "rex_multiupload";
      public $sync_cat = null;
      public $clear_uploadlist_automatically = null;
      public $clear_file_after_finish = null;
      public $upload_simultaneously = null;
      public $javascript_debug = null;
      public $showFootnote = null;
      public $return_markup = true;
      public $onUploadCallback = null;
      public $onSubmitCallback = null;
      public $onProgressCallback = null;
      public $onCompleteCallback = null;
      public $onCancelCallback = null;
      public $mediaCats = null;
      public $time = null;
      
      /**
      * constructor
      */
      function __construct(){
        global $REX;
        
        $this->sync_cat = $REX["ADDON"][$this->myself]["settings"]["SELECT"]["sync_cats"];
        $this->clear_uploadlist_automatically = $REX["ADDON"][$this->myself]["settings"]["SELECT"]["clear_uploadlist_automatically"];
        $this->clear_file_after_finish = $REX["ADDON"][$this->myself]["settings"]["SELECT"]["clear_file_after_finish"];
        $this->upload_simultaneously = $REX["ADDON"][$this->myself]["settings"]["SELECT"]["upload_simultaneously"];
        $this->javascript_debug = $REX["ADDON"][$this->myself]["settings"]["SELECT"]["javascript_debug"];
        $this->showFootnote = $REX["ADDON"][$this->myself]["settings"]["SELECT"]["show_footnote"];
        $this->folder = $REX["ADDON"][$this->myself]["settings"]["folder"];
        $this->markup = $this->return_markup;
        $this->time = uniqid();        
      }
      
      
      /**
      * setValue() function to edit all parameters
      */
      public function setValue($sync = true, $clear_auto = true, $clear_after_finish = true, $simultan_uploads_value = 5, 
        $js_debug = false, $footnote = true, $folder = "/files/") {
        
        global $REX;
        
        $this->sync_cat = $sync;
        $this->clear_uploadlist_automatically = $clear_auto;
        $this->clear_file_after_finish = $clear_after_finish;
        $this->upload_simultaneously = $simultan_uploads_value;
        $this->javascript_debug = $js_debug;
        $this->showFootnote = $footnote;
        $this->folder = $folder;
      }
      
      
      /**
      * setter function for displaying catsync select (boolean: true/false)
      */
      public function setSyncCat($value = true){
        $this->sync_cat = $value;
      }
      
      
      /**
      * setter function for auto clear list (boolean: true/false)
      */
      public function setClearUploadsAutomatically($value = true){
        $this->clear_uploadlist_automatically = $value;
      }
      
      /**
      * setter function for auto clear file after complete/failure (boolean: true/false)
      */
      public function setClearFileAfterFinish($value = true){
        $this->clear_file_after_finish = $value;
      }
      
      /**
      * setter function for simultan uploads (int)
      */
      public function setSimultanUploads($value = 5){
        if(is_numeric($value)){
          $this->upload_simultaneously = $value;
        } else {
          $this->upload_simultaneously = 5;
        }
      }
      
      /**
      * setter function for activating js debug (boolean: true/false)
      */
      public function setJSDebug($value = false){
        $this->javascript_debug = $value;
      }
      
      /**
      * setter function for displaying footnote (boolean: true/false)
      */
      public function setFootnote($value = true){
        $this->showFootnote = $value;
      }
      
      /**
      * setter function for returning multiupload with markup (boolean: true/false)
      */
      public function setMarkup($return_markup = true) {
        $this->markup = $return_markup; 
      }
      
      
      /**
      * function to register javascript callbacks / $fn needs to be a simple "function" without ()
      */
      public function setCallback($type, $fn = null){
        switch ($type) {
          case "upload":
            $this->onUploadCallback = $fn;
          break;
          case "submit":
            $this->onSubmitCallback = $fn;
          break;
          case "progress":
            $this->onProgressCallback = $fn;
          break;
          case "complete":
            $this->onCompleteCallback = $fn;
          break;
          case "cancel":
            $this->onCancelCallback = $fn;
          break;
        }
      }
      
      /**
      * getter function - returns mediaSync select
      */
      public function getMediaCats(){
        global $REX, $I18N, $PERMALL;
        
        $rex_file_category = '';
        if(!$rex_file_category)
        {
          $rex_file_category = rex_session('media[rex_file_category]', 'int');
        }
        
        // include cat sync select
        $cats_sel = new rex_select;
        $cats_sel->setStyle('class="inp100"');
        $cats_sel->setSize(1);
        $cats_sel->setName('rex_file_category');
        $cats_sel->setId('rex_file_category_'.$this->time);
        $cats_sel->addOption($I18N->msg('pool_kats_no'),"0");

        $mediacat_ids = array();
        $rootCat = 0;
        $PERMALL = TRUE;
        if ($rootCats = OOMediaCategory::getRootCategories())
        {
          foreach( $rootCats as $rootCat) {
            rex_mediapool_addMediacatOptionsWPerm( $cats_sel, $rootCat, $mediacat_ids);
          }
        }
        $cats_sel->setSelected($rex_file_category);
        return $cats_sel->get();
        
      }

      
      /**
      * creates and returns the uploadform
      */
      public function createUploadForm() {
          
        global $REX, $I18N, $PERMALL;

        $rex_file_category = '';
        if(!$rex_file_category)
        {
          $rex_file_category = rex_session('media[rex_file_category]', 'int');
        }

        $output = '';
        $script_page_header = '';
        $uploadPath = "index.php?page=".$this->myself."&subpage=upload&upload_folder=".$this->folder."&faceless=1";
        
        if($this->sync_cat){
          // include cat sync select
          $cats_sel = new rex_select;
          $cats_sel->setStyle('class="inp100"');
          $cats_sel->setSize(1);
          $cats_sel->setName('rex_file_category');
          $cats_sel->setId('rex_file_category_'.$this->time);
          $cats_sel->addOption($I18N->msg('pool_kats_no'),"0");

          $mediacat_ids = array();
          $rootCat = 0;
          $PERMALL = TRUE;
          if ($rootCats = OOMediaCategory::getRootCategories())
          {
            foreach( $rootCats as $rootCat) {
              rex_mediapool_addMediacatOptionsWPerm( $cats_sel, $rootCat, $mediacat_ids);
            }
          }
          $cats_sel->setSelected($rex_file_category);
        }
        
        
        if($this->markup){
          $output .='
          <div class="rex-form">
            <fieldset class="rex-form-col-1">
              <legend>Multiupload</legend>
              <div class="rex-form-wrapper">'."\n";
              
              if($this->sync_cat){
                $output .='
                <div class="rex-form-row">
                  <p class="rex-form-text">
                    <label for="rex_file_category">'.$I18N->msg('pool_file_category').'</label>
                    '.$cats_sel->get().'
                  </p>
                </div>'."\n";
              }
              
              $output .='
                <div class="rex-form-row">
                <label>Upload:</label>'."\n";
          }
            $output .='
            <div id="multiupload'.$this->time.'" class="'.($this->markup ? 'behave_normal' : 'styleless').'">    
              <noscript>      
                <p>JavaScript muss aktiviert sein.</p>
              </noscript>         
            </div>';
                  
            if($this->markup){
              $output .='
              </div>';
              if(!$this->clear_uploadlist_automatically) {
                $output .= '<div class="rex-form-row">
                  <p class="rex-form-text">
                    <label>Aktionen</label>
                    <a href="javascript:void(0)" onclick="clearUploadList();">Abgeschlossene / fehlerhafte aus der Liste entfernen</a>
                  </p>
                </div>'."\n";
              }
            }

            $script_page_header .= '
            <script>
              
              function rex_multiupload_createUploader'.$this->time.'(){            
                var uploader = new qq.FileUploader({
                  element: document.getElementById("multiupload'.$this->time.'"),
                  action: "'.$uploadPath.'",
                  mediaPoolSelector: "rex_file_category_'.$this->time.'",
                  sizeLimit: 0, // max size   
                  minSizeLimit: 0, // min size';
                  
                                    
                 $script_page_header .= '
                  onSubmit: function(id,filename) {'."\n";
                   if($this->clear_uploadlist_automatically)
                   {
                   $script_page_header .= '
                   clearUploadList();';
                   }
                   
                   if($this->onSubmitCallback){
                   $script_page_header .= '
                   
                   if(typeof '.$this->onSubmitCallback.' == "function") { 
                     // user callback function
                     '.$this->onSubmitCallback.'(filename);
                   }';
                   }
                 $script_page_header .= '
                  },
                  ';
                  
                  
                  $script_page_header .= '
                  onUpload: function(id,fileName, xhr) {'."\n";
                    
                    if($this->onUploadCallback){
                    $script_page_header .= '
                    
                    if(typeof '.$this->onUploadCallback.' == "function") { 
                      // user callback function
                      '.$this->onUploadCallback.'(fileName, xhr);
                    }';
                    }
                  $script_page_header .= '
                  
                    
                  },
                  ';
                  
                  $script_page_header .= '
                  onProgress: function(id,fileName, loaded, total) {'."\n";
                    
                    if($this->onProgressCallback){
                    $script_page_header .= '
                    
                    if(typeof '.$this->onProgressCallback.' == "function") { 
                      // user callback function
                      '.$this->onProgressCallback.'(fileName, loaded, total);
                    }';
                    }
                  $script_page_header .= '
                  
                    
                  },
                  ';
                                    
                  $script_page_header .= '
                  onComplete: function(id,filename,json) {'."\n";
                    if($this->clear_file_after_finish)
                    {
                    $script_page_header .= '                        
                    window.setTimeout(function(){
                      clearUploadListSuccess();
                    }, 5000);';
                    }
                    
                    if($this->onCompleteCallback){
                    $script_page_header .= '
                    
                    if(typeof '.$this->onCompleteCallback.' == "function" && json.success) { 
                      // user callback function
                      '.$this->onCompleteCallback.'(json);
                    }';
                    }
                  $script_page_header .= '
                  
                    
                  },
                  ';
                  
                  $script_page_header .= '
                  onCancel: function(id,filename) {'."\n";
                    
                    if($this->onCancelCallback){
                    $script_page_header .= '
                    
                    if(typeof '.$this->onCancelCallback.' == "function") { 
                      // user callback function
                      '.$this->onCancelCallback.'(filename);
                    }';
                    }
                  $script_page_header .= '
                  
                    
                  },
                  ';
                  
                  
                  
                  
                  
                  if($this->upload_simultaneously && is_numeric($this->upload_simultaneously)){
                    $script_page_header .= ' 
                  maxConnections: '.$this->upload_simultaneously.',';
                  }

                    $script_page_header .= '
                  debug: '.($this->javascript_debug ? "true" : "false").'
                });           
              }
              
              jQuery(document).ready(function(){
                rex_multiupload_createUploader'.$this->time.'();
              });
            </script>'."\n";
            
            if($this->markup){    
              if($this->showFootnote){
                $output .= 
                '<div class="rex-form-row edit_panel">
                  <label>Dateien editieren</label>
                    <ul class="qq-upload-list edit_uploads">

                    </ul>
                  </p>
                </div>'."\n";
              }
            }

            if($this->markup){    
              if($this->showFootnote){
                $output .= 
                '<div class="rex-form-row">
                  <p class="rex-form-file">
                    <span class="rex-form-notice">
                      Mehrfachauswahl mit STRG(WIN) oder CMD(MAC).<br />
                      Die Dateien werden automatisch in die ausgewählte Kategorie sychronisiert.
                      Ein Wechsel der Medienkategorie greift immer vor Auswahl einer Datei. 
                      Findet ein Upload bereits statt, kann für diese Datei die Kategorie nicht mehr verändert werden.
                    </span>
                  </p>
                </div>'."\n";
              }
              $output .= '  
                </div>
                </fieldset>
                </div>'."\n";
            }
            
            
            // Register EXTENSION POINT
            $header_func = 'return str_replace("<!-- ###MULTIUPLOAD_EP_REPLACE### -->",\''.$script_page_header.'\'."<!-- ###MULTIUPLOAD_EP_REPLACE### -->",$params["subject"]);';            
            rex_register_extension('OUTPUT_FILTER', create_function('$params',$header_func));
            
            // TIME FOR OUTPUT
            return $output;
      }
   }
}

