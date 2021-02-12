<?php
function api_data( $url, $headers = [] ) {
  return json_decode( wp_remote_retrieve_body( wp_remote_get( $url, $headers ) ) );
}

function api_data_score( $wins, $losses, $draws = 0 ) {
  echo $wins . ' - ' . $losses;
  if ($draws > 0) {
    echo ' - ' . $draws;
  }
}

function api_headers_with_key( $league, $privilege = '' ) {
  $news = [
    'nfl' => 'd6ac62df2ca7404babadf22c278dbc8d',
    'mlb' => 'eb6004895b3740f19afdaa5780a9a0c5',
    'nba' => '29c1b1b73ed54138bad9dc260ae3595e'
  ];
  $odds = [
    'nfl' => '2a2e46fcc4504134aadced092416ba1e',
    'mlb' => 'b426343c15c843c3ab56930d2a919e2c',
    'nba' => '14ab1b17eede492d8996908963d2ebbd'
  ];
  switch ( $privilege ) {
    case 'news':
      $key = $news[ $league ];
      break;
    case 'odds':
      $key = $odds[ $league ];
      break;
    default:
      $key = 'aebf9d3f48774f94a9e31e228c57a15c';
      break;
  }
  return [ 'headers' => [ 'Ocp-Apim-Subscription-Key' => $key ] ];
}

function api_data_odds_nfl_current_season() {
  $url = 'https://api.sportsdata.io/v3/nfl/scores/json/CurrentSeason';
  $headers = api_headers_with_key( 'nfl' );
  $season = api_data( $url, $headers );
  return $season;
}

function api_data_odds_nfl_current_week() {
  $url = 'https://api.sportsdata.io/v3/nfl/scores/json/CurrentWeek';
  $headers = api_headers_with_key( 'nfl' );
  $week = api_data( $url, $headers );
  return $week;
}

function api_data_odds_nfl_selected_week( $season, $week ) {
  // $selected = $season . '/' . $week;
  // if ( $week > 17 ) {
  //   $post_week = $week - 17;
  //   $selected = $season . 'POST/' . $post_week;
  // }

  $selected = $season . 'POST/' . $week;

  return $selected;
}

function api_data_odds_nfl( $selection ) {
  $selected_week = $selection;
  if ( $selected_week === '' ) {
    $current_season = api_data_odds_nfl_current_season();
    $current_week = api_data_odds_nfl_current_week();
    $selected_week = api_data_odds_nfl_selected_week( $current_season, $current_week );
  }
  $url = 'https://api.sportsdata.io/v3/nfl/odds/json/GameOddsByWeek/' . $selected_week;
  $headers = api_headers_with_key( 'nfl', 'odds' );
  $odds = api_data( $url, $headers );
  return $odds;
}

function api_data_odds( $league, $selection = '' ) {
  if ( $league === 'nfl' ) {
    $odds = api_data_odds_nfl( $selection );
  } else {
    $selected_date = $selection;
    $formatted_date = date( 'Y-m-d' );
    if ( $selected_date !== '' ) {
      $formatted_date = date( 'Y-m-d', strtotime( $selected_date ) );
    }
    
    $url = 'https://api.sportsdata.io/v3/' . $league . '/odds/json/GameOddsByDate/' . $formatted_date;
    $headers = api_headers_with_key( $league, 'odds' );
    $odds = api_data( $url, $headers );
  }
  return $odds;
}

function api_data_odds_teams( $league ) {
  $url = 'https://api.sportsdata.io/v3/' . $league . '/scores/json/teams';
  $headers = api_headers_with_key( $league );
  $teams = api_data( $url, $headers );
  return $teams;
}
