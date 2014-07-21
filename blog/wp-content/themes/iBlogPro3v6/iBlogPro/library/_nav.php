<div id="nav" class="fix">
	<ul class="fix dropdown">
		<li class="page_item navfirst">
			<a class="home" href="<?php echo get_settings('home'); ?>/" title="<?php _e('Home',TDOMAIN);?>">
				<img width="26" height="24" src="<?php bloginfo('stylesheet_directory'); ?>/images/home-icon-trans.png" alt=""/>
			</a>
		</li>
		<?php 
			$frontpage_id = get_option('page_on_front');
			if($bbpress_forum && pagelinesforum('exclude_pages')){ $forum_exclude = ','.pagelinesforum('exclude_pages');}
			else{ $forum_exclude = '';}
			wp_list_pages('exclude='.$frontpage_id.$forum_exclude.'&depth=3&title_li=');?>
	</ul>
	<?php if(!pagelines('hidesearch')):?>
	<?php include (LIB . '/_searchform.php'); ?>
	<?php endif;?>
</div><!-- /nav -->