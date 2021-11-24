<ul class="--cappers-wrapper" uk-accordion="active: false; content: > .uk-card .uk-card-body; toggle: > .uk-card .uk-card-header">
<?php 
// Get all users from Cappers Role
$users = get_users( [ 'role__in' => [ 'cappers', 'author' ] ] );

foreach( $users as $user ) {

    $posts = new WP_Query ([
        'post_type'       => 'cappers_corner', // This is where we'll know if it's a capper
        'author'          => $user->id, // This is pulled from the loop
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

    while ($posts->have_posts()) : $posts->the_post();

        $postCount = $posts->post_count; // Count Author Total Post base on Meta Query
        $userID    = $posts->post->post_author; // Get author ID

    endwhile;
}

$sticky = get_option( 'sticky_posts' );
$loopCappers = new WP_Query([ 'post_type' => 'cappers_corner', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => -1 ]);

while ( $loopCappers->have_posts() ) : $loopCappers->the_post();

    // ACF Fields
    $activate   = get_field( 'gamepick_activation' );
    $pin        = get_field( 'gamepick_pinpost' );
    
    $author = get_the_author_meta('ID'); // Get author ID base on Post
    $user_post_count = count_user_posts( $author, 'cappers_corner' ); // Count all current author post
    $prediction = get_post_meta(get_the_ID(), 'gamepick_prediction', true);

    // Check if Prediction matched
    // If not, throw 0 from Incorrect meta_query
    if ( $prediction != 1 ) {
        $correct = 0;
    } else {
        $correct = $postCount;
    }

    // Calculate the rest of the predictions
    $wrong = ((int)$user_post_count - (int)$correct);
    $percent = ((int)$correct / (int)$user_post_count * 100);

    if ( $sticky[0] == get_the_ID() ) {
        $stickyClass = 'uk-open';
    } else {
        $stickyClass = '';
    } ?>
    <li class="--cappers-profile <?php echo $stickyClass; ?>">
        <div class="uk-card uk-card-default uk-card-small">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-top uk-flex-between uk-flex-middle" uk-grid>
                    <div class="uk-width-auto">
                        <img src="<?php echo get_avatar_url($author); ?>" class="uk-border-rounded" alt="<?php echo get_the_author_meta('nicename', $author); ?>" width="40px" height="40px">
                    </div>
                    <div class="uk-width-expand">
                        <small><?php echo get_the_author_meta('nicename'); ?></small>
                        <h4><?php the_title(); ?></h4>
                    </div>
                    <div class="uk-width-auto --cappers-action" hidden>
                        <a href="javascript:void(0)" title="View More Info"><!-- &nbsp; --></a>
                    </div>
                </div>
            </div>
            <div class="uk-card-body">
                <div class="uk-grid-collapse uk-flex-between uk-grid-match" uk-grid>
                    <div class="uk-width-expand --profile-action">
                        <a href="<?php the_permalink(); ?>">Read capper’s analysis</a>
                    </div>
                    <div class="--modal-action">
                        <a href="#cappers-standings" uk-toggle><span class="uk-visible@s">Cappers’ Standings</span></a>
                    </div>
                </div>
                <div class="uk-grid-collapse uk-flex-middle uk-flex-between" uk-grid>
                    <div class="uk-width-auto --cappers-stats">
                        Capper's Record: <strong><?php echo $correct; ?> - <?php echo abs($wrong); ?></strong>
                    </div>
                    <div class="uk-width-auto --cappers-stats">
                        Win PCT: <strong><?php echo number_format((float)$percent, 2, '.', ''); ?>%</strong>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <?php endwhile; 
wp_reset_postdata(); ?>
</ul>