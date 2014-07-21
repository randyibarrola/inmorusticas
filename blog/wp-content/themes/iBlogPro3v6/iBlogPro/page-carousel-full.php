<?php
if(VPRO) {
/*
	Template Name: Carousel Page - FullWidth
*/
	global $carousel_page;
	$carousel_page = true;
	
	get_header(); 

	require(PRO.'/template_carousel.php');
	require(LIB.'/template_fullwidth.php');

	get_footer(); 

}
?>
