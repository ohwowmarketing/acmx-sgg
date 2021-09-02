<?php
function api_spread_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
  }
  $league = $_POST['league'];
  $url = 'https://sggpro.vercel.app/api/' . $league . '/spread';
  $teams = api_data( $url );
  if ( isset ( $teams ) && is_array( $teams ) && count( $teams ) > 0 ) :
    foreach ( $teams as $team ) : ?>
      <tr id="row-<?php echo $team->sdio; ?>">
        <td>
          <div class="team-panel">
            <span class="tp-logo">
              <?php if ($team->logo) : ?>
                <img src="<?php echo $team->logo; ?>" />
              <?php endif; ?>
            </span>
            <span class="tp-label">
              <?php echo $team->display; ?>
            </span>
        </td>
        <td class="api-overall"><span><?php api_data_score( $team->wins, $team->losses ); ?></span></td>
        <?php if ( $league !== 'nfl' ) : ?> 
        <td class="api-overall-home">
          <span><?php api_data_score( $team->homeWins, $team->homeLosses ); ?></span>
        </td>
        <td class="api-overall-away">
          <span><?php api_data_score( $team->awayWins, $team->awayLosses ); ?></span>
        </td>
        <?php endif; ?>
        <td class="api-spread-home">
          <span><?php api_data_score( $team->homeSpreadWins, $team->homeSpreadLosses, $team->homeSpreadPushes ); ?></span>
        </td>
        <td class="api-spread-away">
          <span><?php api_data_score( $team->awaySpreadWins, $team->awaySpreadLosses, $team->awaySpreadPushes ); ?></span>
        </td>
        <td class="api-over-under-home">
          <span><?php api_data_score( $team->homeOvers, $team->homeUnders, $team->homeOverUnderPushes ); ?></span>
        </td>
        <td class="api-over-under-away">
          <span><?php api_data_score( $team->awayOvers, $team->awayUnders, $team->awayOverUnderPushes ); ?></span>
        </td>
      </tr>
    <?php endforeach;
  endif;
  die();
}
add_action( 'wp_ajax_api_spread', 'api_spread_ajax' );
add_action( 'wp_ajax_nopriv_api_spread', 'api_spread_ajax' );