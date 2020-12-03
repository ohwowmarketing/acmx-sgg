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
    'future' => ( isset( $_GET['future'] ) ) ? $_GET['future'] : null,
    'league' => ( isset( $_GET['league'] ) ) ? $_GET['league'] : 'nfl'
  ) );
}
add_action( 'wp_enqueue_scripts', 'api_scripts' );

function api_data( $url ) {
  return json_decode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );
}

function score( $wins, $losses, $draws = 0 ) {
  echo $wins . ' - ' . $losses;
  if ($draws > 0) {
    echo ' - ' . $draws;
  }
}

function api_spread_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
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
add_action( 'wp_ajax_api_spread', 'api_spread_ajax' );
add_action( 'wp_ajax_nopriv_api_spread', 'api_spread_ajax' );

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
    $out .= '<th width="120"><span>' . $sportsbook . '</span></th>';
  }
  $out .= '</tr>
  </thead>';
  return $out;
}

function future_tbody( $rows, $sportsbooks ) {
  $out = '<tbody>';
  foreach ( $rows as $row ) {
    $out .= '<tr>';
    $out .= future_participant_td( $row->display, $row->logo );
    foreach( $sportsbooks as $sportsbook ) {
      $out .= future_sportsbook_td( $row->participantBets->{$sportsbook} );
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

function future_sportsbook_td( $sportsbook ) {
  $out = '<td class="sportsbook-panel">
    <div class="uk-panel">
      <div class="odds-sb-bookline">
        <span class="sb-bookline-extlink">
          <span>';
  $out .= future_sportsbook_payout( $sportsbook );
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
  if ( ! isset( $data ) ) {
    echo '<div class="uk-placeholder uk-text-center uk-text-meta uk-text-uppercase _notice"> <span uk-icon="warning"></span> Futures are currently unavailable.</div>';
    die();
  }
  if ( !in_array( 'Consensus', $data->sportsbooks ) ) {
    $sportsbooks = array_merge(['Consensus'], $data->sportsbooks);
  } else {
    $sportsbooks = $data->sportsbooks;
  }
  $out = '';
  $out .= future_thead( $data->meta, $data->sportsbooks );
  $out .= future_tbody( $data->rows, $data->sportsbooks );
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

function get_news_data( $league, $limit = 5 ) {
  $news = [];
  $league = strtolower( $league );
  include( locate_template( includes.'league-keys.php', false, true ) );
  switch ( $league ) {
    case 'mlb':
      $header_npk = $mlb_header_npk;
      $header_dak = $mlb_header_dak;
      $widget = 'widget_gallery_mlbnews';
      break;
    case 'nba':
      $header_npk = $nba_header_npk;
      $header_dak = $nba_header_dak;
      $widget = 'widget_gallery_nbanews';
      break;
    case 'nfl':
      $header_npk = $nfl_header_npk;
      $header_dak = $nfl_header_dak;
      $widget = 'widget_gallery_nflnews';
      break;
    default:
  }

  // Premium News
  $news_request = wp_remote_get( 'https://api.sportsdata.io/v3/' . strtolower( $league ).'/news-rotoballer/json/RotoBallerPremiumNews', $header_npk );
  $news_list = json_decode( wp_remote_retrieve_body( $news_request ) );
  $latest_news = array_slice( $news_list, 0, $limit );
  
  // Tier 1 - Score/Teams
  $team_request = wp_remote_get( 'https://api.sportsdata.io/v3/' . strtolower( $league ).'/scores/json/teams', $header_dak );
  $teams_list = json_decode( wp_remote_retrieve_body( $team_request ) );
  $teams = [];
  foreach( $teams_list as $team ) {
    $teams[ $team->TeamID ] = $team;
  }

  // Widget Images
  $tagged_images = get_field( $widget, 'option' );
  $team_images = json_decode( json_encode( $tagged_images ), FALSE );
  $images = [];
  $captions = [];
  foreach( $team_images as $image ) {
    $images[ $image->title ] = $image->id;
    if ( $image->caption !== '' ) {
      $captions[ $image->caption ] = $image->id;
    } 
  }

  
  foreach( $latest_news as $item) {
    $updated = new DateTime($item->Updated);
    $news_item = [
      'id' => $item->NewsID,
      'title' => $item->Title,
      'updated' => $updated->format('Y-m-d'),
      'display' => '',
      'color' => 'E1E2E9',
      'team_id' => $item->TeamID
    ];
    if ( $item->TeamID !== '' && array_key_exists( $item->TeamID, $teams ) ) {
      $news_item['city'] = $teams[ $item->TeamID ]->City;
      $news_item['name'] = $teams[ $item->TeamID ]->Name;
      $news_item['display'] = $news_item['city'] . ' ' . $news_item['name'];
      $news_item['color'] = $teams[ $item->TeamID ]->PrimaryColor;
      if ( array_key_exists( $news_item['name'], $images ) ) {
        $news_item['imageId'] = $images[ $news_item['name'] ];
      } else {
        $keys = array_keys( $captions );
        foreach ($keys as $key) {
          $terms = explode( ', ', $key );
          if ( in_array( $news_item['name'], $terms ) ) {
            $news_item['imageId'] = $images[ $news_item['name'] ];
          }
        }
      }
    } else {
      if ( ! array_key_exists( 'imageId', $news_item ) ) {
        $image_title = strtoupper( $league ) . ' Player News';
        if ( $images[ $image_title ] ) {
          $news_item['imageId'] = $images[ $image_title ];
        } else {
          $news_item['imageId'] = '';
        }
      }
    }
    $news_item['link'] = site_url( '/article/' . $league ) . 
      '?news=' . $news_item['id'] . 
      '&img=' . $news_item['imageId'] . 
      '&league=' . $league .
      '&date=' . $news_item['updated'];
    $news_item['sm_image'] = wp_get_attachment_image( $news_item['imageId'], [ 60, 60, true ] );
    $news_item['lg_image'] = wp_get_attachment_image( $news_item['imageId'], [ 1000, 1000, true ] );
    $news[] = $news_item;
  }
  return $news;
}

function api_news_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
  }
  $news = get_news_data($_POST['league']);
  ?>
  <ul class="news-lists">
    <?php foreach ( $news as $item ) : ?>
    <li class="uk-grid-collapse uk-flex-middle" uk-grid>
      <div class="uk-width-auto">
        <div style="border-bottom:5px solid #<?php echo $item['color']; ?>">
          <a href="<?php echo $item['link']; ?>" title="<?php echo $item['title']; ?>">
            <?php echo $item['sm_image']; ?>
          </a>
        </div>
      </div>
      <div class="uk-width-expand">
        <div class="uk-panel">                      
          <small><?php echo $_POST['league']; ?> <span>&#x25cf;</span> <?php echo date_format( date_create( $news->Updated ), 'D, F j, Y' ); ?></small>
          <h1><?php echo $item['display']; ?></h1>
          <h4>
            <a href="<?php echo $item['link']; ?>"><?php echo $item['title']; ?></a>
          </h4>
        </div>
      </div>
    </li>
    <?php endforeach; ?>
    <li class="uk-margin-top uk-border-remove">
      <a href="<?php echo esc_url( site_url( strtolower( $_POST['league'] ) . '/news' ) ); ?>" class="uk-button uk-button-primary uk-button-small">
        View All <?php echo $_POST['league']; ?> News
      </a>
    </li>
  </ul>
  <?php
  die();
}
add_action( 'wp_ajax_api_news', 'api_news_ajax' );
add_action( 'wp_ajax_nopriv_api_news', 'api_news_ajax' );


function display_summary_news_articles() {
  
  global $post;
  $league = strtolower( get_the_title( $post->post_parent ) );
  $news = get_news_data($league, 24);
  if ( is_array( $news ) && count( $news ) > 0 ) : ?>
    <div class="uk-card uk-card-default uk-card-body" data-card="league-news">
      <div class="uk-flex uk-flex-between">
          <h1 class="uk-card-title">Latest <?php echo strtoupper( $league ); ?> Team / Player News</h1>
      
          <form class="uk-search uk-search-default">
              <span class="uk-search-icon-flip" uk-search-icon></span>
              <input id="searchNews" class="uk-search-input" type="search" placeholder="Search...">
          </form>
      </div>
      
      <div uk-grid class="uk-grid-match uk-child-width-1-2@s uk-child-width-1-3@xl" uk-height-match="target: > div > article > h3">
      <?php foreach ( $news as $article ) : ?>

        <div class="article-news">
          <article class="uk-article">
            <a href="<?php echo $article['link']; ?>">
              <?php echo $article['lg_image']; ?>
            </a>
            <figure style="background-color:<?php echo '#'.$article['color']; ?>;">
              <span><?php echo $article['display']; ?>&nbsp;</span>
            </figure>
            <h3><a href="<?php echo $article['link'] ?>"><?php echo $article['title']; ?></a></h3>
            <div class="uk-text-meta">
              <span>
                <?php echo strtoupper( $league ); ?>
              </span>
              <span>&#x25cf</span>
              <span>
                <?php $date = new DateTime( $article['updated'] );
                echo $date->format('D, F j, Y'); ?>
              </span>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif;
}
add_action( 'display_summary_news_articles', 'display_summary_news_articles' );