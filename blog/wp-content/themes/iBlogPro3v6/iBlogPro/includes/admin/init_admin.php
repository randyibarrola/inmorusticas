<?php

	//Runs at the beginning of every admin page before the page is rendered
	add_action( 'admin_init', 'load_scripts' );
	function load_scripts(){
		if($_GET['page']=='functions' || $_GET['page']=='feature' || $_GET['page']=='readme'){
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-tabs' );	
		}
	}

	//Runs in the HTML <head> section of the admin panel. 
	add_action( 'admin_head', 'load_head' );
	function load_head(){
		echo '<link rel="stylesheet" href="'.CSS_FOLDER.'/admin.css" type="text/css" media="screen" />';
		echo '<link rel="shortcut icon" href="'.pagelines('favicon').'" type="image/x-icon" />';
	}
	
?>