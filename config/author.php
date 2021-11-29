<?php

// acf_form_head();

// List all Cappers
$users = get_users( [ 'role__in' => [ 'cappers' ] ] );

// Create Loop for Cappers Corner Posts
foreach( $users as $user ) {

    // Set postID
    $post_id   = 'user_'.$user->ID;

    // Return if Post is True
    $postTrue = get_posts ([

        'post_type'   => 'cappers_corner',
        'author'      => $user->ID,
        'meta_query'  => [
            [
                'key'     => 'gamepick_prediction',
                'value'   => 'correct',
                // 'compare' => 'BETWEEN'
            ]
        ]

    ]);

    // Count Post of Correct
    $countTrue = count($postTrue);

    // Update author's Win Bracket
    update_field( 'field_61a32cd4f3b13', $countTrue, $post_id );

    //
    // End $postTrue
    //

    // Return if Post is Wrong
    $postFalse = get_posts ([

        'post_type'   => 'cappers_corner',
        'author'      => $user->ID,
        'meta_query'  => [
            [
                'key'     => 'gamepick_prediction',
                'value'   => 'wrong',
                // 'compare' => 'BETWEEN'
            ]
        ]

    ]);

    // Count Post of Wrong
    $countFalse = count($postFalse);

    // Update author's Win Bracket
    update_field( 'field_61a32cebf3b14', $countFalse, $post_id );

    //
    // End $postFalse
    //

    // Return if Post is Empty
    $postEmpty = get_posts ([

        'post_type'   => 'cappers_corner',
        'author'      => $user->ID,
        'meta_query'  => [
            [
                'key'     => 'gamepick_prediction',
                'value'   => '',
                // 'compare' => 'BETWEEN'
            ]
        ]

    ]);    

    // Count Post of Empty
    $countEmpty = count($postEmpty);

    // Update author's No Rate Bracket
    update_field( 'field_61a45eba0db9b', $countEmpty, $post_id );

    //
    // End $postEmpty
    //

    // Calculate the Total Win & Loss + Create Win Percentage
    $winBracket  = get_field('win_bracket', 'user_'.$user->ID.'');
    $lossBracket = get_field('loss_bracket', 'user_'.$user->ID.'');
    $emptyBracket = get_field('empty_bracket', 'user_'.$user->ID.'');

    $totalPosts = (int)$winBracket + (int)$lossBracket + (int)$emptyBracket;

    if ( $emptyBracket ) {

        $winpct = (int)$winBracket / ((int)$totalPosts - (int)$emptyBracket) * 100;

    } else {

        $winpct = (int)$winBracket / (int)$totalPosts * 100;

    }

   update_field( 'field_61a4037490f67', $winpct, $post_id );

}

































