<!DOCTYPE html>
<html <?php language_attributes() . schema(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
    global $post;
    $post_slug = $post->post_name;
    if ($post_slug === 'header-test') {
        do_action( 'odds_header' );
    }
    get_template_part( _promo );
    get_template_part( _nav );
    get_template_part( _hdr );