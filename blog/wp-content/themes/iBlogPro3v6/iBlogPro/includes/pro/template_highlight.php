<div id="highlight" class="fix">
	<div class="postwrap">
	<div class="fcontent fix">
		<?php if(m_pagelines('featuretitle', $post->ID)):?>
			<h1 class="ftitle"><?php echo m_pagelines('featuretitle', $post->ID);?></h1>
		<?php elseif(m_pagelines('highlight_title', $post->ID)):?>
			<h1 class="ftitle"><?php echo m_pagelines('highlight_title', $post->ID);?></h1>
		<?php endif;?>
		<?php if(m_pagelines('featuretext', $post->ID)):?>
			<?php echo m_pagelines('featuretext', $post->ID);?>
		<?php elseif(m_pagelines('highlight_text', $post->ID)):?>
			<?php echo m_pagelines('highlight_text', $post->ID);?></h1>
		<?php endif;?>

		<?php if(!m_pagelines('featuretitle', $post->ID) && !m_pagelines('featuretext', $post->ID) && !m_pagelines('featuremedia', $post->ID) && !m_pagelines('highlight_text', $post->ID)):?>
			<h1 class="ftitle">Highlight Template</h1><p> Add text in the <a href="<?php echo admin_url()."page.php?action=edit&post=".$post->ID;?>">admin</a> using the interface we've provided in the 'add page' section of the admin or simply add a custom fields to this page called "featuretitle","featuretext", &amp; "featuremedia" (with the corresponding content).</p>
		<?php endif;?>
	</div>
	
	<?php if(m_pagelines('featuremedia', $post->ID)):?>
		<div class="fmedia"><?php echo m_pagelines('featuremedia', $post->ID);?></div>
	<?php elseif(m_pagelines('highlight_media', $post->ID)):?>
		<?php echo m_pagelines('highlight_media', $post->ID);?></h1>
	<?php endif;?>
	<div class="clear"></div>
	</div>
</div>
