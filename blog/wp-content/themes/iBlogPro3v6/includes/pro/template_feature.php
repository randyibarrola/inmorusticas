
<div id="feature">
	<div class="postwrap fix">
		<div class="hentry fix">
			<div id="cycle" class="fix">

						<?php foreach(pagelines('features') as $feature):?>
							<?php if(!empty($feature['text']) || !empty($feature['media'])):?>
							<div id="<?php echo $feature['name'];?>" class="fcontainer" <?php if(isset($feature['background'])):?>style="background:<?php echo $feature['background'];?>"<?php endif;?>>
									<div class="fcontent">
										<div class="fheading">
											<?php echo $feature['title'];?>
										</div>
										<div class="ftext">
											<?php echo $feature['text'];?>

											<?php if($feature['link']):?>
												<a class="featurelink" href="<?php echo $feature['link'];?>"><?php _e('More',TDOMAIN);?></a>
											<?php endif;?>
										</div>
									</div>
									<div class="fmedia">
										<?php echo $feature['media'];?>
									</div>
								<div class="clear"></div>
							</div>
							<?php endif;?>
						<?php endforeach;?>
			</div>
			<div class="hl"></div>
			<div id="feature-footer" class="fix">
				<div id="featurenav"></div>
					<?php include (LIB . '/_twittermessages.php'); ?>
			</div>
			<div class="clear"></div>
			
	
		</div>
	</div>
</div>
<div class="clear"></div>

