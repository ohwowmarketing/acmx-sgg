<?php

// Get Author Meta
$author_id = get_the_author_meta( 'ID' );

?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">

                <div class="uk-card uk-card-body" data-card="cappers-stats">
                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-auto">
                            <a class="avatar">
                                <?php echo get_avatar( get_the_author_meta('email'), 120 ); ?>
                            </a>
                        </div>
                        <div class="uk-widht-expand">
                            <small class="author"><?php echo get_the_author_meta('nicename', $author_id); ?></small>
                            <h1><?php the_title(); ?></h1>
                            <span class="odds">Odds Bet: -7.0</span>
                            <div class="uk-child-width-auto" uk-grid>
                                <div>
                                    <div class="uk-panel">
                                        Capper’s Record
                                        <span class="uk-display-block value">8–2</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="uk-panel">
                                        Win PCT
                                        <span class="uk-display-block value">80%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="--modal-action">
                        <a href="#cappers-standings" uk-toggle><span class="uk-visible@s">Cappers’ Standings</span></a>
                    </div>
                </div>
                
                <div class="uk-card uk-card-default" data-card="cappers-analysis">
                    <div class="uk-card-header">
                        <h4><?php echo get_the_author_meta('nicename', $author_id); ?></h4>
                        <time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date('F j, Y'); ?></time>
                    </div>
                    <div class="uk-card-body">
                        <?php the_field( 'cappers_analysis' ); ?>
                    </div>
                </div>

                <!-- Chat Plugin Here -->
                <div class="uk-card uk-card-default uk-card-body" data-card="cappers-chat">
                    <?php echo do_shortcode('[cappers-corner-chat]'); ?>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <?php get_template_part( widget . 'news' ); ?>
            </div>
        </div>
    </div>
</main>

<!-- Cappers Standing -->
<?php get_template_part( widget . 'cappers-standings' ); ?>