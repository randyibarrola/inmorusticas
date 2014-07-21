<?php

// PAGELINES FUNCTIONS -- DESIGNED BY ANDREW POWERS 

/***** THEME DEFINITIONS ******/

// Theme
define('THEMENAME','iBlogPro3');
define('THEMENAMESHORT','iBlogPro3');

// MEDIA DIMENSIONS
	define('FMEDIAWIDTH','460px');
	define('FMEDIAHEIGHT','350px');
	define('HMEDIAWIDTH','540px');

	define('FBOXMEDIAWIDTH','265px');
	define('SIDEBARMEDIAWIDTH','275px');
	define('ENTRYMEDIAWIDTH','600px');

// COMMON WP VARIABLES
	define('SITENAME',get_bloginfo('name'));
	define('RSSURL',get_bloginfo('rss2_url'));
	define('FEEDID',get_option('feedid'));



// DEFINE DIRECTORY CONSTANTS
	define('INC', TEMPLATEPATH . '/includes');
	define('ADMIN', INC . '/admin');
	define('FUNCTIONS', INC . '/functions');
	define('LIB', TEMPLATEPATH . '/library');
	
	// IF PRO VERSION
		if(file_exists(dirname(__FILE__).'/includes/pro/init_pro.php')){
			define('PRO', INC . '/pro');
			require (PRO.'/init_pro.php');
		}else{ define('VPRO','');}


// DEFINE WEB FOLDERS
	define('ROOT', get_bloginfo('template_url'));
	define('CSS_FOLDER', ROOT . '/css');
	define('JS_FOLDER', ROOT . '/js');
	define('IMAGE_FOLDER', ROOT . '/images');

// LOCALIZATION
	define('TDOMAIN', 'iBlogPro');
	define('LANGUAGE_FOLDER', TEMPLATEPATH.'/languages');
	load_theme_textdomain(TDOMAIN, LANGUAGE_FOLDER );

// THEME WP OPTIONS
	if (function_exists( 'add_theme_support' )) add_theme_support('post-thumbnails');

/***** REQUIRE FILES ON LOAD ******/

	//CORE THEME FUNCTIONS
		require (FUNCTIONS.'/theme_functions.php');
		require (FUNCTIONS.'/options.class.php');
	
		//CREATE PAGELINES GLOBAL OPTIONS OBJECT
		$GLOBALS['pagelines'] = new Options;


	//FOR DEBUGGING AND OPTIMIZATION
		define('DEBUG', false);
		if(DEBUG){
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}
		
		if(pagelines('debug_mode')) require (ADMIN.'/debug_admin.php');
		
	//SIDEBAR FUNCTIONS 
		require (FUNCTIONS.'/sidebar_setup.php');

	//INTEGRATED PLUGINS
		if(!function_exists('twitter_messages')) require (INC.'/plugins/twitter.php');


	//ADMIN INTERFACES
		require (ADMIN.'/init_admin.php');
		require (ADMIN.'/edit_options.php');
		require (ADMIN.'/edit_feature.php');
		require (ADMIN.'/edit_page.php');
		require (ADMIN.'/edit_post.php');
		require (ADMIN.'/readme.php');

?>