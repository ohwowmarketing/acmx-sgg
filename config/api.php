<?php
function api_league() {
  global $post;
  $league = 'nfl';
  $leagues = [ 'nfl' => 23, 'nba' => 25 ];
  // $leagues = [ 'nfl' => 23, 'nba' => 25, 'mlb' => 27 ];
  if ( isset( $post->post_parent ) && in_array( $post->post_parent, $leagues ) ) {
    foreach ( $leagues as $league_name => $league_id ) {
      if ( (int) $post->post_parent === $league_id ) {
        $league = $league_name;
      }
    }
  }
  return $league;
}

function api_global_script_variables() {
  ?>
  <script type="text/javascript">
    var SGGAPI = <?php echo json_encode( [
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce' => wp_create_nonce( 'sgg-nonce' ),
      'permalink' => get_permalink(),
      'directory' => get_template_directory_uri(),
      'league' => api_league(),
      'future' => ( isset( $_GET['future'] ) ) ? $_GET['future'] : null,
    ]); ?>;
  </script>
  <?php
}
add_action( 'wp_head', 'api_global_script_variables' );


function api_enqueue_scripts() {
  $scripts = ['promos', 'futures', 'spread', 'news', 'odds', 'header'];
  $root = get_stylesheet_directory_uri() . '/resources/scripts/sgg/';
  wp_enqueue_script( 'percentageloader', get_template_directory_uri() . '/resources/scripts/percentageloader.js', [ 'jquery' ] );
  foreach ( $scripts as $script ) {
    wp_enqueue_script( $script, $root . $script . '.js', [ 'jquery', 'uikit',  'percentageloader' ] );
  }
  wp_enqueue_style('extra', get_template_directory_uri() . '/resources/styles/extra2.css');
}
add_action( 'wp_enqueue_scripts', 'api_enqueue_scripts' );

function api_query_vars_filter( $vars ) {
  $vars[] = "future";
  $vars[] = "league";
  return $vars;
}
add_filter( 'query_vars', 'api_query_vars_filter', 0 );

function api_score( $wins, $losses, $draws = 0 ) {
  echo $wins . ' - ' . $losses;
  if ($draws > 0) {
    echo ' - ' . $draws;
  }
}

function api_bet_display($num) {
  if ( ! isset( $num ) || $num === '' ) {
    return '..';
  }
  if (is_numeric( $num ) && $num > 0) {
    return '+' . $num;
  }
  return $num;
}

function api_state_from_code( $code ) {
  $states = ['AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'District of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'];
  return $states[ $code ];
}