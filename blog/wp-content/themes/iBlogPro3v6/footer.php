
</div>
<?php 

	global $bbpress_forum;
	if(($bbpress_forum && pagelinesforum('hide_bottom_sidebars')) || !pagelines('bottom_sidebars')) $hide_footer = true;
	else $hide_footer = false;		
?>
<?php if(!$hide_footer):?>
	<div id="morefoot" class="fboxes fix">
		<div class="fboxdividers fix">
			<div class="fbox">
				<div class="fboxcopy">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_left') ) : ?>
					<?php if(!pagelines('sidebar_no_default')):?>
						<h3><?php _e('Looking for something?', TDOMAIN);?></h3>
						<p><?php _e('Use the form below to search the site:', TDOMAIN);?></p>
						<div class="left p"><?php include (LIB . '/_searchform.php'); ?></div>
						<div class="clear"></div>
						<p><?php _e('Still not finding what you\'re looking for? Drop a comment on a post or contact us so we can take care of it!', TDOMAIN);?></p>
					<?php endif;?>
				<?php endif; ?>
				</div>
			</div>

			<div class="fbox">
				<div class="fboxcopy">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_middle') ) : ?>
					<?php if(!pagelines('sidebar_no_default')):?>
						<h3><?php _e('Visit our friends!', TDOMAIN);?></h3><p><?php _e('A few highly recommended friends...', TDOMAIN);?></p><ul><?php wp_list_bookmarks('title_li=&categorize=0'); ?></ul>
					<?php endif;?>
				<?php endif; ?>
				</div>
			</div>

			<div class="fbox">
				<div class="fboxcopy">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_right') ) : ?>
					<?php if(!pagelines('sidebar_no_default')):?>
					<h3><?php _e('Archives');?></h3><p><?php _e('All entries, chronologically...');?></p><ul><?php wp_get_archives('type=monthly&limit=12'); ?> </ul>
					<?php endif;?>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div><!-- Closes morefoot -->
<?php endif; ?>
<?php if(pagelines('footnav') || pagelines('terms')):?>
	<div id="bottomnav" class="fix">
		<?php if(pagelines('footnav')):?>
		<ul class="piped left">
				<li class="page_item "><a class="first" href="<?php echo get_settings('home'); ?>/" title="<?php _e('Home',TDOMAIN);?>"><?php _e('Home',TDOMAIN);?></a></li>
			<?php 
				$frontpage_id = get_option('page_on_front');
				global $bbpress_forum;
				if($bbpress_forum && pagelinesforum('exclude_pages')){ $forum_exclude = ','.pagelinesforum('exclude_pages');}
				else{ $forum_exclude = '';}
				wp_list_pages('sort_column=menu_order&exclude='.$frontpage_id.$forum_exclude.'&depth=1&title_li=');?>
		</ul>
		<?php endif;?>
		<?php if(pagelines('terms')):?><div class="terms <?php if(!pagelines('footnav')):?>nonav<?php endif;?>"><?php echo pagelines('terms'); ?></div><?php endif;?>
	</div>
<?php endif;?>
 	<hr class="hidden" />

  </div><!--/wrapper -->

</div><!--/page -->

<!-- Footer Scripts Go Here -->
<?php if (pagelines('footerscripts')) echo pagelines('footerscripts');?>
<!-- End Footer scripts -->

<?php wp_footer(); ?>
</body>
</html>