<?php
function api_league( $default = 'nfl') {
  global $post;
  $league = $default;
  $leagues = [ 'nfl' => 23, 'nba' => 25, 'mlb' => 27 ];
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
      'league' => api_league(),
      'future' => ( isset( $_GET['future'] ) ) ? $_GET['future'] : null,
    ]); ?>;
  </script>
  <?php
}
add_action( 'wp_head', 'api_global_script_variables' );


function api_enqueue_scripts() {
  $scripts = ['promos', 'futures', 'spread', 'news', 'odds'];
  $root = get_stylesheet_directory_uri() . '/resources/scripts/sgg/';
  foreach ( $scripts as $script ) {
    wp_enqueue_script( $script, $root . $script . '.js', [ 'jquery' ] );
  }
}
add_action( 'wp_enqueue_scripts', 'api_enqueue_scripts' );

function api_score( $wins, $losses, $draws = 0 ) {
  echo $wins . ' - ' . $losses;
  if ($draws > 0) {
    echo ' - ' . $draws;
  }
}
