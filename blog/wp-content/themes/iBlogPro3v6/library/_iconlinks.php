<!-- iphone icons -->
	<div class="icons">

		<?php 
				$iphonedir =  IMAGE_FOLDER.'/iphone/';
		?>
			<?php if(pagelines('commentslink')):?>
			<div class="nav-icon">
				<a href="<?php bloginfo('comments_rss2_url'); ?>"><img class="pngbg" src="<?php echo $iphonedir;?>comments.png" alt="icon"/></a>
			</div>
			<?php endif;?>
			<?php if(pagelines('rsslink')):?>
			<div class="nav-icon">
				<a href='<?php bloginfo('rss2_url');?>'><img class="pngbg" src="<?php echo $iphonedir;?>rss.png" alt="icon"/></a>
			</div>
			<?php endif;?>
			
			<?php if(pagelines('toolslink')):?>
					<div class="nav-icon">
						<a href='<?php echo pagelines('toolslink'); ?>'><img class="pngbg" src="<?php echo $iphonedir;?>tools.png" alt="icon"/></a>
					</div> 
			<?php endif;?>
			<?php if(pagelines('facebooklink')):?>  
				<div class="nav-icon">
					
					<a href='<?php echo pagelines('facebooklink'); ?>'><img class="pngbg" src="<?php echo $iphonedir;?>facebook.png" alt="icon"/></a>
				</div> 
			<?php endif;?>
				<?php if(pagelines('linkedinlink')):?>
						<div class="nav-icon">
							<a href='<?php echo pagelines('linkedinlink');?>'><img class="pngbg" src="<?php echo $iphonedir;?>linkedin.png" alt="icon"/></a>
						</div>	 
				<?php endif;?>
			<?php if(pagelines('callink')):?>
					<div class="nav-icon">
						<a href='<?php echo pagelines('callink')?>'><img class="pngbg" src="<?php echo $iphonedir;?>cal.png" alt="icon"/></a>
					</div> 
			<?php endif;?>
			
			<?php if(pagelines('twitterlink')):?>
					<div class="nav-icon">
						<a href='<?php echo pagelines('twitterlink') ?>'><img class="pngbg" src="<?php echo $iphonedir;?>twitter.png" alt="icon"/></a>
					</div> 
			<?php endif;?>
		
			<?php if(pagelines('photolink')):?>
					<div class="nav-icon">
						<a href='<?php echo pagelines('photolink')?>'><img class="pngbg" src="<?php echo $iphonedir;?>photo.png" alt="icon"/></a>
					</div> 
			<?php endif;?>
			<?php if(pagelines('emaillink')):?>
					<div class="nav-icon">
						<a href='<?php echo pagelines('emaillink')?>'><img class="pngbg" src="<?php echo $iphonedir;?>mail.png" alt="icon"/></a>
					</div> 
			<?php endif;?>
			<?php if(pagelines('maplink')):?>
					<div class="nav-icon">
						<a href='<?php echo pagelines('maplink')?>'><img class="pngbg" src="<?php echo $iphonedir;?>map.png" alt="icon"/></a>
					</div> 
			<?php endif;?>
			<?php if(pagelines('noteslink')):?>
					<div class="nav-icon">
						<a href='<?php echo pagelines('noteslink')?>'><img class="pngbg" src="<?php echo $iphonedir;?>notes.png" alt="icon"/></a>
					</div> 
			<?php endif;?>
	</div>
<!-- /end iphone icons -->