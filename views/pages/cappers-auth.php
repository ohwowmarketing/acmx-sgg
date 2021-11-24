<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">

                <div class="uk-card uk-card-default uk-card-body" data-card="cappers-corner">
                    <div class="uk-card-title">
                        <h3><?php the_title(); ?></h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.</p>
                    </div>

                    <div uk-grid class="uk-grid-small uk-grid-divider --cappers-corner-wrapper">
                        <div class="uk-width-auto@xl --cappers-profile-list">

                            <div class="uk-grid-title">
                                <h4>Cappers Corner Picks</h4>
                                <p>Top picks of the day</p>
                            </div>

                            <!-- Dynamic Cappers Here -->
                            <?php echo get_template_part( widget . 'cappers-odds' ); ?>
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
                    if ( $post->ID == 2168 ) {
                        if ( $_GET['um_action'] === 'edit' ) {
                            echo do_shortcode('[ultimatemember form_id="2160"]');
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

<!-- Cappers Standing -->
<?php get_template_part( widget . 'cappers-standings' ); ?>