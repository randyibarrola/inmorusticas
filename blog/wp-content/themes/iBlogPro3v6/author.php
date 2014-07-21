<?php get_header(); ?>

	  <?php
          global $wp_query;
          $curauth = $wp_query->get_queried_object();
      ?>
    
<div id="left-col">
	<div id="content">
		<div class="postwrap fix">
			<div class="hentry fix">
						<div class="post-title">
							<div class="left"><h1><?php echo $curauth->display_name; ?></h1></div>
							<div class="right"><h1 class="author">Author</h1></div>
							<br class="fix" />
						</div>
						<div class="hl"></div> 
						<div class="copy">
						<div class="pic fl"><?php echo get_avatar("$curauth->user_email", $size = '80', $default = ROOT . '/images/default_avatar_author.gif' ); ?></div>
						<div class="post-author">
						<div class="author-descr">
						<p><?php echo $curauth->user_description; ?></p>
						<div class="author-details"><a href="<?php echo $curauth->user_url; ?>" target="_blank">Visit Authors Website</a></div>
						</div>
						<!--/author-descr -->
						<br class="fix" />
						</div><!--/post-author -->
				
						</div>
						
						<div class="hl"></div>
						<div class="author-info lowlight2">

							<div class="pic left"><?php echo get_avatar("$curauth->user_email", $size = '80', $default = ROOT . '/images/default_avatar_author.gif' ); ?></div>
							<div class="post-author">
								<div class="author-descr">
									<small>About the author</small>
									<h3><?php the_author(); ?></h3>
									<p><?php echo $curauth->user_description; ?></p>
									<div class="author-details"><a href="<?php the_author_url(); ?>" target="_blank">Visit Authors Website</a></div>
								</div>
								<!--/author-descr -->
								<br class="fix" />
							</div>
							<!--/post-author -->
						</div>
			</div>
		</div>

    <?php require(LIB.'/_posts.php');?>


	</div> <!-- end content -->
</div> <!-- end left col -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
