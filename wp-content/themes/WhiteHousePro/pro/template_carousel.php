<div id="carouselwrap" class="content">
	<div class="carouselcontainer">
	<div class="prev"></div>
	<div class="next"></div>
	<div class="thecarousel">
		<ul id="mycarousel" class="mycarousel">

			<?php 
			// Pictures in Carousel
			if(m_pagelines('carouselitems', $post->ID)) $carouselitems = m_pagelines('carouselitems', $post->ID);
			else $carouselitems = 30;
	
			if(m_pagelines('carousel_mode', $post->ID) == 'posts'):?>
				<?php $recentposts = get_posts('numberposts='.$carouselitems.'&offset=0');?>
				<?php foreach($recentposts as $key => $rpost):?>
					<?php setup_postdata($rpost);?>
					
						<li class="list-item fix">
							<a class="carousel_image_link" href="<?php echo get_permalink($rpost->ID); ?>">
							<?php if(has_post_thumbnail()): ?>
		                              <?php the_post_thumbnail(array( 70, 70 ),array( 'class' => 'list_thumb list-thumb' ));?>
							<?php else: ?>
								<img class="list_thumb list-thumb" src="<?php echo CORE_IMAGES;?>/post-blank.jpg" width="70" height="70" class="sidebar_thumb" />
							<?php endif;?> 
								<span class="list-title"><?php echo $rpost->post_title; ?></span>
							</a>
						</li>

				<?php endforeach;?>
			<?php elseif(function_exists('nggDisplayRandomImages')  && m_pagelines('carousel_mode', $post->ID) == 'ngen_gallery'):?>
						<?php 	if(m_pagelines('carousel_ngen_gallery', $post->ID)) $ngen_id = m_pagelines('carousel_ngen_gallery', $post->ID);
								else $ngen_id = 1; 
						?>
						<?php echo do_shortcode('[nggallery id='.$ngen_id.' template=plcarousel]');?>
				
			<?php elseif((function_exists('get_flickrRSS') && m_pagelines('carousel_mode', $post->ID) == 'flickr') || (!m_pagelines('carousel_mode', $post->ID))):?>
			
				<?php 
					get_flickrRSS(array(
						'num_items' => $carouselitems, 
						'html' => '<li><a href="%flickr_page%" title="%title%"><img src="%image_square%" alt="%title%"/><span class="list-title">%title%</span></a></li>'	
					));
				?>
				
			<?php else:?>
				<?php _e("You have selected the carousel page template but the plugins aren't activated for the selected mode (either NextGen-Gallery or FlickrRSS). Options are set in page options. Default mode is FlickrRSS.", TDOMAIN);?>
		
			<?php endif;?>
		</ul>
	</div>
	</div>
</div>