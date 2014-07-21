<?php  global $pagelines;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<?php 
	global $bbpress_forum;

	if($bbpress_forum ):?>
		<title><?php bb_title() ?></title>
		<?php bb_feed_head(); ?>
		<?php bb_head(); ?>
		<link rel="stylesheet" href="<?php bb_stylesheet_uri(); ?>" type="text/css" />
<?php else:?>
	<title><?php if(is_front_page()) { echo SITENAME; } else { wp_title(''); } ?></title>
<?php endif;?>

<!-- Wordpress Stuff -->
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_single() ) wp_enqueue_script( 'comment-reply' ); ?> <!-- This makes the comment box appear where the ‘reply to this comment’ link is -->

<?php wp_enqueue_script("jquery"); ?>

<?php wp_head(); ?>
<!-- End of Wordpress stuff -->

<!-- Stylesheets -->
	<link rel="stylesheet" href="<?php echo CSS_FOLDER.'/reset.css';?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo CSS_FOLDER.'/wp_core.css';?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo ROOT.'/style.css';?>" type="text/css" media="screen" />
	<?php if(pagelines('sideicons')):?>
		<link rel="stylesheet" href="<?php echo CSS_FOLDER.'/sidebar_icons.css';?>" type="text/css" media="screen" />
	<?php endif;?>

<?php if(pagelines('favicon')):?><link rel="shortcut icon" href="<?php echo pagelines('favicon');?>" type="image/x-icon" /><?php endif;?>
<?php if(pagelines('touchicon')):?><link rel="apple-touch-icon" href="<?php echo pagelines('touchicon');?>" /><?php endif;?>


<!-- Javascript -->
<script type="text/javascript" src="<?php echo JS_FOLDER;?>/jquery-ui-1.7.2.custom.js"></script>
	
<?php if(is_page_template('page-feature.php') || is_page_template('page-feature-stdpage.php') || is_page_template('page-feature-full.php')):?><script type="text/javascript" src="<?php echo JS_FOLDER;?>/jquery.cycle.all.js"></script><?php endif;?>

<!-- Modules w/ Javascript -->	
<?php if((is_page_template('page-carousel.php') || is_page_template('page-carousel-full.php')) && VPRO) require (PRO.'/init_carousel.php');?>

<?php if((is_page_template('page-feature.php') || is_page_template('page-feature-stdpage.php') || is_page_template('page-feature-full.php')) && VPRO) require (PRO.'/init_feature.php');?>

<?php if(pagelines('enable_drop_down') && VPRO) require (PRO.'/init_dropdown.php');?>

<script type="text/javascript">

	var $j = jQuery.noConflict();
	
	$j(document).ready(function () {
		<?php if(pagelines('accordionjs')):?>
		$j("#accordion").accordion({ 
			<?php if(pagelines('accordion_active')):?>active: <?php echo pagelines('accordion_active'); ?>,<?php endif;?>
			<?php if(pagelines('accordion_autoheight')):?>autoHeight: true<?php else:?>autoHeight: false<?php endif;?>
		});
		<?php endif;?>
		
		$j("#drag_drop_sidebar").sortable();
		$j("#drag_drop_sidebar").disableSelection();
		
	});	
</script>
	<!--[if lt IE 8]>
	<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
	<![endif]-->
	<!--[if IE 6]>
		<script src="<?php echo JS_FOLDER;?>/belatedpng.js"></script>
		<script>
		  DD_belatedPNG.fix('.pngbg,#nav, .searchform, .fbox img, #nav,#respond h3,a.pagelines,.post .date, .headerimage, #sidebar div ul li a');
		 </script>	
		<style>
			#header #blogtitle .sheen {display: none;height: 1px;}
		</style>
	<![endif]-->
<?php include (LIB.'/_customcss.php'); ?>

<?php if (pagelines('headerscripts')) echo pagelines('headerscripts');?>
</head>
<body <?php body_class(); ?>>

<div class="preload" style="display:none;text-indent:-300em;">
	
	<img style="display:none;" src="<?php echo IMAGE_FOLDER; ?>/nav-hover.png" alt="preload" />
	<img style="display:none;" src="<?php echo IMAGE_FOLDER; ?>/nav-action.png" alt="preload" />
	<img style="display:none;" src="<?php echo IMAGE_FOLDER; ?>/graddark.gif" alt="preload" />
	<img style="display:none;" src="<?php echo IMAGE_FOLDER; ?>/gradlight.gif" alt="preload" />
</div>
	
<div id="page" class="fix" style="">
  <div id="wrapper" class="fix" >
	<div class="pagelinespos nav-icon"><a class="pagelines" href="<?php e_pagelines('partner_link', pagelines('credlink'));?>"><?php echo pagelines('credtext');?></a></div>
    <div id="header" class="fix">
		<?php if(pagelines('custom_header')):?>
			<a href="<?php echo get_settings('home'); ?>">
			<img class="headerimage" src="<?php echo pagelines('custom_header');?>" alt="<?php echo SITENAME; ?>"/>
			</a>
		<?php else:?>
	      		<h1 id="blogtitle"><a href="<?php echo get_settings('home'); ?>"><span class="sheen"></span><?php bloginfo('name'); ?></a></h1>
	      		<div id="blogdescription"><?php bloginfo('description'); ?></div>
		<?php endif; ?>
		<?php include (LIB.'/_iconlinks.php'); ?>
	</div><!-- /header -->
	<?php include (LIB.'/_nav.php'); ?>


<div id="container" class="fix">
	
	<?php require(LIB.'/_subnav.php');?>