<?php 
// Import Title from Cappers Corner
$cappers = new WP_Query([ 'post_type' => 'page', 'page_id' => '2053' ]); 

?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge" id="Contents">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">

                <div class="uk-card uk-card-default uk-card-body" data-card="cappers-corner">
                    <?php while ( $cappers->have_posts() ) : $cappers->the_post(); ?>
                    <div class="uk-card-title">
                        <h3><?php the_title(); ?></h3>
                        <p><?php the_field( 'cc_sub_heading' ); ?></p>
                    </div>
                    <?php endwhile; wp_reset_query(); ?>
                    

                    <div uk-grid class="uk-grid-small uk-grid-divider --cappers-corner-wrapper">
                        <div class="uk-width-auto@xl uk-flex-first@xl --cappers-profile-list">

                            <?php while ( $cappers->have_posts() ) : $cappers->the_post(); ?>
                            <div class="uk-grid-title">
                            <?php while ( have_rows( 'corner_picks' ) ) : the_row(); ?>
                                <h4><?php the_sub_field( 'cc_title' ); ?></h4>
                                <p><?php the_sub_field( 'cc_descriptions' ); ?></p>
                            <?php endwhile; ?>
                            </div>
                            <?php endwhile; wp_reset_query(); ?>

                            <!-- Dynamic Cappers Here -->
                            <?php echo get_template_part( widget . 'cappers-odds' ); ?>
                        </div>
                        <div class="uk-width-expand@xl uk-flex-first --cappers-chat-plugin">

                            <?php while ( $cappers->have_posts() ) : $cappers->the_post(); ?>
                            <div class="uk-grid-title">
                            <?php while ( have_rows( 'corner_chat' ) ) : the_row(); ?>
                                <h4><?php the_sub_field( 'cc_title' ); ?></h4>
                                <p><?php the_sub_field( 'cc_descriptions' ); ?></p>
                            <?php endwhile; ?>
                            </div>
                            <?php endwhile; wp_reset_query(); ?>

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
                    if ( !is_user_logged_in() ) {
                        wp_redirect( site_url( '/cappers-corner' ) ); exit;
                    } else {
                        if ( $post->ID == 2168 ) {
                            if ( $_GET['um_action'] === 'edit' ) {
                                echo do_shortcode('[ultimatemember form_id="2160"]');
                            } else {
                                echo do_shortcode('[ultimatemember_account]'); ?>
                                <a href="<?php echo esc_url( site_url('logout') ); ?>" class="uk-button uk-button-large uk-button-primary --log-out">Log Out</a>
                            <?php }
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
<?php


// Cappers Standing
get_template_part( widget . 'cappers-standings' ); ?>