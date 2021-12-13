<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge" id="Contents">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">

                <div class="uk-card uk-card-default uk-card-body" data-card="cappers-corner">
                    <div class="uk-card-title">
                        <h3><?php the_title(); ?></h3>
                        <p><?php the_field( 'cc_sub_heading' ); ?></p>
                    </div>

                    <div uk-grid class="uk-grid-small uk-grid-divider --cappers-corner-wrapper">
                        <div class="uk-width-auto@xl uk-flex-first@xl --cappers-profile-list">

                            <div class="uk-grid-title">
                            <?php while ( have_rows( 'corner_picks' ) ) : the_row(); ?>
                                <h4><?php the_sub_field( 'cc_title' ); ?></h4>
                                <p><?php the_sub_field( 'cc_descriptions' ); ?></p>
                            <?php endwhile; ?>
                            </div>

                            <!-- Dynamic Cappers Here -->
                            <?php echo get_template_part( widget . 'cappers-odds' ); ?>
                        </div>
                        <div class="uk-width-expand@xl uk-flex-first --cappers-chat-plugin">

                            <div class="uk-grid-title">
                            <?php while ( have_rows( 'corner_chat' ) ) : the_row(); ?>
                                <h4><?php the_sub_field( 'cc_title' ); ?></h4>
                                <p><?php the_sub_field( 'cc_descriptions' ); ?></p>
                            <?php endwhile; ?>
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
                        // echo do_shortcode('[ultimatemember form_id="2160"]');
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