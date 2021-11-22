<?php 

// Get All Cappers Role
$args = [ 'role' => 'cappers', 'orderby' => 'user_nicename', 'order' => 'DESC' ];
$cappersRole = get_users( $args );

?>
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
                    <?php foreach ( $cappersRole as $role ) : ?>
                    <tr>
                        <td>
                            <a class="uk-width-auto"><?php echo get_avatar( $role->user_id, 40, '', $role->user_nicename, [ 'class' => 'uk-border-circle' ] ); ?></a>
                            <h4><?php echo esc_html( $role->user_nicename ); ?></h4>
                        </td>
                        <td><span class="bracket-winloss">8 - 2</span></td>
                        <td><span class="bracket-winpercentage">80 %</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>