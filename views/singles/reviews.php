<?php 
    // Reviews setfields from ACF
    $logo = get_field('sportsbooks_logo');
    $caption = get_field('sportsbooks_details');

    // Get all stars ratings
    $rating1 = get_field('promos_rating');
    $rating2 = get_field('friendliness_rating');
    $rating3 = get_field('methods_rating');
    $rating4 = get_field('wagering_rating');
    $rating5 = get_field('overall_rating');

    // Calculate all star ratings
    $ratings = [ $rating1, $rating2, $rating3, $rating4, $rating5 ];
    $ratings = array_sum($ratings);
    $totalRatings = ($ratings / 5);

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
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
                <div class="uk-card uk-card-default uk-card-body" data-card="reviews-introduction">

                    <ul class="rColumn">
                        <li class="rItem _logo">
                            <?php echo wp_get_attachment_image( $logo['id'], [ 9999, 120, true ] ); ?>
                        </li>
                        <li class="rItem _offer">
                            <?php echo $caption; ?>
                        </li>
                        <li class="rItem _star">
                            <span class="starValue"><?php echo $totalRatings; ?></span>
                        </li>
                    </ul>
                    <nav>
                        <ul class="uk-subnav uk-subnav-pill">
                            <li><a href="#promos" uk-scroll="offset: 120;">Promos</a></li>
                            <li><a href="#friendliness" uk-scroll="offset: 120;">User Friendliness</a></li>
                            <li><a href="#methods" uk-scroll="offset: 120;">Deposit / Withdrawal Methods</a></li>
                            <li><a href="#options" uk-scroll="offset: 120;">Wagering Options</a></li>
                            <li><a href="#ratings" uk-scroll="offset: 120;">Overall Rating</a></li>
                        </ul>
                    </nav>

                </div>

                <div class="uk-card uk-card-default uk-card-body" data-card="reviews-summary">
                    
                    <h3><?php the_title(); ?> Sportsbook Review</h3>
                    <?php the_field('summary'); ?>

                    <hr class="uk-divider-icon uk-margin-medium">

                    <div class="summary-list-ratings">
                        
                        <ul uk-grid class="uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-3@l uk-child-width-1-5@xl uk-grid-small uk-grid-match uk-flex-center summary-list">
                            <?php while ( have_rows('summary_chart') ) : the_row(); ?>
                            <li>
                                <div class="summary-item">
                                    <h4><?php the_sub_field('chart_label') ?></h4>
                                    <div class="summary-item-icon">
                                        <?php starRating( 'chart_rating', '2' ); ?>
                                    </div>
                                </div>
                            </li>
                            <?php endwhile; ?>
                        </ul>

                        <div class="uk-text-center uk-margin-top">
                            <?php while ( have_rows('summary_promotion') ): the_row();

                                $banner = get_sub_field('promotion_banner');
                                $link   = get_sub_field('promotion_url');

                                echo '<a href="'.$link.'" target="_blank">';
                                echo wp_get_attachment_image( $banner['id'], 'full', '', [ 'class' => 'uk-responsive-width' ] );
                                echo '</a>';
                            endwhile; ?>
                        </div>
                    </div>

                </div>

                <div id="promos" class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
                    <h4>Promos</h4>

                    <div class="sportsbooks-lists _alt">
                        <div id="sb">
                            <?php do_action('sportsbook_promos'); ?>
                        </div>
                    </div>

                    <?php the_field('promos'); ?>

                    <div class="_starRating">
                        <?php starRating( 'promos_rating', '1' ); ?>
                    </div>                    
                </div>

                <div id="friendliness" class="uk-card uk-card-default uk-card-body" data-card="reviews-friendliness">
                    <h4>User Friendliness</h4>
                    <?php the_field('friendliness'); ?>

                    <div class="_starRating">
                        <?php starRating( 'friendliness_rating', '1' ); ?>
                    </div>                    
                </div>

                <div id="methods" class="uk-card uk-card-default uk-card-body" data-card="reviews-methods">
                    <h4>Deposit/Withdrawal Methods</h4>
                    <?php the_field('methods'); ?>

                    <table class="uk-table uk-table-divider uk-table-responsive">
                        <thead>
                            <tr>
                                <th>Payment/Withdrawal Method</th>
                                <th>Advertised Processing Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ( have_rows('withdrawal_method_list') ) : the_row(); ?>
                            <tr>
                                <td><?php the_sub_field('withdrawal_method'); ?></td>
                                <td><?php the_sub_field('processing_time'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <div class="_starRating">
                        <?php starRating( 'methods_rating', '1' ); ?>
                    </div>
                </div>

                <div id="options" class="uk-card uk-card-default uk-card-body" data-card="reviews-options">
                    <h4>Wagering Options</h4>
                    <?php the_field('wagering_options'); ?>
                    <div class="uk-width-1-2@m">
                        <table class="uk-table uk-table-divider">
                            <thead>
                                <tr>
                                    <th>Here's a selection of the sports you can bet on at BetRivers:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ( have_rows('wagering_list') ) : the_row(); ?>
                                <tr>
                                    <td><?php the_sub_field('sports_item'); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="_starRating">
                        <?php starRating( 'wagering_rating', '1' ); ?>
                    </div>                    
                </div>

                <div id="ratings" class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
                    <h4>Overall Rating</h4>
                    
                    <?php the_field('overall'); ?>

                    <!-- <div class="sportsbooks-lists _alt">
                        <div id="sb">
                            <?php // do_action('sportsbook_promos'); ?>
                        </div>
                    </div> -->

                    <div class="_starRating">
                        <?php starRating( 'overall_rating', '1' ); ?>
                    </div>                    
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <?php get_sidebar(); ?>
            </div>

        </div>
    </div>
</main>
