<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">
                <div id="guide-content" class="uk-card uk-card-default uk-card-body" data-card="guides">
                    <article class="uk-article uk-margin-bottom">
                    <?php
                        if ( has_post_thumbnail() ) {
                            the_post_thumbnail();
                            $description = get_post(get_post_thumbnail_id())->post_excerpt;
                        }

                        the_title('<h2 class="uk-article-title">','</h2>');
                        the_content();

                        echo '<div class="uk-text-meta uk-margin">'. makeUrltoLink($description) .'</div>';
                    ?>
                    </article>
                </div>
                <div id="sb">
                    <?php // do_action('sportsbook_promos'); ?>
                </div>
            </div>
            <div class="uk-width-1-1 uk-width-large@l">
                <?php get_template_part( widget . 'news' ); ?>
                <?php // get_template_part( widget . 'guides' ); ?>
            </div>
        </div>
    </div>
</main>
