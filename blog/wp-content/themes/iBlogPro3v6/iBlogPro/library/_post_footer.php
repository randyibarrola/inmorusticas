
	<div class="post-footer">
		<?php if(is_single() || pagelines('post_footer_link')=='always_social'):?>
			<div class="left">
				<?php edit_post_link(__('Edit Post', TDOMAIN), '', ' | ');?>
				<?php e_pagelines('post_footer_social_text', '');?>	
			</div>
			<div class="right">
				<?php if(pagelines('share_twitter')):?><a href="http://twitter.com/home/?status=<?php the_title();?>%20<?php echo get_permalink(); ?>" title="Post At Twitter" rel="nofollow" target="_blank"><img src="<?php echo IMAGE_FOLDER; ?>/ico-soc5.gif" alt="Digg" /></a><?php endif;?> 
				<?php if(pagelines('share_delicious')):?><a href="http://del.icio.us/post?url=<?php the_permalink() ?>&title=<?php the_title(); ?>" title="Bookmark at Delicious" rel="nofollow" target="_blank"><img src="<?php echo IMAGE_FOLDER; ?>/ico-soc1.gif" alt="Delicious" /></a><?php endif;?>
				<?php if(pagelines('share_mixx')):?><a href="http://www.mixx.com/submit?page_url=<?php the_permalink() ?>" title="Bookmark at Mixx" rel="nofollow" target="_blank"><img src="<?php echo IMAGE_FOLDER; ?>/ico-soc2.gif" alt="Mixx" /></a> <?php endif;?>
				<?php if(pagelines('share_stumbleupon')):?><a href="http://www.stumbleupon.com/submit?url=<?php the_permalink() ?>&title=<?php the_title(); ?>" title="Bookmark at StumbleUpon" rel="nofollow" target="_blank"><img src="<?php echo IMAGE_FOLDER; ?>/ico-soc3.gif" alt="StumbleUpon" /></a> <?php endif;?>
				<?php if(pagelines('share_digg')):?><a href="http://digg.com/submit?phase=2&url=<?php the_permalink() ?>&title=<?php the_title(); ?>" title="Bookmark at Digg" rel="nofollow" target="_blank"><img src="<?php echo IMAGE_FOLDER; ?>/ico-soc4.gif" alt="Digg" /></a><?php endif;?> 
				
			</div>
		<?php else:?>
			<div class="left">
				<span><?php comments_number(0, 1, '%'); ?></span>
				<a href="<?php the_permalink(); ?>#comments" title="<?php _e('View Comments', TDOMAIN);?>"><?php _e('Comments',TDOMAIN)?></a>
			</div>
			<div class="right">
				<?php edit_post_link(__('Edit Post', TDOMAIN), '', ' | ');?>
				<?php if(pagelines('post_footer_link') != 'hide'):?>
					<span>
					<?php if(pagelines('post_footer_link')=='post_link'):?>
						<a href="<?php the_permalink(); ?>">
							<?php e_pagelines('post_footer_text', __('View Full Post &raquo;',TDOMAIN));?>
						</a>
					<?php else:?>
						<a href="<?php the_permalink(); ?>#respond">
							<?php e_pagelines('post_footer_text',__('Leave A Response', TDOMAIN));?>
						</a>
						
					<?php endif;?>
					</span>
				<?php endif;?>
			</div>
		<?php endif; ?>
		<br class="fix" />
		
	</div>