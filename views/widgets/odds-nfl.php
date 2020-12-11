<?php
// Get Parent Name
$leagueName = get_the_title( $post->post_parent );

// Include API Keys
include( locate_template( includes.'league-keys.php', false, true ) );

function json_response($url, $headers) {
    return json_decode( wp_remote_retrieve_body( wp_remote_get( $url, $headers ) ) );
}

$team_body = json_response('https://api.sportsdata.io/v3/nfl/scores/json/teams', $nfl_header_dak);
$current_season = json_response('https://api.sportsdata.io/v3/nfl/scores/json/CurrentSeason', $nfl_header_dak);
$current_week = json_response('https://api.sportsdata.io/v3/nfl/scores/json/CurrentWeek', $nfl_header_dak);

$selected_week = $current_season . '/' . $current_week;
if ( $current_week > 17 ) {
    $post_week = $current_week - 17;
    $selected_week = $current_season . 'POST/' . $post_week;
}
$url = 'https://api.sportsdata.io/v3/nfl/odds/json/GameOddsByWeek/' . $selected_week;
$gameoddsbydate_body = json_response($url, $nfl_header_opk);

$week_options = '<option disabled>Choose Schedule</option>';
for ($i = 1; $i < 18; $i++) {
    $week_options .= '<option ';
    if ($selected_week == $i) {
        $week_options .= 'selected ';
    }
    $week_options .= 'value="' . $current_season . '/' . $i . '">Week ' . $i . '</option>';
}
for ($i = 1; $i < 5; $i++) {
    $week_options .= '<option ';
}

$weeks = [];
for ($i = 1; $i < 18; $i++) {
    $weeks[$current_season . '/' . $i] = 'Week ' . $i;
}
$weeks[$current_season . 'POST/1'] = 'Wild Card';
$weeks[$current_season . 'POST/2'] = 'Division Round';
$weeks[$current_season . 'POST/3'] = 'Conference Championship';
$weeks[$current_season . 'POST/4'] = 'Super Bowl';
$weeks[$current_season . 'STAR/1'] = 'Pro Bowl';

$sportsbooks = [ 
    [ 
        'id' => 'RiversCasinoPA',
        'display' => 'BetRivers',
        'badge' => _uri . '/resources/images/sportsbooks/betrivers.jpg',
        'state' => [
            'PA' => 'https://wlsugarhouseaffiliates.adsrv.eacdn.com/C.ashx?btag=a_3320b_380c_&affid=947&siteid=3320&adid=380&c=',
            'IL' => 'http://wlsugarhouseaffiliates.adsrv.eacdn.com/C.ashx?btag=a_4043b_817c_&affid=1142&siteid=4043&adid=817&c='
        ]
    ], 
    [ 
        'id' => 'UnibetNJ',
        'display' => 'Unibet',
        'badge' => _uri . '/resources/images/sportsbooks/unibet.jpg',
        'state' => [
            'PA' => 'https://wlkindred.adsrv.eacdn.com/C.ashx?btag=a_783b_150c_&affid=195&siteid=783&adid=150&c='
        ]
    ],
    [ 'id' => 'DraftKings', 'display' => 'DraftKings' ], 
    [ 'id' => 'FanDuel', 'display' => 'FanDuel' ], 
    [ 'id' => 'ParxPA', 'display' => 'Parx' ] 
];

$available = [];

if ( isset( $gameoddsbydate_body ) && count( $gameoddsbydate_body ) > 0 ) {
    foreach ( $gameoddsbydate_body as $single ) {
        if ( isset( $single->PregameOdds ) && 
            is_array( $single->PregameOdds ) &&
            count( $single->PregameOdds ) > 0 )  {
            $odds = $single->PregameOdds;
            if ( is_array( $odds ) && count ( $odds ) > 0 ) {
                foreach ( $odds as $odd ) {
                    if ( ! in_array( $odd->Sportsbook, $available ) ) {
                        $available[] = $odd->Sportsbook;
                    }
                }
            }
        }
    }
}

function getSportsbookById( $needle, $haystack ) {
    for ( $i = 0; $i < count( $haystack ); $i++ ) {
        if ( $haystack[ $i ] === $needle ) {
            return $haystack[ $i ];
        }
    }
}

function bookline( $spread, $payout, $link, $sportsbook ) {
?>
<div class="odds-sb-bookline">
    <?php if ( $link !== '' ) : ?>
    <a href="<?php echo $link; ?>">
        <span class="sb-bookline-extlink">
            <span>
                <?php echo ($spread < 0) ? $spread : '+'.$spread; ?>
                <small class="uk-margin-small-left"><?php echo $payout ?></small>
            </span>
            <span class="sb-extlink-hover">
                <svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
                <span>Bet Now</span>
            </span>
        </span>
    </a>
    <?php else : ?>
    <span class="sb-bookline-extlink">
        <span>
            <?php echo ($spread < 0) ? $spread : '+'.$spread; ?>
            <small class="uk-margin-small-left"><?php echo $payout ?></small>
        </span>
    </span>
    <?php endif; ?>
</div>
<?php }

function naBookline() {
?>
<td class="sportsbook-panel">
    <div class="uk-panel">
        <div class="odds-sb-bookline">
            <div class="uk-background-muted sb-bookline-extlink">
                <span class="uk-text-muted uk-text-small">N/A</span>
            </div>
        </div>
        <div class="odds-sb-bookline">
            <div class="uk-background-muted sb-bookline-extlink">
                <span class="uk-text-muted uk-text-small">N/A</span>
            </div>
        </div>
    </div>
</td>
<?php
}

function sportsbookPanel( $odds, $sportsbook ) { 
    if ( isset( $_COOKIE['state_abbr'] ) && isset( $sportsbook['state'] ) && in_array( $_COOKIE['state_abbr'], array_keys( $sportsbook['state'] ) ) ) {
        $link = $sportsbook['state'][ $_COOKIE['state_abbr'] ];
    } else {
        $link = '';
    }
?>
<td class="sportsbook-panel">
    <div class="uk-panel">
        <?php bookline( $odds->AwayPointSpread, $odds->AwayPointSpreadPayout, $link, $sportsbook ); ?>
        <?php bookline( $odds->HomePointSpread, $odds->HomePointSpreadPayout, $link, $sportsbook ); ?>
    </div>
</td>
<?php 
}

function consensusOdds( $spread ) {
?>
    <div class="odds-consensus">
        <?php echo ( $spread > 0 ) ? '+' . $spread : $spread; ?>
    </div>
<?php
}

function consensusPanel( $single ) {
?>
<div class="uk-panel">
    <?php consensusOdds( $single->AwayPointSpread ); ?>
    <?php consensusOdds( $single->HomePointSpread ); ?>
</div>
<?php
}
?>

<script type="text/javascript">
// variables for saving the state of the page
var responseGameOdds = <?php echo json_encode($gameoddsbydate_body); ?>;
var teamsObj = <?php echo json_encode($team_body); ?>;
var teamsHashByID = {};
var oddsType = "Spread";

// League specific valiables
// change Url for each League
var apiLeagueUrl = "https://api.sportsdata.io/v3/nfl/odds/json/GameOddsByWeek/";
var headerValue = "2a2e46fcc4504134aadced092416ba1e";

// Odds Type dropdown onchange event handler
function updateOddsWeek(oType) {
	// initialize teams Hash
	buildTeamsHash(teamsObj);
	makeNewOddsRequest(apiLeagueUrl + oType.value);
}
</script>

<?php

    // include odds Ajax Scripts
    include "oddsAjaxInc.php";

?>

<div uk-grid class="uk-flex-between uk-flex-middle uk-margin-bottom odds-locations">
    <?php do_action( 'odds_nav', 'nfl' ); ?>
    <?php do_action( 'odds_location', 'nfl' ); ?>
</div>

<div class="odds-filter">
    <div uk-grid class="uk-grid-small uk-child-width-1-1 uk-child-width-expand@m uk-light">
        <div class="odds-search">
          <div class="uk-search uk-search-default uk-width-1-1">
              <span uk-search-icon></span>
              <input type="search" id="searchOdds" class="uk-search-input" placeholder="Search..." onkeyup="searchTeam()">
          </div>  
        </div>
        <div class="odds-schedule">
            <select class="uk-select" placeholder="Odds Schedule" onchange="updateOddsWeek(this);">
                <?php foreach( $weeks as $week_value => $week_display ): ?>
                    <option <?php echo ($week_value === $selected_week) ? 'selected ' : ''; ?>value="<?php echo $week_value; ?>"><?php echo $week_display; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="odds-type">
            <select id="typeOdds" class="uk-select" name="typeOdds" onchange="updateOddsType(this);">
                <option disabled>Choose Odds Type</option>
                <option selected value="Spread">Spread</option>
                <option value="Total">Total</option>
                <option value="Moneyline">Moneyline</option>
            </select>
        </div>
    </div>
</div>

<div class="uk-position-relative">
    <div class="uk-overflow-auto">
        <!-- Start Table -->
        <table id="odds-list" class="uk-table uk-table-divider">

            <thead>
                <tr>
                    <th>
                        <div class="team-label">
                            <?php echo $leagueName; ?>
                        </div>
                    </th>
                    <th width="120"><span>Consensus</span></th>
                    <?php foreach ( $sportsbooks as $sportsbookHeading ) : ?>
                        <th width="120">
                            <span>
                                <?php if ( isset( $_COOKIE['state_abbr'] ) && isset( $sportsbookHeading['state'] ) && in_array( $_COOKIE['state_abbr'], array_keys( $sportsbookHeading['state'] ) ) ) : ?>
                                    <a href="<?php echo $sportsbookHeading['state'][ $_COOKIE['state_abbr'] ]; ?>" target="_blank">
                                <?php endif; ?>
                                    
                                    <?php if ( isset( $_COOKIE['state_abbr'] ) && isset( $sportsbookHeading['state'] ) && in_array( $_COOKIE['state_abbr'], array_keys( $sportsbookHeading['state'] ) ) && isset( $sportsbookHeading['badge'] ) ) : ?>
                                        <img src="<?php echo $sportsbookHeading['badge']; ?>" width="120" height="40" alt="<?php echo $sportsbookHeading['display'] ?>">
                                    <?php else : ?>
                                        <?php echo $sportsbookHeading['display']; ?>
                                    <?php endif; ?>

                                <?php if ( isset( $_COOKIE['state_abbr'] ) && isset( $sportsbookHeading['state'] ) && in_array( $_COOKIE['state_abbr'], array_keys( $sportsbookHeading['state'] ) ) ) : ?>
                                    </a>
                                <?php endif; ?>
                            </span>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody id="odds-list-body">
            <?php  if ( isset( $gameoddsbydate_body ) ) : ?>
                <?php foreach ( $gameoddsbydate_body as $gameodd ) : ?>
                <tr>
                    <td>
                        <div class="team-panel">
                        <?php if ( isset( $team_body ) ) : ?>
                            <?php foreach ( $team_body as $team ) {

                            if ( $gameodd->AwayTeamId != $team->TeamID )
                                continue;

                                $AwayTeamName = $team->Name;
                                $AwayTeamLogo = $team->WikipediaLogoUrl;

                            }

                            foreach ( $team_body as $team ) {

                                if ( $gameodd->HomeTeamId != $team->TeamID )
                                    continue;

                                    $HomeTeamName = $team->Name;
                                    $HomeTeamLogo = $team->WikipediaLogoUrl;

                            } ?>
                            <div class="uk-panel">
                                <div class="odds-away">
                                    <div class="odds-away-team">
                                        <img src="<?php echo esc_url($AwayTeamLogo); ?>" height="24" alt="<?php echo $AwayTeamName; ?>">
                                        <?php echo $AwayTeamName; ?>
                                    </div>
                                    <div class="odds-away-score"><?php echo ( $gameodd->AwayTeamScore ) ? $gameodd->AwayTeamScore : '' ; ?></div>
                                </div>
                                <div class="odds-home">
                                    <div class="odds-home-team">
                                        <img src="<?php echo esc_url($HomeTeamLogo); ?>" height="24" alt="<?php echo $HomeTeamName; ?>">
                                        <?php echo $HomeTeamName; ?>
                                    </div>
                                    <div class="odds-home-score"><?php echo ( $gameodd->HomeTeamScore ) ? $gameodd->HomeTeamScore : '' ; ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        </div>
                    </td>
                    <td class="consensus-panel">
                        <?php  if ( ! empty( $gameodd->PregameOdds ) ) : 
                            foreach ( $gameodd->PregameOdds as $single ) :
                                if ( $single->Sportsbook === 'Consensus' ) :
                                    consensusPanel( $single );
                                    break;
                                endif;
                            endforeach;
                        endif; ?>
                    </td>
                    
                    <?php 
                    if ( ! empty( $gameodd->PregameOdds ) ) : 
                        foreach ( $sportsbooks as $sportsbookItem ) :
                            $found = false;
                            foreach ( $gameodd->PregameOdds as $odds) :
                                if ( $odds->Sportsbook === $sportsbookItem['id'] ) :
                                    $found = true;
                                    sportsbookPanel( $odds, $sportsbookItem );
                                endif;
                            endforeach;
                            if ( ! $found ) {
                                naBookline();
                            }
                        endforeach;
                    
                    else : 
                        foreach ( $sportsbooks as $sportsbookTemp ) : 
                            if ( in_array( $sportsbookTemp['id'], $available ) ) {
                                naBookline();
                            }
                        endforeach;
                    endif; ?>
                </tr>
                <tr class="schedule-row">
                    <td colspan="1" class="schedule-panel">
                    <?php
                        $date_set = strtotime($gameodd->DateTime);
                        $date_set = date('D n/d, g:i A', $date_set);

                        echo '<div>'. $date_set .'| Status: '. $gameodd->Status .'</div>';
                    ?>
                    </td>
                    <td colspan="6">
                        <div>&nbsp;</div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>

        </table>
        <!-- End Table -->
    </div>
</div>