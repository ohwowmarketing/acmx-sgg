<?php
function odds_table_location( $curr_league = 'nfl' ) {
  $curr_league = strtolower( $curr_league );
  $valid_states = get_all_sportsbook_states();
  ?>
  <div class="uk-width-auto@m">
    <div class="button-select-wrapper">
      <button id="odd-location-btn" type="button" class="uk-button uk-button-outline">Choose Betting Location</button>
      <div uk-dropdown="mode: click">
        <ul class="uk-nav uk-dropdown-nav">
          <?php foreach ( $valid_states as $state_code => $full_state_name ) : ?>
            <?php $url = 'state/?state_abbr=' . $state_code;  ?>
            <li>
              <a href="<?php echo esc_url( site_url( $url ) ); ?>" target="_self" rel="noopener">
                <?php echo $full_state_name; ?>
              </a>
            </li>                    
          <?php endforeach; ?>
        </ul>
      </div>
    </div> 
  </div>
  <?php
}

function odds_table_nav( $curr_league = 'nba' ) {
  $curr_league = strtolower( $curr_league );
  // $leagues = [ 'nba', 'mlb', 'nfl' ]; // MLB is disabled
  $leagues = [ 'nba', 'nfl' ];
  ?>
  <div class="uk-width-expand@m">
    <ul class="uk-subnav uk-subnav-pill uk-subnav-divider odds-localnav" uk-margin>
      <?php foreach ( $leagues as $league ) : ?>
        <li<?php echo ( $league === $curr_league ) ? ' class="uk-active"' : ''; ?>>
          <a href="<?php echo esc_url( site_url(  $league . '/odds-betting-lines' ) ); ?>">
            <?php echo strtoupper( $league ); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php
}

function odds_table_schedule( $league = 'nfl', $week = '', $season = '' ) {
  $league = strtolower( $league );
  if ( $league ===  'nfl' ) :
    $weeks = [];
    for ($i = 1; $i < 18; $i++) {
        $weeks[ $season . '/' . $i ] = 'Week ' . $i;
    }
    $weeks[ $season . 'POST/1' ] = 'Wild Card';
    $weeks[ $season . 'POST/2' ] = 'Division Round';
    $weeks[ $season . 'POST/3' ] = 'Conference Championship';
    $weeks[ $season . 'POST/4' ] = 'Super Bowl';
    $weeks[ $season . 'STAR/1' ] = 'Pro Bowl';
    ?>
    <div class="odds-schedule">
      <select id="odds-schedule-selection" class="uk-select" placeholder="Odds Schedule">
        <?php foreach( $weeks as $week_value => $week_display ):

          // Odds Line Control from Widgets Admin
          $widget_schedule = get_field( 'activate_odds_schedule', 'option' );
          $widget_week     = get_field( 'odds_schedule_selection', 'option' );

          if ( $widget_schedule ) :
          $widget_week = $season.'/'.$widget_week;
          ?>
          <option <?php echo ($week_value === $widget_week) ? 'selected ' : ''; ?>value="<?php echo $week_value; ?>"><?php echo $week_display; ?></option>
          <?php else : ?>
          <option <?php echo ($week_value === $week) ? 'selected ' : ''; ?>value="<?php echo $week_value; ?>"><?php echo $week_display; ?></option>
          <?php endif; ?>
        <?php endforeach; ?>
      </select>
    </div>
  <?php else : ?>
    <div class="odds-schedule">
      <div>
        <button type="button" class="uk-icon-link _prevDay" uk-icon="triangle-left"></button>
        <span id="dateOdds"></span>
        <button type="button" class="uk-icon-link _nextDay" uk-icon="triangle-right"></button>
      </div>
    </div>
  <?php endif;
}

function odds_table_type( $curr_type = 'Spread' ) {
  $types = ['Spread', 'Total', 'Moneyline',];
  ?>
  <div class="odds-type">
    <select id="odds-type-selection" class="uk-select" name="typeOdds">
      <option disabled>Choose Odds Type</option>
      <?php foreach ( $types as $type ) : ?>
        <option <?php echo $curr_type === $type ? 'selected ' : ''; ?>value="<?php echo $type; ?>"><?php echo $type; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php
}

function odds_table_filter( $league, $week, $season ) {
  ?>
  <div class="odds-filter">
    <div uk-grid class="uk-grid-small uk-child-width-1-1 uk-child-width-expand@m uk-light">
      <div class="odds-search">
        <div class="uk-search uk-search-default uk-width-1-1">
          <span uk-search-icon></span>
          <input type="search" id="searchOdds" class="uk-search-input" placeholder="Search..." onkeyup="searchTeam()">
        </div>  
      </div>
      <?php odds_table_schedule( $league, $week, $season ); ?>
      <?php odds_table_type(); ?>
    </div>
  </div>
  <?php
}

function odds_table_head( $league, $sbs ) {
  ?>
  <thead>
    <tr>
      <th><div class="team-label"><?php echo strtoupper( $league ); ?></div></th>
      <th width="120"><span>Consensus</span></th>
      <?php foreach ( $sbs as $sb ) : ?>
        <?php $display = $sb['badge'] === NULL ? $sb['name'] : $sb['badge']; ?>
        <th width="120">
          <?php if ( $sb['url'] !== NULL ) : ?>
            <a href="<?php echo $sb['url']; ?>"><?php echo $display; ?></a>
          <?php elseif ( $sb['id'] ) : ?>
            <a href="#bet-now" class="hero-sb-bet-now" data-sbid="<?php echo $sb['id']; ?>"><?php echo $display; ?></a>
          <?php else : ?>
            <span><?php echo $sb['name']; ?></span>
          <?php endif; ?>
        </th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <?php
}

function odds_table_row_date_status( $date_time, $status ) {
  $d = new DateTime( $date_time );
  $sportsbooks = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ];
  $columns = 2; // teams + consensus
  query_posts( $sportsbooks );
  $dk_link = '';
  while ( have_posts() ) {
    the_post();
    if ( get_the_title() === 'DraftKings' ) {
      $dk_link = get_field( 'global_affiliate_link' );
    }
    $columns++;
  }
  ?>
  <tr class="schedule-row">
    <td colspan="<?php echo $columns; ?>" class="schedule-panel">
      <div>
        <?php echo $d->format( 'D n/d, g:i A' ); ?> | <?php echo $status; ?>&nbsp;
        <span class='odds-game-bet-now'>
        <?php if ( $dk_link !== '' ) : ?>
          <a href="<?php echo $dk_link; ?>" role="button" type="button" class="uk-button uk-button-default uk-button-small">
            <img src="<?php echo get_template_directory_uri(); ?>/resources/images/sportsbooks/dk-d-crown.png" width="75" height="75" class="dk" alt="DraftKings" />
            &nbsp;Bet Now
          </a>
        <?php endif; ?>
        </span>
      </div>
    </td>
  </tr>
  <?php
}

function odds_table_row_team_by_id( $id, $teams ) {
  if ( isset( $id ) && is_numeric( $id ) ) {
    foreach ( $teams as $team ) {
      if ( isset( $team->TeamID ) && (string) $id === (string) $team->TeamID ) {
        return [ 'name' => $team->Name, 'logo' => $team->WikipediaLogoUrl ];
      }
    }
  }
  
  return NULL;
}

function odds_table_row_team_score( $is_home, $name, $logo, $score = '' ) {
  $home_or_away = $is_home ? 'home' : 'away';
  ?>
  <div class="odds-<?php echo $home_or_away; ?>">
    <div class="odds-<?php echo $home_or_away; ?>-team">
      <img src="<?php echo esc_url( $logo ); ?>" height="24" alt="<?php echo $name; ?>">
      <?php echo $name; ?>
    </div>
    <div class="odds-<?php echo $home_or_away; ?>-score"><?php echo $score; ?></div>
  </div>
  <?php
}

function odds_table_row_team_panel( $teams = [], $home_id, $home_score, $away_id, $away_score ) {
  ?>
    <td>
      <div class="team-panel">
        <?php 
        if ( is_array( $teams ) && count( $teams ) > 0 && is_numeric($home_id) && is_numeric($away_id) ) : 
          $home = odds_table_row_team_by_id( $home_id, $teams );
          $away = odds_table_row_team_by_id( $away_id, $teams );
        ?>
          <div class="uk-panel">
            <?php odds_table_row_team_score( false, $away['name'], $away['logo'], $away_score ); ?>
            <?php odds_table_row_team_score( true, $home['name'], $home['logo'], $home_score ); ?>
          </div>
        <?php endif; ?>
      </div>
    </td>
  <?php
}

function odds_table_row_consensus_spread( $spread ) {
  ?>
    <div class="odds-consensus">
        <?php echo ( $spread > 0 ) ? '+' . $spread : $spread; ?>
    </div>
  <?php
}

function odds_table_row_consensus_panel( $pregame = [] ) {
  ?>
  <td class="consensus-panel">
    <?php if ( is_array( $pregame ) && count( $pregame ) > 0 ) : ?>
      <?php foreach ( $pregame as $odd ) : ?>
        <?php if ( $odd->Sportsbook === 'Consensus' ) : ?>
          <div class="uk-panel">
            <?php odds_table_row_consensus_spread( $odd->AwayPointSpread ); ?>
            <?php odds_table_row_consensus_spread( $odd->HomePointSpread ); ?>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </td>
  <?php
}

function odds_table_row_sportsbook_panel_not_found() {
  ?>
  <div class="odds-sb-bookline">
    <div class="uk-background-muted sb-bookline-extlink">
      <span class="uk-text-muted uk-text-small">N/A</span>
    </div>
  </div>
  <?php
}

function odds_table_row_sportsbook_panel_bookline( $values, $sb ) {
  if ( $values['spread'] === '' || $values['spread_payout'] === '' ) :
    odds_table_row_sportsbook_panel_not_found();
  else : 
    $hover = '<span class="sb-extlink-hover"><svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg><span>Bet Now</span></span>';
    $inner = '<span class="sb-value-spread">';
    if ($values['spread'] >= 0) {
      $inner .= '+';
    }
    $inner .= $values['spread'];
    $inner .= '<small class="uk-margin-small-left">' . $values['spread_payout'] . '</small></span>
      <span class="sb-value-moneyline" style="display: none;">';
    if ($values['moneyline'] >= 0) {
      $inner .= '+';
    }
    $inner .= $values['moneyline'];
    $inner .= '<small class="uk-margin-small-left">ML</small></span>
      <span class="sb-value-total" style="display: none;">';
    if ($values['over_under'] >= 0) {
      $inner .= '+';
    }
    $inner .= $values['over_under'];
    $inner .= '<small class="uk-margin-small-left">' . $values['over_under_payout'] . '</small></span>';
    ?>
    <div class="odds-sb-bookline">
      <?php if ( $sb['url'] !== NULL ) : ?>
      <a class="sb-bookline-extlink odds-data-sportsbook-<?php echo $sb['id']; ?>" href="<?php echo $sb['url']; ?>">
        <?php echo $inner; ?>
        <?php echo $hover; ?>
      </a>
      <?php elseif ( $sb['id'] !== NULL )  : ?>
        <a class="sb-bookline-extlink odds-data-sportsbook-<?php echo $sb['id']; ?> hero-sb-bet-now" data-sbid="<?php echo $sb['id']; ?>" href="#bet-now">
        <?php echo $inner; ?>
        <?php echo $hover; ?>
      </a>
      <?php else : ?>
      <a class="sb-bookline-extlink" href="#">
        <?php echo $inner; ?>
      </a>
      <?php endif; ?>
    </div>
  <?php endif;
}

function odds_table_row_sportsbook_panel_item( $odd, $sb ) {

  $away = [
    'spread' => $odd->AwayPointSpread,
    'spread_payout' => $odd->AwayPointSpreadPayout,
    'moneyline' => $odd->AwayMoneyLine,
    'over_under' => $odd->OverUnder,
    'over_under_payout' => $odd->OverPayout
  ];

  $home = [
    'spread' => $odd->HomePointSpread,
    'spread_payout' => $odd->HomePointSpreadPayout,
    'moneyline' => $odd->HomeMoneyLine,
    'over_under' => $odd->OverUnder,
    'over_under_payout' => $odd->UnderPayout
  ];
  ?>
  <td class="sportsbook-panel">
    <div class="uk-panel">
      <?php odds_table_row_sportsbook_panel_bookline( $away, $sb ); ?>
      <?php odds_table_row_sportsbook_panel_bookline( $home, $sb ); ?>
    </div>
  </td>
  <?php
}

function odds_table_row_sportsbook_panel( $pregame, $sbs ) {
  foreach ( $sbs as $sb ) {
    $found = false;
    foreach ( $pregame as $odd ) {
      if ( $odd->Sportsbook === $sb['sdio'] ) {
        $found = true;
        odds_table_row_sportsbook_panel_item( $odd, $sb );
      }
    }
    if ( ! $found ) : ?>
      <td class="sportsbook-panel">
        <div class="uk-panel">
          <?php odds_table_row_sportsbook_panel_not_found(); ?>
          <?php odds_table_row_sportsbook_panel_not_found(); ?>
        </div>
      </td>
    <?php endif;
  }
}

function odds_table_row( $odd, $teams, $sbs ) {
  ?>
  <tr>
    <?php 
    odds_table_row_team_panel($teams, $odd->HomeTeamId, $odd->HomeTeamScore, $odd->AwayTeamId, $odd->AwayTeamScore );
    odds_table_row_consensus_panel( $odd->PregameOdds );
    odds_table_row_sportsbook_panel( $odd->PregameOdds, $sbs );
    ?>
  </tr>
  <?php odds_table_row_date_status( $odd->DateTime, $odd->Status );
}

function valid_odds( $odds ) {
  $valid = false;
  if ( is_array( $odds ) ) {
    foreach ( $odds as $odd ) {
      if ( isset( $odd->HomeTeamId ) && isset( $odd->AwayTeamId ) ) {
        $valid = true;
      }
    }
  }
  return $valid;
}

function odds_sportsbooks() {
  $sbs = [];
  $sportsbooks_query = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'meta_key' => 'odds_display',
    'meta_value' => true
  ];
    
  query_posts( $sportsbooks_query );

  while ( have_posts() ) {
    the_post();
    $sbs[] = [
      'name' => get_the_title(),
      'badge' => get_field( 'badge' ) ? '<img src="' . get_field( 'badge' ) . '" width="120" height="40" alt="' . get_the_title() . '" />' : NULL,
      'id' => get_field( 'header_display' ) ? get_post_field( 'post_name' ) : NULL,
      'sdio' => get_field('sb_odds_id'),
      'url' => get_field( 'state_affiliate_links' ) ? NULL : get_field( 'global_affiliate_link' ),
    ];
  }
  wp_reset_query();
  return $sbs;
}

function odds_table() {
  $league = api_league();
  $selected_week = '';
  $current_season = '';
  if ( $league === 'nfl' ) {
    $current_season = api_data_odds_nfl_current_season();
    $current_week = api_data_odds_nfl_current_week();
    $selected_week = api_data_odds_nfl_selected_week( $current_season, $current_week );
  }
  $odds = api_data_odds( $league );
  $teams = api_data_odds_teams( $league );
  $sbs = odds_sportsbooks();
  ?>
  <div uk-grid class="uk-flex-between uk-flex-middle uk-margin-bottom odds-locations">
    <?php odds_table_nav( $league ); ?>
    <?php //odds_table_location( $league ); ?>
  </div>
  <?php odds_table_filter( $league, $selected_week, $current_season ); ?>
  <div class="uk-position-relative">
    <div class="uk-overflow-auto">
      <?php 
      /* disable this temporary
      if ( valid_odds( $odds ) ) : */ ?>
      <table id="odds-list" class="uk-table uk-table-divider">
        <?php odds_table_head( $league, $sbs ); ?>
        <tbody id="odds-list-body">
          <?php 
          if ( isset( $odds ) ) {
            foreach ( $odds as $odd ) {
              odds_table_row( $odd, $teams, $sbs );
            }
          }
          ?>
        </tbody>
      </table>
      <?php /* endif; */ ?>
    </div>
  </div>
  <?php
}
add_action( 'odds_table', 'odds_table' );

function odds_table_data() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
    die( 'Unable to verify sender.' );
  }
  if ( isset( $_POST['league'] ) ) {
    $league = strtolower( $_POST['league'] );
  }

  $odds = api_data_odds( $league, $_POST['selection'] );
  $teams = api_data_odds_teams( $league );
  $sbs = odds_sportsbooks();

  if ( isset( $odds ) ) {
    foreach ( $odds as $odd ) {
      odds_table_row( $odd, $teams, $sbs );
    }
  }
  die();
}
add_action( 'wp_ajax_odds_table_data', 'odds_table_data' );
add_action( 'wp_ajax_nopriv_odds_table_data', 'odds_table_data' );

function odds_header_data() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
    die( 'Unable to verify sender.' );
  }
  
  if ( isset( $_POST['league'] ) ) {
    $league = strtolower( $_POST['league'] );
  } else {
    $league = api_league();
  }

  $odds = api_data_odds( $league );

  $dates = [];
  if ( isset( $odds ) ) {
    foreach ( $odds as $odd ) {
      $slot = [
        'home' => $odd->HomeTeamName,
        'away' => $odd->AwayTeamName,
        'time' => date('g:i A', strtotime($odd->DateTime)),
        'homeSpread' => '.',
        'awaySpread' => '.',
        'overUnder' => '.'
      ];
      if ( isset( $odd->PregameOdds ) ) {
        foreach ( $odd->PregameOdds as $pregameOdd ) {
          if ( $pregameOdd->Sportsbook === 'Consensus' ) {
            $slot['homeSpread'] = api_bet_display($pregameOdd->HomePointSpread);
            $slot['awaySpread'] = api_bet_display($pregameOdd->AwayPointSpread);
            $slot['overUnder'] = is_numeric( $pregameOdd->OverUnder ) ? $pregameOdd->OverUnder : '..';
            break;
          }
        }
      }
      $dates[$odd->Day][] = $slot;
    }
  }

  if ( isset( $odds ) ) {
    $date_displayed = false;
    foreach ( $dates as $date => $games ) {
      $formatted_date = date( 'F j, Y', strtotime( $date ) );
      foreach ( $games as $game ) {
        echo '<li>
          <div class="uk-position-left uk-panel game">
            <div class="timeslot">' . $game['time'] . '</div>
            <div class="uk-flex">
              <div class="team-column">
                <div>' . $game['away'] . '</div>
                <div>' . $game['home'] . '</div>
              </div>
              <div class="spread-column">
                <div>' . $game['awaySpread'] . '</div>
                <div>' . $game['homeSpread'] . '</div>
              </div>
              <div class="totals-column">' . $game['overUnder'] . '</div>
            </div>
            <div class="date-display">';
        if ($date_displayed === false) {
          echo $formatted_date;
          $date_displayed = true;
        }
        echo '</div>
          </div>
        </li>';
      }
    }
  }
  die();

}
add_action( 'wp_ajax_odds_header_data', 'odds_header_data' );
add_action( 'wp_ajax_nopriv_odds_header_data', 'odds_header_data' );

function odds_header() {
  $league = api_league();
  ?>
  <div class="quick-odds uk-light">
    <div class="sport-select">
      <form>
        <div class="uk-form-select" data-uk-form-select>
          <select id="quick-odds-sport" class="uk-select">
            <option <?php echo ($league === 'nfl') ? 'selected' : ''; ?>>NFL</option>
            <option <?php echo ($league === 'nba') ? 'selected' : ''; ?>>NBA</option>
            <?php /* Activate this if necessary
            <option <?php echo ($league === 'mlb') ? 'selected' : ''; ?>>MLB</option>
            */ ?>
          </select>
        </div>
      </form>
    </div>
    <div class="slider-section uk-position-relative uk-visible-toggle" tabindex="-1" uk-slider="sets: true; finite: true">
      <div class="uk-slider-container uk-light">
        <ul class="uk-slider-items"></ul>
        <a class="uk-position-center-left uk-position-small bg-grad" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
        <a class="uk-position-center-right uk-position-small bg-grad" href="#" uk-slidenav-next uk-slider-item="next"></a>
      </div>
    </div>
  </div>
  <?php
}
add_action( 'odds_header', 'odds_header' );