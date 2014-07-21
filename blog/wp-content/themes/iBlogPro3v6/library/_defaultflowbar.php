<?php if(function_exists('get_flickrRSS')):?>
<!--sidebox start -->
  <div id="dflickr" class="widget_flickrRSS widget">
	<div class="winner">
    	<div class="wcontent fix">
          <ul>
          	<?php include(LIB.'/_flickr.php');?>
    	  </ul>
		</div>
    </div>
  </div>
  <!--sidebox end -->
<?php endif; ?>
<!--sidebox start -->
  <div id="dcategories" class="widget_categories widget">
	<div class="winner">
 	   <h3 class="wtitle"><?php _e('Categories', TDOMAIN);?></h3>
    	<div class="wcontent">
          <ul>
            <?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
          </ul>
		</div>
    </div>
  </div>
  <!--sidebox end -->

<!--sidebox start -->
  <div id="dtags" class="widget_tags widget">
	<div class="winner">
 	   <h3 class="wtitle"><?php _e('Tags', TDOMAIN);?></h3>
  		<div class="wcontent">
          <ul>
			<?php wp_tag_cloud('smallest=8&largest=17&number=30'); ?>
   		 </ul>
		</div>
    </div>
  </div>
  <!--sidebox end -->
