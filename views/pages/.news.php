<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
            <!-- Start Content -->
                <?php
                    // Get Post Name
                    $leagueName = get_the_title( $post->post_parent );

                    // Include API Keys
                    include( locate_template( includes.'league-keys.php', false, true ) );

                    switch ($leagueName) {
                        case 'NFL':
                            $header_npk = $nfl_header_npk;
                            $header_atk = $nfl_header_atk;
                            $widget     = 'widget_gallery_nflnews';
                            break;
                        
                        case 'MLB':
                            $header_npk = $mlb_header_npk;
                            $header_atk = $mlb_header_atk;
                            $widget     = 'widget_gallery_mlbnews';
                            break;

                        case 'NBA':
                            $header_npk = $nba_header_npk;
                            $header_atk = $nba_header_atk;
                            $widget     = 'widget_gallery_nbanews';
                            break;
                    }

                    // Premium News
                    $news_request = wp_remote_get( 'https://fly.sportsdata.io/v3/'.strtolower($leagueName).'/news-rotoballer/json/RotoBallerPremiumNews', $header_npk );
                    $news_body    = json_decode( wp_remote_retrieve_body( $news_request ) );

                    // Trial - Score/Teams
                    $team_request = wp_remote_get( 'https://fly.sportsdata.io/v3/'.strtolower($leagueName).'/scores/json/teams', $header_atk );
                    $team_body    = json_decode( wp_remote_retrieve_body( $team_request ) );

                    // Widget Images
                    $images = get_field($widget, 'option');
                    $images = json_decode (json_encode ($images), FALSE);

                    // echo '<pre>';
                    // print_r($team_body);
                    // echo '</pre>';
                    // die();
                ?>
                <div class="uk-card uk-card-default uk-card-body" data-card="league-news">
                    <h1 class="uk-card-title">Latest <?php echo $leagueName; ?> Team / Player News</h1>

                    <div uk-grid class="uk-grid-small uk-grid-match uk-child-width-1-2@s uk-child-width-1-3@xl" uk-height-match="target: > div > article > h3">
                    <?php foreach ( $news_body as $news ) : 

                        foreach ( $team_body as $team ) {

                            if ( $team->TeamID != $news->TeamID )
                                continue;

                                $teamName  = $team->Name;
                                $teamCity  = $team->City;
                                $teamFName = $team->FullName;
                                $teamLogo  = $team->WikipediaLogoUrl;
                                $teamColor = $team->PrimaryColor;

                        } ?>
                        <div>
                            <article class="uk-article">
                                <?php 
                                foreach ( $images as $image ) {

                                    if ( in_array( $teamName, explode(", ", $image->caption) ) ) {
                                        $imgID = $image->id;
                                    } 

                                    if ( $teamName == $image->title ) {
                                        $imgID = $image->id;
                                    }

                                } ?>
                                <a href="<?php echo esc_html( site_url('/article/'.strtolower($leagueName).'?newsID=') . $news->NewsID .'&imgID=' . $imgID ); ?>">
                                    <?php echo wp_get_attachment_image( $imgID, 'medium' ); ?>
                                </a>
                                <figure style="background-color:<?php echo '#'.$teamColor; ?>;">
                                    <div>
                                        <img src="<?php echo $teamLogo; ?>" alt="<?php echo $teamFName; ?>">
                                    </div>
                                    <span>
                                        <?php echo !empty($teamFName) ? $teamFName : $teamCity .' '. $teamName ; ?>
                                    </span>
                                </figure>
                                <h3><a href="<?php echo esc_html( site_url('/article/'.strtolower($leagueName).'?newsID=') . $news->NewsID .'&imgID=' . $imgID ); ?>"><?php echo $news->Title; ?></a></h3>
                                <div class="uk-text-meta">
                                    <span>
                                        <?php echo $leagueName; ?>
                                    </span>
                                    <span>&#x25cf</span>
                                    <span>
                                    <?php  
                                        $time = date_create($news->Updated);
                                        $time = date_format($time, 'D, F j, Y');

                                        echo $time;
                                    ?>
                                    </span>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            <!-- End Content -->
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
            <?php

                // get_template_part( widget.'guides' );
            
            ?>
            </div>

        </div>
    </div>
</main>