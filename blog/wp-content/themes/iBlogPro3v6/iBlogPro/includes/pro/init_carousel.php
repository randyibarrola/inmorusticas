<script type="text/javascript" src="<?php echo JS_FOLDER.'/carousel.jcarousellite.js';?>"></script>
<script type="text/javascript" src="<?php echo JS_FOLDER.'/carousel.mousewheel.js';?>"></script>

<link rel="stylesheet" href="<?php echo CSS_FOLDER.'/carousel.css';?>" type="text/css" media="screen" />

<script type="text/javascript">

	var $j = jQuery.noConflict();

	$j(document).ready(function () {
	    $j(".thecarousel").jCarouselLite({
	        btnNext: ".next",
	        btnPrev: ".prev", 
			visible: 9, 
			circular: true, 
			scroll: 6,
			speed: 600,
			mouseWheel: true
		});
	});
	
</script>

