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
                            <?php $cappers = [ 'post_type' => 'cappers_corner', 'post_status' => 'publish', 'has_password' => false, 'posts_per_page' => 5, 'orderby' => 'none', 'order' => 'DESC' ];
                            query_posts( $cappers ); ?>
                            <ul class="--cappers-wrapper" uk-accordion="active: false; content: > .uk-card .uk-card-body; toggle: > .uk-card .uk-card-header">
                                <?php while ( have_posts() ) : the_post();

                                $activate = get_field( 'activate_gamepick' );
                                $pin = get_field( 'pin_gamepick' );
                                $category = get_field( 'category_gamepick' );

                                if ( $activate ) : ?>
                                <li class="--cappers-profile <?php echo ($pin) ? 'uk-open' : ''; ?> " data-category="<?php echo $category; ?>">
                                    <div class="uk-card uk-card-default uk-card-small">
                                        <div class="uk-card-header">
                                            <div class="uk-grid-small uk-flex-top uk-flex-between" uk-grid>
                                                <div class="uk-width-auto">
                                                    <?php echo get_avatar( get_the_author_meta('email'), 40, '', get_the_author_meta('nicename'), [ 'class' => 'uk-border-circle' ] ); ?>
                                                </div>
                                                <?php while ( have_rows( 'cappers_gamepick' ) ) : the_row(); ?>
                                                <div class="uk-width-expand">
                                                    <small><?php echo get_the_author_meta('nicename'); ?></small>
                                                    <h4><?php the_sub_field( 'cappers_pick' ); ?> [<?php the_sub_field( 'cappers_odds' ); ?>] <span>vs <?php the_sub_field( 'cappers_matchup' ); ?></span></h4>
                                                </div>
                                                <?php endwhile; ?>
                                                <div class="uk-width-auto --cappers-action">
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
                                                    <a href="#standings"><span class="uk-visible@s">Cappers’ Standings</span></a>
                                                </div>
                                            </div>
                                            <div class="uk-grid-collapse uk-flex-middle uk-flex-between" uk-grid>
                                                <?php while ( have_rows( 'cappers_record' ) ) : the_row(); ?>
                                                <div class="uk-width-auto --cappers-stats">
                                                    Cappers' Record: <strong><?php the_sub_field( 'record_win' ); ?> - <?php the_sub_field( 'record_loss' ); ?></strong>
                                                </div>
                                                <?php endwhile;
                                                while ( have_rows( 'cappers_winpct' ) ) : the_row(); ?>
                                                <div class="uk-width-auto --cappers-stats">
                                                    Win PCT: <strong><?php the_sub_field( 'win_pct' ); ?>%</strong>
                                                </div>
                                                <?php endwhile; ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php endif; // end activate

                                endwhile; wp_reset_query(); ?>
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

                <div class="uk-card uk-card-default uk-card-body" data-card="ulimate-member">

                    <h1 class="uk-card-title"><?php the_title(); ?></h1>

                    <?php
                    if ( $post->ID == 2108 ) {
                        echo do_shortcode('[ultimatemember_password]');
                    } elseif ( $post->ID == 1964 ) {
                        if ( $_GET['um_action'] === 'edit' ) {
                            echo do_shortcode('[ultimatemember form_id="2042"]');
                        } else {
                            echo do_shortcode('[ultimatemember_account]');
                        }
                    }
                    ?>
                </div>

                <?php 
                    get_template_part( widget . 'news' );
                    get_template_part( widget . 'instagram' );
                ?>
            </div>
        </div>
    </div>
</main>