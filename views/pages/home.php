<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
                <?php get_template_part( widget.'oddslines' ); ?>

                <div class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
                    <div class="uk-flex uk-flex-between _headings">
                        <h1 class="uk-card-title">Best Books</h1>
                    </div>
                    <div class="sportsbooks-lists _alt">
                        <?php do_action('sportsbook_promos'); ?>
                    </div>
                </div>

                <div class="uk-card uk-card-default uk-card-body" data-card="content">
                    <?php the_content(); ?>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <?php 

                    get_sidebar();

                ?>
            </div>

        </div>
    </div>
</main>