<?php
if(VPRO) {
/*
	Template Name: Carousel Page - Standard
*/
	
	global $carousel_page;
	$carousel_page = true;
	
	get_header(); 

	require(PRO.'/template_carousel.php');
	require(LIB.'/template_page.php');

	get_footer(); 

}
?>
