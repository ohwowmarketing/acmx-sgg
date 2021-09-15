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
                            echo '<div class="uk-text-right uk-text-meta">'. makeUrltoLink($description) .'</div>';
                        }

                        the_title('<h2 class="uk-article-title">','</h2>'); ?>
                        <address class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <?php echo get_avatar( get_the_author_meta('ID'), 40); ?>
                            </div>
                            <div class="uk-width-expand">
                                <span rel="author" class="uk-text-small uk-link-reset"><?php 
                                    $author_id = get_the_author_meta( 'ID' );
	                                echo get_the_author_meta('display_name', $author_id);  
                                ?></span>
                                <span class="uk-display-block uk-text-meta uk-margin-remove"><?php the_date(); ?></span>
                            </div>
                        </address>
                        <?php 
                        the_content();
                    ?>
                    </article>
                </div>
                <div id="sb">
                    <?php // do_action('sportsbook_promos'); ?>
                </div>
            </div>
            <div class="uk-width-1-1 uk-width-large@l">
                <?php get_template_part( widget . 'news' ); ?>
                <?php get_template_part( widget.'instagram' ); ?>
            </div>
        </div>
    </div>
</main>
