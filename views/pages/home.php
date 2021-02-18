<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">
                <div class="uk-card uk-card-default uk-card-body" data-card="leaderboard">
                    <a href="https://dksb.sng.link/As9kz/4w3b?_dl=https%3A%2F%2Fsportsbook.draftkings.com%2Fgateway%3Fs%3D517197608%26wpcid%3D129760%26wpcn%3DNBACBB_Bet1Win100%26wpsrc%3DSports%2520Gambling%2520Guides%26wpcrid%3D&pcid=129760">
                        <img src="<?php echo get_template_directory_uri(); ?>/resources/images/sportsbooks/dk-leaderboard-1.gif" style="width: 100%; border: none;" alt="Draft Kings Sportsbook Promotion" />
                    </a>
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