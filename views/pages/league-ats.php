<?php         
switch ( $post->post_parent ) { 
    case '23': $league = 'nfl'; break;
    case '25': $league = 'nba'; break;
    case '27': $league = 'mlb'; break;
    default: $league = ''; break;
}
?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-container uk-container-xlarge">
            <div class="uk-card uk-card-default uk-card-body" data-card="odds-ats">
                <div class="uk-flex uk-flex-between uk-flex-middle">
                    <h1 class="uk-card-title"><?php echo get_the_title( $post->post_parent ) . ' Against the Spread'; ?></h1>
                </div>
                <div class="uk-position-relative">
                    <div class="uk-overflow-auto">
                        <table id="ats-table" class="uk-table uk-table-divider" data-league="<?php echo $league; ?>">
                            <thead>
                                <tr>
                                    <th class="team-label">Team</th>
                                    <th>Overall</th>
                                    <?php if ( $league !== 'nfl' ) : ?>
                                    <th>Home</th>
                                    <th>Away</th>
                                    <?php endif; ?>
                                    <th><small>Last 10 Games</small> ATS Home</th>
                                    <th><small>Last 10 Games</small> ATS Away</th>
                                    <th><small>Last 10 Games</small> OV/UN Home</th>
                                    <th><small>Last 10 Games</small> OV/UN Away</th>
                                </tr>
                            </thead>
                            <tbody><tr><td>Loading&hellip;</td></tr></tbody>
                        </table>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</main>