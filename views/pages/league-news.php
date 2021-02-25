<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">
                <?php do_action( 'display_summary_news_articles' ); ?>
                <div id="sb">
                    <?php do_action('sportsbook_promos'); ?>
                </div>
            </div>
            <div class="uk-width-1-1 uk-width-large@l">
                <?php get_template_part( widget . 'news' ); ?>
                <?php get_template_part( widget . 'guides' ); ?>
            </div>
        </div>
    </div>
</main>