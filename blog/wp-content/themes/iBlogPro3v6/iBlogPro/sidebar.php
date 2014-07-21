<div id="sidebar" class="">

	
	<?php if(is_home() || pagelines('welcomeall')):?>
		
		<?php if(pagelines('greeting') || pagelines('welcomemessage')):?>
		<div id="welcome">
			<?php if(pagelines('greeting')):?><h2 class="greeting"><?php echo pagelines('greeting');?></h2><?php endif;?>
			<?php if(pagelines('welcomemessage')):?><p class="welcomemessage"><?php echo pagelines('welcomemessage');?></p><?php endif;?>
		</div><!-- Closes welcome -->
		<?php endif; ?>
		
		
		<?php include (LIB . '/_twittermessages.php'); ?>
		
	<?php endif;?>
	
	<?php include(LIB.'/_grandchildnav.php');?>
	
	<?php if(pagelines('showads') && function_exists('wp125_write_ads') && !get_post_meta($post->ID, 'hide_ads', true)):?>
	<div id="ads" class="wp125_write_ads_widget widget">
		<div class="winner clear">
			<?php wp125_write_ads(); ?>
			<div class="clear"></div>
		</div>
	</div>
	<?php endif;?>
	
	
	<?php
		$flow_sidebar = get_post_meta($post->ID, 'flow_sidebar', true);
		$drag_drop_sidebar = get_post_meta($post->ID, 'drag_drop_sidebar', true);
		$accordion_sidebar = get_post_meta($post->ID, 'accordion_sidebar', true);
	?>
	<?php if($flow_sidebar || $drag_drop_sidebar || $accordion_sidebar ):?>
		<?php if($accordion_sidebar):?>
		<div id="accordion">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('accordion_sidebar')): ?>
				<?php include(LIB.'/_defaultaccordion.php');?>
			<?php endif; ?>			
		</div>
		<?php endif; ?>
		<?php if($flow_sidebar):?>
		<div id="flowbar">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('flow_sidebar') ) : ?>
				<div class="widget">
					<div class="winner">
				
					<?php _e('No widgets have been added to this sidebar. Add some widgets to your this sidebar in your admin.', TDOMAIN);?>
					</div>
				</div>
			<?php endif; ?>			
		</div>
		<?php endif; ?>
		<?php if($drag_drop_sidebar):?>
		<div id="drag_drop_sidebar">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('drag_drop_sidebar') ) : ?>
				<div class="widget">
					<div class="winner">
				<?php _e('No widgets have been added to this sidebar. Add some widgets to your this sidebar in your admin.', TDOMAIN);?>
					</div>
				</div>
			<?php endif; ?>			
		</div>
		<?php endif; ?>
		
	<?php else:?>
		<?php if(pagelines('default_sidebar') == 'flowbar'):?>
		<div id="flowbar">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('flow_sidebar') ) : ?>
				<?php if(!pagelines('sidebar_no_default')):?>
					<?php include(LIB.'/_defaultflowbar.php');?>
				<?php endif;?>
		  	<?php endif; ?>

		</div><!-- end flowbar-->
		<?php else:?>
			<div id="accordion">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('accordion_sidebar') ) : ?>
					<?php if(!pagelines('sidebar_no_default')):?>
						<?php include(LIB.'/_defaultaccordion.php');?>
					<?php endif;?>
			  	<?php endif; ?>

			</div><!-- end flowbar-->
			<div id="drag_drop_sidebar">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('drag_drop_sidebar') ) : ?>
				<?php endif; ?>			
			</div>
		<?php endif;?>
		<?php include(LIB.'/_emailsubscribe.php');?>
	<?php endif;?>
</div><!--/sidebar -->