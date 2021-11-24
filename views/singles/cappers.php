<?php

// Get Author Meta
$author_id = get_the_author_meta( 'ID' );

$posts = new WP_Query ([
    'post_type'       => 'cappers_corner', // This is where we'll know if it's a capper
    'author'          => $author_id, // This is pulled from the loop
    'post_status'     => 'publish', // Make sure all are published
    'meta_query'      => [
        [ 
            'key'     => 'gamepick_prediction', 
            'value'   => 1, 
            'compare' => '=', 
            'type'    => 'NUMERIC' 
        ],
    ],
]);

// echo '<pre>';
// print_r($posts);
// echo '</pre>';

while ($posts->have_posts()) : $posts->the_post();

    $postCount = $posts->post_count; // Count Author Total Post base on Meta Query
    $userID    = $posts->post->post_author; // Get author ID

endwhile;

$loopCappers = new WP_Query([ 'post_type' => 'cappers_corner', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => -1 ]);
while ( $loopCappers->have_posts() ) : $loopCappers->the_post();

    $author = get_the_author_meta('ID'); // Get author ID base on Post
    $user_post_count = count_user_posts( $author, 'cappers_corner' ); // Count all current author post
    $prediction = get_post_meta(get_the_ID(), 'gamepick_prediction', true);


    // Check if Prediction matched
    // If not, throw 0 from Incorrect meta_query
    if ( $postCount != 1 ) {
        $correct = 0;
    } else {
        $correct = $postCount;
    }

    // Calculate the rest of the predictions
    $wrong = ((int)$user_post_count - (int)$correct);
    $percent = ((int)$correct / (int)$user_post_count * 100);

endwhile; wp_reset_postdata();
?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">

                <div class="uk-card uk-card-body" data-card="cappers-stats">
                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-auto">
                            <a class="avatar">
                                <img src="<?php echo get_avatar_url($author_id); ?>" alt="<?php echo get_the_author_meta('nicename', $author); ?>" width="120px" height="120px">
                            </a>
                        </div>
                        <div class="uk-widht-expand">
                            <small class="author"><?php echo get_the_author_meta('nicename', $author_id); ?></small>
                            <h1><?php the_title(); ?></h1>
                            <div class="uk-child-width-auto" uk-grid>
                                <div>
                                    <div class="uk-panel">
                                        Capper’s Record
                                        <span class="uk-display-block value"><?php echo $correct; ?>–<?php echo abs($wrong); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="uk-panel">
                                        Win PCT
                                        <span class="uk-display-block value"><?php echo number_format((float)$percent, 2, '.', ''); ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="--modal-action">
                        <a href="#cappers-standings" uk-toggle><span class="uk-visible@s">Cappers’ Standings</span></a>
                    </div>
                </div>
                
                <div class="uk-card uk-card-default" data-card="cappers-analysis">
                    <div class="uk-card-header">
                        <h4><?php echo get_the_author_meta('nicename', $author_id); ?></h4>
                        <time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date('F j, Y'); ?></time>
                    </div>
                    <div class="uk-card-body">
                        <?php the_field( 'cappers_analysis' ); ?>
                    </div>
                </div>

                <!-- Chat Plugin Here -->
                <div class="uk-card uk-card-default uk-card-body" data-card="cappers-chat">
                    <?php echo do_shortcode('[cappers-corner-chat]'); ?>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <?php get_template_part( widget . 'news' ); ?>
            </div>
        </div>
    </div>
</main>

<!-- Cappers Standing -->
<?php get_template_part( widget . 'cappers-standings' ); ?>