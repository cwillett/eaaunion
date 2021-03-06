<?php get_header(); ?>
<?php
    $template = 'search';
?>

<section id="main">

    <div class="main-container"> 
        <div class="row">
            <div class="twelve columns main-container-wrapper">   
                <div class="row">
                    <div class="twelve columns">
                        <?php
                            if( have_posts () ){
                        ?>
                                <h2 class="content-title">
                                    <span>
                                    <?php _e( 'Search results: ' , 'cosmotheme' );  ?>
                                    </span>
                                </h2>
                        <?php
                            }else{
                                ?><h2 ><span><?php _e( 'Sorry, no posts found' , 'cosmotheme' ); ?></h2></span><?php
                            }
                        ?> 
                    </div>
                </div>
                <?php
                    $layout = new LBSidebarResizer( 'search' );
                    $layout -> render_frontend();
                ?>
            </div>
        </div>
    </div>  
</section>
<?php get_footer(); ?>