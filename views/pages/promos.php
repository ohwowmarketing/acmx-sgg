<?php
    // Init Star Display
    function starRating( $fieldName, $option ) {

        switch ($option) {
            case '1':
                $rating = get_field( $fieldName );
                break;
            
            case '2':
                $rating = get_sub_field( $fieldName );
                break;
        }
        
        if ( $rating ) {
            $average_stars = round( $rating * 2 ) / 2;
            $drawn = 5;

            // Full Stars
            for ( $i = 0; $i < floor( $average_stars ); $i++ ) {
                $drawn--;
                // echo '<span class="icon full" uk-svg uk-icon="icon: star;"></span>';
                echo '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="-2 -3 40 40"><defs><linearGradient id="fullRating"><stop offset="100%" stop-color="rgb(251,179,24)"></stop><stop offset="0%" stop-color="#EBECF5"></stop></linearGradient></defs><path d="M20.388,10.918L32,12.118l-8.735,7.749L25.914, 31.4l-9.893-6.088L6.127,31.4l2.695-11.533L0, 12.118l11.547-1.2L16.026,0.6L20.388,10.918z" fill="url(#fullRating)" stroke="rgb(251,179,24)" stroke-width="0"></path></svg>';
            }

            // Half Stars
            if ( $rating - floor( $average_stars ) === 0.5 ) {
                $drawn--;
                // echo '<span class="icon half" uk-svg uk-icon="icon: star;"></span>';
                echo '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="-2 -3 40 40"><defs><linearGradient id="halfRating"><stop offset="50%" stop-color="rgb(251,179,24)"></stop><stop offset="0%" stop-color="#EBECF5"></stop></linearGradient></defs><path d="M20.388,10.918L32,12.118l-8.735,7.749L25.914, 31.4l-9.893-6.088L6.127,31.4l2.695-11.533L0, 12.118l11.547-1.2L16.026,0.6L20.388,10.918z" fill="url(#halfRating)" stroke="rgb(251,179,24)" stroke-width="0"></path></svg>';
            }

            // Empty Stars
            for ( $i = 0; $i < $drawn; $i++ ) {
                // echo '<span class="icon" uk-svg uk-icon="icon: star;"></span>';
                echo '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="-2 -3 40 40"><defs><linearGradient id="emptyRating"><stop offset="100%" stop-color="#EBECF5"></stop><stop offset="0%" stop-color="#EBECF5"></stop></linearGradient></defs><path d="M20.388,10.918L32,12.118l-8.735,7.749L25.914, 31.4l-9.893-6.088L6.127,31.4l2.695-11.533L0, 12.118l11.547-1.2L16.026,0.6L20.388,10.918z" fill="url(#emptyRating)" stroke="rgb(251,179,24)" stroke-width="0"></path></svg>';
            }
        }
    } // End Function
?>
<main id="main" class="main" role="main">
    <div class="uk-container" id="promo">

        <div class="uk-card uk-card-default" data-card="promos">
            <div class="uk-card-body">
                <h3 class="uk-card-title"><?php the_title(); ?></h3>

                <div class="uk-grid-small --promo-lists" uk-grid>
                    <!-- SB Starts Here -->
                    <?php
                    // Import Title from Cappers Corner
                    $promosPosts = [ 'post_type' => 'sgg_promo', 'posts_per_page' => -1, 'post_status' => 'publish', 'order' => 'DESC' ];
                    query_posts( $promosPosts );

                    while ( have_posts() ) : the_post();
                    
                    // Relationship
                    $featured_posts = get_field('sb_promotion_post_connection');

                    // Overrides
                    $override_posts = get_field( 'sb_promotion_override_description' );
                    $summary_or     = get_field( 'sb_promotion_summary' );
                    $link_or        = get_field( 'sb_promotion_link' );
                    $description_or = get_field( 'sb_promotion_description' );

                    $rating_or      - get_field( 'sb_promotion_rating' );

                    // Responsive Images
                    $desktop    = get_field( 'sb_responsive_desktop' );
                    $tablet     = get_field( 'sb_responsive_tablet' );
                    $mobile     = get_field( 'sb_responsive_mobile' );

                    $display    = get_field( 'promotion_display' );

                    if ( $featured_posts ) :
                        foreach ( $featured_posts as $featured_post ) :

                            $fp_permalink   = get_permalink( $featured_post->ID );

                            $link        = get_field( 'global_affiliate_link', $featured_post->ID );
                            $summary     = get_field( 'sb_promotion', $featured_post->ID );
                            $description = get_field( 'description', $featured_post->ID );
                            $rating      = get_field( 'rating', $featured_post->ID );

                        endforeach;

                    endif; 

                    if ( $override_posts ) {

                        // Bet Now Link
                        if ( !empty($link_or) ) {
                            $betnow = $link_or;
                        } else {
                            $betnow = $link;
                        }

                        // Summary
                        if ( !empty($summary_or) ) {
                            $summary = $summary_or;
                        } else {
                            $summary = $summary;
                        }

                        // Description
                        if ( !empty($description_or) ) {
                            $description = $description_or;
                        } else {
                            $description = $description;
                        }
                    }
                    ?>
                    <div <?php echo ($display) ? 'class="hidden"' : ''; ?>>
                        <div class="uk-card --promo-item">
                            <div class="uk-card-header uk-position-relative">
                                <?php # echo wp_get_attachment_image( $desktop['id'], [ 1120, 360, true ] ); ?>
                                <figure id="promo-card-banner" class="uk-position-relative">
                                    <a href="<?php echo esc_url( $betnow ); ?>" class="uk-position-cover"></a>
                                    <img src="<?php echo $desktop['url'] ?>" alt="<?php echo $desktop['alt'] ?>" class="desktop-screen">
                                    <img src="<?php echo $tablet['url'] ?>" alt="<?php echo $tablet['alt'] ?>" class="tablet-screen">
                                    <img src="<?php echo $mobile['url'] ?>" alt="<?php echo $mobile['alt'] ?>" class="mobile-screen">
                                </figure>

                                <!-- Overlay -->
                                <div id="reviews-overlay-ID<?php the_ID(); ?>" class="uk-overlay-default uk-position-cover uk-padding" uk-overflow-auto hidden>
                                    <div class="promos-info" uk-grid>
                                        <div class="uk-width-1-1">
                                            <h3><span><?php echo get_the_title(); ?></span> Review & Signup Offer</h3>
                                        </div>
                                        <div id="descriptions" class="uk-width-1-2@s uk-width-2-3@m">
                                            <?php echo $description; ?>
                                            <hr class="uk-divider-small">
                                            <div class="uk-panel">
                                                <a href="<?php echo esc_url( $betnow ); ?>" class="uk-button uk-button-primary uk-button-large">Bet Now at <?php echo get_the_title(); ?></a>
                                                <p class="uk-text-small"><?php echo $summary; ?></p>
                                                <small class="uk-text-meta">Terms and Conditions Apply</small>
                                            </div>
                                        </div>
                                        <div id="ratings" class="uk-width-1-2@s uk-width-1-3@m">
                                            <table class="uk-table uk-table-small uk-table-divider">
                                                <tbody>
                                                    <?php while ( have_rows( 'ratings', $featured_post->ID ) ) : the_row(); ?>
                                                    <tr>
                                                        <td><?php the_sub_field( 'label' ); ?></td>
                                                        <td class="uk-text-right@m"><?php the_sub_field( 'rating' ); ?></td>
                                                    </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td>Overall Rating</td>
                                                        <td class="uk-text-right@m"><?php echo $rating; ?></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Overlay -->

                            </div>
                            <div class="uk-card-footer uk-background-secondary">
                                <div class="uk-grid-small uk-flex-middle" uk-grid>
                                    <div class="uk-width-expand@s">
                                        <div class="uk-panel">
                                            <h4><?php the_title(); ?></h4>
                                            <p><?php echo $summary; ?></p>
                                            <div class="uk-margin-small-top">
                                                <?php starRating( 'sb_promotion_rating', '1' ); ?> <span>/ <?php echo $rating; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-auto@s">
                                        <div class="uk-button-group">
                                            <button type="button" data-sbid="<?php echo strtolower(get_the_title()); ?>" class="uk-button uk-button-primary uk-button-small sb-more-info" uk-toggle="target: #reviews-overlay-ID<?php the_ID(); ?>"> <span uk-icon="icon: info"></span> </button>
                                            <a href="<?php echo esc_url( $betnow ); ?>" class="uk-button uk-button-primary uk-button-large"> Bet Now </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    endwhile; wp_reset_query(); ?>
                    <!-- SB Ends Here -->
                </div>

            </div>
        </div>

    </div>
</main>