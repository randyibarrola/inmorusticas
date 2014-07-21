<?php
/**
 * Post admin hooks
 */
add_action('admin_menu', "add_edit_page");
add_action('save_post', 'save_edit_page');

/**
 * Add video posting widget and options page.
*/

function add_edit_page(){
	if( function_exists("add_meta_box")){
		add_meta_box("edit_page_interface", THEMENAME." Page Options", "edit_page_interface", "page", "advanced");
	}
}

/**
 * Saves the thumbnail image as a meta field associated
 * with the current post. Runs when a post is saved.
 */
function save_edit_page( $postID ) {
	if($_POST['update'] || $_POST['save']){
		$editpagearray= get_edit_page_post_array();
		foreach($editpagearray as $optionid => $o){
			update_post_meta($postID, $optionid, $_POST[$optionid] );
		}
	}
}
/**
 * Code for the meta box.
 */
function edit_page_interface()
{
	global $post_ID;
	
	$editpagearray= get_edit_page_post_array();

?>	
	<?php foreach($editpagearray as $optionid => $o):?>
		<?php if(isset($o['where']) && $o['where'] == 'post'):?><?php else:?>
		<div class="page_option">
			
				<?php if($o['type']=='check'):?>
				<p style="">
					<label for="<?php echo $optionid;?>">
						<input class="admin_checkbox" type="checkbox" id="<?php echo $optionid;?>" name="<?php echo $optionid;?>" <?php if(m_pagelines($optionid, $post_ID)) echo 'checked'; else echo 'unchecked';?> /><strong><?php echo $o['inputlabel'];?></strong>
					</label>
				</p>
				<p><?php echo $o['exp'];?></p>
				<?php elseif($o['type'] == 'textarea'):?>
					<div class="page_option_text_exp">
						<p>
						<label for="<?php echo $optionid;?>"><strong><?php echo $o['inputlabel'];?></strong></label><br/>
						<small><?php echo $o['exp'];?></small>
						</p>
					</div>
						<textarea class="html-textarea"  id="<?php echo $optionid;?>" name="<?php echo $optionid;?>" /><?php em_pagelines($optionid, $post_ID); ?></textarea>
				
				<?php endif;?>
			<div class="clear"></div>
		</div>
		<?php endif;?>
	<?php endforeach;?>

<p style="margin:10px 0 0 25px;">
	<input id="update" class="button-primary" type="submit" value="<?php _e("Update"); ?>" accesskey="p" tabindex="5" name="update"/>
</p>
<br/>

<?php } ?>
