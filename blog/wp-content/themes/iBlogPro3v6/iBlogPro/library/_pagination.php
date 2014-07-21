<?php if(function_exists('wp_pagenavi')):?> 
	<?php wp_pagenavi(); ?>  
<?php elseif (show_posts_nav()) : ?>
	<div class="page-nav fix">
		<span class="previous-entries"><?php next_posts_link('Previous Entries') ?></span>
		<span class="next-entries"><?php previous_posts_link('Next Entries') ?></span>
	</div><!-- page nav -->
<?php endif;?>