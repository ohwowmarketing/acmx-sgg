<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">

                <div class="uk-card uk-card-default uk-card-body" data-card="cappers-corner">
                    <div class="uk-card-title">
                        <h3>Cappers Corner</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.</p>
                    </div>

                    <div uk-grid class="uk-grid-small uk-grid-divider --cappers-corner-wrapper">
                        <div class="uk-width-auto@xl --cappers-profile-list">

                            <div class="uk-grid-title">
                                <h4>Cappers Corner Picks</h4>
                                <p>Top picks of the day</p>
                            </div>

                            <!-- Dynamic Cappers Here -->
                            <ul class="--cappers-wrapper" uk-accordion="active: false; content: > .uk-card .uk-card-body; toggle: > .uk-card .uk-card-header">
                            <?php 
                                $cappers = [ 'post_type' => 'cappers_corner', 'posts_per_page' => 5, 'order' => 'DESC' ];
                                $loopCappers = new WP_Query( $cappers );

                                while ( $loopCappers->have_posts() ) : $loopCappers->the_post();

                                    // ACF Fields
                                    $activate   = get_field( 'gamepick_activation' );
                                    $pin        = get_field( 'gamepick_pinpost' );
                                    $category   = get_field( 'gamepick_category' );
                                    $predict    = get_field( 'gamepick_prediction' );
                                    
                                    $author = get_the_author_meta('ID');    
                                    $user_post_count = count_user_posts( $author, 'cappers_corner' );

                                if ( $activate ) : ?>
                                <li class="--cappers-profile <?php echo ($pin) ? 'uk-open' : ''; ?> " data-category="<?php echo $category; ?>">
                                    <div class="uk-card uk-card-default uk-card-small">
                                        <div class="uk-card-header">
                                            <div class="uk-grid-small uk-flex-top uk-flex-between" uk-grid>
                                                <div class="uk-width-auto">
                                                    <?php echo get_avatar( get_the_author_meta('email'), 40, '', get_the_author_meta('nicename'), [ 'class' => 'uk-border-circle' ] ); ?>
                                                </div>
                                                <div class="uk-width-expand">
                                                    <small><?php echo get_the_author_meta('nicename'); ?></small>
                                                    <h4><?php the_title(); ?></h4>
                                                    <span>Odds Bet: <?php the_field( 'gamepick_odds' ); ?></span>
                                                </div>
                                                <div class="uk-width-auto --cappers-action" hidden>
                                                    <a href="javascript:void(0)" title="View More Info"><!-- &nbsp; --></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-card-body">
                                            <div class="uk-grid-collapse uk-flex-between uk-grid-match" uk-grid>
                                                <div class="uk-width-expand --profile-action">
                                                    <a href="<?php the_permalink(); ?>">Read capper’s analysis</a>
                                                </div>
                                                <div class="--modal-action">
                                                    <a href="#cappers-standings" uk-toggle><span class="uk-visible@s">Cappers’ Standings</span></a>
                                                </div>
                                            </div>
                                            <div class="uk-grid-collapse uk-flex-middle uk-flex-between" uk-grid>
                                                <div class="uk-width-auto --cappers-stats">
                                                    Cappers' Record: <strong><?php echo $totalCorrect; ?> - <?php echo abs($totalWrong); ?></strong>
                                                </div>
                                                <div class="uk-width-auto --cappers-stats">
                                                    Win PCT: <strong><?php echo number_format((float)$totalPercent, 2, '.', ''); ?>%</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php endif; // end activate

                                endwhile; 
                                wp_reset_postdata(); ?>
                            </ul>

                        </div>
                        <div class="uk-width-expand@xl --cappers-chat-plugin">

                            <div class="uk-grid-title">
                                <h4>Cappers Corner Chat</h4>
                                <p>Join the Discussion</p>
                            </div>

                            <!-- Chat Plugin Here -->
                            <?php echo do_shortcode('[cappers-corner-chat]'); ?>
                        </div>
                    </div>
                </div>

            </div>


            <div class="uk-width-1-1 uk-width-large@l">
                <?php
                    if ( $_GET['um_action'] == 'edit' ) {
                        echo do_shortcode('[ultimatemember form_id="2042"]');
                    }

                    get_template_part( widget . 'news' );
                    get_template_part( widget . 'instagram' );
                ?>
            </div>
        </div>
    </div>
</main>

<!-- Cappers Standing -->
<?php get_template_part( widget . 'cappers-standings' ); ?>