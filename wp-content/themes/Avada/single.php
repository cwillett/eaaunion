<?php get_header(); ?>
	<?php
	$content_css = '';
	$sidebar_css = '';
	if(get_post_meta($post->ID, 'pyre_full_width', true) == 'yes') {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
		$content_class= 'full-width';
	}
	elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'left') {
		$content_css = 'float:right;';
		$sidebar_css = 'float:left;';
	} elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'right') {
		$content_css = 'float:left;';
		$sidebar_css = 'float:right;';
	} elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'default') {
		if($data['default_sidebar_pos'] == 'Left') {
			$content_css = 'float:right;';
			$sidebar_css = 'float:left;';
		} elseif($data['default_sidebar_pos'] == 'Right') {
			$content_css = 'float:left;';
			$sidebar_css = 'float:right;';
		}
	}

	if($data['single_post_full_width']) {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
		$content_class= 'full-width';
	}
	?>
	<div id="content" class="<?php echo $content_class; ?>" style="<?php echo $content_css; ?>">
		<?php wp_reset_query(); ?>
		<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
		<?php //query_posts($query_string.'&paged='.$paged); ?>
		<?php if(!$data['blog_pn_nav']): ?>
		<div class="single-navigation clearfix">
			<?php previous_post_link('%link', __('Previous', 'Avada')); ?>
			<?php next_post_link('%link', __('Next', 'Avada')); ?>
		</div>
		<?php endif; ?>
		<?php if(have_posts()): the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
			<?php
			global $data;
			if((!$data['legacy_posts_slideshow'] && !$data['posts_slideshow']) && get_post_meta($post->ID, 'pyre_video', true)): ?>
			<!--<div class="flexslider post-slideshow">
				<ul class="slides">
					<li class="full-video">
						<?php echo get_post_meta($post->ID, 'pyre_video', true); ?>
					</li>
				</ul>
			</div>-->
			<?php endif;
			if($data['featured_images_single']):
			if($data['legacy_posts_slideshow']):
			$args = array(
			    'post_type' => 'attachment',
			    'numberposts' => $data['posts_slideshow_number']-1,
			    'post_status' => null,
			    'post_parent' => $post->ID,
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post_mime_type' => 'image',
				'exclude' => get_post_thumbnail_id()
			);
			$attachments = get_posts($args);
			if((has_post_thumbnail() || get_post_meta($post->ID, 'pyre_video', true))):
			?>
			<div class="flexslider post-slideshow">
				<ul class="slides">
					<?php if(!$data['posts_slideshow']): ?>
					<?php if(get_post_meta($post->ID, 'pyre_video', true)): ?>
					<li>
						<div class="full-video">
							<?php echo get_post_meta($post->ID, 'pyre_video', true); ?>
						</div>
					</li>
					<?php elseif(has_post_thumbnail() ): ?>
					<?php $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id()); ?>
					<li>
						<?php if( ! $data['status_lightbox']  ): ?>
						<a href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id()); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" /></a>
						<?php else: ?>
						<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" />
						<?php endif; ?>
					</li>
					<?php endif; ?>
					<?php else: ?>
					<?php if(get_post_meta($post->ID, 'pyre_video', true)): ?>
					<li>
						<div class="full-video">
							<?php echo get_post_meta($post->ID, 'pyre_video', true); ?>
						</div>
					</li>
					<?php endif; ?>
					<?php if(has_post_thumbnail() && !get_post_meta($post->ID, 'pyre_video', true)): ?>
					<?php $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id()); ?>
					<li>
						<?php if( ! $data['status_lightbox']  ): ?>
						<a href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id()); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" /></a>
						<?php else: ?>
						<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" />
						<?php endif; ?>
					</li>
					<?php endif; ?>
					<?php foreach($attachments as $attachment): ?>
					<?php $attachment_image = wp_get_attachment_image_src($attachment->ID, 'full'); ?>
					<?php $full_image = wp_get_attachment_image_src($attachment->ID, 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata($attachment->ID); ?>
					<li>
						<?php if( ! $data['status_lightbox']  ): ?>
						<a href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field('post_excerpt', $attachment->ID); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta($attachment->ID, '_wp_attachment_image_alt', true); ?>" /></a>
						<?php else: ?>
						<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" />
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php else: ?>
			<?php
			if((has_post_thumbnail() || get_post_meta($post->ID, 'pyre_video', true))):
			?>
			<div class="flexslider post-slideshow">
				<ul class="slides">
					<?php if(!$data['posts_slideshow']): ?>
					<?php if(get_post_meta($post->ID, 'pyre_video', true)): ?>
					<li>
						<div class="full-video">
							<?php echo get_post_meta($post->ID, 'pyre_video', true); ?>
						</div>
					</li>
					<?php elseif(has_post_thumbnail() ): ?>
					<?php $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id()); ?>
					<li>
						<?php if( ! $data['status_lightbox']  ): ?>
						<a href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id()); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" /></a>
						<?php else: ?>
						<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" />
						<?php endif; ?>
					</li>
					<?php endif; ?>
					<?php else: ?>
					<?php if(get_post_meta($post->ID, 'pyre_video', true)): ?>
					<li>
						<div class="full-video">
							<?php echo get_post_meta($post->ID, 'pyre_video', true); ?>
						</div>
					</li>
					<?php endif; ?>
					<?php if(has_post_thumbnail() ): ?>
					<?php $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id()); ?>
					<li>
						<?php if( ! $data['status_lightbox']  ): ?>
						<a href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id()); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" /></a>
						<?php else: ?>
						<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" />
						<?php endif; ?>
					</li>
					<?php endif; ?>
					<?php
					$i = 2;
					while($i <= $data['posts_slideshow_number']):
					$attachment_new_id = kd_mfi_get_featured_image_id('featured-image-'.$i, 'post');
					if($attachment_new_id):
					?>
					<?php $attachment_image = wp_get_attachment_image_src($attachment_new_id, 'full'); ?>
					<?php $full_image = wp_get_attachment_image_src($attachment_new_id, 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata($attachment_new_id); ?>
					<li>
						<?php if( ! $data['status_lightbox']  ): ?>
						<a href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field('post_excerpt', $attachment_new_id); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta($attachment_new_id, '_wp_attachment_image_alt', true); ?>" /></a>
						<?php else: ?>
						<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta($attachment_new_id, '_wp_attachment_image_alt', true); ?>" />
						<?php endif; ?>
					</li>
					<?php endif; $i++; endwhile; ?>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			<?php endif; ?>
			<?php wp_reset_query(); ?>
			<?php if($data['blog_post_title']): ?>
			<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php else: ?>
			<span class="entry-title" style="display: none;"><?php the_title(); ?></span>
			<?php endif; ?>
			<div class="post-content">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
			<?php if($data['post_meta'] && ( (!$data['post_meta_author']) || (!$data['post_meta_date']) || (!$data['post_meta_cats']) || (!$data['post_meta_comments']) || (!$data['post_meta_tags']) ) ): ?>
			<div class="meta-info">
				<div class="alignleft vcard">
					<?php if(!$data['post_meta_author']): ?><?php echo __('By', 'Avada'); ?> <span class="fn"><?php echo the_terms( $post->ID, 'wpfc_preacher', '', ', ', ' ' ); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$data['post_meta_date']): ?><span class="updated"><?php the_time($data['date_format']); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$data['post_meta_cats']): ?><?php if(!$data['post_meta_tags']){ echo __('Categories:', 'Avada') . ' '; } ?><?php the_category(', '); ?><span class="sep">|</span><?php endif; ?><?php if(!$data['post_meta_tags']): ?><span class="meta-tags bottom"><?php the_tags( ); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$data['post_meta_comments']): ?><?php comments_popup_link(__('0 Comments', 'Avada'), __('1 Comment', 'Avada'), '% '.__('Comments', 'Avada')); ?><?php endif; ?></p>
				</div>
			</div>
			<?php endif; ?>
			<?php if($data['social_sharing_box']): ?>
			<?php $nofollow = '';
			if($data['nofollow_social_links']) {
				$nofollow = ' rel="nofollow"';
			} ?>
			<div class="share-box">
				<h4><?php echo __('Share This Story, Choose Your Platform!', 'Avada'); ?></h4>
				<ul class="social-networks social-networks-<?php echo strtolower($data['socialbox_icons_color']); ?>">
					<?php if($data['sharing_facebook']): ?>
					<li class="facebook">
						<a href="http://www.facebook.com/sharer.php?m2w&s=100&p&#91;url&#93;=<?php the_permalink(); ?>&p&#91;title&#93;=<?php the_title(); ?>"	target="_blank"<?php echo $nofollow; ?>>
							Facebook
						</a>
						<div class="popup">
							<div class="holder">
								<p>Facebook</p>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if($data['sharing_twitter']): ?>
					<li class="twitter">
						<a href="http://twitter.com/home?status=<?php echo urlencode($post->post_title); ?> <?php the_permalink(); ?>" target="_blank"<?php echo $nofollow; ?>>
							Twitter
						</a>
						<div class="popup">
							<div class="holder">
								<p>Twitter</p>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if($data['sharing_linkedin']): ?>
					<li class="linkedin">
						<a href="http://linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" target="_blank"<?php echo $nofollow; ?>>
							LinkedIn
						</a>
						<div class="popup">
							<div class="holder">
								<p>LinkedIn</p>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if($data['sharing_reddit']): ?>
					<li class="reddit">
						<a href="http://reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" target="_blank"<?php echo $nofollow; ?>>
							Reddit
						</a>
						<div class="popup">
							<div class="holder">
								<p>Reddit</p>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if($data['sharing_tumblr']): ?>
					<li class="tumblr">
						<a href="http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink()); ?>&amp;name=<?php echo urlencode($post->post_title); ?>&amp;description=<?php echo urlencode(get_the_excerpt()); ?>" target="_blank"<?php echo $nofollow; ?>>
							Tumblr
						</a>
						<div class="popup">
							<div class="holder">
								<p>Tumblr</p>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if($data['sharing_google']): ?>
					<li class="google">
						<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank"<?php echo $nofollow; ?>>
							Google +1
						</a>
						<div class="popup">
							<div class="holder">
								<p>Google +1</p>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if($data['sharing_pinterest']): ?>
					<li class="tf-pinterest">
						<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
						<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;description=<?php echo urlencode($post->post_title); ?>&amp;media=<?php echo urlencode($full_image[0]); ?>" target="_blank"<?php echo $nofollow; ?>>
							Pinterest
						</a>
						<div class="popup">
							<div class="holder">
								<p>Pinterest</p>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if($data['sharing_email']): ?>
					<li class="email">
						<a href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>">
							Email
						</a>
						<div class="popup">
							<div class="holder">
								<p>Email</p>
							</div>
						</div>
					</li>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php if($data['author_info']): ?>
			<div class="about-author">
				<div class="title"><h2><?php echo __('About the Author:', 'Avada'); ?> <?php the_author_posts_link(); ?></h2><div class="title-sep-container"><div class="title-sep"></div></div></div>
				<div class="about-author-container">
					<div class="avatar">
						<?php echo get_avatar(get_the_author_meta('email'), '72'); ?>
					</div>
					<div class="description">
						<?php the_author_meta("description"); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if( ($data['related_posts'] && get_post_meta($post->ID, 'pyre_related_posts', true ) != 'no' ) ||
					  ( ! $data['related_posts'] && get_post_meta($post->ID, 'pyre_related_posts', true) == 'yes' ) ): ?>
			<?php $related = get_related_posts($post->ID, $data['number_related_posts']); ?>
			<?php if($related->have_posts()): ?>
			<div class="related-posts single-related-posts">
				<div class="title"><h2><?php echo __('Related Posts', 'Avada'); ?></h2><div class="title-sep-container"><div class="title-sep"></div></div></div>
				<div id="carousel" class="es-carousel-wrapper">
					<div class="es-carousel">
						<ul>
							<?php while($related->have_posts()): $related->the_post(); ?>
							<?php if(has_post_thumbnail()): ?>
							<li>
								<div class="image" aria-haspopup="true">
									<?php if($data['image_rollover']): ?>
									<?php the_post_thumbnail('related-img'); ?>
									<?php else: ?>
									<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('related-img'); ?></a>
									<?php endif; ?>
									<?php
									if(get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'link') {
										$link_icon_css = 'display:inline-block;';
										$zoom_icon_css = 'display:none;';
									} elseif(get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'zoom') {
										$link_icon_css = 'display:none;';
										$zoom_icon_css = 'display:inline-block;';
									} elseif(get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'no') {
										$link_icon_css = 'display:none;';
										$zoom_icon_css = 'display:none;';
									} else {
										$link_icon_css = 'display:inline-block;';
										$zoom_icon_css = 'display:inline-block;';
									}

									$icon_url_check = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true); if(!empty($icon_url_check)) {
										$icon_permalink = get_post_meta($post->ID, 'pyre_link_icon_url', true);
									} else {
										$icon_permalink = get_permalink($post->ID);
									}
									?>
									<div class="image-extras">
										<div class="image-extras-content">
											<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
											<a style="<?php echo $link_icon_css; ?>" class="icon link-icon" href="<?php echo $icon_permalink; ?>">Permalink</a>
											<?php
											if(get_post_meta($post->ID, 'pyre_video_url', true)) {
												$full_image[0] = get_post_meta($post->ID, 'pyre_video_url', true);
											}
											?>
											<a style="<?php echo $zoom_icon_css; ?>" class="icon gallery-icon" href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery]">Gallery</a>
											<h3><a href="<?php echo $icon_permalink; ?>"><?php the_title(); ?></a></h3>
										</div>
									</div>
								</div>
							</li>
							<?php endif; endwhile; ?>
						</ul>
					</div>
					<div class="es-nav"><span class="es-nav-prev">Previous</span><span class="es-nav-next">Next</span></div>
				</div>
			</div>
			<?php endif; ?>
			<?php endif; ?>

			<?php if($data['blog_comments']): ?>
				<?php wp_reset_query(); ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
	<div id="sidebar" style="<?php echo $sidebar_css; ?>">
	<?php generated_dynamic_sidebar(); ?>
	</div>
<?php get_footer(); ?>