<div class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
    <div class="sportsbooks-lists">    
    <?php $sportsbooks = ['post_type'=>'sportsbooks','has_password'=>false,'posts_per_page'=>-1,'order'=>'asc'];
        query_posts( $sportsbooks );

        // Get the slug
        $post_review = $post->post_name;

        while ( have_posts() ) : the_post();

            // Get the Sportsbook Title
            $root_sportsbook = strtolower(get_the_title());

            if ( $root_sportsbook != $post_review )
                continue;

            $image   = get_field('sb_image');
            $promo   = get_field('sb_promotion');
            $details = get_field('sb_details'); 

        ?>        
        <ul>
            <li class="sbl-sportsbook">
                <div class="sbl-item">
                    <a href="<?php echo esc_url( site_url('best-books') ); ?>" target="_blank">
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
                    <p class="uk-text-bold uk-text-uppercase">States Available</p>
                    <ul>
                        <?php while ( have_rows('sb_affiliation') ) : the_row(); ?>
                        <li><?php $state = get_sub_field('sb_state'); 
                            echo $state['label'];
                        ?></li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </li>
            <li class="sbl-link">
                <div class="sbl-item">
                    <a href="<?php echo esc_url( site_url('best-books') ); ?>" class="uk-button uk-button-primary uk-button-small">Visit Sportsbooks</a>
                    <span class="uk-display-block uk-margin-small-top" hidden>
                        <a href="#" class="uk-button-text uk-text-bold">Full Review</a>
                    </span>
                </div>
            </li>
        </ul>
        <?php endwhile; 

    wp_reset_query(); ?>    
    </div>
</div>
