
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="postwrap fix">
	    <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<div class="copy fix">
	      	  	<?php if(pagelines('pagetitles')):?>
					<h1 class="pagetitle"><?php the_title(); ?></h1>
				<?php endif;?>
				<div class="entry">
					<?php the_content(__('<p>Continue reading &raquo;</p>',TDOMAIN)); ?>
					<?php link_pages(__('<p><strong>Pages:</strong> ',TDOMAIN), '</p>', 'number'); ?>
					<?php edit_post_link(__('Edit',TDOMAIN), '<p>', '</p>'); ?>
				</div><!--/entry -->

			</div>

		</div><!--/post -->
	</div>
	<?php endwhile; endif; ?>
	
	<?php if(get_post_meta($post->ID, 'content_sidebar', true)):?>
				<div id="content_sidebar">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('content_sidebar') ) : ?>
					<?php endif; ?>
				</div>
	
	<?php endif;?>


		<div class="clear"></div>

		<?php if(pagelines('pagecomments') || m_pagelines('allow_comments', $post->ID)):?>

			<?php include(LIB.'/_commentsform.php');?>
	
		<?php endif;?>

