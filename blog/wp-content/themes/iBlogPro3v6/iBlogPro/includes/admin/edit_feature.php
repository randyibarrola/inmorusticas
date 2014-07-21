<?php 

add_action('admin_menu', 'add_feature_interface');

add_action('admin_post_savefeatures', 'savefeatures');

function add_feature_interface() {
 add_theme_page('Feature Setup', THEMENAMESHORT.' Feature', '8', 'feature', 'featureoptions');
}

function savefeatures(){
	
    $pagelines = new Options;
	
	if($_POST['restore']){
		
		$pagelines->restore_features();		
		wp_redirect(admin_url('themes.php?page=feature&pageaction=restored&selectedtab=' . $_POST['selectedtab']));
		
	}elseif($_POST['restore_feature_backup']){

		$pagelines->restore_features_from_backup();
		wp_redirect(admin_url('themes.php?page=feature&pageaction=restoredfrombackup&selectedtab=' . $_POST['selectedtab']));

	}elseif($_POST['backup_features']){

		$pagelines->backup_features($_POST);
		wp_redirect(admin_url('themes.php?page=feature&pageaction=backup&selectedtab=' . $_POST['selectedtab']));

	}else{	
		
		$pagelines->save_features($_POST);
		wp_redirect(admin_url('themes.php?page=feature&updated=true&selectedtab=' . $_POST['selectedtab']));
	}
	
}

function featureoptions() { ?> 	
	
<div class='wrap'>
	<table id="optionstable"><tbody><tr><td valign="top" width="100%">
	  <h2><?php echo THEMENAME;?> Feature Setup</h2>

	  <form method="post" action="<?php echo admin_url('admin-post.php?action=savefeatures'); ?>">
		  		<?php wp_nonce_field('update-options') ?>	
				<?php if(isset($_GET['updated'])=='true'):?>
						<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);">
							<p>	<strong>Features Saved.</strong></p>
						</div>
				<?php endif;?>
					<?php if($_GET['pageaction']):?>
							<?php $a = $_GET['pageaction'];?>
							<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);">
								<p>	<strong>
									<?php 
									if($a == 'restored') echo "Feature information restored to default.";
									elseif($a =='restoredfrombackup') echo "Feature information restored from backup";	
									elseif($a =='backup') echo "Feature information backed up in database";	
									?>

								</strong></p>
							</div>
					<?php endif;?>
				<?php if(floatval(phpversion()) < 5.0):?>
				<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);">	
				<p><strong>You are using PHP version <?php echo phpversion(); ?>.</strong>  Version 5 or higher is required for this theme to work correctly.</p>  <p>Please check with your host about upgrading to a newer version.</p> 
				</div>
				<?php endif;?>
			
			
			
				<?php
					if(isset($_GET['selectedtab']) && !empty($_GET['selectedtab'])) {
						$tab = $_GET['selectedtab'];
					} else {
						$tab = 0;
					}
				?>

					<script type="text/javascript">
							jQuery.noConflict();
							jQuery(document).ready(function($) {						
								var myTabs = $("#tabs").tabs({ fx: { opacity: "toggle", duration: "fast" }, selected: <?php echo $tab; ?>});

								$('#tabs').bind('tabsshow', function(event, ui) {
									$("#selectedtab").val($('#tabs').tabs('option', 'selected'));
								});
							});
					</script>
			

				<?php $featurearray = get_feature_array();?>

						<div id="optionsheader">
							
							<div class="hl"></div>
							<div class="options_intro"><a href="http://www.pagelines.com" title="PageLines Themes"><img alt="PageLines Themes" src="<?php echo IMAGE_FOLDER;?>/pagelines.png" class="alignright"/></a><small><strong>Welcome to <?php echo THEMENAME;?> feature setup.</strong> We hope your enjoying this premium theme from <a href="http://www.pagelines.com">PageLines</a>.<br/>
								This section allows you to customize your 'feature-page' template included in this theme.</small></div>
						
						</div>
						<div id="tabs">

							<ul id="tabsnav">
								<?php foreach($featurearray as $menuitem => $options):?>
								<li><a onClick="" class="<?php echo $menuitem;?>" href="#<?php echo $menuitem;?>"><span><?php echo ucwords(str_replace('_',' ',$menuitem));?></span></a></li>
								<?php endforeach;?>
								
								<?php if(is_array(pagelines('features'))):?>
									<?php foreach(pagelines('features') as $key => $feature):?>
										<li>
											<a onClick="" class="feature <?php echo 'feature'.$key;?>" href="#<?php echo 'feature'.$key;?>">
												<span><?php echo ucwords(str_replace('_',' ',$feature['name']));?></span>
											</a>
										</li>
									<?php endforeach;?>
								<?php endif;?>
								
								<?php if(is_array(pagelines('fboxes'))):?>
									<?php foreach(pagelines('fboxes') as $key => $fbox):?>
										<li>
											<a onClick="" class="<?php echo 'fbox'.$key;?>" href="#<?php echo 'fbox'.$key;?>">
												<span>Feature Box <?php echo $key;?></span>
											</a>
										</li>
									<?php endforeach;?>
								<?php endif;?>
								
								<li class="listsave"><input class="button-secondary" type="submit" name="Submit" value="Save Options" /></li>

							</ul>
							<div id="thetabs" class="fix">
								
									<?php foreach($featurearray as $menuitem => $options):?>
									<div id="<?php echo $menuitem;?>" class="tabinfo fix">
										<div class="tabtitle"><?php echo ucwords(str_replace('_',' ',$menuitem));?></div>
									
										<?php foreach($options as $optionid => $o):?>
										<div class="optionrow fix <?php if(isset($o['layout']) && $o['layout']=='full') echo 'wideinputs';?>">

											<div class="optiontitle">
												<?php if($o['optionicon']):?>
													<img src="<?php echo $o['optionicon'];?>" class="optionicon" style=" ">
												<?php endif;?>
												<strong><?php echo $o['title'];?></strong><br/>
												<small><?php echo $o['shortexp'];?></small><br/>
											</div>
											<div class="theinputs ">
												<div class="optioninputs">

													<?php if($o['type'] == 'image_url'):?>
														<p>	<label class="context" for="<?php echo $optionid;?>">Full Image URL</label><br/>
															<input class="regular-text" type="text" id="<?php echo $optionid;?>" name="<?php echo $optionid;?>" value="<?php echo pagelines($optionid); ?>" /></p>
														<p>
															<?php if(pagelines($optionid)):?>
																<div class="context">Current image:</div> 
																<img class="border" src="<?php echo pagelines($optionid);?>" style="width:<?php echo $o['imagepreview'];?>px"/></p>
															<?php endif;?>
														</p>

													<?php elseif($o['type'] == 'check'):?>
														<p>
															<label for="<?php echo $optionid;?>" class="context"><input class="admin_checkbox" type="checkbox" name="<?php echo $optionid;?>" <?php if(pagelines($optionid)) echo 'checked'; else echo 'unchecked';?> /><?php echo $o['inputlabel'];?></label>
														</p>

													<?php elseif($o['type'] == 'text_small'):?>
														<p>
															<label for="<?php echo $optionid;?>" class="context"><?php echo $o['inputlabel'];?></label>
															<input class="small-text"  type="text" name="<?php echo $optionid;?>" id="<?php echo $optionid;?>" value="<?php echo pagelines($optionid); ?>" />
														</p>
													<?php elseif($o['type'] == 'text'):?>
														<p>
															<label for="<?php echo $optionid;?>" class="context"><?php echo $o['inputlabel'];?></label>
															<input class="regular-text"  type="text" name="<?php echo $optionid;?>" id="<?php echo $optionid;?>" value="<?php echo pagelines($optionid); ?>" />
														</p>
													<?php elseif($o['type'] == 'textarea'):?>
														<p>
															<label for="<?php echo $optionid;?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
															<textarea name="<?php echo $optionid;?>" class="html-textarea" cols="70%" rows="5"><?php echo pagelines($optionid); ?></textarea>
														</p>
													<?php elseif($o['type'] == 'radio'):?>
														<p>
															<?php foreach($o['selectvalues'] as $selectid => $selecttext):?>
															<input type="radio" name="<?php echo $optionid;?>" value="<?php echo $selectid;?>" <?php if(pagelines($optionid) == $selectid):?>checked<?php endif;?>> <?php echo $selecttext;?><br/>
															<?php endforeach;?>
														</p>
													<?php elseif($o['type'] == 'select' || $o['type'] == 'select_same'):?>
														<p>
															<label for="<?php echo $optionid;?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
															<select id="<?php echo $optionid;?>" name="<?php echo $optionid;?>">
																<option value="">&mdash;SELECT&mdash;</option>
																
																<?php foreach($o['selectvalues'] as $sval):?>
																	<option value="<?php echo $sval;?>" <?php if(pagelines($optionid)==$sval) echo 'selected';?>><?php echo $sval;?></option>
																<?php endforeach;?>
															
															</select>
														</p>
														
													<?php endif;?>
												</div>
											</div>

											<?php if($o['exp']):?>
											<div class="theexplanation">
												<div class="context">More Info</div>
												<p><?php echo $o['exp'];?></p>

											</div>
											<?php endif;?>
											<div class="clear"></div>
										</div>
										<?php endforeach; ?>
								</div>

								<?php endforeach; ?>
								
								<?php $fset = get_feature_setup();?>
								
								<?php if(is_array(pagelines('features'))):?>
									<?php foreach(pagelines('features') as $key => $feature):?>
										
										<div id="<?php echo 'feature'.$key;?>" class="featuretab tabinfo fix">
											<div class="tabtitle"><?php echo ucwords(str_replace('_',' ',$feature['name']));?></div>
												
											<?php foreach($feature as $field => $fieldvalue):?>
												<?php if(isset($fset[$field])):?>
													<div class="optionrow fix">

														<div class="optiontitle">
															<strong><?php echo $fset[$field]['title'];?></strong><br/>
															<small><?php echo $fset[$field]['shortexp'];?></small><br/>
														</div>
														<div class="theinputs ">
															<div class="optioninputs">
														
														
																<?php if($fset[$field]['type']=='textarea' || $fset[$field]['type']=='textarea_big'):?>
																<p>
																	<label for="feature[<?php echo $key;?>][<?php echo $field;?>]" class="context"><?php echo $fset[$field]['inputlabel'];?></label><br/>
																	<textarea name="feature[<?php echo $key;?>][<?php echo $field;?>]" class="html-textarea <?php if($fset[$field]['type']=='textarea_big') echo "longtext";?>" cols="70%" rows="5"><?php echo $fieldvalue; ?></textarea>
																</p>
																<?php elseif($fset[$field]['type']=='text'):?>
																<p>
																	<label for="feature[<?php echo $key;?>][<?php echo $field;?>]" class="context"><?php echo $fset[$field]['inputlabel'];?></label><br/>
																	<input class="regular-text"  type="text" name="feature[<?php echo $key;?>][<?php echo $field;?>]" id="feature[<?php echo $key;?>][<?php echo $field;?>]" value="<?php echo $fieldvalue; ?>" />
															
																</p>
																<?php endif;?>
															</div>
														</div>

														<?php if($fset[$field]['exp']):?>
														<div class="theexplanation">
															<div class="context">More Info</div>
															<p><?php echo $fset[$field]['exp'];?></p>

														</div>
														<?php endif;?>
														<div class="clear"></div>
													</div>
												<?php endif;?>
											<?php endforeach;?>
									
										</div>

										
									<?php endforeach;?>
								<?php endif;?>

								<?php $fboxset = get_fbox_setup();?>

								<?php if(is_array(pagelines('fboxes'))):?>
									<?php foreach(pagelines('fboxes') as $key => $fbox):?>

										<div id="<?php echo 'fbox'.$key;?>" class="tabinfo fix">
											<div class="tabtitle">Feature Box <?php echo $key;?></div>
	
											<?php foreach($fbox as $field => $fieldvalue):?>
												<?php if(isset($fboxset[$field])):?>
													<div class="optionrow fix">
														<div class="optiontitle">
															<strong><?php echo $fboxset[$field]['title'];?></strong><br/>
															<small><?php echo $fboxset[$field]['shortexp'];?></small><br/>
														</div>
														<div class="theinputs ">
															<div class="optioninputs">


																<?php if($fboxset[$field]['type']=='textarea' || $fboxset[$field]['type']=='textarea_big'):?>
																<p>
																	<label for="fbox[<?php echo $key;?>][<?php echo $field;?>]" class="context"><?php echo $fboxset[$field]['inputlabel'];?></label><br/>
																	<textarea name="fbox[<?php echo $key;?>][<?php echo $field;?>]" class="html-textarea <?php if($fboxset[$field]['type']=='textarea_big') echo "longtext";?>" cols="70%" rows="5"><?php echo $fieldvalue; ?></textarea>
																</p>
																<?php elseif($fset[$field]['type']=='text'):?>
																<p>
																	<label for="fbox[<?php echo $key;?>][<?php echo $field;?>]" class="context"><?php echo $fboxset[$field]['inputlabel'];?></label><br/>
																	<input class="regular-text"  type="text" name="fbox[<?php echo $key;?>][<?php echo $field;?>]" id="feature[<?php echo $key;?>][<?php echo $field;?>]" value="<?php echo $fieldvalue; ?>" />

																</p>
																<?php endif;?>
															</div>
														</div>

														<?php if($fboxset[$field]['exp']):?>
														<div class="theexplanation">
															<div class="context">More Info</div>
															<p><?php echo $fboxset[$field]['exp'];?></p>

														</div>
														<?php endif;?>
														<div class="clear"></div>
													</div>
												<?php endif;?>
											<?php endforeach;?>

										</div>


									<?php endforeach;?>
								<?php endif;?>
								

							</div> <!-- End the tabs -->


						</div> <!-- end tabs -->
						<div id="optionsfooter">
							
							<div class="hl"></div>
				 			<div class="theinputs">
					
									<input type="hidden" name="selectedtab" id="selectedtab" value="" />
								
						 			<input type="hidden" name="action" value="savefeatures" /> <!-- the function we execute to process -->
					  	  		<input class="button-primary" type="submit" name="Submit" value="Save Options" />


							</div>
							<div class="clear"></div>
						</div>
						<div class="optionrestore">
							<p>
								<div class="context"><input class="button-primary" type="submit" name="backup_features" value="Backup Feature Information" />To be sure you don't lose your feature information, make sure to save a copy in your DB from time to time.</div>

							</p>
							<p><div class="context">	<input class="button-secondary" type="submit" name="restore_feature_backup" onClick="return ConfirmRestoreBackup();" value="Restore From Backup" />If you'd like to restore your features from your latest feature backup, use this button.</div>
							<script language="jscript" type="text/javascript">
							function ConfirmRestoreBackup(){	
								var a = confirm ("Are you sure? This will restore your feature options to your most recent database backup.");
								if(a) return true;
								else return false;
							}
							</script>

							</p>
							<p>	<div class="context"><input class="button-secondary" type="submit" name="restore" onClick="return ConfirmRestore();" value="Restore Features To Default" />Sometimes the features can get tweaked and its best to restore them to their defaults. To do that use this button.</div>
							<script language="jscript" type="text/javascript">
							function ConfirmRestore(){	
								var a = confirm ("Are you sure? This will restore the features to their default HTML.");
								if(a) return true;
								else return false;
							}
							</script>

							</p>
							<br/>
							<br/>
						</div>
						
				  </form>
				</td></tr></tbody></table>

			<div class="clear"></div>
			
		
  </div>
<?php } ?>