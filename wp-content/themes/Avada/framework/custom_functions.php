<?php
function get_related_posts($post_id, $number_posts = -1) {
	$query = new WP_Query();

    $args = '';

	if($number_posts == 0) {
		return $query;
	}

	$args = wp_parse_args($args, array(
		'posts_per_page' => $number_posts,
		'post__not_in' => array($post_id),
		'ignore_sticky_posts' => 0,
        'meta_key' => '_thumbnail_id',
        'category__in' => wp_get_post_categories($post_id)
	));

	$query = new WP_Query($args);

  	return $query;
}

function get_related_projects($post_id, $number_posts = 8) {
    $query = new WP_Query();

    $args = '';

	if($number_posts == 0) {
		return $query;
	}

    $item_cats = get_the_terms($post_id, 'portfolio_category');
    if($item_cats):
    foreach($item_cats as $item_cat) {
        $item_array[] = $item_cat->term_id;
    }
    endif;

    $args = wp_parse_args($args, array(
        'posts_per_page' => $number_posts,
        'post__not_in' => array($post_id),
        'ignore_sticky_posts' => 0,
        'meta_key' => '_thumbnail_id',
        'post_type' => 'avada_portfolio',
        'tax_query' => array(
            array(
                'taxonomy' => 'portfolio_category',
                'field' => 'id',
                'terms' => $item_array
            )
        )
    ));

    $query = new WP_Query($args);

    return $query;
}

if(!function_exists('themefusion_pagination')):
function themefusion_pagination($pages = '', $range = 2)
{
    global $data;

     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if(1 != $pages)
     {
     	if ( ( $data['blog_pagination_type'] == 'Infinite Scroll' && is_home() ) || ( $data['grid_pagination_type'] == 'Infinite Scroll' && is_page_template('portfolio-grid.php') ) ) {
        	echo "<div class='pagination infinite-scroll clearfix'>";
        } else {
        	echo "<div class='pagination clearfix'>";
        }
         //if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'><span class='arrows'>&laquo;</span> First</a>";
         if($paged > 1) echo "<a class='pagination-prev' href='".get_pagenum_link($paged - 1)."'><span class='page-prev'></span>".__('Previous', 'Avada')."</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages) echo "<a class='pagination-next' href='".get_pagenum_link($paged + 1)."'>".__('Next', 'Avada')."<span class='page-next'></span></a>";
         //if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last <span class='arrows'>&raquo;</span></a>";
         echo "</div>\n";
     }
}
endif;

function string_limit_words($string, $word_limit)
{
	$words = explode(' ', $string, ($word_limit + 1));

	if(count($words) > $word_limit) {
		array_pop($words);
	}

	return implode(' ', $words);
}

if(!function_exists('themefusion_breadcrumb')):
function themefusion_breadcrumb() {
        global $data,$post;
        echo '<ul class="breadcrumbs">';

         if ( !is_front_page() ) {
        echo '<li>'.$data['breacrumb_prefix'].' <a href="';
        echo home_url();
        echo '">'.__('Home', 'Avada');
        echo "</a></li>";
        }

        $params['link_none'] = '';
        $separator = '';

        if (is_category() && !is_singular('avada_portfolio')) {
            $category = get_the_category();
            $ID = $category[0]->cat_ID;
            echo is_wp_error( $cat_parents = get_category_parents($ID, TRUE, '', FALSE ) ) ? '' : '<li>'.$cat_parents.'</li>';
        }

        if(is_singular('avada_portfolio')) {
            echo get_the_term_list($post->ID, 'portfolio_category', '<li>', '&nbsp;/&nbsp;&nbsp;', '</li>');
            echo '<li>'.get_the_title().'</li>';
        }

        if(is_singular('event')) {
            $terms = get_the_term_list($post->ID, 'event-categories', '<li>', '&nbsp;/&nbsp;&nbsp;', '</li>');
            if( ! is_wp_error( $terms ) ) {
                echo get_the_term_list($post->ID, 'event-categories', '<li>', '&nbsp;/&nbsp;&nbsp;', '</li>');
            }
            echo '<li>'.get_the_title().'</li>';
        }

        if (is_tax()) {
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            echo '<li>'.$term->name.'</li>';
        }

        if(is_home()) { echo '<li>'.$data['blog_title'].'</li>'; }
        if(is_page() && !is_front_page()) {
            $parents = array();
            $parent_id = $post->post_parent;
            while ( $parent_id ) :
                $page = get_page( $parent_id );
                if ( $params["link_none"] )
                    $parents[]  = get_the_title( $page->ID );
                else
                    $parents[]  = '<li><a href="' . get_permalink( $page->ID ) . '" title="' . get_the_title( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a></li>' . $separator;
                $parent_id  = $page->post_parent;
            endwhile;
            $parents = array_reverse( $parents );
            echo join( '', $parents );
            echo '<li>'.get_the_title().'</li>';
        }
        if(is_single() && !is_singular('avada_portfolio')  && !is_singular('event')) {
            $categories_1 = get_the_category($post->ID);
            if($categories_1):
                foreach($categories_1 as $cat_1):
                    $cat_1_ids[] = $cat_1->term_id;
                endforeach;
                $cat_1_line = implode(',', $cat_1_ids);
            endif;
            if( $cat_1_line ) {
                $categories = get_categories(array(
                    'include' => $cat_1_line,
                    'orderby' => 'id'
                ));
                if ( $categories ) :
                    foreach ( $categories as $cat ) :
                        $cats[] = '<li><a href="' . get_category_link( $cat->term_id ) . '" title="' . $cat->name . '">' . $cat->name . '</a></li>';
                    endforeach;
                    echo join( '', $cats );
                endif;
            }
            echo '<li>'.get_the_title().'</li>';
        }
        if(is_tag()){ echo '<li>'."Tag: ".single_tag_title('',FALSE).'</li>'; }
        if(is_404()){ echo '<li>'.__("404 - Page not Found", 'Avada').'</li>'; }
        if(is_search()){ echo '<li>'.__("Search", 'Avada').'</li>'; }
        if(is_year()){ echo '<li>'.get_the_time('Y').'</li>'; }

        echo "</ul>";
}
endif;

function tf_checkIfMenuIsSetByLocation($menu_location = '') {
    if(has_nav_menu($menu_location)) {
        return true;
    }

    return false;
}

// Custom RSS Link
add_filter('feed_link','pyre_feed_link', 1, 2);
function pyre_feed_link($output, $feed) {
    global $data;

    if($data['rss_link']):
    $feed_url = $data['rss_link'];

    $feed_array = array('rss' => $feed_url, 'rss2' => $feed_url, 'atom' => $feed_url, 'rdf' => $feed_url, 'comments_rss2' => '');
    $feed_array[$feed] = $feed_url;
    $output = $feed_array[$feed];
    endif;

    return $output;
}

function tf_addURLParameter($url, $paramName, $paramValue) {
     $url_data = parse_url($url);
     if(!isset($url_data["query"]))
         $url_data["query"]="";

     $params = array();
     parse_str($url_data['query'], $params);
     $params[$paramName] = $paramValue;
     $url_data['query'] = http_build_query($params);
     return tf_build_url($url_data);
}


 function tf_build_url($url_data) {
     $url="";
     if(isset($url_data['host']))
     {
         $url .= $url_data['scheme'] . '://';
         if (isset($url_data['user'])) {
             $url .= $url_data['user'];
                 if (isset($url_data['pass'])) {
                     $url .= ':' . $url_data['pass'];
                 }
             $url .= '@';
         }
         $url .= $url_data['host'];
         if (isset($url_data['port'])) {
             $url .= ':' . $url_data['port'];
         }
     }
     if (isset($url_data['path'])) {
     	$url .= $url_data['path'];
     }
     if (isset($url_data['query'])) {
         $url .= '?' . $url_data['query'];
     }
     if (isset($url_data['fragment'])) {
         $url .= '#' . $url_data['fragment'];
     }
     return $url;
 }

function getClassAlign($post_count)
{
    if(($post_count % 2)>0)
        return " align-left ";
    else
        return " align-right ";
}

function avada_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

add_action('wp_head', 'avada_set_post_views');
function avada_set_post_views() {
    global $post;

    if('post' == get_post_type() && is_single()) {
        $postID = $post->ID;

        if(!empty($postID)) {
            $count_key = 'avada_post_views_count';
            $count = get_post_meta($postID, $count_key, true);

            if($count == '') {
                $count = 0;
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
            } else {
                $count++;
                update_post_meta($postID, $count_key, $count);
            }
        }
    }
}

add_filter( 'bbp_get_forum_pagination_links', 'tf_get_forum_pagination_links', 1 );
function tf_get_forum_pagination_links() {
	$bbp = bbpress();

	$pagination_links = $bbp->topic_query->pagination_links;

	$pagination_links = str_replace( 'page-numbers current', 'current', $pagination_links );
	$pagination_links = str_replace( 'page-numbers', 'inactive', $pagination_links );
	$pagination_links = str_replace( 'prev inactive', 'pagination-prev', $pagination_links );
	$pagination_links = str_replace( 'next inactive', 'pagination-next', $pagination_links );

	$pagination_links = str_replace( '&larr;', __('Previous', 'Avada').'<span class="page-prev"></span>', $pagination_links );
	$pagination_links = str_replace( '&rarr;', __('Next', 'Avada').'<span class="page-next"></span>', $pagination_links );

	return $pagination_links;
}

add_filter( 'bbp_get_topic_pagination_links', 'tf_get_topic_pagination_links', 1 );
function tf_get_topic_pagination_links() {
	$bbp = bbpress();

	$pagination_links = $bbp->reply_query->pagination_links;
	$permalink        = get_permalink( $bbp->current_topic_id );
	$max_num_pages    = $bbp->reply_query->max_num_pages;
	$paged            = $bbp->reply_query->paged;

	$pagination_links = str_replace( 'page-numbers current', 'current', $pagination_links );
	$pagination_links = str_replace( 'page-numbers', 'inactive', $pagination_links );
	$pagination_links = str_replace( 'prev inactive', 'pagination-prev', $pagination_links );
	$pagination_links = str_replace( 'next inactive', 'pagination-next', $pagination_links );

	$pagination_links = str_replace( '&larr;', __('Previous', 'Avada').'<span class="page-prev"></span>', $pagination_links );
	$pagination_links = str_replace( '&rarr;', __('Next', 'Avada').'<span class="page-next"></span>', $pagination_links );

	return $pagination_links;
}

add_filter( 'bbp_get_search_pagination_links', 'tf_get_search_pagination_links', 1 );
function tf_get_search_pagination_links() {
	$bbp = bbpress();

	$pagination_links = $bbp->search_query->pagination_links;

	$pagination_links = str_replace( 'page-numbers current', 'current', $pagination_links );
	$pagination_links = str_replace( 'page-numbers', 'inactive', $pagination_links );
	$pagination_links = str_replace( 'prev inactive', 'pagination-prev', $pagination_links );
	$pagination_links = str_replace( 'next inactive', 'pagination-next', $pagination_links );

	$pagination_links = str_replace( '&larr;', __('Previous', 'Avada').'<span class="page-prev"></span>', $pagination_links );
	$pagination_links = str_replace( '&rarr;', __('Next', 'Avada').'<span class="page-next"></span>', $pagination_links );

	return $pagination_links;
}