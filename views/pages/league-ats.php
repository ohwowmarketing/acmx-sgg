<?php         
switch ( $post->post_parent ) { 
    case '23': $league = 'nfl'; break;
    case '25': $league = 'nba'; break;
    case '27': $league = 'mlb'; break;
    default: $league = ''; break;
}
?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge" id="Contents">

        <div class="uk-card uk-card-default uk-card-body" data-card="odds-ats">

            <header class="uk-flex uk-flex-middle">
                <h1 class="uk-card-title"><?php echo get_the_title( $post->post_parent ) . ' Against the Spread'; ?></h1>
            </header>

            <div class="uk-position-relative">
                <div class="uk-overflow-auto">
                    <table hidden id="ats-table" class="uk-table uk-table-divider" data-league="<?php echo $league; ?>">
                        <thead>
                            <tr>
                                <th>
                                    <div class="team-label">Team</th>
                                </th>
                                <th width="120"><span>Overall</span></th>
                                <?php if ( $league !== 'nfl' ) : ?>
                                <th width="120"><span>Home</span></th>
                                <th width="120"><span>Away</span></th>
                                <?php endif; ?>
                                <th width="120"><span>ATS Home<br />(Last 10 Games)</span></th>
                                <th width="120"><span>ATS Away<br />(Last 10 Games)</span></th>
                                <th width="120"><span>OV/UN Home<br />(Last 10 Games)</span></th>
                                <th width="120"><span>OV/UN Away<br />(Last 10 Games)</span></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div id="table-loading" uk-spinner></div>
                </div>
            </div>

        </div>

    </div>
</main>