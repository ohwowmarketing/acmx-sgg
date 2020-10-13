<?php
function api_scripts() {
  wp_enqueue_script( 
    'apiscript', 
    get_stylesheet_directory_uri() . '/resources/scripts/inc/api.js', 
    array( 'jquery' ) 
  );
  wp_localize_script( 'apiscript', 'SGGAPI', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'nonce' => wp_create_nonce( 'sgg-nonce' ),
  ) );
}
add_action('wp_enqueue_scripts', 'api_scripts');

function api_data( $url ) {
  return json_decode( wp_remote_retrieve_body( wp_remote_get($url) ) );
}

function score( $wins, $losses, $draws = 0 ) {
  echo $wins . ' - ' . $losses;
  if ($draws > 0) {
    echo ' - ' . $draws;
  }
}

function api_spread_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die('Unable to verify sender.');
  }
  $league = $_POST['league'];
  $url = 'https://sgg.vercel.app/api/' . $league . '/spread';
  $teams = api_data( $url );
  foreach ( $teams as $team ) : ?>
    <tr id="row-<?php echo $team->sdio; ?>">
      <td class="team-panel">
        <?php if ($team->logo) : ?>
          <img src="<?php echo $team->logo; ?>" />
        <?php endif; ?>
        <?php echo $team->display; ?>
      </td>
      <td class="api-overall"><?php score( $team->wins, $team->losses ); ?></td>
      <?php if ( $league !== 'nfl' ) : ?> 
      <td class="api-overall-home"></td>
      <td class="api-overall-away"></td>
      <?php endif; ?>
      <td class="api-ten-spread-home">
        <?php score( $team->homeSpreadWins, $team->homeSpreadLosses, $team->homeSpreadPushes ); ?>
      </td>
      <td class="api-ten-spread-away">
        <?php score( $team->awaySpreadWins, $team->awaySpreadLosses, $team->awaySpreadPushes ); ?>
      </td>
      <td class="api-ten-over-under-home">
        <?php score( $team->homeOvers, $team->homeUnders, $team->homeOverUnderPushes ); ?>
      </td>
      <td class="api-ten-over-under-away">
        <?php score( $team->awayOvers, $team->awayUnders, $team->awayOverUnderPushes ); ?>
      </td>
    </tr>
  <?php endforeach;
  die();
}
add_action('wp_ajax_api_spread', 'api_spread_ajax' );
add_action('wp_ajax_nopriv_api_spread', 'api_spread_ajax' ); 