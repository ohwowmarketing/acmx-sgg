<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">
                <!-- <div class="uk-card uk-card-default uk-card-body" data-card="leaderboard"></div> -->
                <?php 
                // Oddsline
                get_template_part( widget . 'oddslines' ); 

                // Guides Large View
                get_template_part( widget . 'guides-large' );

                // News (mobile only)
                if ( wp_is_mobile() ) {
                    get_template_part( widget.'news-large' );
                }

                // Sates
                get_template_part( widget . 'states' );

                ?>
                <div class="uk-card uk-card-default uk-card-body" data-card="content">
                    <?php the_content(); ?>
                </div>
            </div>
            <div class="uk-width-1-1 uk-width-large@l">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>