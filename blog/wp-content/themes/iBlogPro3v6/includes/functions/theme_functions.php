<?php

function checkauthority(){
	if (!current_user_can('edit_themes'))
	wp_die('Sorry, but you don&#8217;t have the administrative privileges needed to do this.');
}
function show_posts_nav() {
	global $wp_query;
	return ($wp_query->max_num_pages > 1);
}

function is_ie(){
    if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;
}
function get_option_array(){
	
	return array(
		'custom_images' => array(
				'custom_header' => array(
						'default' => '',
						'type' => 'image_url',
						'imagepreview' => '200',
						'title' => 'Custom Header Image',						
						'shortexp' => 'Input Full URL to your custom header or logo image.',
						'exp' => 'Optional way to replace "heading" and "description" text for your website with an image.'
					),
				'favicon' => array(
						'default' => ROOT."/images/favicon-pagelines.ico",
						'type' => 'image_url',
						'imagepreview' => '16',
						'title' => 'Favicon Image',						
						'shortexp' => 'Input Full URL to favicon image ("favicon.ico" image file)',
						'exp' => 'Enter the full URL location of your custom "favicon" which is visible in browser favorites and tabs (typically called favicon.ico ).'
					),
				'touchicon' => array(
						'default' => '',
						'type' => 'image_url',
						'imagepreview' => '57',
						'title' => 'Apple Touch Image',						
						'shortexp' => 'Input Full URL to Apple touch image (.jpg, .gif, .png)',
						'exp' => 'Enter the full URL location of your Apple Touch Icon which is visible when your users set your site as a <strong>webclip</strong> in Apple iPhone and Touch Products. It is an image approximately 57px by 57px in either .jpg, .gif or .png format.'
					)
			),
		'header_and_nav' => array(
				'enable_drop_down' => array(
						'default' => '',
						'type' => 'check',
						'inputlabel' => 'Enable Drop Down Navigation?',
						'title' => 'Drop Down Navigation',						
						'shortexp' => 'Enable universal drop down navigation',
						'exp' => 'Checking this option will create drop down menus for all child pages when users hover over main navigation items.'
					),
				'subnav_categories' => array(
						'default' => '',
						'type' => 'text',
						'inputlabel' => 'Category Subnav Items (Comma Seperated)',
						'title' => 'Index (Posts Page) Categories List',						
						'shortexp' => 'Enter Category IDs to show specific categories in the subnav area on the posts page.',
						'exp' => "This option will show category links on the posts page and categories pages. Enter comma seperated category IDs.<br/><br/>For example, <strong>12, 8, 21</strong> for categories with those IDs in WordPress."
					),
				'hidesearch' => array(
						'default' => '',
						'type' => 'check',
						'inputlabel' => 'Hide search on top of theme?',
						'title' => 'Hide Search',						
						'shortexp' => 'Remove the search field from the top of theme (in sub header and top of sidebar)',
						'exp' => 'Removes the search field from the sub nav and sidebar.'
					),
			),
		'pages_and_posts' => array(
				'pagetitles' => array(
						'default' => true,
						'type' => 'check',
						'inputlabel' => 'Show WP titles on pages?',
						'title' => 'WP Page Titles',						
						'shortexp' => 'Show default WordPress titles on pages.',
						'exp' => 'This option adds the default titles that you set for pages in the admin.'
					),
				'authorinfo' => array(
						'default' => '',
						'type' => 'check',
						'inputlabel' => 'Show author info in posts?',
						'title' => 'Author Info',						
						'shortexp' => 'Show author information on the bottom of posts.',
						'exp' => 'This option adds a small "about the author" box on the bottom of posts.'
					),
				'excerpts' => array(
						'default' => true,
						'type' => 'check',
						'inputlabel' => 'Show only excerpts &amp; thumbs on front page instead of full posts?',
						'title' => 'Excerpts On News/Blog Page',						
						'shortexp' => 'Display only excerpts or full entries on main news/blog page (full posts on separate page)',
						'exp' => 'This option will make it so your posts page only shows post thumbs, titles, and short summary called an excerpt.<br/><br/> <strong>Uncheck to show full posts on the posts page.</strong>'
					),
				'excerptshidesingle' => array(
						'default' => '',
						'type' => 'check',
						'inputlabel' => 'Hide excerpts and thumbs when users are viewing individual posts?',
						'title' => 'Disable Excerpts On Individual Posts',						
						'shortexp' => 'Removes excerpts and thumbs on individual (single) post pages',
						'exp' => 'If excerpts are enabled and in use, this option will hide post thumbs and excerpts when users are viewing individual post pages (i.e. reading the full article).'
					),
				'excerptshide' => array(
						'default' => '',
						'type' => 'check',
						'inputlabel' => 'Disable excerpts completely in the theme?',
						'title' => 'Disable Excerpts',						
						'shortexp' => 'Disable excerpt functionality in the theme',
						'exp' => 'This will remove excerpts completely from the posts page and individual post pages.'
					),
				'carouselitems' => array(
						'default' => 30,
						'type' => 'text_small',
						'inputlabel' => 'Number of carousel items',
						'title' => 'Carousel Page Template Items',						
						'shortexp' => 'The number of pictures to show when using your carousel template',
						'exp' => "The default is 30 items from the Flickr Account associated with the FlickrRSS plugin (use FlickrRSS options to set the source). Note: if the user account isn't set, multiple instances of FlickrRSS will cause an error."
					),
				'post_footer_link' => array(
						'default' => 'leave_response',
						'type' => 'select',
						'selectvalues' => array(
							'post_link'=>'Point Link To Post "Single" Page',
							'leave_response' => 'Point Link To Post Responses',
							'hide' => 'Hide Link On Posts Pages',
							'always_social' => 'Show Sharing Or "Meme" Links Instead'
						),
						'inputlabel' => 'Select Post Footer Link Location',
						'title' => 'Post Footer Link On Posts Page',						
						'shortexp' => 'Where should the post footer link point to?',
						'exp' => "This option allows you to choose where the post footer link points to."
					),
				'post_footer_text' => array(
						'default' => 'Leave A Response',
						'type' => 'text',
						'inputlabel' => 'Post Footer Link Text',
						'title' => 'Post Footer Link Text',						
						'shortexp' => 'The text for the post footer link',
						'exp' => "This sets the text for the link on the bottom of posts on pages where multiple posts are shown."
					),
				'post_footer_social_text' => array(
						'default' => 'If you enjoyed this article, please consider sharing it!',
						'type' => 'text',
						'inputlabel' => 'Post Footer Social Links Text',
						'title' => 'Post Footer Social Links Text',						
						'shortexp' => 'The text next to your social icons',
						'exp' => "Set the text next to your social links shown on single post pages or on all posts pages if the post footer link is set to 'always sharing links'."
					),
				'post_footer_share_links' => array(
						'default' => '',
						'type' => 'check_multi',
						'selectvalues'=> array(
							'share_twitter'=> array('inputlabel'=>'Twitter Sharing Icon', 'default'=> true),
							'share_delicious' => array('inputlabel'=>'Del.icio.us Sharing Icon', 'default'=> true),
							'share_mixx' => array('inputlabel'=>'Mixx Sharing Icon', 'default'=> false),
							'share_digg' => array('inputlabel'=>'Digg Sharing Icon', 'default'=> true),
							'share_stumbleupon' => array('inputlabel'=>'StumbleUpon Sharing Icon', 'default'=> false)
						),
						'inputlabel' => 'Select Which Share Links To Show',
						'title' => 'Post Footer Sharing Icons',						
						'shortexp' => 'Select Which To Show',
						'exp' => "Select which icons you would like to show in your post footer when sharing links are shown."
					)
			),
					
		'display_options' => array(
				'headercolor' => array(
						'default' => '',
						'type' => 'text_small',
						'inputlabel' => 'Hex Code',
						'title' => 'Text Header Color',
						'shortexp' => 'Change the color of your text headers (H1,H2, etc...)',
						'exp' => 'Use "hex" colors. For example #000000 for black, #3399CC for light blue, etc... Visit <a href="http://html-color-codes.com/">this site</a> for a reference.'
					),
				'linkcolor' => array(
						'default' => '',
						'type' => 'text_small',
						'inputlabel' => 'Hex Code:',
						'title' => 'Text Link Color',						
						'shortexp' => 'Change the default color of your links as well as other similar elements.',
						'exp' => 'Same as above'
					),
				'linkcolor_hover' => array(
						'default' => '',
						'type' => 'text_small',
						'inputlabel' => 'Hex Code:',
						'title' => 'Text Link Hover Color',						
						'shortexp' => 'Change the default color of your links when users hover over them.',
						'exp' => 'Same as above'
					),
				'body_background'=> array(
						'default' => '',
						'type' => 'text',
						'inputlabel' => 'Site Background (CSS Background Shorthand)',
						'title' => 'Site Background (CSS)',						
						'shortexp' => 'Set the background for your site',
						'exp' => 'Use <a href="http://www.w3schools.com/css/css_background.asp">CSS background shorthand</a> to style the background of your site. For example: <strong>#fff url(image.gif) repeat-x 0 0</strong> or simply <strong>#000</strong> for black.'
					),
				
			),
		'sidebar_options' => array(
				'sideicons' => array(
						'default' => false,
						'type' => 'check',
						'inputlabel' => 'Display "mini" list icons in sidebar widgets?',
						'title' => 'Sidebar List Icons',
						'shortexp' => 'Adds mini icons next to list elements in sidebars',
						'exp' => "Displays mini icons in your sidebar for standard widgets. To use your own replace the icons located in the 'images'>'icons' folder"
					),
				'greeting' => array(
						'default' => "Hi! Welcome to ".SITENAME."!",
						'type' => 'text',
						'inputlabel' => 'Greeting Text',
						'title' => 'Welcome Heading',
						'shortexp' => 'Your main greeting heading in the sidebar',
						'exp' => 'The greeting title text on your site. Format with HTML (e.g. paragraph tags, line breaks, img tags etc..)'
					),
				'welcomemessage' => array(
						'default' => "Thanks for dropping by! Feel free to join the discussion by leaving comments, and stay updated by subscribing to the <a href='".RSSURL."'>RSS feed</a>.",
						'layout' => 'full',
						'type' => 'textarea',
						'inputlabel' => 'Your Welcome Message',
						'title' => 'Welcome Message',
						'shortexp' => 'Insert your welcome message',
						'exp' => 'The welcome text (underneath your greeting) on your site. Format with HTML (e.g. paragraph tags, line breaks, img tags etc..)'
					),
				'welcomeall' => array(
						'default' => '',
						'type' => 'check',
						'inputlabel' => 'Place on all pages?',
						'title' => 'Welcome Message Placement',
						'shortexp' => 'Place the welcome message on your posts page or all pages',
						'exp' => 'By default the welcome message only shows up on your main news/blog page, check this option if you want it on all pages.'
					),
				'default_sidebar' => array(
						'default' => 'flowbar',
						'type' => 'radio',
						'selectvalues' => array(
							'accordion'=>'Accordion, Drag &amp; Drop Sidebars',
							'flowbar' => 'Flowbar'
						),
						'title' => 'Individual Page &amp; Post Default Sidebar',
						'shortexp' => 'The default sidebar on your individual posts and pages',
						'exp' => ''
					),
				'sidebar_no_default' => array(
						'default' => '',
						'type' => 'check',
						'inputlabel' => 'Hide Sidebars When Empty (no widgets)',
						'title' => 'Remove Default Sidebars When Empty',
						'shortexp' => 'Hide default sidebars when sidebars have no widgets in them',
						'exp' => 'This allows you to remove sidebars completely when they have no widgets in them.'
					),
				'accordionjs' => array(
						'default' => true,
						'type' => 'check',
						'inputlabel' => 'Activate accordion effect',
						'title' => 'Activate accordion',
						'shortexp' => 'Activate or deactivate the accordion functionality',
						'exp' => ''
					),
				'accordion_autoheight' => array(
						'default' => false,
						'type' => 'check',
						'inputlabel' => 'Enable accordion autoheight',
						'title' => 'Accordion Autoheight',
						'shortexp' => 'Set the functionality of the accordion height',
						'exp' => 'The autoheight option allows the accordion to either stay at a constant height (defined by the tallest element) or adjust to the height of the selected widget'
					),
				'accordion_active' => array(
						'default' => 0,
						'type' => 'text_small',
						'inputlabel' => 'Default "active" accordion widget',
						'title' => 'Default Active Accordion Widget',
						'shortexp' => 'Which widget is open on page load',
						'exp' => "By default this is set to '0' which means the first widget is selected. Set to 1 for the second and so on..."
					),
				'showads' => array(
						'default' => false,
						'type' => 'check',
						'inputlabel' => 'Show ads?',
						'title' => 'Enable Ads (non-widget)',
						'shortexp' => 'Places ads from the WP125 plugin in the sidebar on all pages.',
						'exp' => "Enabling this option will place a special ad widget in the sidebar that will show up on all pages. You can also enable ads by adding the WP125 widget to your sidebars."
					)
			),
			
		'media_options' => array(
				'twittername' => array(
						'default' => '',
						'type' => 'text',
						'inputlabel' => 'Your Twitter Username',
						'title' => 'Twitter Feed',
						'shortexp' => 'Places your Twitter feed in your site',
						'exp' => 'This places your Twitter feed on the site. Leave blank if you want to hide or not use.'
					),
				'commentslink' => array(
						'default' => true,
						'optionicon' =>  IMAGE_FOLDER.'/iphone/comments.png',
						'type' => 'check',
						'inputlabel' => 'Display the Comments RSS icon and link?',
						'title' => 'Comments RSS Icon',
						'shortexp' => 'Places comments RSS icon in your header',
						'exp' => ''
					),
				'rsslink' => array(
						'default' => true,
						'optionicon' =>  IMAGE_FOLDER.'/iphone/rss.png',
						'type' => 'check',
						'inputlabel' => 'Display the Blog RSS icon and link?',
						'title' => 'News/Blog RSS Icon',
						'shortexp' => 'Places News/Blog RSS icon in your header',
						'exp' => ''
					),
				'facebooklink' => array(
						'default' => '',
						'optionicon' =>  IMAGE_FOLDER.'/iphone/facebook.png',
						'type' => 'text',
						'inputlabel' => 'Your Facebook Profile URL',
						'title' => 'Facebook Profile Link',
						'shortexp' => 'Places Facebook icon in your header',
						'exp' => ''
					),
				'twitterlink' => array(
						'default' => '',
						'optionicon' =>  IMAGE_FOLDER.'/iphone/twitter.png',
						'type' => 'text',
						'inputlabel' => 'Your Twitter Profile URL',
						'title' => 'Twitter Profile Link',
						'shortexp' => 'Places Twitter icon in your header',
						'exp' => 'This is the full URL not the username as above.'
					),
				'linkedinlink' => array(
						'default' => '',
						'optionicon' =>  IMAGE_FOLDER.'/iphone/linkedin.png',
						'type' => 'text',
						'inputlabel' => 'Your LinkedIn Profile URL',
						'title' => 'LinkedIn Profile Link',
						'shortexp' => 'Places LinkedIn icon in your header',
						'exp' => ''
					),
				'emaillink' => array(
						'default' => '',
						'optionicon' =>  IMAGE_FOLDER.'/iphone/mail.png',
						'type' => 'text',
						'inputlabel' => 'Target URL',
						'title' => 'Mail Icon Link',
						'shortexp' => 'Places mail icon with link in your header',
						'exp' => ''
					),
				'callink' => array(
						'default' => '',
						'optionicon' =>  IMAGE_FOLDER.'/iphone/cal.png',
						'type' => 'text',
						'inputlabel' => 'Target URL',
						'title' => 'Calendar Icon Link',
						'shortexp' => 'Places calendar icon with link in your header',
						'exp' => ''
					),
				'toolslink' => array(
						'default' => '',
						'optionicon' =>  IMAGE_FOLDER.'/iphone/tools.png',
						'type' => 'text',
						'inputlabel' => 'Target URL',
						'title' => 'Settings Icon Link',
						'shortexp' => 'Places settings/tools icon with link in your header',
						'exp' => ''
					),
				'photolink' => array(
						'default' => '',
						'optionicon' =>  IMAGE_FOLDER.'/iphone/photo.png',
						'type' => 'text',
						'inputlabel' => 'Target URL',
						'title' => 'Photo Icon Link',
						'shortexp' => 'Places photo icon with link in your header',
						'exp' => ''
					),
				'maplink' => array(
						'default' => '',
						'optionicon' =>  IMAGE_FOLDER.'/iphone/map.png',
						'type' => 'text',
						'inputlabel' => 'Target URL',
						'title' => 'Map Icon Link',
						'shortexp' => 'Places map icon with link in your header',
						'exp' => ''
					),
				'noteslink' => array(
						'default' => '',
						'optionicon' =>  IMAGE_FOLDER.'/iphone/notes.png',
						'type' => 'text',
						'inputlabel' => 'Target URL',
						'title' => 'Notes Icon Link',
						'shortexp' => 'Places notes icon with link in your header',
						'exp' => ''
					),
			),
		'footer_options' => array(
				'bottom_sidebars' => array(
						'default' => true,
						'type' => 'check',
						'inputlabel' => 'Show footer sidebars?',
						'title' => 'Footer Sidebars',
						'shortexp' => 'Show the three widgetized sidebars in your footer',
						'exp' => 'This enables three widgetized sidebars in your footer that can be customized in the appearance > widgets panel in the admin'
					),
				'footnav' => array(
						'default' => true,
						'type' => 'check',
						'inputlabel' => 'Show main navigation in footer?',
						'title' => 'Footer Navigation',
						'shortexp' => 'Places duplicate navigation in your footer',
						'exp' => ''
					),
				'terms' => array(
						'default' => '&copy; '.date('Y').' '.SITENAME,
						'type' => 'text',
						'inputlabel' => 'Terms line in footer:',
						'title' => 'Site Terms Statement',
						'shortexp' => 'A line in your footer for "terms and conditions text" or similar',
						'exp' => "It's sometimes a good idea to give your users a terms and conditions statement so they know how they should use your service or content."
					),
			),
		'custom_code' => array(
				'partner_link' => array(
						'default' => '',
						'type' => 'text',
						'inputlabel' => 'Enter Partner Link',
						'title' => 'PageLines Partner Link',
						'shortexp' => 'Change your PageLines footer link to a partner link',
						'exp' => 'If you are a <a href="http://www.pagelines.com/partners">PageLines Partner</a> enter your link here and the footer link will become a partner or affiliate link.'
					),
				'customcss' => array(
						'default' => 'body{}',
						'type' => 'textarea',
						'layout' => 'full',
						'inputlabel' => 'CSS Rules',
						'title' => 'Custom CSS',
						'shortexp' => 'Insert custom CSS styling here (this will override any default styling)',
						'exp' => '<div class="theexample">Example:<br/> <strong>body{<br/> &nbsp;&nbsp;color:  #3399CC;<br/>&nbsp;&nbsp;line-height: 20px;<br/>&nbsp;&nbsp;font-size: 11px<br/>}</strong></div>Enter CSS Rules to change the style of your site.<br/><br/> A lot can be accomplished by simply changing the default styles of the "body" tag such as "line-height", "font-size", or "color" (as in text color).'
					),
				'headerscripts' => array(
						'default' => '',
						'type' => 'textarea',
						'layout' => 'full',
						'inputlabel' => 'Headerscripts Code',
						'title' => 'Header Scripts',
						'shortexp' => 'Scripts inserted directly before the end of the HTML &lt;head&gt; tag',
						'exp' => ''
					),
				'footerscripts' => array(
						'default' => '',						
						'type' => 'textarea',
						'layout' => 'full',
						'inputlabel' => 'Footerscripts Code or Analytics',
						'title' => 'Footer Scripts &amp; Analytics',
						'shortexp' => 'Any footer scripts including Google Analytics',
						'exp' => ""
					),
				'debug_mode' => array(
						'default' => false,
						'type' => 'check',
						'inputlabel' => 'Activate Debugging Mode',
						'title' => 'Debugging Mode',
						'shortexp' => 'Enable debugging functions for help debugging and setting up your site.',
						'exp' => 'This functionality will enable multiple debugging options. Visit PageLines for specifics.'
					)
			),
			
	);
}

function get_edit_page_post_array(){
	
	return array(
			'flow_sidebar' => array(
					
					'type' => 'check',
					'inputlabel' => 'Show Flow Sidebar',
					'exp' => 'Shows Flow Sidebar Sidebar on this page.'
				),
			'drag_drop_sidebar' => array(
					'type' => 'check',
					'inputlabel' => 'Show Drag &amp; Drop Sidebar',
					'exp' => 'Shows Content Sidebar on this page.'
				),
			'accordion_sidebar' => array(
					'type' => 'check',					
					'inputlabel' => 'Show Accordion Sidebar',
					'exp' => 'Shows Accordion Sidebar on this page.'
				),
			'content_sidebar' => array(
					'type' => 'check',
					
					'inputlabel' => 'Show Content Sidebar',
					'exp' => 'Shows Content Sidebar on this page'
				),
			'hide_ads' => array(
					'type' => 'check',
					
					'inputlabel' => 'Hide Ads',
					'exp' => 'Hide ads (if activated) on this page'
				),
			'allow_comments' => array(
					'where' => 'page',
					'type' => 'check',					
					'inputlabel' => 'Allow Comments On This Page',
					'exp' => 'Allow users to leave comments on this page.'
				),
			'featuretitle' => array(
					'where' => 'page',
					'type' => 'textarea',					
					'inputlabel' => 'Highlight Title (Highlight Page Template)',
					'exp' => 'The title in the highlight section of the highlight page (preformatted inside an H1 tag).'
				),
			'featuretext' => array(
					'where' => 'page',
					'type' => 'textarea',					
					'inputlabel' => 'Highlight Text (Highlight Page Template)',
					'exp' => 'The description text for your highlight page (use HTML to format).'
				),
			'featuremedia' => array(
					'where' => 'page',
					'type' => 'textarea',					
					'inputlabel' => 'Highlight Media (Highlight Page Template)',
					'exp' => 'Highlight Page Media HTML or Embed Code.<br/> Media width: '.HMEDIAWIDTH
				)
		);

}

function get_feature_array(){
	
	
	return array(
		'feature_settings' => array(
					'timeout' => array(
							'default' => 0,
							'type' => 'text_small',
							'inputlabel' => 'Timeout (ms)',
							'title' => 'Feature Viewing Time (Timeout)',
							'shortexp' => 'The amount of time a feature is set before it transitions in milliseconds',
							'exp' => 'Set this to 0 to only transition on manual navigation. Use milliseconds, for example 10000 equals 10 seconds of timeout.'
						),
					'fspeed' => array(
							'default' => 1500,
							'type' => 'text_small',
							'inputlabel' => 'Transition Speed (ms)',
							'title' => 'Feature Transition Time (Timeout)',
							'shortexp' => 'The time it takes for your features to transition in milliseconds',
							'exp' => 'Use milliseconds, for example 1500 equals 1.5 seconds of transition time.'
						),
					'feffect' => array(
							'default' => 'fade',
							'type' => 'select_same',
							'selectvalues' => array('blindX','blindY','blindZ', 'cover','curtainX','curtainY','fade','fadeZoom','growX','growY','none','scrollUp','scrollDown','scrollLeft','scrollRight','scrollHorz','scrollVert','shuffle','slideX','slideY','toss','turnUp','turnDown','turnLeft','turnRight','uncover','wipe','zoom'),
							'inputlabel' => 'Select Transition Effect',
							'title' => 'Transition Effect',
							'shortexp' => "How the features transition",
							'exp' => "This controls the mode with which the features transition to one another."
						),
					'fremovesync' => array(
							'default' => false,
							'type' => 'check',
							'inputlabel' => 'Remove Transition Syncing',
							'title' => 'Remove Feature Transition Syncing',
							'shortexp' => "Make features wait to move on until after the previous one has cleared the screen",
							'exp' => "This controls whether features can move on to the screen while another is transitioning off. If removed features will have to leave the screen before the next can transition on to it."
						)
						
		)
	); 
		
}

function get_feature_setup(){
	return array(	
			'title' => array(
						'title' => 'Feature Title',
						'shortexp'=> 'Styling the "title" section of the feature',
						'exp' => 'This is where to type the title text or other HTML that you would like to accompany the media in the feature.<br/> <strong>We recommend H1 tags with a class of "ftitle" and H3 tags with a class of "fsub"</strong>',
						'inputlabel' => 'Feature Title (text + html)',
						'type' => 'textarea'
				),
			'text' => array(
						'title' => 'Feature Text',
						'shortexp'=> 'Use text with html to style your description',
						'exp' => 'This is where to type the describing text or other HTML that you would like to accompany the media in the feature. HTML, links & images are all possible.<br/> <strong>Make sure to use paragraph tags for formatting ("&lt;p&gt;" tags).</strong>',
						'inputlabel' => 'Feature Text (text + html)',
						'type' => 'textarea_big'
				),
			'media' => array(
						'title' => 'Feature Media',
						'shortexp'=> 'Add pictures, videos, text or anything you can embed to the feature (HTML)',
						'exp' => "Add media like pictures or youtube videos here. HTML is ok, or use 'embed' code from any website.<br/> Make it any size you like but it's optimized for a width of <strong>".FMEDIAWIDTH."</strong> and height of <strong>".FMEDIAHEIGHT."</strong>.<br/><br/> Add a 'br' tag on top to create a separation between the top of the feature and the media.",
						'inputlabel' => 'Feature Media Code (Embed Code or HTML)',
						'type' => 'textarea_big'
				),
			'link' => array(
						'title' => 'Feature Link (Optional)',
						'shortexp'=> 'Add a URL for the feature to tell users where to "learn more"',
						'exp' => 'This link will show up under the featuretext. Use full URL.',
						'inputlabel' => 'Feature Link (URL)',
						'type' => 'text'
				),
			'background' => array(
						'title' => 'Feature Background (CSS - Optional)',
						'shortexp'=> 'Use CSS shorthand to control the feature background',
						'exp' => 'Use <a href="http://www.w3schools.com/css/css_background.asp">CSS background shorthand</a> to style the background of each feature. For example: <strong>#fff url(image.gif) no-repeat top left</strong>.',
						'inputlabel' => 'Feature Background (CSS Background Shorthand)',
						'type' => 'text'
				),
			'name' => array(
						'title' => 'Feature Name (optional)',
						'shortexp'=> 'For easy referencing in the menu',
						'exp' => 'This just allows you to change the name of the feature in the menu navigation. It may be used for more features in the future.',
						'inputlabel' => 'Feature Name',
						'type' => 'text'
				)
		);
	
}



function get_default_features(){
	return array(
			'1' => array(
		        	'title' => '<h3 class="fsub">Welcome to </h3><h1 class="ftitle">'.THEMENAME.'</h1>',
		        	'text' => '<h4>Edit This In The Admin</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam quis molestie nunc. Vivamus molestie, quam vitae pharetra  at ultricies tortor quam sed ante.</p>',
		        	'media' => '<img src="'.IMAGE_FOLDER.'/feature1.jpg" alt="feature1" />',
		        	'link' => '#',
					'background' => '',
					'name'=>'feature_1',
		    ),
			'2' => array(
		        	'title' => '<h3 class="fsub">Make An</h3><h1 class="ftitle">Impression</h1>',
		        	'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam quis molestie nunc. Vivamus molestie, quam vitae pharetra  at ultricies tortor quam sed ante.</p>',
		        	'media' => '<br/>
<object width="460" height="350"><param name="movie" value="http://www.youtube.com/v/4oAB83Z1ydE&hl=en&fs=1&showinfo=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/4oAB83Z1ydE&hl=en&fs=1&showinfo=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="460" height="350"></embed></object>',
		        	'link' => '#',
					'background' => '',
					'name'=>'feature_2'
		    ),
			'3' => array(
				 	'title' => '<h3 class="fsub">Wordpress Theme By</h3><h1 class="ftitle">PageLines</h1>',
		        	'text' => '<h4>Edit This In The Admin</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam quis molestie nunc. Vivamus molestie, quam vitae pharetra  at ultricies tortor quam sed ante.</p><p><a href="http://www.pagelines.com" class="featurelink">Visit PageLines</a></p>',
		        	'media' => '<img src="'.IMAGE_FOLDER.'/feature2.jpg" />',
		        	'link' => '',
					'background' => '',
					'name'=>'feature_3'
		    ),
			'4' => array(
		        	'title' => '',
		        	'text' => '',
		        	'media' => '',
		        	'link' => '',
					'background' => '',
					'name'=>'feature_4'
		    ),
			'5' => array(
		        	'title' => '',
		        	'text' => '',
		        	'media' => '',
		        	'link' => '',
					'background' => '',
					'name'=>'feature_5'
		    ),
			'6' => array(
		        	'title' => '',
		        	'text' => '',
		        	'media' => '',
		        	'link' => '',
					'background' => '',
					'name'=>'feature_6'
		    )
	);
}

function get_fbox_setup(){
	return array(	
			'title' => array(
						'title' => 'Feature Box Title',
						'shortexp'=> 'Styling the "title" section of the feature box',
						'exp' => 'Type the feature box title text with HTML formatting.<br/><br/> We recommend H3 tags for example:<br/> <strong>&lt;h3&gt;Your Feature Box Title &lt;/h3&gt;</strong>',
						'inputlabel' => 'Feature Title (text + html)',
						'type' => 'textarea'
				),
    		'text' => array(
						'title' => "Feature Box Text + HTML",
						'shortexp' => "The text inside of your footer text boxes",
						'exp' => "Set the text for your feature boxes. Use HTML markup including image tags for pictures.<br/><br/>For example:<br/> <strong>&lt;img src='image_url.com' alt='alt text' /&gt;</strong>",
						'inputlabel' => 'Feature box text and html',
						'type' => 'textarea_big'
				)
		);
}

function get_default_fboxes(){
	return array(
		'1' => array(
	        	'title' => '<h3>You\'ll love this theme</h3>',
	        	'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In et nulla diam, ac interdum nisl. Nunc mattis tincidunt dictum. Etiam luctus consequat ipsum,</p><p><img alt="fbox1" class="aligncenter" src="'.IMAGE_FOLDER.'/fbox1.png" /></p>'
	    ),
		'2' => array(
	        	'title' => '<h3>PageLines Themes</h3>',
	        	'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In et nulla diam, ac interdum nisl. Nunc mattis tincidunt dictum. Etiam luctus consequat ipsum,</p><p><img alt="fbox2" class="aligncenter" src="'.IMAGE_FOLDER.'/fbox2.png" /></p>'
	    ),
		'3' => array(
	        	'title' => '<h3>Thanks!</h3>',
	        	'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In et nulla diam, ac interdum nisl. Nunc mattis tincidunt dictum. Etiam luctus consequat ipsum,</p><p><img alt="fbox3" class="aligncenter" src="'.IMAGE_FOLDER.'/fbox3.png" /></p>'
	    )
	);
}



?>