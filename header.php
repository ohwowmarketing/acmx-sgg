<!DOCTYPE html>
<html <?php language_attributes() . schema(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <?php 
    // Yoast Bug and not displaying CPT for Custom Taxonomy
    $taxonomyName = get_query_var( 'taxonomy' );
    if ( $taxonomyName == 'guides_category' ) : ?>
        <title><?php wp_title(); ?></title>
    <?php

    // Post Category
    $catLeague = $_GET['league'];
    $category = single_cat_title('', false);

    elseif ( $catLeague == $category ) :
        do_action('display_league_category_title');
    endif;

    wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
    do_action( 'odds_header' );
    // get_template_part( _promo );
    get_template_part( _nav );
    get_template_part( _hdr );