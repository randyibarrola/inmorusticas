<?php 

if ( function_exists('register_sidebar') ){
    register_sidebar(array(
	'name'=>'accordion_sidebar',
        'before_widget' => '<h3 id="%1$s" class="%2$s accordion_sidebar drawer-handle">',
        'after_widget' => '&nbsp;</div>',
        'before_title' => '<a href="#">',
        'after_title' => '&nbsp;</a></h3><div class="drawer-content">'
    ));
    register_sidebar(array(
	'name'=>'drag_drop_sidebar',
        'before_widget' => '<div id="%1$s"  class="%2$s drag_drop_sidebar widget">',
        'after_widget' => '&nbsp;</div></div>',
        'before_title' => '<h3 class="drawer-handle">',
        'after_title' => '&nbsp;</h3><div class="drawer-content">'
    ));

	register_sidebar(array(
	'name'=>'flow_sidebar',
        'before_widget' => '<div id="%1$s" class="%2$s widget"><div class="winner">',
        'after_widget' => '&nbsp;</div></div></div>',
        'before_title' => '<h3 class="wtitle">',
        'after_title' => '&nbsp;</h3><div class="wcontent">'
    ));

	register_sidebar(array(
	'name'=>'content_sidebar',
	    'before_widget' => '<div id="%1$s" class="%2$s widget"><div class="winner">',
	    'after_widget' => '&nbsp;</div></div></div>',
	    'before_title' => '<h3 class="wtitle">',
	    'after_title' => '&nbsp;</h3><div class="wcontent">'
	));

    register_sidebar(array(
	'name'=>'footer_left',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
	'name'=>'footer_middle',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
	'name'=>'footer_right',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
}
function widget_iblog_search() {}
if ( function_exists('register_sidebar_widget') ) register_sidebar_widget(__('Search', TDOMAIN), 'widget_iblog_search');

?>