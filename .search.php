<?php get_header();

if ( have_posts() ) : 
    while ( have_posts() ) : the_post(); 
        
        echo '<h2>'.get_the_title().'</h2>';

    endwhile; 
endif; 

get_footer();