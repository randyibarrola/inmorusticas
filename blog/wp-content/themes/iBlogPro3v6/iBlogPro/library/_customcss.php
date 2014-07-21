<style type="text/css">

<?php if(pagelines('body_background')):?>
	body{
		background:<?php echo pagelines('body_background');?>;
	}
	#blogtitle .sheen {display:none;}
<?php endif;?>

<?php if(pagelines('primary_header_font')):?>
	h1, h2, #feature .fheading{ font-family: <?php echo pagelines('primary_header_font');?>;}
<?php endif;?>

<?php if(pagelines('secondary_header_font')):?>
	h3, h4, h5 { font-family: <?php echo pagelines('secondary_header_font');?>;}
<?php endif;?>

<?php if(pagelines('entry_image_style')):?>
 	.hentry img{ <?php echo pagelines('entry_image_style');?>;}
<?php endif;?>


<?php if (pagelines('headercolor')):?>
	h1, h2, h3, h4, h5, h6, h1 a, h2 a, h3 a, h4 a, h5 a{color: <?php echo pagelines('headercolor'); ?>;}
<?php endif;?>

<?php if (pagelines('linkcolor')):?>
	a, #blogtitle a:hover, .postdata a:hover,h2.posttitle a:hover, .tags a:hover, .commentlist cite, .commentlist cite a, #morefoot a:hover, #sidebar ul li ul li a, #wp-calendar caption, #subnav .current_page_item a, #subnav .current_page_ancestor a, #subnav li a:hover{color:<?php echo pagelines('linkcolor'); ?>;}
<?php endif;?>

<?php if (pagelines('linkcolor_hover')):?>
	a:hover, .commentlist cite a:hover, #sidebar ul li ul li a:hover,  #subnav .current_page_item a:hover, #subnav .current_page_ancestor a:hover{color:<?php echo pagelines('linkcolor_hover'); ?>;}
<?php endif;?>

<?php if(pagelines('hidesearch')):?>
	#nav ul{width: 950px;}
<?php endif;?>

<?php if (pagelines('customcss')):?>
	<?php echo pagelines('customcss');?>
<?php endif;?>

</style>