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
                </div>
            </li>
        </ul>
        <?php endwhile; 

    wp_reset_query(); ?>    
    </div>
</div>
