<script type="text/javascript" src="<?php echo JS_FOLDER;?>/jquery.cycle.all.js"></script>

<script type="text/javascript">

	var $j = jQuery.noConflict();

	$j(document).ready(function () {
	
			$j('#cycle').cycle({ 
			    fx: '<?php if(pagelines('feffect')):?><?php echo pagelines('feffect');?><?php else:?>fade<?php endif;?>',
				sync: <?php if(pagelines('fremovesync')):?>0<?php else:?>1<?php endif;?>,
				timeout: <?php if(pagelines('timeout')):?><?php echo pagelines('timeout');?><?php else:?>0<?php endif;?>,
			    speed:  <?php if(pagelines('fspeed')):?><?php echo pagelines('fspeed');?><?php else:?>1500<?php endif;?>, 
				pager:  '#featurenav',
				cleartype:  true,
    			cleartypeNoBg:  true
			 });

	});	
	
</script>