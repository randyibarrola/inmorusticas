<div id="carouselwrap">
	<div class="carouselcontainer">
	<div class="prev"></div>
	<div class="next"></div>
	<div class="thecarousel">
		<ul id="mycarousel" class="mycarousel">

			<?php 
			if(pagelines('carouselitems')) $carouselitems = pagelines('carouselitems');
			else $carouselitems = 30;
			if(function_exists('get_flickrRSS')){
				get_flickrRSS(array(
					'num_items' => $carouselitems, 
					'html' => '<li><a href="%flickr_page%" title="%title%"><img src="%image_square%" alt="%title%"/></a></li>'	
				));
			}else{echo "FlickRSS plugin not installed";}
			?>
		</ul>
	</div>
	</div>
</div>