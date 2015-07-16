
<div id="highlight" class="fix">
	
	<?php if(get_post_meta($post->ID, 'featuremedia', true)):?><div class="fmedia"><?php echo do_shortcode(get_post_meta($post->ID, 'featuremedia', true));?></div><?php endif;?>
	<div class="fcontent">
		<?php if(get_post_meta($post->ID, 'featuretitle', true)):?><h1><?php echo do_shortcode(get_post_meta($post->ID, 'featuretitle', true));?></h1><?php endif;?>
		<?php if(get_post_meta($post->ID, 'featuretext', true)):?><?php echo do_shortcode(get_post_meta($post->ID, 'featuretext', true));?><?php endif;?>
		<?php if(!get_post_meta($post->ID, 'featuretitle', true) || !get_post_meta($post->ID, 'featuretext', true)):?>
			<h1>Add text in <a href="<?php echo admin_url()."page.php?action=edit&post=".$post->ID;?>">admin</a></h1><p> using the interface we've provided in the 'add page' section of the admin or simply add a custom fields to this page called "featuretitle" &amp; "featuretext" (with the corresponding content).</p><?php endif;?>
	</div>
</div>
<div class="clear"></div>
