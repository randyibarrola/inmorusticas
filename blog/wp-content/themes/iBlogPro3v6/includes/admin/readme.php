<?php 
add_action('admin_menu', 'add_readme');

function add_readme() {
  add_theme_page(THEMENAME.' Getting Started', THEMENAMESHORT.' Guide', '8', 'readme', 'readme');
}
?><?php function readme() { ?>
<div class='wrap'>
	<table id="optionstable"><tbody><tr><td valign="top" width="100%">
  	<h2><?php echo THEMENAME;?> Guide</h2>

	<script type="text/javascript">
			jQuery.noConflict();
			jQuery(document).ready(function($) {						
				var myTabs = $("#tabs").tabs({ fx: { opacity: "toggle", duration: "fast" }});
				
				$('#tabs').bind('tabsshow', function(event, ui) {
					$("#selectedtab").val($('#tabs').tabs('option', 'selected'));
				});
			});
	</script>

			<div id="optionsheader">
				
				<div class="hl"></div>
				
				<div class="options_intro"><a href="http://www.pagelines.com" title="PageLines Themes"><img alt="PageLines Themes" src="<?php echo IMAGE_FOLDER;?>/pagelines.png" class="alignright"/></a><small><strong>Hi! Welcome to the getting started guide for <?php echo THEMENAME;?>.</strong> We aim to make getting started with PageLines themes as easy as possible,  so if there are any unanswered questions please <a href="http://www.pagelines.com">contact us</a> to let us know.</small></div>
			</div>
			<div id="tabs">
			
				<ul id="tabsnav" class="readme">
					<li><a class="readme_welcome" href="#welcome"><span>Welcome</span></a></li>
					<li><a class="readme_install" href="#install"><span>Installation</span></a></li>
					<li><a class="readme_featurepage" href="#featurepage"><span>Using The Feature Page Template</span></a></li>
					<li><a class="readme_createposts" href="#createposts"><span>Adding Excerpts And Thumbs</span></a></li>
					<li><a class="readme_custompage" href="#custompage"><span>Customizing Pages</span></a></li>
					<li><a class="readme_themeoptions" href="#themeoptions"><span>Setting Theme Options</span></a></li>
					<li><a class="readme_flickr" href="#flickr"><span>Using FlickrRSS</span></a></li>
					<li><a class="readme_forms" href="#forms"><span>Contact Forms With CForm</span></a></li>
					<li><a class="readme_faq" href="#faq"><span>Troubleshooting And FAQs</span></a></li>
					
				</ul>
				<div id="thetabs" class="fix">
						
					<div id="welcome" class="tabinfo fix">
						<div class="tabtitle">Your New Pro Theme</div>
						<div class="inforow fix">
							<p><img class='alignright border' src='<?php echo ROOT;?>/screenshot.png' /><strong>Thank you for purchasing <?php echo THEMENAME;?>!</strong></p>
							<p>We hope that you will enjoy it and use it to be successful at whatever you are working on.</p> <p>We understand that it can be difficult to get a new website running so if you have a problem please don't hesitate to <a href='http://www.pagelines.com' target='_blank'>contact us</a> at PageLines.</p>
							
							 <p><strong>Next Steps:</strong><br/>
							   <ul>
							   	<li>Make sure you've uploaded and activated all the plugins that come with the theme.</li>
								<li>To set up a feature page, create a new page and select the 'Feature Page' template. Then configure it in the feature setup panel in the admin.</li>
							   	<li>Change all theme settings in the options panels within the admin > apprearance area. Here you can show things like authors on posts, only excerpts on the homepage, etc...</li>
							   	<li>Be careful not to break the theme markup (html), markup problems can cause whacky things to happen (like the sidebar falling to the bottom of the page)</li>
							   	<li>Have fun with the design.  We hope you create something that is totally unique and will stand out in the crowd.  Good luck!</li></ul>
						</div>
					</div>
					<div id="install" class="tabinfo fix">
						<div class="tabtitle">Installation</div>
					
						<div class="inforow fix">
							<p>The first thing you need to do now that you've activated the theme is install your plugins.</p>
							<p> You will need to locate and upload the plugins that were in the files you downloaded. Upload the plugins and read the installation instructions that come with each.</p><p> Usually installing a plugin is as simply as uploading it to the 'plugins' directory of your wordpress install. These files are all located in your download file in a folder named 'Plugins'</p>
							<strong>Included Plugins</strong>
							<ul>
								<li><a href="http://wordpress.org/extend/plugins/wp125/" target="_blank">WP125</a> - <small>Allows ad monitoring and places a 'position' on your theme for 125px by 125px ads.</small></li>
								<li><a href="http://eightface.com/wordpress/flickrrss/" target="_blank" >FlickrRSS</a> - <small>Shows pictures from your Flickr Account on your page.</small></li>
								<li><a href="http://www.seoadsensethemes.com/wp-post-thumbnail-wordpress-plugin/" target="_blank">WP-Post-Thumbnail</a> - <small>The easiest way we've found for adding thumbs to posts.
									<br/> (Note: If you have problems, this plugin is not required to get thumbs to work it just helps you)</small></li>
								<li><a href="http://wordpress.org/extend/plugins/wp-pagenavi/" target="_blank">WP-PageNavi</a> - <small>Allows the 'pagination' of posts, which is a superior way to navigate your content.</small></li>
								<li><a href="http://www.deliciousdays.com/cforms-plugin" target="_blank">CFormsII</a> - <small>A contact form system with a lot of options and extras.</small></li>
								<li><a href="http://wordpress.org/extend/plugins/all-in-one-seo-pack/" target="_blank">All In One SEO</a> - <small>The best way to set metatags and tune your site for SEO.</small></li>
							</ul>
							<strong>Recommended Plugins</strong>
							<ul>
								<li><a href="http://wordpress.org/extend/plugins/google-sitemap-generator/" target="_blank">Google XML Sitemaps</a> - <small>Automatically submits your site map to search engines</small></li>
								<li><a href="http://wordpress.org/extend/plugins/codestyling-localization/" target="_blank">Codestyling Localization</a> - <small>Helps you translate the theme to your desired language</small></li>
								<li><a href="http://wordpress.org/extend/plugins/pagemash/" target="_blank">PageMash</a> - <small>Easy drag &amp; drop page management</small></li>
								<li><a href="http://wordpress.org/extend/plugins/lj-custom-menu-links/" target="_blank">LJ Custom Menulinks</a> - <small>Advanced menu or navigation management</small></li>
								<li><a href="http://wordpress.org/extend/plugins/widget-logic/" target="_blank">Widget Logic</a> - <small>Provides you finer control over your sidebars and widgets</small></li>
							</ul>
						</div>
					</div>
					<div id="featurepage" class="tabinfo fix">
						<div class="tabtitle">The Feature Page Template</div>
					
						<div class="inforow fix">
							<p>Setting up the features for the "Feature Page" template is easy.  The first step is to create a new page and select the "Feature Page" template for it.</p>
							<p>If you would like your feature to show up as your 'home' page, then you'll need to set your new page as the home page, and your blog to something else. This is done under 'settings' &gt; 'reading'.</p><img src="<?php echo IMAGE_FOLDER."/guide/frontpage.jpg";?>" class="aligncenter"/>

							<p>
							<img src="<?php echo IMAGE_FOLDER."/guide/titlebox.jpg";?>" class="alignright"/>Now you need to configure your features. For this, we've created a special feature setup panel in the Wordpress Admin &gt; Appearance panel. </p>
							<p><strong>Text</strong><br/> To get text to look the way you want it, we've allowed you to add your own HTML markup in your features.  You can and should use 'h' tags and 'p' tags to markup your features.  Recommended Markup is as follows (all are optional):</p>
								<ul>
									<li><strong>Title in "title" section:</strong> 'h1' tag with a class of 'ftitle'</li>
									<li><strong>Subtitle in "title" section:</strong> 'h3' tag with a class of 'fsub'</li>
									<li><strong>Summary title in "text" section:</strong> text wrapped with 'h4' tag</li>
									<li><strong>Summary text in "text" section:</strong> text wrapped with p tag</li>
								</ul>

								<p><strong>Media</strong><br/> In this theme we have given you a lot of flexibility as to how you show media.  You can show pictures running to the edges, pictures with padding, videos, etc...  Recommended Markup is as follows (all are optional):</p>
								<ul>
									<li><strong>For Videos or Embeds</strong> Add a 'br' tag on top (to create space) and set the media to a width of <?php echo FMEDIAWIDTH;?> and a height of <?php echo FMEDIAHEIGHT;?>.</li>
									<li><strong>For Images Running to Edge:</strong> Create images wider and taller than the recommended specs (use your own judgement on media size here )</li>
									<li><strong>For images with space padded around them:</strong> Add a 'br' tag on top (to create space) and set the media to a width of <?php echo FMEDIAWIDTH;?> and a height of <?php echo FMEDIAHEIGHT;?>.</li>
								</ul>

								<p><strong>Feature Boxes</strong><br/> The feature boxes underneath your feature are designed to be flexible.  Use your own markup to format how you would like them and throw in your own pictures.</p>

								<p><strong>Note:</strong><br/> If the feature-setup page gives you errors, or doesn't show 5 features and 3 feature-box areas, then you may want to use the 'restore features' button on the bottom of the setup page. It will restore the features to their default 'filler' HTML.<img src="<?php echo IMAGE_FOLDER."/guide/restore.jpg";?>" class="aligncenter"/> </p>
							
						</div>
					</div>
			
					<div id="createposts" class="tabinfo fix">
						<div class="tabtitle">Adding Excerpts &amp; Thumbs</div>

						<div class="inforow fix">
							<strong>Excerpts</strong>
							<p>When creating posts in the WP admin, be sure to fill in the excerpt as this theme uses the excerpt to display text on non post view pages.Try and keep the character length of your excerpt to and under 225 characters for optimal visual performance.</p> <p>Also, there are several theme options for changing the way excerpts work.</p>
							<strong>Thumbs</strong>
							<p><strong>Note: As of WordPress version 2.9 this plugin is obsolete and has been integrated into the core of WP.  All PageLines themes now support this native functionality; so if you are using WP 2.9 or newer feel free to uninstall the WP-Post-Thumbnail plugin.</strong></p>
							<p>To optimize the look of your site you can add images to your posts called thumbs. As of WordPress version 2.9 this is a standard feature.  Just set your default thumbnail size to <strong>200px by 200px</strong> and select 'add thumbnail' when creating posts. It will then prompt you to upload.</p>

							<br/><br/><strong>Using WP-Post Thumbnail For Thumbs</strong>
							<p>If you are using WP 2.8.x or lower, you might want to use this plugin to help you add thumbs to posts...</p>
							<div class="imagerow"><img src="<?php echo IMAGE_FOLDER."/guide/customsettings.jpg";?>" class="aligncenter"/></div>
							<p> To setup wp-post-thumbnail for this theme you will need to go under the settings for this in the WP admin under settings. Set "default thumbnail" to "<strong>no default</strong>", and set a preset to a dimension of <strong>200px by 200px</strong>, and set '<strong>assign to custom key</strong>' to the word <strong>thumb</strong>.</p>
								<img src="<?php echo IMAGE_FOLDER."/guide/nodefault.jpg";?>" class="alignright"/>

							<p>Once the defaults are set, you will see an image uploader on the bottom of the 'add post' page in the admin</p><p>To use this, first upload an image to the media library. Then select this using the image uploader and use the cropping feature in the plugin to crop it to 200px by 200px.  Then save.</p> <p>If you've set your defaults correctly this will add a custom field called 'thumb' to the post with the URl of the cropped image. Publish your post and your thumb should be there.</p>
						</div>
					</div>
					<div id="custompage" class="tabinfo fix">
						<div class="tabtitle">Customizing Pages</div>

						<div class="inforow fix">
							<p><img src="<?php echo IMAGE_FOLDER."/guide/custompage.jpg";?>" class="border alignright"/>PageLines has created a special interface that allows you to further customize your pages. When creating a new page, you will notice a panel on the bottom of the admin interface where you can set "page options."</p><p> Here you can select which sidebars you would like to show, and other options.</p>
						</div>
					</div>
					<div id="themeoptions" class="tabinfo fix">
						<div class="tabtitle">Setting Theme Options</div>

						<div class="inforow fix">
							<p>Under your appearance settings in WP admin, visit "<?php echo THEMENAME;?> Options" to set your theme preferences. This allows you to add things like a custom logo, links in the header, etc... Make sure to have the full URLs ready for images you would like to use. This can be attained by looking for the "file URl" field given to you for images in your gallery.</p>
							<img src="<?php echo IMAGE_FOLDER."/guide/fileurl.jpg";?>" class="aligncenter"/>
							<p>Further instructions about what you can do with theme options can be found on that page.</p>
						</div>
					</div>
		
					<div id="flickr" class="tabinfo fix">
						<div class="tabtitle">Using FlickrRSS</div>

						<div class="inforow fix">
							<p>In this plugins settings area, you will see the settings panel for FlickrRSS.  To display your own images, set the "display" to "user" then add your flickr user or group ID using the 'Find your ID' link. Only add the ID not the URL.</p> 

							<p>Change the number of desired photos to display in the sidebar of the site (This works best in multiples of 3, so 3, 6, 9, 12, etc work best). </p>
							<img src="<?php echo IMAGE_FOLDER."/guide/flickruser.jpg";?>" class="aligncenter"/> 
							<p>Finally, Leave the 'before list' field and the 'after list' fields blank. Then save your settings.</p>
							<p><img src="images/flickr_rss.gif" width="707" height="713" /></p>
						</div>
					</div>
					<div id="forms" class="tabinfo fix">
						<div class="tabtitle">Contact Forms With CFormsII</div>

						<div class="inforow fix">
							<p>Getting started with contact forms in <?php echo THEMENAME;?> is easy. <strong>Important: For more forms support please visit <a href="http://www.deliciousdays.com/cforms-plugin">this site</a>.</strong></p>
							
							<ol>
							<li>Once CForms is installed go to the "CFormsII" panel now in the admin menu.  Set up a new form or tweak the default one and save. <div class="imagerow"><img  class="" src="<?php echo IMAGE_FOLDER."/guide/cformsinterface.jpg";?>" class="aligncenter"/></div></li>
							<li>Make <strong>SURE</strong> to disable default styling of the forms under "cformsII"&gt;"stying". (This is required if you want your forms to look good)	<div class="imagerow"><img class="" src="<?php echo IMAGE_FOLDER."/guide/cformsstyling.jpg";?>" class="aligncenter"/></div></li>
							<li>Finally, in any page use the button (if enabled) that has been placed in your composition panel
							<div class="imagerow"><img class="" src="<?php echo IMAGE_FOLDER."/guide/cformsbutton.jpg";?>" class="aligncenter"/></div></li>
							<li>or simply add code to your HTML like this: 
							<div class="imagerow"><img class="" src="<?php echo IMAGE_FOLDER."/guide/cformscode.jpg";?>" class="aligncenter"/></div></li>
							</ol>
							<p>Your forms should now be working or close to it. Depending on your setup you may have to set up email preferences and tweak some other settings. Again for additional support visit the <a href="http://www.deliciousdays.com/cforms-plugin">plugin provider</a>.</p>
							
						</div>
					</div>
					<div id="faq" class="tabinfo fix">
						<div class="tabtitle">Troubleshooting &amp; FAQ</div>

						<div class="inforow fix">
							<p><strong>Why is my sidebar falling to the bottom of the page or footer going across the entire screen?</strong></p><p>The most common problem we hear about is when themes start doing strange things like this. Luckily, its easy to fix.  This problem is usually (90% of the time) caused by posts or plugins breaking the HTML markup in the theme.  For example there might be an extra 'div' element or one might not be closed properly.</p><p> If you have this problem look for the markup in your theme that could be causing your problem using the FireBug plugin for Firefox.</p>
							<p><strong>Why did I install the WP-PageNavi plugin?</strong></p><p>This theme supports 'pagination' which is a better way of navigating posts. This plugin is needed for this.</p>
							<p><strong>How do I use my WP uploads for custom images in the theme?</strong></p><p>Glad you asked.  All you need is to get the image URLs for these images once you upload theme to your WP gallery. WP will show you this under the term "FILE URL" after you have uploaded a pic.</p>
							<p><strong>Have another question or find a bug?</strong></p><p>Don't hesitate to get in touch with us directly if you can't figure something out. We are happy to help you get up and running.</p>
						</div>
					</div>
				</div> <!-- End the tabs -->
			
				
			</div> <!-- end tabs -->
			<div id="optionsfooter">
				<div class="hl"></div>
	 			<div class="theinputs">
					<a href="#optionstable">Go to top &raquo;</a>
					
				</div>
				<div class="clear"></div>
			</div>
			
		</td></tr></tbody></table>
		
<div class="clear"></div>
</div>
<?php } ?>