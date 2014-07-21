<?php if(function_exists('get_flickrRSS')):?>
<!--sidebox start -->
  <h3 id="dflickr" class="widget_flickrRSS drawer-handle"><a href="#"><?php _e('Photos',TDOMAIN); ?></a></h3>
	<div class="drawer-content">
          <ul>
            <?php include(LIB.'/_flickr.php');?>
          </ul>
			<div class="clear"></div>
    </div>
<?php endif; ?>
<h3 id="dcategories" class="widget_categories drawer-handle"><a href="#"><?php _e('Categories', TDOMAIN);?></a></h3>
<div class="drawer-content">
	<ul><?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?></ul>
</div>

<h3 id="dtags" class="widget_tags drawer-handle"><a href="#"><?php _e('Tag Cloud', TDOMAIN);?></a></h3>
<div class="drawer-content">
	<ul><?php wp_tag_cloud('smallest=8&largest=17&number=30'); ?></ul>
</div>

<h3 id="darchives" class="widget_archives drawer-handle"><a href="#"><?php _e('Archives', TDOMAIN);?></a></h3>
<div class="drawer-content">
	<ul><?php wp_get_archives('type=monthly'); ?></ul>
</div>

<h3 id="dmeta" class="widget_meta drawer-handle"><a href="#"><?php _e('Meta', TDOMAIN);?></a></h3>
<div class="drawer-content">
	<ul>
		<?php wp_register(); ?>
		<li class="login"><?php wp_loginout(); ?></li>
		<?php wp_meta(); ?>
		<li class="rss"><a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Entries (RSS)', TDOMAIN);?></a></li>
		<li class="rss"><a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments (RSS)', TDOMAIN);?></a></li>
	</ul>
</div>