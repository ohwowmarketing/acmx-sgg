<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge" id="Contents">
        <div uk-grid class="uk-grid-small">

            <div class="uk-width-expand@l">
                <?php get_template_part( widget.'news-article' ); ?>
                <!-- <div id="sb">
                    <?php // do_action('sportsbook_promos'); ?>
                </div> -->
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <?php 

                    get_template_part( widget.'news' );
                    get_template_part( widget.'instagram' );

                ?>
            </div>

        </div>
    </div>
</main>