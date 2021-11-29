<?php $authors = [ 'role' => 'cappers' ];
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


                    // start foreach loop
                    asort($authorsQuery->results);
                    foreach ( $authorsQuery->results as $user ) :  

                        $correct  = get_field('win_bracket', 'user_'.$user->ID.'');
                        $wrong    = get_field('loss_bracket', 'user_'.$user->ID.'');
                        $winpct   = get_field('win_pct', 'user_'.$user->ID.'');

                         ?>
                        <tr>
                            <td>
                                <a class="uk-width-auto"><img src="<?php echo get_avatar_url($user->ID); ?>" class="uk-border-rounded" alt="<?php echo get_the_author_meta('nicename', $user->ID); ?>" width="40px" height="40px"></a>
                                <h4><?php echo $user->user_nicename; ?></h4>
                            </td>
                            <td><span class="bracket-winloss"><?php echo $correct; ?> - <?php echo abs($wrong); ?></span></td>
                            <td><span class="bracket-winpercentage"><?php echo number_format((float)$winpct, 2, '.', ''); ?>%</span></td>
                        </tr>
                        <?php

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