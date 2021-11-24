<?php $authors = [ 'role' => 'author' ];
$authorsQuery = new WP_User_Query( $authors ); ?>

<div id="cappers-standings" class="uk-flex-top" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Cappers' Standings</h2>
        </div>
        <div class="uk-modal-body" uk-overflow-auto>
            
            <table class="uk-table uk-table-divider uk-table-responsive">
                <thead class="uk-light">
                    <tr>
                        <td width="50%">Capper's Name</td>
                        <td width="25%">Last 10 Games</td>
                        <td width="25%">Win Percentage</td>
                    </tr>
                </thead>
                <tbody>
                <?php if ( ! empty( $authorsQuery->results ) ) {

                    foreach ( $authorsQuery->results as $user ) {

                        // echo $user->roles[0] . ' - ' . $user->user_nicename . '<br>';
                        $posts = new WP_Query ([
                            'post_type'       => 'cappers_corner', // This is where we'll know if it's a capper
                            'author'          => $user->ID, // This is pulled from the loop
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

                            $postCount  = $posts->post_count; // Count Author Total Post base on Meta Query
                            $userID[]   = $posts->post->post_author; // Get author ID in array format

                        endwhile; 
                    }

                    // start foreach loop
                    asort($authorsQuery->results);
                    foreach ( $authorsQuery->results as $user ) :  

                        $loopCappers = new WP_Query([ 'post_type' => 'cappers_corner', 'posts_per_page' => -1 ]);
                        while ( $loopCappers->have_posts() ) : $loopCappers->the_post();

                            $user_post_count = count_user_posts( $user->ID, 'cappers_corner' ); // Count all current author post

                        endwhile; wp_reset_postdata();

                        // Set the number of Correct per Post from User
                        if ( in_array( $user->ID, $userID ) ) {
                            $correct = $postCount;
                        } else {
                            $correct = 0;
                        }

                        // Set the number of Incorrect
                        $wrong = ((int)$user_post_count - (int)$correct);

                        // Get user post count
                        if ( $user_post_count != '0' ) :

                        $percent = ((int)$correct / (int)$user_post_count * 100); ?>
                        <tr>
                            <td>
                                <a class="uk-width-auto"><img src="<?php echo get_avatar_url($user->ID); ?>" class="uk-border-rounded" alt="<?php echo get_the_author_meta('nicename', $user->ID); ?>" width="40px" height="40px"></a>
                                <h4><?php echo $user->user_nicename; ?></h4>
                            </td>
                            <td><span class="bracket-winloss"><?php echo $correct; ?> - <?php echo abs($wrong); ?></span></td>
                            <td><span class="bracket-winpercentage"><?php echo number_format((float)$percent, 2, '.', ''); ?>%</span></td>
                        </tr>
                        <?php endif;

                    endforeach;
                    // end foreach loop

                } else { ?>
                    <tr>
                        <td colspan="3" align="center" width="100%" style="display:table-cell;">
                            <h4 class="uk-text-center uk-text-danger">No Cappers Record Found.</h4>
                        </td>
                    </tr>
                <?php } // end if ?>
                </tbody>
            </table>

        </div>
    </div>
</div>