<div class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
    <div class="uk-flex uk-flex-between _headings">
        <h1 class="uk-card-title">Best Sportsbooks</h1>
        <div class="button-select-wrapper">
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
                    <li><a href="<?php echo home_url('/best-books/').'?state_abbr='.$state['value']; ?>" target="_self" rel="noopener"><?php echo $state['label'] ?></a></li>
                <?php 
                endforeach; ?>
                </ul>
            </div>
        </div>    
    </div>
    
    
    <div class="sportsbooks-lists">
    <?php $sportsbooks = ['post_type'=>'sportsbooks','has_password'=>false,'posts_per_page'=>-1,'order'=>'asc'];
    if ( isset( $_COOKIE['state_abbr'] ) ) {
        $sportsbooks['meta_query'] = [['key'=>'sb_state','value'=>$_COOKIE['state_abbr'],'compare'=>'LIKE']];
    }
    query_posts( $sportsbooks );

    while ( have_posts() ) : the_post();
        
        $image   = get_field('sb_image');
        $url     = get_field('sb_url');
        $promo   = get_field('sb_promotion');
        $details = get_field('sb_details');
        $states  = get_field('sb_state');

        if ( ! isset( $_COOKIE['state_abbr'] ) || isset( $_COOKIE['state_abbr'] ) && in_array( $_COOKIE['state_abbr'], $states) ) : ?>
        <ul>
            <li class="sbl-sportsbook">
                <div class="sbl-item">
                    <a href="<?php echo esc_url( $url ); ?>" target="_blank">
                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                    </a>
                </div>
            </li>
            <li class="sbl-offers">
                <div class="sbl-item">
                    <h4><?php echo $promo; ?></h4>
                </div>
            </li>
            <li class="sbl-details">
                <div class="sbl-item">
                    <?php echo $details; ?>
                    <hr class="uk-divider-small uk-margin-small">
                    <span class="uk-display-block">
                        <a href="#" class="uk-button-text uk-text-bold">Full Review</a>
                    </span>
                </div>
            </li>
            <li class="sbl-link">
                <div class="sbl-item">
                    <a href="<?php echo $url; ?>" target="_blank" class="uk-button uk-button-primary uk-button-large">Bet Now</a>
                </div>
            </li>
        </ul>
        <?php 
        endif;

    endwhile; 

    wp_reset_query(); ?>
    </div>

</div>