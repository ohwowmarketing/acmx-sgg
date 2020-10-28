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
      <td class="api-overall"><span><?php score( $team->wins, $team->losses ); ?></span></td>
      <?php if ( $league !== 'nfl' ) : ?> 
      <td class="api-overall-home">
        <span><?php score( $team->homeWins, $team->homeLosses ); ?></span>
      </td>
      <td class="api-overall-away">
        <span><?php score( $team->awayWins, $team->awayLosses ); ?></span>
      </td>
      <?php endif; ?>
      <td class="api-spread-home">
        <span><?php score( $team->homeSpreadWins, $team->homeSpreadLosses, $team->homeSpreadPushes ); ?></span>
      </td>
      <td class="api-spread-away">
        <span><?php score( $team->awaySpreadWins, $team->awaySpreadLosses, $team->awaySpreadPushes ); ?></span>
      </td>
      <td class="api-over-under-home">
        <span><?php score( $team->homeOvers, $team->homeUnders, $team->homeOverUnderPushes ); ?></span>
      </td>
      <td class="api-over-under-away">
        <span><?php score( $team->awayOvers, $team->awayUnders, $team->awayOverUnderPushes ); ?></span>
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
    echo '<div class="uk-placeholder uk-text-center uk-text-meta uk-text-uppercase _notice"> <span uk-icon="warning"></span> Futures are currently unavailable.</div>';
    die();
  }
  if ( !in_array( 'Consensus', $data->sportsbooks ) ) {
    $sportsbooks = array_merge(['Consensus'], $data->sportsbooks);
  } else {
    $sportsbooks = $data->sportsbooks;
  }
  ?>
  <thead>
    <tr>
      <th>
        <div class="team-label">
        <?php if ( $data->meta->known ) {
          echo ( $data->meta->isTeam ) ? 'Teams' : 'Players';
        } ?>
        </div>
      </th>
      <?php foreach ( $sportsbooks as $sportsbook ) : ?>
      <th width="120"><span><?php echo $sportsbook; ?></span></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ( $data->rows as $row ) : ?>
    <tr>
      <td>
        <div class="team-panel">
          <span class="tp-logo">
          <?php if ($row->logo) : ?>
            <img src="<?php echo $row->logo; ?>" uk-img>
          <?php endif; ?>
          </span>
          <span class="tp-label">
            <?php echo $row->display; ?>  
          </span>
        </div>
      </td>
      <?php foreach( $sportsbooks as $sportsbook ) : ?>
      <td class="sportsbook-panel">
        <div class="uk-panel">
          <div class="odds-sb-bookline">
            <span class="sb-bookline-extlink">
              <span>

                <?php
                if ( isset( $row->participantBets->{$sportsbook} ) ) {
                  if ( in_array( 'american', array_keys(get_object_vars( $row->participantBets->{$sportsbook} ) ) ) ) {
                    future_payout( $row->participantBets->{$sportsbook}->american );
                  } else {
                    $types = get_object_vars( $row->participantBets->{$sportsbook} );

                    if ( ! isset( $types ) ) {
                      echo 'N/A';
                    } else {
                      $original_keys = array_keys( $types );

                      $key_priority = ['Over', 'Yes'];
                      $sorted_keys = [];
                      $unsorted_keys = [];
                      
                      foreach( $original_keys as $key ) {
                        if ( in_array( $key, $key_priority ) ) {
                          $sorted_keys[] = $key;
                        } else {
                          $unsorted_keys[] = $key;
                        }
                      }
                      
                      $keys = array_merge( $sorted_keys, $unsorted_keys );
                      
                      $value = '';
                      if ( $types[ $keys[0] ]->value ) {
                        $value = ' (' . $types[ $keys[0] ]->value . ')';
                      }
                
                      $i = 1;
                      foreach( $keys as $key ) {
                        echo $key . $value . ': ';
                        future_payout( $types[ $key ]->american );
                        if ( $i < count( $keys ) ) {
                          echo "<br />";
                          $i++;
                        }
                      }
                    }
                  }
                } else {
                  echo 'N/A';
                }
                ?>

              </span>
            </span>
          </div>
        </div>
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


/*
        