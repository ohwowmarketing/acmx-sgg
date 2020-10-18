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
    'permalink' => get_permalink(),
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
      <td class="api-overall-home">
        <?php score( $team->homeWins, $team->homeLosses ); ?>
      </td>
      <td class="api-overall-away">
        <?php score( $team->awayWins, $team->awayLosses ); ?>
      </td>
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

function api_market_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die('Unable to verify sender.');
  }
  $url = 'https://sgg.vercel.app/api/' . $_POST['league'] . '/market';
  $markets = api_data( $url );
  $defaults = ['NFL Championship Winner', 'World Series Winner', 'NBA Champion'];
  foreach ( $markets as $market ) {
    $selected = false;
    if ( $_POST['future'] === '' ) {
      if ( in_array( $market->display, $defaults ) ) {
        $selected = true;
      }
    } else {
      if ( (int) $_POST['future'] === $market->id) {
        $selected = true;
      }
    }
    echo '<option ';
    echo 'value="' . $market->id . '"';
    echo $selected ? ' selected="selected"' : '';
    echo '>';
    echo $market->display;
    echo '</option>';
  }
  die();
}
add_action('wp_ajax_api_market', 'api_market_ajax' );
add_action('wp_ajax_nopriv_api_market', 'api_market_ajax' );

function future_payout( $payout ) {
  if ( is_numeric( $payout ) ) {
    if ( $payout > 0 ) {
      echo '+';
    }
    echo number_format( $payout, 0, '.', ',' );
  } else {
    echo 'N/A';
  }
}

function api_future_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die('Unable to verify sender.');
  }
  $league = $_POST['league'];
  $url = 'https://sgg.vercel.app/api/' . $_POST['league'] . '/future/' . $_POST['future'];
  $data = api_data( $url );
  if ( ! isset( $data ) ) {
    echo '<p>Futures are currently unavailable.</p>';
    die();
  }
  $sportsbooks = array_merge(['Consensus'], $data->sportsbooks);
  ?>
  <thead>
    <tr>
      <td class="team-label">Futures</td>
      <?php foreach ( $sportsbooks as $sportsbook ) : ?>
      <td><?php echo $sportsbook; ?></td>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ( $data->rows as $row ) : ?>
    <tr>
      <td class="team-panel">
      <?php if ($row->logo) : ?>
        <img src="<?php echo $row->logo; ?>" />
      <?php endif; ?>
      <?php echo $row['display']; ?>
      </td>
      <?php foreach( $sportsbooks as $sportsbook ) : ?>
      <td>
        <?php 
        if ( isset( $row['sportsbooks'][ $sportsbook ] ) ) {
          if ( in_array( 'american', array_keys( $row['sportsbooks'][ $sportsbook ] ) ) ) {
            future_payout( $row['sportsbooks'][ $sportsbook ]['american'] );
          } else {
            $count = 1;
            $total_types = count( $row['sportsbooks'][ $sportsbook ] );
            foreach ( $row['sportsbooks'][ $sportsbook ] as $type => $value ) {
              echo $value . ' ';
              future_payout( $value );
              if ($count !== $total_types) {
                echo "<br />";
                $count++;
              }
            }
          }
        }
        ?>
      </td>
      <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <?php 
  die();
}
add_action('wp_ajax_api_future', 'api_future_ajax' );
add_action('wp_ajax_nopriv_api_future', 'api_future_ajax' );