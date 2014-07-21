<?php 

add_action('admin_menu', 'add_debug_interface');


function add_debug_interface() {
 add_theme_page('Debug Interface', THEMENAMESHORT.' Debug Mode', '8', 'debug', 'debugmode');
}

function debugmode() { ?> 	
	

<div class='wrap'>
	<table id="optionstable"><tbody><tr><td valign="top" width="100%">
	  <h2><?php echo THEMENAME;?> Debug Mode</h2>
		<div id="optionsheader">
			
			<div class="hl"></div>
			<div class="options_intro"><small><strong>Welcome to <?php echo THEMENAME;?> debugging mode.</strong> It is designed to show you information you need to debug your install.</div>
		
		</div>
			<div id="tabs" style="overflow:hidden;width: 880px;padding: 20px">
				<?php
					$pagelines = new Options;
					
					echo "<pre>";
					print_r($pagelines);
					echo "</pre>";
				?>
				<?php// phpinfo();?>
	  		</div>
	</td></tr></tbody></table>

	<div class="clear"></div>
	
	
 </div>
<?php } ?>