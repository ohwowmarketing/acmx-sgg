<ul class="--cappers-wrapper" uk-accordion="active: false; content: > .uk-card .uk-card-body; toggle: > .uk-card .uk-card-header .uk-grid .-toggle-me">
<?php 
$sticky = get_option( 'sticky_posts' );
$sticky = array_slice( $sticky, -1, 1 );
$loopCappers = new WP_Query([ 'post_type' => 'cappers_corner', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => -1 ]);

while ( $loopCappers->have_posts() ) : $loopCappers->the_post();
    // Get author ID base on Post
    $author   = get_the_author_meta('ID');

    // ACF Fields
    $activate   = get_field( 'gamepick_activation' );
    $notif      = get_field( 'message_alert' );
    $msgnotif   = get_field( 'msg_notification' );
    
    // Brackets
    $correct  = get_field('win_bracket', 'user_'.$author.'');
    $wrong    = get_field('loss_bracket', 'user_'.$author.'');
    $winpct   = get_field('win_pct', 'user_'.$author.'');

    if ( $sticky[0] == get_the_ID() ) {
        $stickyClass = 'uk-open';
    } else {
        $stickyClass = '';
    } ?>
    <li class="--cappers-profile <?php echo $stickyClass; ?>">
        <div class="uk-card uk-card-default uk-card-small">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-top uk-flex-between uk-flex-middle" uk-grid>
                    <div class="uk-width-expand uk-grid-small -toggle-me" uk-grid>
                        <div class="uk-width-auto">
                            <img src="<?php echo get_avatar_url($author); ?>" class="uk-border-rounded" alt="<?php echo get_the_author_meta('display_name', $author); ?>" width="40px" height="40px">
                        </div>
                        <div class="uk-width-expand">
                            <small><?php echo get_the_author_meta('display_name'); ?></small>
                            <h4><?php the_title(); ?></h4>
                        </div>
                    </div>
                    <?php 
                    if ( $notif ) : ?>
                    <div class="uk-width-auto --cappers-action" uk-scrollspy="target: #msgnotif; cls: uk-animation-fade uk-animation-shake; delay: 2500">
                        <a href="javascript:void(0)" id="msgnotif" uk-tooltip title="Hi! I have a quick message." onclick="UIkit.notification({pos: 'top-right', timeout: 10000, message: '<img src=\'<?php echo get_avatar_url($author); ?>\' width=\'40\' alt=\'<?php echo get_the_author_meta('display_name', $author); ?>\' > <?php echo addslashes($msgnotif); ?>'})"><!-- &nbsp; --></a>
                    </div>
                    <?php endif; ?>
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
                        Win PCT: <strong><?php echo number_format((float)$winpct, 2, '.', ''); ?>%</strong>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <?php endwhile; 
wp_reset_postdata(); ?>
</ul>