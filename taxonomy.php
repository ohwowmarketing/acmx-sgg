<?php get_header(); ?>

<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge" id="Contents">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
                <div class="uk-card uk-card-default uk-card-body" data-card="guides-large">
                    <div class="--headings">
                        <h1 class="uk-card-title">Articles Keyword: <?php single_term_title(); ?></h1>
                        <div class="--filter-control">
                            <button class="uk-button uk-button-secondary uk-button-small" uk-toggle="target: #filter-control;" uk-tooltip="title: Filter Search"> <span uk-icon="settings"></span> </button>
                        </div>
                        <div id="filter-control" hidden>
                            <div class="--list-categories">
                                <button class="uk-button uk-button-primary uk-button-small">View All Categories <span uk-icon="icon: album" class="uk-margin-small-left"></span></button>
                                <div uk-dropdown="mode: click; pos: bottom-right; offset: 0; animation: uk-animation-slide-bottom-small;">
                                    <ul class="uk-nav uk-dropdown-nav">
                                    <?php

                                        $terms = get_terms([
                                            'taxonomy'   => 'guides_category',
                                            'hide_empty' => false,
                                            'show_count' => true,
                                            'orderby'    => 'id',
                                        ]);

                                        if ( ! empty( $terms ) && is_array( $terms ) ) {
                                            foreach ( $terms as $term ) {
                                                echo '<li><a href="'.esc_url( get_term_link( $term ) ).'">'. $term->name .'</a></li>';
                                            }
                                        } else {
                                            echo 'No Category Found';
                                        }

                                    ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="--search-articles">
                                <?php echo do_shortcode('[ivory-search id="1333"]'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="uk-position-relative uk-margin-top">
                        <?php while ( have_posts() ) : the_post(); ?>
                        <article class="uk-article --guides-lists">
                            <div class="uk-grid-small uk-flex-top" uk-grid>
                                <div class="uk-width-auto@s uk-width-auto@m">
                                    <?php if ( has_post_thumbnail() ) :
                                        echo '<a href="'.get_permalink().'" title="'.get_the_title().'">';
                                        echo wp_get_attachment_image( get_post_thumbnail_id(), [ 360, 9999, true] );
                                        echo '</a>';
                                    endif; ?>
                                </div>
                                <div class="uk-width-1-1@s uk-width-expand@m">
                                    <h3><a href="<?php the_permalink(); ?>" class="uk-link-text"><?php the_title(); ?></a></h3>
                                    <div class="uk-text-small"><?php echo custom_field_excerpt( get_the_content(), 50 ); ?> <a href="<?php the_permalink(); ?>" class="uk-link-text">Read more</a></div>
                                    <div class="--categories">
                                        <?php $terms = wp_get_post_terms( $post->ID, 'guides_category' );
                                        foreach ( $terms as $term ) : ?>
                                        <a href="<?php echo site_url( 'guides-category/'.$term->slug ); ?>"><?php echo $term->name; ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                        </article>
                        <?php endwhile; wp_reset_query(); ?>
                    </div>
                    <div class="uk-margin-medium-top">
                        <a href="<?php echo esc_url( site_url('gambling-guides') ); ?>" class="uk-button uk-button-primary uk-button-small">View All Guides</a>
                    </div>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
            <!-- Start Content -->
            <?php 

                get_template_part( widget.'news' );
                get_template_part( widget.'instagram' );

            ?>
            <!-- End Content -->                
            </div>

        </div>
    </div>
</main>

<?php get_footer();