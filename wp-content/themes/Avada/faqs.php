<?php
// Template Name: FAQs
get_header(); ?>
	<?php
	if(get_post_meta($post->ID, 'pyre_full_width', true) == 'yes') {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
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
	?>
	<div id="content" class="faqs" style="<?php echo $content_css; ?>">
		<?php while(have_posts()): the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-content">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
		</div>
		<?php endwhile; ?>
		<?php
		$portfolio_category = get_terms('faq_category');
		if($portfolio_category):
		?>
		<ul class="faq-tabs clearfix">
			<li class="active"><a data-filter="*" href="#"><?php echo __('All', 'Avada'); ?></a></li>
			<?php foreach($portfolio_category as $portfolio_cat): ?>
			<li><a data-filter=".<?php echo urldecode($portfolio_cat->slug); ?>" href="#"><?php echo $portfolio_cat->name; ?></a></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<div class="portfolio-wrapper">
			<div class="accordian">
			<?php
			$args = array(
				'post_type' => 'avada_faq',
				'nopaging' => true
			);
			$gallery = new WP_Query($args);
			while($gallery->have_posts()): $gallery->the_post();
			?>
			<?php
			$item_classes = '';
			$item_cats = get_the_terms($post->ID, 'faq_category');
			if($item_cats):
			foreach($item_cats as $item_cat) {
				$item_classes .= urldecode($item_cat->slug) . ' ';
			}
			endif;
			?>
			<div class="faq-item <?php echo $item_classes; ?>">
				<span class="entry-title" style="display: none;"><?php the_title(); ?></span>
				<span class="vcard" style="display: none;"><span class="fn"><?php the_author_posts_link(); ?></span></span>
				<span class="updated" style="display: none;"><?php the_time('c'); ?></span>
				<h5 class="toggle"><a href="#"><span class="arrow"></span><span class="toggle-title"><?php the_title(); ?></span></a></h5>
				<div class="toggle-content post-content">

			<?php
			if($data['faq_featured_image']):
			if(has_post_thumbnail()):
			?>
			<div class="flexslider post-slideshow">
				<ul class="slides">
				   <?php $attachment_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'); ?>
					<li>
						<a href="<?php echo $attachment_image[0]; ?>" rel="prettyPhoto[gallery<?php the_ID(); ?>]" title="<?php echo the_title_attribute('echo=0'); ?>">
						   <?php the_post_thumbnail('blog-large'); ?>
				  		</a>;
					</li>
				</ul>
			</div>
			<?php endif; ?>
			<?php endif; ?>

					<?php the_content(); ?>
				</div>
			</div>
			<?php endwhile; ?>
			</div>
		</div>
	</div>
	<div id="sidebar" style="<?php echo $sidebar_css; ?>"><?php generated_dynamic_sidebar(); ?></div>
<?php get_footer(); ?>