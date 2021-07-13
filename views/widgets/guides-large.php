<?php $guides = [
    'post_type'         => 'sports_guides',
    'post_status'       => 'publish',
    'has_password'      => false,
    'posts_per_page'    => 9,
    'orderby'           => 'menu_order',
    'order'             => 'ASC',
];

query_posts( $guides ); ?>

<div class="uk-card uk-card-default uk-card-body" data-card="guides-full">
    <div class="uk-flex">
        <h1 class="uk-card-title"><?php the_field('widget_title_guides', 'option'); ?></h1>
        <div class="uk-panel">
            <button id="listview" class="uk-button uk-button-secondary uk-button-small --switcher"><i uk-icon="list"></i></button>
            <button id="gridview" class="uk-button uk-button-secondary uk-button-small --switcher"><i uk-icon="grid"></i></button>
            <button class="uk-button uk-button-secondary uk-button-small" uk-search-icon uk-toggle="target: #search-category; animation: uk-animation-slide-top-small"></button>
        </div>
        <div id="search-category" hidden>
            <?php echo do_shortcode('[ivory-search id="1329"]'); ?>
        </div>
    </div>

    <div class="uk-position-relative">
        <div class="uk-grid-small uk-child-width-1-2@s uk-child-width-1-3@m" uk-grid uk-height-match="target: > div > figure > figcaption">
            <?php while ( have_posts() ) : the_post(); ?>
            <div class="guides-lists">
                <figure class="uk-card uk-grid uk-grid-collapse">
                    <?php if ( has_post_thumbnail() ) :
                        echo '<div class="uk-card-media-top uk-width-auto@m"><a href="'.get_permalink().'">';
                        echo wp_get_attachment_image( get_post_thumbnail_id(), [ 640, 360, true] );
                        echo '</a></div>';
                    endif; ?>
                    <figcaption class="uk-card-body uk-width-expand@m">
                        <a href="<?php the_permalink(); ?>" class="uk-link-text"><?php the_title(); ?></a>
                        <p><?php echo custom_field_excerpt( get_the_content(), 50 ); ?></p>
                    </figcaption>
                    <span class="uk-card-footer uk-width-1-1">
                        <?php $terms = wp_get_post_terms( $post->ID, 'guides_category' );
                        foreach ( $terms as $term ) : ?>
                        <a href="<?php echo site_url( 'guides-category/'.$term->slug ); ?>"><?php echo $term->name; ?></a>
                        <?php endforeach; ?>
                    </span>
                </figure>
            </div>
            <?php endwhile; wp_reset_query(); ?>
        </div>
        <div class="uk-margin-medium-top">
            <a href="<?php echo esc_url( site_url('gambling-guides') ); ?>" class="uk-button uk-button-primary uk-button-small">View All Guides</a>
        </div>
    </div>
</div>