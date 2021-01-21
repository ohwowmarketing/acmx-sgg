<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">
                <div class="uk-card uk-card-default uk-card-body" data-card="leaderboard">
                    <img src="<?php echo get_template_directory_uri(); ?>/resources/images/sportsbooks/dk-leaderboard.gif" style="width: 100%" alt="Draft Kings Sportsbook Promotion" />
                </div>
                <?php get_template_part( widget . 'oddslines' ); ?>
                <?php do_action('sportsbook_promos'); ?>
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