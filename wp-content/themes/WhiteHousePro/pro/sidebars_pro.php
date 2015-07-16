<?php

register_sidebar(array(
	'name'=>'Secondary Sidebar',
	'description' => __('The secondary sidebar will show up instead of your main sidebar if you select "show secondary sidebar" on page/post options.', TDOMAIN),
	    'before_widget' => '<div id="%1$s" class="%2$s widget"><div class="winner">',
	    'after_widget' => '&nbsp;</div></div>',
	    'before_title' => '<h4 class="wtitle">',
	    'after_title' => '&nbsp;</h4>'
	));
register_sidebar(array(
	'name'=>'Short Sidebar',
	'description' => __('This is just like the secondary sidebar, but we recommend you keep it short for pages without long form content (to show: select "short sidebar" on page/post options).', TDOMAIN),
	    'before_widget' => '<div id="%1$s" class="%2$s widget"><div class="winner">',
	    'after_widget' => '&nbsp;</div></div>',
	    'before_title' => '<h4 class="wtitle">',
	    'after_title' => '&nbsp;</h4>'
	));
register_sidebar(array(
	'name'=>'Content Sidebar',
	'description' => __('These widgets appear underneath your page/post content. Select the "individual page" checkbox option for them to appear.',TDOMAIN),
	    'before_widget' => '<div id="%1$s" class="%2$s widget"><div class="winner">',
	    'after_widget' => '</div></div>',
	    'before_title' => '<h3>',
	    'after_title' => '</h3>'
	));

register_sidebar(array(
'name'=>'Full-Width Bottom Sidebar',
'description' => __('These widgets appear full-width underneath your page content and sidebar. Select the "individual page" option for them to appear.',TDOMAIN),
    'before_widget' => '<div id="%1$s" class="%2$s widget fix"><div class="winner">',
    'after_widget' => '</div></div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
));
register_sidebar(array(
'name'=>'Footer Left',
'description' => __('Left sidebar in "morefoot" element enabled in options.',TDOMAIN),
    'before_widget' => '<div id="%1$s" class="%2$s widget fix">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
));
register_sidebar(array(
'name'=>'Footer Middle',
'description' => __('Middle sidebar in "morefoot" element enabled in options.',TDOMAIN),
    'before_widget' => '<div id="%1$s" class="%2$s widget fix">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
));
register_sidebar(array(
'name'=>'Footer Right',
'description' => __('Right sidebar in "morefoot" element enabled in options.',TDOMAIN),
    'before_widget' => '<div id="%1$s" class="%2$s widget fix">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
));
register_sidebar(array(
'name'=>'Footer Columns',
'description' => __('Use this 5-widget sidebar if you want to use widgets in your footer columns instead of the default.',TDOMAIN),
    'before_widget' => '<div id="%1$s" class="%2$s fcol"><div class="fcol_pad">',
    'after_widget' => '</div></div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
));

?>