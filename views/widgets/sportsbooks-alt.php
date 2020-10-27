<div class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
    <div class="uk-flex uk-flex-between _headings">
        <h1 class="uk-card-title">Best Sportsbooks</h1> 
        <div class="button-select-wrapper" hidden>
        <?php 
            $betting_states = get_field( 'states_operation', 'option' );
            $valid_states = [];
            $label = '';
            foreach ($betting_states as $state) {
                $valid_states[$state['label']] = $state['value'];
            }

            if ( isset( $_COOKIE['state_abbr'] ) && in_array( $_COOKIE['state_abbr'], $valid_states) ) : ?>
            <button type="button" class="uk-button uk-button-outline"><?php echo array_search( $_COOKIE['state_abbr'], $valid_states ); ?></button>
            <?php else : ?>
            <button type="button" class="uk-button uk-button-outline">Choose Betting Location</button>
            <?php endif; ?>

            <div uk-dropdown="mode: click">
                <ul class="uk-nav uk-dropdown-nav">
                <?php 
                foreach ( $betting_states as $state ) : ?>
                    <li><a href="<?php echo esc_url(site_url('/best-books/').'?state_abbr='.$state['value']); ?>"><?php echo $state['label'] ?></a></li>
                    <?php /* <li><a href="<?php echo esc_url( site_url('checking-location.php?key='.$post->ID.'&state_abbr='.$state['value']) ); ?>" target="_self" rel="noopener"><?php echo $state['label'] ?></a></li> */ ?>
                <?php 
                endforeach; ?>
                </ul>
            </div>
        </div>    
    </div>

    <div class="sportsbooks-lists _alt">
    <?php $sportsbooks = ['post_type'=>'sportsbooks','has_password'=>false,'posts_per_page'=>-1,'order'=>'asc'];
    query_posts( $sportsbooks );

    while ( have_posts() ) : the_post();

        $image   = get_field('sb_image');
        $promo   = get_field('sb_promotion');
        $details = get_field('sb_details'); 

        ?>
        <ul>
            <li class="sbl-sportsbook">
                <div class="sbl-item">
                    <a href="<?php echo esc_url( site_url('best-books') ); ?>">
                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                    </a>
                </div>
            </li>
            <li class="sbl-offers">
                <div class="sbl-item">
                    <h3><?php echo $promo; ?></h3>
                </div>
            </li>
            <li class="sbl-details">
                <div class="sbl-item">
                    <?php echo $details; ?>
                </div>
            </li>
            <li class="sbl-link">
                <div class="sbl-item">
                    <button type="button" class="uk-button uk-button-primary">Choose Betting Location</button>
                    <div uk-dropdown="mode: click; pos: bottom-justify; boundary: .sbl-item; offset: 5">
                        <ul class="uk-nav uk-dropdown-nav">
                        <?php $affiliations = get_field('sb_affiliation');
                            foreach ( $affiliations as $affiliate ) : ?>
                                <li><a href="<?php echo esc_url($affiliate['sb_url']); ?>" target="_blank"><?php echo $affiliate['sb_state']['label'] ?></a></li>
                            <?php
                        endforeach; ?>
                        </ul>
                    </div>
                    <?php if ( get_field('isReviewTrue') ) : 
                    $url = get_field('review_link_url'); ?>
                    <span class="uk-display-block uk-margin-small-top">
                        <a href="<?php echo esc_url( $url ); ?>" class="uk-button-text uk-text-bold">Full Review</a>
                    </span>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
        <?php endwhile; 

    wp_reset_query(); ?>
    </div>

</div>
