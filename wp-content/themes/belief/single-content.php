<?php
    global $post;
    $post_id = $post -> ID;
                    
    $s = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , 'full' );
    

?>
<article class="post <?php if( get_post_type($post -> ID) == 'event') { echo ' post-event '; } ?> ">

    <?php if (get_post_type($post -> ID) == 'event') { 
        $sidebar_value = meta::get_meta( $post->ID, 'layout' ); 
        if($sidebar_value['layout_type'] == 'full_width'){
            $title_column_nr = 'ten';
            $time_column_nr = 'two';
        } else {
            $title_column_nr = 'nine';
            $time_column_nr = 'three';
        }
    ?>
        <div class="row">
            <div class="<?php echo $title_column_nr; ?> columns">
                <h3 class="post-title"><?php the_title(); ?></h3>
            </div>
            <div class="<?php echo $time_column_nr; ?> columns">
                <?php
                    $event_repeat = meta::get_meta( $post->ID, 'date' );
                    $start_date = $event_repeat['start_date_time']; 
                    $end_date = $event_repeat['end_date_time']; 
                    if($start_date != '' || $end_date != ''){
                ?>
                <div class="time-left <?php if($sidebar_value['layout_type'] != 'full_width') { echo 'time-left-no-margin'; } ?> ">         
                <?php                     
                    if(get_post_type() == 'event' && $event_repeat['is_repeating'] == 'yes') { 
                        echo __('Every', 'cosmotheme') . ' '. $event_repeat['repeating']; 
                    } else {
                        echo dateDiff(time(), $start_date, $end_date);
                    } ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php 
    } else { ?>
    <h3 class="post-title">
        <?php the_title(); ?>
    </h3>
    <?php }?>
    <?php  
        if(get_post_type($post -> ID) == 'post'){
            $cat_tax = 'category';    
        } elseif(get_post_type( $post -> ID) == 'page') {
            $cat_tax = '';
        } elseif(get_post_type( $post -> ID) == 'event') {
            $cat_tax = 'event-category';
        }

        if(isset($cat_tax)){
            $the_categories = post::get_post_categories($post->ID, $only_first_cat = false, $taxonomy = $cat_tax, $margin_elem_start = ' <li>', $margin_elem_end = '</li> ', $delimiter = ''); 
     
            if((get_post_type() == 'page' && options::logic( 'blog_post' , 'page_meta' )) || (get_post_type() == 'post' && options::logic( 'blog_post' , 'meta' ))){ 
                if( meta::logic( $post , 'settings' , 'meta' ) ){
                    get_meta($the_categories, $post );           
            
                } 
            } 
        }
        
    ?>

    <?php
    if((get_post_type() == 'event' && options::logic( 'blog_post' , 'meta' ))){ 
        if( meta::logic( $post , 'settings' , 'meta' ) ){
            get_meta($the_categories, $post );   
    ?>
    <?php } } ?>
    <?php
    if(get_post_type() != 'event'){?>
        <?php get_template_part('featured_image'); ?>
    <?php } ?>
    <div class="excerpt">
        <?php if(get_post_type() == 'event') { ?>
            <?php 
            $event_date = meta::get_meta( $post->ID, 'date' );
            $event_info = meta::get_meta( $post->ID, 'info' );
            $date_format = get_option( 'date_format' );
            $time_format = get_option( 'time_format' );

            /*we need to check if there is any meta to be shown*/
            if(strlen($event_date['start_date_time']) || strlen($event_date['end_date_time']) || strlen($event_info['venue']) || strlen($event_info['place'])){
                $have_meta_info = true;
            }else{
                $have_meta_info = false;
            }

            /* check if there is any custom meta fields generated by the user */
            if(isset($event_info['custominfometa']) && is_array($event_info['custominfometa']) && sizeof($event_info['custominfometa'])){
                $have_custom_meta_info = true;
            }else{
                $have_custom_meta_info = false;
            }

            /*we need to check if the map will be displayed*/
            $map = meta::get_meta( $post->ID, 'map' );
            if(strlen(trim($map['map_code'])) != ''){ 
                $have_map = true;
            }else{
                $have_map = false;
            }

            $sidebar_value = meta::get_meta( $post->ID, 'layout' ); 
            /*if the posts is full width (no sidebar) and there are both info meta and map to be shown then bloks for map and info meta will have 6 columns*/
            if($sidebar_value['layout_type'] == 'full_width' &&  ($have_meta_info || $have_custom_meta_info) && $have_map ) {
                $map_info_block = ' six ';
            }else{ /*bloks for map and info meta will have 12 columns*/
                $map_info_block = ' twelve ';
            }
            ?>
            <div class="row">
                <?php 
                    if(strlen($map['map_code']) != ''){ 
                ?>
                <div class="twelve columns">
                    <div class="event-map">
                        <?php 
                            echo $map['map_code'];
                        ?>
                    </div>
                </div>
                <?php } ?>   
                <div class="four columns">
                    <div class="event-details">                      
                        <ul class="event-details-list">
                           <?php if(strlen($event_date['start_date_time']) != '') { ?>
                            <li class="event-details-list-elem">
                                <div class="event-details-list-elem-name"><?php if($event_date['is_repeating'] == 'yes') { echo __('Start:','cosmotheme'); } else { echo __('Start date:','cosmotheme');} ?></div>
                                <div class="event-details-list-elem-content"><?php if($event_date['is_repeating'] == 'yes') {  echo  getRepeatingDate($event_repeat['start_date_time'], $event_date['repeating'] ); }else { if(isset($event_repeat['start_date_time']) && $event_repeat['start_date_time'] != '') { echo date($date_format, strtotime($event_repeat['start_date_time'])) . ', ' . date( $time_format, strtotime($event_repeat['start_date_time']) ) ; }  } ?></div>
                            </li>
                            <?php } 
                                if(strlen($event_date['end_date_time']) != '') { 
                            ?>
                            <li class="event-details-list-elem">
                                <div class="event-details-list-elem-name"><?php if($event_date['is_repeating'] == 'yes') { echo __('End:','cosmotheme');} else {echo __('End date:','cosmotheme'); } ?></div>
                                <div class="event-details-list-elem-content"><?php if($event_date['is_repeating'] == 'yes') { echo  getRepeatingDate($event_repeat['end_date_time'], $event_date['repeating'] ); }else { if(isset($event_repeat['end_date_time']) && $event_repeat['end_date_time'] != '' ){ echo date($date_format, strtotime($event_repeat['end_date_time'])) . ', ' . date( $time_format, strtotime($event_repeat['end_date_time']) ) ; }  } ?></div>
                            </li>
                            <?php } if(strlen($event_info['venue']) != '') { ?>
                            <li class="event-details-list-elem">
                                <div class="event-details-list-elem-name"><?php echo __('Venue:','cosmotheme'); ?></div>
                                <div class="event-details-list-elem-content"><?php echo $event_info['venue']; ?></div>
                            </li>
                            <?php } if(strlen($event_info['place']) != '') { ?>
                            <li class="event-details-list-elem">
                                <div class="event-details-list-elem-name"><?php echo __('Place:','cosmotheme'); ?></div>
                                <div class="event-details-list-elem-content"><?php echo $event_info['place']; ?></div>
                            </li>
                            <?php } ?>     
                            <?php  
                                if($have_custom_meta_info){ /*if we have meta info added by user we want to show it*/
                                    foreach ($event_info['custominfometa'] as $key => $custom_value) {
                            ?>
                                        <li class="event-details-list-elem">
                                            <div class="event-details-list-elem-name"><?php echo $key; ?></div>
                                            <div class="event-details-list-elem-content"><?php echo $custom_value; ?></div>
                                        </li>
                            <?php            
                                    }
                                }
                            ?>                       
                        </ul>
                    </div>
                </div>

                             
        
        <?php  } /*EOF if POST type is Event*/ ?>
        <?php if(get_post_type() == 'event') { ?><div class="eight columns"><?php get_template_part('featured_image'); ?><?php } ?>
        <?php
            if( get_post_format( $post -> ID ) == 'audio' ){
                $audio = new AudioPlayer(); 
                echo $audio->processContent( post::get_audio_file( $post -> ID ) );
            }
        ?>
        
        <?php if(isset($post -> post_excerpt) && strlen(trim($post -> post_excerpt))){ /*show excerpt if available*/ ?>
        <div class="subtext">    
            <?php the_excerpt(); ?>
        </div>    
            
        <?php } ?>
        
        <?php 
            
            //-------------------------
            if( get_post_format( $post -> ID ) == 'video' ){

                $video_format = meta::get_meta( $post -> ID , 'format' );
            ?>
                
            <div class="embedded_videos">    
                            
                <?php    

                if(isset($video_format['video_ids']) && !empty($video_format['video_ids'])){
                    foreach($video_format["video_ids"] as $videoid)
                    {
                        if( isset( $video_format[ 'video_urls' ][ $videoid ] ) ){
                            $video_url = $video_format[ 'video_urls' ][ $videoid ];
                            if( post::get_youtube_video_id($video_url) != "0" ){
                                echo post::get_embeded_video( post::get_youtube_video_id( $video_url ), "youtube" );
                            }else if( post::get_vimeo_video_id( $video_url ) != "0" ){
                                echo post::get_embeded_video( post::get_vimeo_video_id( $video_url ) , "vimeo" );
                            }
                        }
                        else echo post::get_local_video( urlencode(wp_get_attachment_url($videoid)));
                    }
                }    
            ?>
            </div>
            <?php                                     
            }

            //---------------------------
            
            if(get_post_format($post->ID)=="image" && !(isset($single_slideshow) && strlen($single_slideshow)) )
            {
                $image_format = meta::get_meta( $post -> ID , 'format' );

                if(isset($image_format['images']) && is_array($image_format['images']))
                {
                    echo "<div class=\"attached-image-gallery\">";
                    echo '<div class="row">';
                    foreach($image_format['images'] as $index=>$img_id)
                    {
                        $thumbnail= wp_get_attachment_image_src( $img_id, 't_attached_gallery');
                        $full_image=wp_get_attachment_url($img_id);
                        $url=$thumbnail[0];
                        $width=$thumbnail[1];
                        $height=$thumbnail[2];
                        echo '<div class="three columns mobile-one">';
                        echo "<div class=\"attached-image-gallery-elem\">";
                        echo "<a title=\"\" rel=\"prettyPhoto[".get_the_ID()."]\" href=\"".$full_image."\">";

                        if($height<150)
                        {
                            $vertical_align_style="style=\"margin-top:".((150-$height)/2)."px;\"";
                        }
                        else
                        {
                            $vertical_align_style="";
                        }

                        echo "<img alt=\"\" src=\"$url\" $vertical_align_style>";
                        echo "</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";  
                    echo "</div>";   
                }
                
            }

            the_content();
            
        ?>
        
        <?php wp_link_pages(array('before' => '<div class="pagenumbers"><p>Pages:','after' => '</p></div>  ', 'next_or_number' => 'number'));  ?>
                
    </div>
    <?php if(get_post_type() == 'event') { ?></div></div><?php  } ?>
    <?php
        if( get_post_format( $post -> ID ) == 'link' ){
            echo post::get_attached_file( $post -> ID );
        }

        $tags = wp_get_post_terms($post->ID, 'post_tag');
        if (!empty($tags) && options::get_value( 'blog_post' , 'post_sharing' ) == 'yes' && meta::logic( $post , 'settings' , 'sharing' )){
            $class_nr = 'six';
        }else if(empty($tags) || (options::get_value( 'blog_post' , 'post_sharing' ) == 'no' || !meta::logic( $post , 'settings' , 'sharing' ))){
            $class_nr = 'twelve';
        }else { $class_nr = ''; }
        
    ?> 

    <div class="row"> 
    <?php
        if (!empty($tags)) {
        ?>
        <div class="<?php echo $class_nr; ?> columns">
            <ul class="entry-content-tag-list">
                    <?php
                    foreach ($tags as $tag) {
                        $t = get_tag($tag);
                        echo '<li class="entry-content-tag-elem"><a href="' . get_tag_link($tag) . '" rel="tag">' . $t->name . '</a></li> ';
                    }
                    ?>
                    
            </ul>
        </div>
        <?php
        }
    ?>
       
    <?php 
        if (options::get_value( 'blog_post' , 'post_sharing' ) == 'yes' && meta::logic( $post , 'settings' , 'sharing' )) {
            /*Add here social sharing*/ 
            echo '<div class="' . $class_nr . ' columns">';
            get_template_part('social-sharing');
            echo '</div>';
        }
    ?> 
    </div>
</article>
<?php
    if(is_singular()){

        /*related posts*/
        if(is_single()){
            get_template_part('related-posts');
        }
        
        /*comments*/
        if( comments_open() ){
    ?>
        <div class="row">
            <div class="cosmo-comments twelve columns">            
    <?php        
            if( options::logic( 'general' , 'fb_comments' ) ){
                ?>
                    <div id="comments-title" class="comment-here">
                        
                            <?php 
                                $comments_label = sprintf('<span>' . __('Leave a comment','cosmotheme').'</span>');
                                echo $comments_label;
                            ?>
                       
                    </div>    
                    
                    <fb:comments href="<?php the_permalink(); ?>" num_posts="5" width="430" height="120" reverse="true"></fb:comments>
                    
                <?php
            }else{
                comments_template( '', true );
            }
    ?>   
            </div>     
        </div>         
    <?php    
        }
    }
    
?>

