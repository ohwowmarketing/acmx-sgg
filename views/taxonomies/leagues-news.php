<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div uk-grid class="uk-grid-small">

            <div class="uk-width-expand@l">
                <?php get_template_part( widget.'news-article' ); ?>
                <div class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
                    <div class="uk-flex uk-flex-between _headings">
                        <h1 class="uk-card-title">Best Sportsbooks</h1>
                    </div>
                    <div class="sportsbooks-lists _alt">
                        <?php do_action('sportsbook_promos'); ?>
                    </div>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <?php 

                    get_template_part( widget.'news' );
                    get_template_part( widget.'guides' );

                ?>
            </div>

        </div>
    </div>
</main>