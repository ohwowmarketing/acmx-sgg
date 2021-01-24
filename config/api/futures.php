<?php
function futures_table_location( $curr_league = 'nfl' ) {
  $curr_league = strtolower( $curr_league );
  $valid_states = get_all_sportsbook_states();
  ?>
  <div class="uk-width-auto@m">
    <div class="button-select-wrapper">
      <button id="futures-location-btn" type="button" class="uk-button uk-button-outline">Choose Betting Location</button>
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

function api_market_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
  }
  $transient = get_transient( 'sgg_api_future_' . $_POST['league'] . '_market_select' );
  if ( ! empty( $transient ) ) {
    echo $transient;
    die();
  }
  $url = 'https://sgg.vercel.app/api/' . $_POST['league'] . '/market';
  $markets = api_data( $url );
  $defaults = ['NFL Championship Winner', 'World Series Winner', 'NBA Champion'];
  $out = '';
  foreach ( $markets as $market ) {
    $out .= '<option value="' . $market->id . '">' . $market->display . '</option>';
  }
  if ( $out !== '' ) {
    set_transient( 'sgg_api_future_' . $_POST['league'] . '_market_select', $out, DAY_IN_SECONDS );
    echo $out;
  }
  die();
}
add_action( 'wp_ajax_api_market', 'api_market_ajax' );
add_action( 'wp_ajax_nopriv_api_market', 'api_market_ajax' );

function future_thead( $meta, $sportsbooks ) {
  $out = '<thead>
  <tr>
    <th>
      <div class="team-label">';
  if ( $data->meta->known ) {
    $out .= ( $data->meta->isTeam ) ? 'Teams' : 'Players';
  }
  
  $out .= '</div>
    </th>';
  foreach ( $sportsbooks as $sportsbook ) {
    $out .= '<th width="120"><span class="futures-data-sportsbook-title-' . $sportsbook . '">' . $sportsbook . '</span></th>';
  }
  $out .= '</tr>
  </thead>';
  return $out;
}

function future_tbody( $rows, $sportsbooks ) {
  if (count($rows) < 1) {
    return '';
  }
  $out = '<tbody>';
  foreach ( $rows as $row ) {
    
    $out .= '<tr>';
    $out .= future_participant_td( $row->display, $row->logo );
    foreach( $sportsbooks as $sportsbook ) {
      $out .= future_sportsbook_td( $sportsbook, $row->participantBets->{$sportsbook} );
    }
    $out .= '</tr>';
  }
  $out .= '</tbody>';
  return $out;
}

function future_participant_td( $display, $logo ) {
  $out = '<td>
    <div class="team-panel">
      <span class="tp-logo">';
  $out .= ( $logo ) ? '<img src="' . $logo . '" uk-img>' : '';
  $out .= '</span>
      <span class="tp-label">' . $display . '</span>
    </div>
  </td>';
  return $out;
}

function future_sportsbook_td( $sportsbook, $payouts ) {
  $out = '<td class="sportsbook-panel">
    <div class="uk-panel">
      <div class="odds-sb-bookline">
        <span class="sb-bookline-extlink futures-data-sportsbook-';
  $out .= $sportsbook;
  $out .= '">
          <span>';
  $out .= future_sportsbook_payout( $payouts );
  $out .= '</span>
        </span>
      </div>
    </div>
  </td>';
  return $out;
}

function future_sportsbook_payout( $sportsbook ) {
  $out = '';
  if ( isset( $sportsbook ) ) {
    if ( in_array( 'american', array_keys( get_object_vars( $sportsbook ) ) ) ) {
      $out .= future_payout( $sportsbook->american );
    } else {
      $types = get_object_vars( $sportsbook );

      if ( ! isset( $types ) ) {
        $out .= 'N/A';
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
          $out .= $key . $value . ': ';
          $out .= future_payout( $types[ $key ]->american );
          if ( $i < count( $keys ) ) {
            $out .= "<br />";
            $i++;
          }
        }
      }
    }
  } else {
    $out .= 'N/A';
  }
  return $out;
}

function future_payout( $payout ) {
  $out = '';
  if ( is_numeric( $payout ) ) {
    if ( $payout > 0 ) {
      $out .= '+';
    }
    $out .= number_format( $payout, 0, '.', ',' );
  } else {
    $out .= 'N/A';
  }
  return $out;
}

function api_future_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die('Unable to verify sender.');
  }

  if ( $_POST['future'] === '' && in_array( $_POST['league'], ['nfl', 'nba'] ) ) {
    if ($_POST['league'] === 'nfl') {
      $_POST['future'] = 38;
    } else {
      $_POST['future'] = 36;
    }
  }

  $transient = get_transient( 'sgg_api_future_' . $_POST['league'] . '_market_' . $_POST['future'] );
  if ( ! empty( $transient ) ) {
    echo $transient;
    die();
  }

  $url = 'https://sgg.vercel.app/api/' . $_POST['league'] . '/future/' . $_POST['future'];
  $data = api_data( $url );
  if ( !isset( $data ) ) {
    echo '<div class="uk-placeholder uk-text-center uk-text-meta uk-text-uppercase _notice"> <span uk-icon="warning"></span> Please refresh the page.<br /><a href="' . get_permalink() . '" id="futures-location-btn" type="button" class="uk-button uk-button-default">Refresh</a></div>';
    die();
  }
  // Sort Sportsbooks by WP menu order
  if ( !in_array( 'Consensus', $data->sportsbooks ) ) {
    $sportsbooksWithConsensus = array_merge(['Consensus'], $data->sportsbooks);
  } else {
    $sportsbooksWithConsensus = $data->sportsbooks;
  }
  $sportsbooksQuery = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ];
  query_posts( $sportsbooksQuery );
  $orderedSportsbooks = ['Consensus'];
  while ( have_posts() ) {
    the_post();
    if (get_field('futures_display')) {
      $orderedSportsbooks[] = get_field('sb_odds_id');
    }
  }
  wp_reset_query();

  

  $sportsbooks = [];
  foreach ( $orderedSportsbooks as $orderedSportsbook ) {
    if ( in_array( $orderedSportsbook, $sportsbooksWithConsensus ) ) {
      for ( $i = 0; $i < count($sportsbooksWithConsensus); $i++ )  {
        if ( $sportsbooksWithConsensus[$i] === $orderedSportsbook ) {
          $sportsbooks[] = $orderedSportsbook;
        }
      }
    }
  }

  $out = '';
  $out .= future_thead( $data->meta, $sportsbooks );
  $out .= future_tbody( $data->rows, $sportsbooks );
  if ( $out !== '' ) {
    $minutes = 10;
    $seconds = 60;
    $cache_time = $minutes * $seconds;
    set_transient( 'sgg_api_future_' . $_POST['league'] . '_market_' . $_POST['future'], $out, $cache_time );
    echo $out;
  }
  die();
}
add_action( 'wp_ajax_api_future', 'api_future_ajax' );
add_action( 'wp_ajax_nopriv_api_future', 'api_future_ajax' );

function futures_user_settings() {
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
add_action( 'wp_ajax_futures_user_settings', 'futures_user_settings' );
add_action( 'wp_ajax_nopriv_futures_user_settings', 'futures_user_settings' );