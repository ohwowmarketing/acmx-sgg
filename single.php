<?php get_header();

    if ( post_password_required() ) : ?>

        <main class="main" role="main">
            <section class="uk-section uk-section-muted">
                <div class="uk-container uk-container-small uk-height-meidum uk-flex uk-flex-middle uk-flex-center uk-text-center">
                    
                    <article>
                        <span uk-icon="icon: lock; ratio: 5"></span>
                        <hr class="uk-divider-small uk-margin-auto">
                        <?php the_content(); ?>
                    </article>

                </div>
            </section>
        </main>        

    <?php //* Sports Gambling Guides
    elseif ( $post->post_type == 'sports_guides' ) :

        get_template_part( _single.'guides' );

    elseif ( $post->post_type == 'sportsbooks_reviews' ) :

        get_template_part( _single.'reviews' );

    endif;

get_footer();