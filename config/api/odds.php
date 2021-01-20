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

function odds_table_nav( $curr_league = 'nfl' ) {
  $curr_league = strtolower( $curr_league );
  $leagues = [ 'nfl', 'nba', 'mlb' ];
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
        <?php foreach( $weeks as $week_value => $week_display ): ?>
          <option <?php echo ($week_value === $week) ? 'selected ' : ''; ?>value="<?php echo $week_value; ?>"><?php echo $week_display; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php else : ?>
    <div class="odds-schedule">
      <div>
        <button type="button" onclick="updateDateChange(false);" class="uk-icon-link _prevDay" uk-icon="triangle-left"></button>
        <span id="dateOdds"></span>
        <button type="button" onclick="updateDateChange(true);" class="uk-icon-link _nextDay" uk-icon="triangle-right"></button>
      </div>
    </div>
  <?php endif;
}

function odds_table_type( $curr_type = 'Spread' ) {
  $types = ['Spread', 'Total', 'Moneyline'];
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

function odds_table_head( $league ) {
  ?>
  <thead>
    <tr>
      <th><div class="team-label"><?php echo strtoupper( $league ); ?></div></th>
      <th width="120"><span>Consensus</span></th>
      <?php
        $sportsbook_query = [
          'post_type' => 'sportsbooks',
          'has_password' => false,
          'posts_per_page' => -1,
          'orderby' => 'menu_order',
          'order' => 'asc'
        ];
        query_posts( $sportsbook_query );
        while ( have_posts() ) {
          the_post();
          ?>
          <th width="120">
            <span class="odds-data-sportsbook-title-<?php the_field('sb_odds_id'); ?>"><?php the_title(); ?></span>
          </th>
          <?php
        }
        wp_reset_query();
      ?>
    </tr>
  </thead>
  <?php
}

function odds_table_row_date_status( $date_time, $status ) {
  $d = new DateTime( $date_time );
  ?>
  <tr class="schedule-row">
    <td colspan="1" class="schedule-panel">
      <div>
        <?php echo $d->format( 'D n/d, g:i A' ); ?> | <?php echo 'Status: ' . $status; ?>
      </div>
    </td>
    <td colspan="6">
      <div>&nbsp;</div>
    </td>
  </tr>
  <?php
}

function odds_table_row_team_by_id( $id, $teams ) {
  foreach ( $teams as $team ) {
    if ( isset( $team->TeamID ) && (string) $id === (string) $team->TeamID ) {
      return [ 'name' => $team->Name, 'logo' => $team->WikipediaLogoUrl ];
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
        <?php if ( is_array( $teams ) && count( $teams ) > 0 ) : 
          $home = odds_table_row_team_by_id( $home_id, $teams );
          $away = odds_table_row_team_by_id( $away_id, $teams ); ?>
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

function odds_table_row_sportsbook_panel_bookline( $values, $sportsbook ) {
  if ( $values['spread'] === '' || $values['spread_payout'] === '' ) :
    odds_table_row_sportsbook_panel_not_found();
  else :
  ?>
  <div class="odds-sb-bookline">
    <a class="sb-bookline-extlink odds-data-sportsbook-<?php echo $sportsbook; ?>" href="#">
        <span class="sb-value-spread">
            <?php echo ($values['spread'] < 0) ? $values['spread'] : '+' . $values['spread']; ?>
            <small class="uk-margin-small-left"><?php echo $values['spread_payout'] ?></small>
        </span>
        <span class="sb-value-moneyline" style="display: none;">
            <?php echo ($values['moneyline'] < 0) ? $values['moneyline'] : '+' . $values['moneyline']; ?>
            <small class="uk-margin-small-left">ML</small>
        </span>
        <span class="sb-value-total" style="display: none;">
            <?php echo ($values['over_under'] < 0) ? $values['over_under'] : '+' . $values['over_under']; ?>
            <small class="uk-margin-small-left"><?php echo $values['over_under_payout'] ?></small>
        </span>
    </a>
  </div>
  <?php endif;
}

function odds_table_row_sportsbook_panel_item( $odd, $sportsbook = '' ) {

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
      <?php odds_table_row_sportsbook_panel_bookline( $away, $sportsbook ); ?>
      <?php odds_table_row_sportsbook_panel_bookline( $home, $sportsbook ); ?>
    </div>
  </td>
  <?php
}

function odds_table_row_sportsbook_panel( $pregame ) {
  $all_states = [];
  $sportsbooks = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ];
  query_posts( $sportsbooks );
  while ( have_posts() ) {
    the_post();
    $found = false;
    foreach ( $pregame as $odd ) {
      if ( $odd->Sportsbook === get_field('sb_odds_id') ) {
        $found = true;
        odds_table_row_sportsbook_panel_item( $odd, $odd->Sportsbook );
      }
    };
    if ( ! $found ) {
      ?>
      <td class="sportsbook-panel">
        <div class="uk-panel">
          <?php odds_table_row_sportsbook_panel_not_found(); ?>
          <?php odds_table_row_sportsbook_panel_not_found(); ?>
        </div>
      </td>
      <?php
    }
  }
  wp_reset_query();
}

function odds_table_row( $odd, $teams = [] ) {
  ?>
  <tr>
    <?php 
    odds_table_row_team_panel($teams, $odd->HomeTeamId, $odd->HomeTeamScore, $odd->AwayTeamId, $odd->AwayTeamScore );
    if ( ! empty( $odd->PregameOdds ) ) {
      odds_table_row_consensus_panel( $odd->PregameOdds );
      odds_table_row_sportsbook_panel( $odd->PregameOdds );
    } else {
      odds_table_row_sportsbook_panel_not_found();
    }
    ?>
  </tr>
  <?php odds_table_row_date_status( $odd->DateTime, $odd->Status );
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
  ?>
  <div uk-grid class="uk-flex-between uk-flex-middle uk-margin-bottom odds-locations">
    <?php odds_table_nav( $league ); ?>
    <?php odds_table_location( $league ); ?>
  </div>
  <?php odds_table_filter( $league, $selected_week, $current_season ); ?>
  <div class="uk-position-relative">
    <div class="uk-overflow-auto">
      <table id="odds-list" class="uk-table uk-table-divider">
        <?php odds_table_head( $league ); ?>
        <tbody id="odds-list-body">
          <?php 
          if ( isset( $odds ) ) {
            foreach ( $odds as $odd ) {
              odds_table_row( $odd, $teams );
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php
}
add_action( 'odds_table', 'odds_table' );

function odds_table_data() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
  }
  $league = 'nfl';
  if ( ! isset( $_POST['league'] ) ) {
    $league = strtolower( $_POST['league'] );
  }

  $odds = api_data_odds( $league, $_POST['selection'] );
  $teams = api_data_odds_teams( $league );

  if ( isset( $odds ) ) {
    foreach ( $odds as $odd ) {
      odds_table_row( $odd, $teams );
    }
  }
  die();
}
add_action( 'wp_ajax_odds_table_data', 'odds_table_data' );
add_action( 'wp_ajax_nopriv_odds_table_data', 'odds_table_data' );

function odds_user_settings() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
  }
  
  $post = get_post();
  $user_state = get_user_state();
  if ( $user_state === '' ) {
    die();
  }

  $data = [ 'state' => get_state_from_code( $user_state ), 'sportsbooks' => [] ];

  $sportsbooks_query = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ];
  query_posts( $sportsbooks_query );
  while ( have_posts() ) {
    the_post();
    if ( have_rows( 'promos' ) ) {
      while ( have_rows( 'promos' ) ) {
        the_row();
        if ( $user_state === get_sub_field( 'state' ) ) {
          $data['sportsbooks'][] = [
            'id' => get_field( 'sb_odds_id' ),
            'name' => get_the_title(),
            'logo' => get_field( 'sb_image' ),
            'badge' => get_field( 'badge' ),
            'link' => get_sub_field( 'link' )
          ];
        }
      }
    }
  }
  wp_reset_query();
  echo json_encode( $data );
  die();
}
add_action( 'wp_ajax_odds_user_settings', 'odds_user_settings' );
add_action( 'wp_ajax_nopriv_odds_user_settings', 'odds_user_settings' );