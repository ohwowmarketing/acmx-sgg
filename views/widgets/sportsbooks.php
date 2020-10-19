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
                    <li><a href="<?php echo esc_url(site_url('/best-books/').'?state_abbr='.$state['value']); ?>"><?php echo $state['label'] ?></a></li>
                    <?php /* <li><a href="<?php echo esc_url( site_url('checking-location.php?key='.$post->ID.'&state_abbr='.$state['value']) ); ?>" target="_self" rel="noopener"><?php echo $state['label'] ?></a></li> */ ?>
                <?php 
                endforeach; ?>
                </ul>
            </div>
        </div>    
    </div>

    <div class="sportsbooks-lists">
    <?php
    //* ACF Repeater within the meta_query
    function my_posts_where( $where ) {
        $where = str_replace("meta_key = 'sb_affiliation_$", "meta_key LIKE 'sb_affiliation_%", $where);
        return $where;
    }
    add_filter('posts_where', 'my_posts_where');

    if ( empty($_GET['state_abbr']) ) {
        $args = [
            'numberposts'   => -1,
            'post_type'     => 'sportsbooks',
            'has_password'  => false,
            'order'         =>'asc',            
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key'       => 'sb_affiliation_$_sb_state',
                    'compare'   => '=',
                    'value'     => $_COOKIE['state_abbr'],
                ]
            ]
        ];
    } else {
        $args = [
            'numberposts'   => -1,
            'post_type'     => 'sportsbooks',
            'has_password'  => false,
            'order'         =>'asc',            
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key'       => 'sb_affiliation_$_sb_state',
                    'compare'   => '=',
                    'value'     => $_GET['state_abbr'],
                ]
            ]
        ];
    }

    // $sportsbooks = ['post_type'=>'sportsbooks','has_password'=>false,'posts_per_page'=>-1,'order'=>'asc'];
    // if ( empty( $_GET['state_abbr'] ) ) {
    //     $sportsbooks['meta_query'] = [['key'=>'sb_state','value'=>$_COOKIE['state_abbr'],'compare'=>'LIKE']];
    // } else {
    //     $sportsbooks['meta_query'] = [['key'=>'sb_state','value'=>$_GET['state_abbr'],'compare'=>'LIKE']];
    // }
    // query_posts( $sportsbooks );
    $sportsbooks = new WP_Query( $args );

    while ( $sportsbooks->have_posts() ) : $sportsbooks->the_post();

        $image   = get_field('sb_image');
        $promo   = get_field('sb_promotion');
        $details = get_field('sb_details');

        while ( have_rows('sb_affiliation') ) :
            the_row();

            $states = get_sub_field('sb_state');
            $urls   = get_sub_field('sb_url');

            if ( empty($_GET['state_abbr']) ) {

                if ( $_COOKIE['state_abbr'] != $states )
                    continue;

                    $stae = $states;
                    $url  = $urls;

            } else {

                if ( $_GET['state_abbr'] != $states )
                    continue;

                    $stae = $states;
                    $url  = $urls;

            }
        endwhile; ?>
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
        <?php endwhile; 

    wp_reset_postdata(); ?>
    </div>

</div>
