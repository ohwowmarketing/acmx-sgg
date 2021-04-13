<?php
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
  $news_request = wp_remote_get( 'https://fly.sportsdata.io/v3/' . strtolower( $league ).'/news-rotoballer/json/RotoBallerPremiumNews', $header_npk );
  $news_list = json_decode( wp_remote_retrieve_body( $news_request ) );
  $latest_news = array_slice( $news_list, 0, $limit );
  
  // Tier 1 - Score/Teams
  $team_request = wp_remote_get( 'https://fly.sportsdata.io/v3/' . strtolower( $league ).'/scores/json/teams', $header_dak );
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

function get_news_request( $league, $date = NULL ) {
  global $post;
  $league = strtolower( $league );
  $new_date = new DateTime();
  if ( $date !== NULL ) {
    $new_date = new DateTime( $date );
  }
  $api_date = strtoupper( $new_date->format('Y-M-d') );
  include( locate_template( includes . 'league-keys.php', false, true ) );
  switch ( $league ) {
    case 'nfl':
      $header_npk = $nfl_header_npk;
      $header_dak = $nfl_header_dak;
      break;
    case 'mlb':
      $header_npk = $mlb_header_npk;
      $header_dak = $mlb_header_dak;
      break;
    case 'nba':
      $header_npk = $nba_header_npk;
      $header_dak = $nba_header_dak;
      break;
  }
  $request_url = 'https://fly.sportsdata.io/v3/' . $league . '/news-rotoballer/json/RotoBallerPremiumNewsByDate/' . $api_date;
  $news_request = wp_remote_get( $request_url , $header_npk );
  $news_body    = json_decode( wp_remote_retrieve_body( $news_request ) );
  $team_request = wp_remote_get( 'https://fly.sportsdata.io/v3/' . $league . '/scores/json/teams', $header_dak );
  $team_body    = json_decode( wp_remote_retrieve_body( $team_request ) );
  $response = [
    'news_request' => $news_request,
    'news_body' => $news_body,
    'team_request' => $team_request,
    'team_body' => $team_body
  ];
  return $response;
}

function get_news_article( $league, $date, $article_id, $image_id ) {
  global $post;
  $league = strtolower( $league );
  $new_date = new DateTime( $date );
  $response = get_news_request( $league, $new_date->format('Y-m-d') );
  
  if ( wp_remote_retrieve_response_code( $response['news_request'] ) == 200 ) {
    foreach ( $response['news_body'] as $news ) {
      if ( (string) $news->NewsID === $article_id ) {
        $result = [
          'team_id' => $news->TeamID,
          'title' => $news->Title,
          'content' => $news->Content,
          'date' => $news->Updated,
          'link' => $news->Url,
          'team_name' => '',
          'team_city' => '',
          'team_full_name' => '',
          'team_logo' => '',
          'team_color' => '',
          'image_url' => '',
          'image_width' => '',
          'image_height' => ''
        ];

        foreach ( $response['team_body'] as $team ) {
          if ( $team->TeamID === $result['team_id'] ) {
            $result['team_name'] = $team->Name;
            $result['team_city'] = $team->City;
            $result['team_full_name'] = ($league === 'nfl') ? $team->FullName : $team->Name;
            $result['team_logo'] = $team->WikipediaLogoUrl;
            $result['team_color'] = $team->PrimaryColor;
          }
        }

        if ( is_numeric( $image_id ) ) {
          [$image_url, $image_width, $image_height] = wp_get_attachment_image_src( $image_id, 'full' );
          $result['image_url'] = $image_url;
          $result['image_width'] = $image_width;
          $result['image_height'] = $image_height;
        }
        return $result;
      }
    } 
  }
  return false;
}

function display_full_news_article() {
  global $post;
  $league = strtolower( single_cat_title( '', false ) );
  $date = new DateTime( get_query_var( 'date' ) );
  $date_format = $date->format('Y-m-d');
  $api_date = strtoupper( $date->format('Y-M-d') );
  $article_id = get_query_var( 'news' );
  $image_id  = get_query_var( 'img' );
  $article = get_news_article( $league, $date_format, $article_id, $image_id );
  if ( $article ) : ?>
    <figure>
        <?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
        <figcaption style="background-color:<?php echo '#'.$article['team_color']; ?>;">
            <span>
                <?php echo ! empty($article['team_full_name']) ? $article['team_full_name'] : $article['team_city'] .' '. $article['team_name'] ; ?>
            </span>
        </figcaption>
    </figure>
    <h1><?php echo $article['title']; ?></h1>
    <p><?php echo $article['content']; ?></p>
    <div class="uk-text-meta uk-flex uk-flex-top uk-flex-between">
        <div>
            <span>
                <?php echo strtoupper( $league ); ?>
            </span>
            <span>&#x25cf</span>
            <span>
                <?php 
                  $date_format = new DateTime( $article['date'] );
                  echo $date_format->format( 'D, F j, Y' );
                ?>
            </span>
        </div>
        <div id="accreditation">
            <span>Powered by</span>
            <a href="<?php echo esc_url( $article['link'] ); ?>"><img src="<?php echo _uri.'/resources/images/accreditation/rotoballer-black.png' ?>" height="50" alt="RotoBaller Premium News"></a>
        </div>
    </div>
  <?php endif;
}
add_action( 'display_full_news_article', 'display_full_news_article' );

function get_news_article_summaries( $league, $date ) {
  global $post;
  $results = [];
  $league = strtolower( $league );
  $new_date = new DateTime( $date );
  $response = get_news_request( $league, $new_date->format('Y-m-d') );
  if ( wp_remote_retrieve_response_code( $response['news_request'] ) == 200 ) {
    
    foreach ( $response['news_body'] as $news ) {
      $result = [
        'team_id' => $news->TeamID,
        'title' => $news->Title,
        'content' => $news->Content,
        'date' => $news->Updated,
        'link' => $news->Url,
        'team_name' => '',
        'team_city' => '',
        'team_full_name' => '',
        'team_logo' => '',
        'team_color' => '',
        'image_url' => '',
        'image_width' => '',
        'image_height' => ''
      ];

      foreach ( $response['team_body'] as $team ) {
        if ( $team->TeamID === $result['team_id'] ) {
          $result['team_name'] = $team->Name;
          $result['team_city'] = $team->City;
          $result['team_full_name'] = ($league === 'nfl') ? $team->FullName : $team->Name;
          $result['team_logo'] = $team->WikipediaLogoUrl;
          $result['team_color'] = $team->PrimaryColor;
        }
      }

      if ( isset( $image_id ) && is_numeric( $image_id ) ) {
        [$image_url, $image_width, $image_height] = wp_get_attachment_image_src( $image_id, 'full' );
        $result['image_url'] = $image_url;
        $result['image_width'] = $image_width;
        $result['image_height'] = $image_height;
      }
        
      $results[] = $result;
    } 
  }
  return $results;
}

function custom_article_presenter( $presenters ) {
  global $post;
  if ( ! is_archive() ) {
      return $presenters;
  }
  $remove_list = [
      'Title_Presenter', // og:title
      'Meta_Description_Presenter', // description
      'Description_Presenter', // og:description
      'Image_Presenter', // og:image, og:image:width, og:image:height
      'Url_Presenter', // og:url
      // 'Robots_Presenter', 
      // 'Canonical_Presenter', 
      // 'Rel_Prev_Presenter', 
      // 'Rel_Next_Presenter', 
      // 'Locale_Presenter', 
      // 'Type_Presenter', 
      // 'Title_Presenter', 
      // 'Site_Name_Presenter', 
      // 'FB_App_ID_Presenter', 
      // 'Card_Presenter', 
      // 'Title_Presenter', 
      // 'Description_Presenter', 
      // 'Site_Presenter', 
      // 'Schema_Presenter', 
  ];

  $new_presenters = [];

  for( $i = 0; $i <= count( $presenters ); $i++ ) {
    if ( isset( $presenters[ $i ] ) && is_object( $presenters[ $i ] ) ) {
      $raw_presenter_name = get_class( $presenters[ $i ] );
      if ( isset( $raw_presenter_name ) ) {
        $presenter_name = substr( $raw_presenter_name, strrpos( $raw_presenter_name, "\\" ) + 1 );
        if ( ! in_array( $presenter_name, $remove_list ) ) {
          $new_presenters[] = $presenters[ $i ];
        }
      }
    }
  }

  return $new_presenters;
}
add_filter( 'wpseo_frontend_presenters', 'custom_article_presenter' );

function get_article_url() {
  global $post;
  if ( is_archive() ) {
    $news_id = get_query_var( 'news' );
    $img_id  = get_query_var( 'img' );
    $date = get_query_var( 'date' );
    $cat = strtolower( single_cat_title( '', false ) );
    $url = 'article/' . $cat . '?league=' . $cat . '&date=' . $date  . '&news=' . $news_id . '&img=' . $img_id;
    return site_url( $url );
  }
  return '';
}

function article_open_graph() {
  global $post;
  if ( ! is_archive() ) {
      return;
  }
  global $post;
  $league = strtolower( single_cat_title( '', false ) );
  $date = new DateTime( get_query_var( 'date' ) );
  $date_format = $date->format('Y-m-d');
  $api_date = strtoupper( $date->format('Y-M-d') );
  $article_id = get_query_var( 'news' );
  $image_id  = get_query_var( 'img' );
  $article = get_news_article( $league, $date_format, $article_id, $image_id );
  $summary = mb_strimwidth( $article['content'], 0, 120, "..." );
  $date = new DateTime( $article['date'] );
  $formatted_date = $date->format('c');
  echo '<meta property="og:url" content="' . get_article_url() . '">';
  echo '<meta property="og:title" content="' . $article['title'] . ' - Sports Gambling Guides">';
  echo '<meta name="description" content="' . $summary . '">';
  echo '<meta property="og:description" content="' . $summary . '">';
  echo '<meta property="article:modified_time" content="' . $formatted_date . '">';
  if ( $article['image_url'] ) {
    echo '<meta property="og:image" content="' . $article['image_url'] . '">';
    echo '<meta property="og:image:width" content="' . $article['image_width'] . '">';
    echo '<meta property="og:image:height" content="' . $article['image_height'] . '">';
  }
}
add_action( 'wp_head', 'article_open_graph' );