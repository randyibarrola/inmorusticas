<div class="fboxes fix">
	<div class="fboxdividers fix">
		<?php foreach(pagelines('fboxes') as $fbox):?>
		<div class="fbox">
			<div class="fboxcopy">	
				<div class="fboxtitle"><?php echo $fbox['title'];?></div>
				<div class="fboxtext"><?php echo $fbox['text'];?></div>
			</div>
		</div>
		<?php endforeach;?>
	</div>
</div>
<div class="clear"></div>