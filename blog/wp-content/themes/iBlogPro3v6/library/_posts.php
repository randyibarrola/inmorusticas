<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php if(is_single()):?>
		<div class="post-nav"> <span class="previous"><?php previous_post_link('%link') ?></span> <span class="next"><?php next_post_link('%link') ?></span></div>
	<?php endif;?>
	
		
	<div class="postwrap fix">
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">	
			<div class="copy fix">
				<?php if(is_single() && pagelines('excerptshidesingle')):?>
				<?php else: ?>
					<?php if(function_exists('the_post_thumbnail') && has_post_thumbnail()): ?>
		            		<div class="thumb left">
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e('Permanent Link To', TDOMAIN);?> <?php the_title_attribute();?>">
									<?php the_post_thumbnail('thumbnail');?>
								</a>
				            </div>
					<?php elseif (get_post_meta($post->ID, 'thumb', true)): ?>
						<?php $postimageurl = get_post_meta($post->ID, 'thumb', true); ?>
		            	<div class="thumb left">
			              <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e('Permanent Link To', TDOMAIN);?> <?php the_title_attribute();?>">
							<img src="<?php echo $postimageurl; ?>" alt="Post Pic" width="200" height="200" />
						</a>
			            </div>
					<?php endif; ?>
				<?php endif;?>
				<div class="post-title">
					<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e('Permanent Link To', TDOMAIN);?> <?php the_title_attribute();?>"><?php the_title(); ?></a></h2>
				</div>
				<!--/post-title -->
				<div class="post-date">
					<em>
						<?php _e('On',TDOMAIN);?> <?php the_time(get_option('date_format')); ?>, 
						<?php _e('in',TDOMAIN);?> <?php the_category(', ') ?>, 
						<?php _e('by',TDOMAIN);?> <?php the_author(); ?>
					</em>
				</div>
					<?php if(is_single() && pagelines('excerptshidesingle')):?>
					<?php else: ?>						
						<?php if(!pagelines('excerptshide') || is_search()):?>
							<div class="post-excerpt"><?php the_excerpt(); ?></div>
						<?php endif;?>
					<?php endif;?>
			</div>
			<?php  if((is_single() || !pagelines('excerpts')) && !is_search()):?> 	
				<div class="copy fix">
					<div class="post-content"><?php the_content(); ?></div>	
					
					<?php link_pages(__('<p><strong>Pages:</strong>', TDOMAIN), '</p>', __('number', TDOMAIN)); ?>		
				</div>
				<?php if(pagelines('authorinfo')):?>
					<?php include(LIB.'/_authorinfo.php');?>
				<?php endif;?>
								
			<?php endif;?>
			<div class="hl"></div>
					
			<?php include(LIB.'/_post_footer.php');?>
		</div><!--post -->

	</div>
	
	<div class="tags">
	<?php the_tags(__('Tagged with: ', TDOMAIN),' &bull; ',''); ?>&nbsp;
	</div>
	
		<div class="clear"></div>
		<?php if(is_single()):?>
			<?php include(LIB.'/_commentsform.php');?>
			
		<?php endif; endwhile; ?>
	
	<?php include(LIB.'/_pagination.php');?>

		
	<?php else : ?>
		<div class="postwrap fix">
			<div class="hentry">
			<div class="billboard">
				<h2 class="center"><?php _e('Nothing Found', TDOMAIN);?></h2>
				<p class="center"><?php _e('Sorry, what you are looking for isn\'t here.', TDOMAIN);?></p>
				<div class="center fix"><?php get_search_form(); ?></div>
			</div>
			</div>
		</div>
<?php endif; ?>
