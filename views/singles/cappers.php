<?php
// Get Author Meta
$author_id = get_the_author_meta( 'ID' );

// Brackets
$correct  = get_field('win_bracket', 'user_'.$author_id.'');
$wrong    = get_field('loss_bracket', 'user_'.$author_id.'');
$winpct   = get_field('win_pct', 'user_'.$author_id.'');

?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge" id="Contents">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">

                <div class="uk-card uk-card-default" data-card="cappers-stats">
                    <div class="uk-card-body">
                        <div class="uk-grid-small" uk-grid>
                            <div class="uk-width-auto">
                                <a class="avatar">
                                    <img src="<?php echo get_avatar_url($author_id); ?>" alt="<?php echo get_the_author_meta('display_name', $author_id); ?>" width="120px" height="120px">
                                </a>
                            </div>
                            <div class="uk-widht-expand">
                                <small class="author"><?php echo get_the_author_meta('display_name', $author_id); ?></small>
                                <h1><?php the_title(); ?></h1>
                                <div class="uk-child-width-auto uk-margin-small-top" uk-grid>
                                    <div>
                                        <div class="uk-panel">
                                            Capper’s Record
                                            <span class="uk-display-block value"><?php echo $correct; ?>–<?php echo abs($wrong); ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="uk-panel">
                                            Win PCT
                                            <span class="uk-display-block value"><?php echo number_format((float)$winpct, 2, '.', ''); ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="--modal-action">
                            <a href="#cappers-standings" uk-toggle><span class="uk-visible@s">Cappers’ Standings</span></a>
                        </div>
                    </div>

                    <div class="uk-card-footer">
                        <?php echo get_the_author_meta('description', $author_id); ?>
                    </div>
                </div>
                
                <div class="uk-card uk-card-default" data-card="cappers-analysis">
                    <div class="uk-card-header">
                        <h4><?php echo get_the_author_meta('display_name', $author_id); ?></h4>
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