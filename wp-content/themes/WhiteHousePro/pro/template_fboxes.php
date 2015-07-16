<div class="fboxes fix">
	<div class="fboxdividers fix">
		<?php 
		// inserts a clearing element at the end of each line of boxes
		$perline = 3;
		$total = count(pagelines('fboxes'));
		$count = 1;?>
		<?php foreach(pagelines('fboxes') as $fbox):?>
			
			<?php if(showfeature($fbox['page'], $post->ID) && !isset($fbox['draft']) && isset($fbox['text']) && !empty($fbox['text'])):?>
				<div class="fbox">
					<div class="fboxcopy">
						<div class="fboxtitle"><?php echo do_shortcode($fbox['title']);?></div>
						<div class="fboxtext"><?php echo do_shortcode($fbox['text']);?></div>

					</div>
				</div>
				
				<?php if($count % $perline == 0 && $count != $total):?>
						
					<div class="clear"></div>
					
				<?php endif;?>
				<?php $count++;?>
			<?php endif;?>
		<?php endforeach;?>
	</div>
</div>
<div class="clear"></div>
