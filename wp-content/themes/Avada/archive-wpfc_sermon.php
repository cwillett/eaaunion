<?php get_header(); ?>
	<?php
	if($data['blog_archive_sidebar'] == 'None') {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
		$content_class= 'full-width';
	} elseif($data['blog_sidebar_position'] == 'Left') {
		$content_css = 'float:right;';
		$sidebar_css = 'float:left;';
	} elseif($data['blog_sidebar_position'] == 'Right') {
		$content_css = 'float:left;';
		$sidebar_css = 'float:right;';
	}
	?>
	<div id="content" style="<?php echo $content_css; ?>">
		<?php if (have_posts()) : ?>
		<?php while(have_posts()): the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
			<?php
			if($data['featured_images']):
			if($data['legacy_posts_slideshow']) {
				get_template_part('legacy-slideshow');
			} else {
				get_template_part('new-slideshow');
			}
			endif;
			?>
			<?php if($data['blog_post_title']): ?>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php else: ?>
			<span class="entry-title" style="display: none;"><?php the_title(); ?></span>
			<?php endif; ?>

			<div class="post-content">
				<?php render_wpfc_sermon_excerpt(); ?>
			</div>
			<div style="clear:both;"></div>
			<?php if($data['post_meta'] && ( (!$data['post_meta_author']) || (!$data['post_meta_date']) || (!$data['post_meta_cats']) || (!$data['post_meta_comments']) || (!$data['post_meta_tags']) ) ): ?>
			<div class="meta-info">
				<div class="alignleft vcard">
					<?php if(!$data['post_meta_author']): ?><?php echo __('By', 'Avada'); ?> <span class="fn"><?php echo the_terms( $post->ID, 'wpfc_preacher', '', ', ', ' ' ); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$data['post_meta_date']): ?><span class="updated"><?php the_time($data['date_format']); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$data['post_meta_cats']): ?><?php if(!$data['post_meta_tags']){ echo __('Categories:', 'Avada') . ' '; } ?><?php the_category(', '); ?><span class="sep">|</span><?php endif; ?><?php if(!$data['post_meta_tags']): ?><span class="meta-tags bottom"><?php the_tags( ); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$data['post_meta_comments']): ?><?php comments_popup_link(__('0 Comments', 'Avada'), __('1 Comment', 'Avada'), '% '.__('Comments', 'Avada')); ?><?php endif; ?></p>
				</div>
				<div class="alignright">
					<a href="<?php the_permalink(); ?>" class="read-more"><?php echo __('Read More', 'Avada'); ?></a>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php endwhile; ?>
		<?php themefusion_pagination($pages = '', $range = 2); ?>
		<?php else: ?>
		<?php endif; ?>
	</div>
	<div id="sidebar" style="<?php echo $sidebar_css; ?>">
		<?php
		if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Blog Sidebar')):
		endif;
		?>
	</div>
<?php get_footer(); ?>