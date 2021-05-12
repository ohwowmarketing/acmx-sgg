<?php
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

function future_thead( $meta, $sportsbooks, $sbs ) {
  $out = '<thead>
  <tr>
    <th>
      <div class="team-label">';
  if ( $data->meta->known ) {
    $out .= ( $data->meta->isTeam ) ? 'Teams' : 'Players';
  }
  
  $out .= '</div>
    </th>
    <th width="120"><span>CONSENSUS</span></th>';
  foreach ( $sportsbooks as $sportsbook ) {
    foreach ( $sbs as $sb ) {
      if ( $sportsbook === $sb['sdio'] ) {
        $out .= '<th width="120"><span>';
        if ( $sb['has_header'] ) {
          $display = $sb['badge'] === NULL ? strtoupper( $sb['name'] ) : $sb['badge'];
          if ( $sb['has_states'] ) {
            $out .= '<a href="#bet-now" class="hero-sb-bet-now" data-sbid="' . $sb['slug'] . '>"' . $display . '</a>';
          } else {
            $out .= '<a href="' . $sb['url'] . '">' . $display . '</a>';
          }
        } else {
          $out .= strtoupper( $sb['name'] );
        }
        $out .= '</span></th>';
      }
    }
    
  }
  $out .= '</tr>
  </thead>';
  return $out;
}

function future_tbody( $rows, $sportsbooks, $sbs ) {
  if (count($rows) < 1) {
    return '';
  }
  $out = '<tbody>';
  foreach ( $rows as $row ) {
    
    $out .= '<tr>';
    $out .= future_participant_td( $row->display, $row->logo );
    $out .= future_sportsbook_consensus_td( $row->participantBets->Consensus );
    foreach( $sportsbooks as $sportsbook ) {
      $out .= $sportsbook;
      foreach ( $sbs as $sb ) {
        if ( $sportsbook === $sb['sdio'] ) {
          $out .= future_sportsbook_td( $sportsbook, $row->participantBets->{$sportsbook}, $sb );
        }
      }
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

function future_sportsbook_consensus_td( $payouts ) {
  $out = '<td class="sportsbook-panel">
    <div class="uk-panel">
      <div class="odds-sb-bookline">
        <span class="sb-bookline-extlink">
          <span>';
  $out .= future_sportsbook_payout( $payouts );
  $out .= '</span>
        </span>
      </div>
    </div>
  </td>';
  return $out;
}

function future_sportsbook_td( $sportsbook, $payouts, $sb = NULL ) {
  $out = '<td class="sportsbook-panel">
    <div class="uk-panel">
      <div class="odds-sb-bookline">';
  if ( $sb['has_header'] ) {
    if ( $sb['has_states'] ) {
      $out .= '<a href="#bet-now" class="hero-sb-bet-now" data-sdio="' . $sb['slug'] . '">';
      $out .= '<span class="sb-bookline-extlink">';
      $out .= future_sportsbook_payout( $payouts );
      $out .= '<span>';
      $out .= '<span class="sb-extlink-hover"><svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg><span>Bet Now</span></span>';
      $out .= '</a>';
    } else {
      $out .= '<a href="' . $sb['url'] . '">';
      $out .= '<span class="sb-bookline-extlink">';
      $out .= future_sportsbook_payout( $payouts );
      $out .= '<span>';
      $out .= '<span class="sb-extlink-hover"><svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg><span>Bet Now</span></span>';
      $out .= '</a>';
    }
  } else {
    $out .= future_sportsbook_payout( $payouts );
  }
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

  // $transient = get_transient( 'sgg_api_future_' . $_POST['league'] . '_market_' . $_POST['future'] );
  // if ( ! empty( $transient ) ) {
  //   echo $transient;
  //   die();
  // }

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
    'order' => 'ASC',
    'meta_key' => 'futures_display',
    'meta_value' => true
  ];
  query_posts( $sportsbooksQuery );
  $orderedSportsbooks = ['Consensus'];
  $sbs = [];
  while ( have_posts() ) {
    the_post();
    $orderedSportsbooks[] = get_field('sb_odds_id');
    $sb = [
      'name' => get_the_title(),
      'badge' => get_field( 'badge' ) ? '<img src="' . get_field( 'badge' ) . '" width="120" height="40" alt="' . get_the_title() . '" />' : NULL,
      'has_header' => get_field( 'header_display' ) ? true : false,
      'slug' => get_post_field( 'post_name' ),
      'sdio' => get_field('sb_odds_id'),
      'has_states' => get_field( 'state_affiliate_links' ) ? true : false,
      'url' => get_field( 'state_affiliate_links' ) ? NULL : get_field( 'global_affiliate_link' ),
    ];
    $sbs[] = $sb;
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
  $out .= future_thead( $data->meta, $sportsbooks, $sbs );
  $out .= future_tbody( $data->rows, $sportsbooks, $sbs );
  // if ( $out !== '' ) {
  //   $minutes = 10;
  //   $seconds = 60;
  //   $cache_time = $minutes * $seconds;
  //   set_transient( 'sgg_api_future_' . $_POST['league'] . '_market_' . $_POST['future'], $out, $cache_time );
  // }
  echo $out;
  die();
}
add_action( 'wp_ajax_api_future', 'api_future_ajax' );
add_action( 'wp_ajax_nopriv_api_future', 'api_future_ajax' );
