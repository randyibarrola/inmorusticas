<?php 

//Takes post data and sends it to the appended filename ( after admin_post_ )
add_action('admin_post_saveoptions', 'saveoptions');

function saveoptions(){

	$pagelines = new Options;

	if($_POST['restore']){
		
		$pagelines->restore_options();
		wp_redirect(admin_url('themes.php?page=functions&pageaction=restored'));
		
	}elseif($_POST['restorebackup']){
		
		$pagelines->restore_from_backup();
		wp_redirect(admin_url('themes.php?page=functions&pageaction=restoredfrombackup'));
		
	}elseif($_POST['backupoptions']){
		
		$pagelines->backup_options($_POST);
		wp_redirect(admin_url('themes.php?page=functions&pageaction=backup'));
		
	}else{
	
		$pagelines->update_options_from_array(get_option_array());
		
		$pagelines->save_options();

		wp_redirect(admin_url('themes.php?page=functions&pageaction=updated&selectedtab=' . $_POST['selectedtab']));
	}
}


//Runs after the basic admin panel menu structure is in place. 
add_action('admin_menu', 'add_option_interface');
function add_option_interface() {
  add_theme_page(THEMENAME.' Options', THEMENAME.' Options', '8', 'functions', 'editoptions');
}
?><?php function editoptions() { ?>

<div class='wrap'>
	<table id="optionstable"><tbody><tr><td valign="top" width="100%">
  	<h2><?php echo THEMENAME;?> Options</h2>
	  <form method="post" action="<?php echo admin_url('admin-post.php?action=saveoptions'); ?>">
		  <?php wp_nonce_field('update-options') ?>	
				<?php if($_GET['pageaction']):?>
						<?php $a = $_GET['pageaction'];?>
						<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);">
							<p>	<strong>
								<?php 
								if($a == 'restored') echo "Options restored to default.";
								elseif($a =='restoredfrombackup') echo "Options restored from backup";	
								elseif($a =='backup') echo "Options backed up in database";	
								elseif($a == 'updated') echo "Options Saved."
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

					<div id="optionsheader">
						
						<div class="hl"></div>
						
						<div class="options_intro"><a href="http://www.pagelines.com" title="PageLines Themes"><img alt="PageLines Themes" src="<?php echo IMAGE_FOLDER;?>/pagelines.png" class="alignright"/></a><small><strong>Welcome to <?php echo THEMENAME;?> theme options.</strong> We hope your enjoying this premium theme from <a href="http://www.pagelines.com">PageLines</a>.<br/>
							This section allows you to customize your theme.</small></div>
					</div>
					<div id="tabs">
					
						<ul id="tabsnav">
							
							<?php 	$optionarray = get_option_array();	?>
							<?php foreach($optionarray as $menuitem => $options):?>
							<li>
								<a onClick="" class="<?php echo $menuitem;?>" href="#<?php echo $menuitem;?>">
									<span><?php echo ucwords(str_replace('_',' ',$menuitem));?></span>
								</a>
							</li>
							
							<?php endforeach;?>
							<li class="listsave"><input class="button-secondary" type="submit" name="Submit" value="Save Options" /></li>

						</ul>
						<div id="thetabs" class="fix">
								<?php foreach($optionarray as $menuitem => $options):?>
								<div id="<?php echo $menuitem;?>" class="tabinfo fix">
									<div class="tabtitle"><?php echo ucwords(str_replace('_',' ',$menuitem));?></div>
								
									<?php foreach($options as $optionid => $o):?>
									<div class="optionrow fix <?php if(isset($o['layout']) && $o['layout']=='full') echo 'wideinputs';?>">
									
										<div class="optiontitle ">
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
												<?php elseif($o['type'] == 'check_multi'):?>
													
														<?php foreach($o['selectvalues'] as $multi_optionid => $multi_o):?>
														<p>
															<label for="<?php echo $multi_optionid;?>" class="context"><input class="admin_checkbox" type="checkbox" id="<?php echo $multi_optionid;?>" name="<?php echo $multi_optionid;?>" <?php if(pagelines($multi_optionid)) echo 'checked'; else echo 'unchecked';?> /><?php echo $multi_o['inputlabel'];?></label>
														</p>
														<?php endforeach;?>
													
												
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
													
														<?php foreach($o['selectvalues'] as $selectid => $selecttext):?>
														<p><input type="radio" name="<?php echo $optionid;?>" value="<?php echo $selectid;?>" <?php if(pagelines($optionid) == $selectid):?>checked<?php endif;?>> <label for=""><?php echo $selecttext;?></label></p>
														<?php endforeach;?>
													
												<?php elseif($o['type'] == 'select' || $o['type'] == 'select_same'):?>
													<p>
														<label for="<?php echo $optionid;?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
														<select id="<?php echo $optionid;?>" name="<?php echo $optionid;?>">
															<option value="">&mdash;SELECT&mdash;</option>
															
															<?php foreach($o['selectvalues'] as $sval => $stext):?>
																<?php if($o['type']=='select_same'):?>
																		<option value="<?php echo $stext;?>" <?php if(pagelines($optionid)==$stext) echo 'selected';?>><?php echo $stext;?></option>
																<?php else:?>
																		<option value="<?php echo $sval;?>" <?php if(pagelines($optionid)==$sval) echo 'selected';?>><?php echo $stext;?></option>
																<?php endif;?>
															
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
							
							
						</div> <!-- End the tabs -->
					
						
					</div> <!-- end tabs -->
					<div id="optionsfooter">
						<div class="hl"></div>
			 			<div class="theinputs">
							<input type="hidden" name="selectedtab" id="selectedtab" value="" />
							<input type="hidden" name="action" value="saveoptions" /> <!-- the function we execute to process -->
				  	  		<input class="button-primary" type="submit" name="Submit" value="Save Options" />
						
							
						</div>
						<div class="clear"></div>
					</div>
					<div class="optionrestore">
						<p>
							<div class="context"><input class="button-primary" type="submit" name="backupoptions" value="Backup Option Information" />To be sure you don't lose your option information, make sure to save a copy in your DB from time to time.</div>
							
						</p>
						<p><div class="context">	<input class="button-secondary" type="submit" name="restorebackup" onClick="return ConfirmRestoreBackup();" value="Restore From Backup" />If you'd like to restore you options from your latest option backup, use this button.</div>
						<script language="jscript" type="text/javascript">
						function ConfirmRestoreBackup(){	
							var a = confirm ("Are you sure? This will restore your options to your most recent database backup.");
							if(a) return true;
							else return false;
						}
						</script>
					
						</p>
						<p>	<div class="context"><input class="button-secondary" type="submit" name="restore" onClick="return ConfirmRestore();" value="Restore Options To Default" />Sometimes the options can get tweaked and its best to restore them to their defaults. To do that use this button.</div>
						<script language="jscript" type="text/javascript">
						function ConfirmRestore(){	
							var a = confirm ("Are you sure? This will restore your options to their defaults.");
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