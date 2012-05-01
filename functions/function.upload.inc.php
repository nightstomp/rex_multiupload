<?php
		
	if(!function_exists('toBytes')) {
		function toBytes($str){
			$val = trim($str);
			$last = strtolower($str[strlen($str)-1]);
			switch($last) {
				case 'g': $val *= 1024;
				case 'm': $val *= 1024;
				case 'k': $val *= 1024;        
			}
			return $val;
		}
	}
	
	
	if(!function_exists('rex_mediapool_MultiUploadform')){ 
		function rex_mediapool_MultiUploadform($display = 'mediapool') {
		
			$rex_file_category = '';
			$output = '';
			//sizeLimit: '.toBytes(ini_get('upload_max_filesize')).', // max size   

			global $REX, $I18N, $PERMALL;
			$myself = 'rex_multiupload';
			$uploadPath = 'index.php?page='.$myself.'&subpage=upload&faceless=1';
			$catSyncError = 'Die Medienkategorie kann leider nur im Medienpool dargestellt werden. Uploads aus dem AddOn-Backend werden in "Keine Kategorie" synchronisiert';

			$cats_sel = new rex_select;
			$cats_sel->setStyle('class="inp100"');
			$cats_sel->setSize(1);
			$cats_sel->setName('rex_file_category');
			$cats_sel->setId('rex_file_category');
			$cats_sel->addOption($I18N->msg('pool_kats_no'),"0");
			// $cats_sel->setAttribute('onChange')

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

			
			$output .=
			'
				<div class="rex-form">
					<fieldset class="rex-form-col-1">
						<legend>Multiupload</legend>
						<div class="rex-form-wrapper">
							<div class="rex-form-row">
								<p class="rex-form-text">
									<label for="rex_file_category">'.$I18N->msg('pool_file_category').'</label>
									'.$cats_sel->get().'
								</p>
							</div>
					
							<div class="rex-form-row">
								<label>Upload:</label>
								<div id="multiupload">		
									<noscript>			
										<p>Please enable JavaScript to use file uploader.</p>
									</noscript>         
								</div>
							</div>
							<div class="rex-form-row">
								<p class="rex-form-text">
									<label>Aktionen</label><a href="javascript:void(0)" onclick="clearUploadList();">Abgeschlossene / fehlerhafte aus der Liste entfernen</a>
								</p>
							</div>
							<script>
											
								function createUploader(){            
									var uploader = new qq.FileUploader({
										element: document.getElementById(\'multiupload\'),
										action: \''.$uploadPath.'\',
										sizeLimit: 0, // max size   
										minSizeLimit: 0, // min size';
										if($REX['ADDON'][$myself]['clear_uploadlist_automatically'])
										{
											$output .= '
											onSubmit: function() {
												clearUploadListSuccess();
											},
									
											onComplete: function() {
												window.setTimeout(function(){
													clearUploadListSuccess();
												}, 3000);
											},
									
											';
										}
										if($REX['ADDON'][$myself]['upload_simultaneously'] != 3){
											$output .= '
											maxConnections: '.$REX['ADDON'][$myself]['upload_simultaneously'].',
											';
										}
										
										$output .= '
										debug: false
								
									});           
								}
								
								jQuery(document).ready(function(){
									createUploader();
								});
						
						    
							</script>
							<div class="rex-form-row">
								<p class="rex-form-file">
									<span class="rex-form-notice">
										Mehrfachauswahl mit STRG(WIN) oder CMD(MAC).<br />
										Die Dateien werden automatisch in die ausgewählte Kategorie sychronisiert.
										Ein Wechsel der Medienkategorie greift immer vor Auswahl einer Datei. 
										Findet ein Upload bereits statt, kann für diese Datei die Kategorie nicht mehr verändert werden.
									</span>
								</p>
							</div>
						</div>
					</fieldset>
				
				</div>';
			return $output;
		}
	}