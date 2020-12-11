<?php
//! ===
//! Do not edit anything in this file unless you know what you're doing
//! ===

//* Schema.Org
function schema() {
    if ( is_single() ) :
        $type = 'Article';

    elseif ( is_author() ) :
        $type = 'ProfilePage';

    elseif ( is_search() ) :
        $type = 'SearchResultsPage';

    else :
        $type = 'WebPage';
    endif;

    $schema = 'http://schema.org/';
    echo 'itemscope itemtype="'.$schema.$type.'"';
}

//* Remove Posts & Comments (WP Navigation)
add_action('admin_menu', function() {
    // remove_menu_page( 'edit.php' ); // Remove Posts
    remove_menu_page( 'edit-comments.php' ); // Remove Comments
});

add_action( 'wp_before_admin_bar_render', 'my_admin_bar_render' );
function my_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}

//* Remove Emoji & Admin-Bar
add_filter('emoji_svg_url', '__return_false');
// add_filter('show_admin_bar', '__return_false');

//* Removes or edits the 'Protected:' part from posts titles
function remove_protected_text() {
  return __('%s');
}
add_filter( 'protected_title_format', 'remove_protected_text' );

//* Allow Unfiltered Uploads & Edit themes/plugins
define('ALLOW_UNFILTERED_UPLOADS', true);
define('DISALLOW_FILE_EDIT', true);

//* Replace <p> to <figure> wrapping image tag
function img_caption_shortcode_filter($val, $attr, $content = null) {
  extract(shortcode_atts(array(
    'id'      => '',
    'align'   => 'aligncenter',
    'width'   => '',
    'caption' => ''
  ), $attr));

  // No caption, no dice... But why width?
  if ( 1 > (int) $width || empty($caption) )
    return $val;

  if ( $id )
    $id = esc_attr( $id );

  // Add itemprop="contentURL" to image - Ugly hack
  $content = str_replace('<img', '<img itemprop="contentURL"', $content);
  return '<figure id="' . $id . '" aria-describedby="figcaption_' . $id . '" class="wp-caption ' . esc_attr($align) . '" itemscope itemtype="http://schema.org/ImageObject">' . do_shortcode( $content ) . '<figcaption id="figcaption_'. $id . '" class="wp-caption-text uk-text-small" itemprop="description">' . $caption . '</figcaption></figure>';
}
add_filter( 'img_caption_shortcode', 'img_caption_shortcode_filter', 10, 3 );

//* Format textarea for display
$filters = array('term_description');
foreach ( $filters as $filter ) {
  add_filter( $filter, 'wptexturize' );
  add_filter( $filter, 'convert_chars' );
  remove_filter( $filter, 'wpautop' );
}

//* Remove empty <p> tags
function remove_empty_p( $content ) {
  $content = force_balance_tags( $content );
  $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
  $content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
  return $content;
}
add_filter('the_content', 'remove_empty_p', 20, 1);

//* Allow VCard Uploading
function vcard_upload($mimes) {
  $mimes['vcf'] = 'text/x-vcard';
  return $mimes;
}
add_filter( 'upload_mimes', 'vcard_upload' );

//* Allow SVG Uploading
function svg_upload($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'svg_upload', 99);

//* Create sub-navigation to main menu
class subMenuWrap extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class=\"uk-navbar-dropdown\" uk-dropdown=\"offset: 0\"><ul class=\"uk-nav uk-navbar-dropdown-nav\">\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul></div>\n";
    }
}

//* Create sub-navigation to mobile menu
class mobileMenuWrap extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"uk-nav-sub\">\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}

//* Display Popular Post
function observePostViews($postID) {
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if($count=='') {
    $count = 0;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
  } else {
    $count++;
    update_post_meta($postID, $count_key, $count);
  }
}

function fetchPostViews($postID) {
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if($count=='') {
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
  return "0 View";
  }
  return $count.' Views';
}

function add_state_query_vars_filter( $vars ) {
  $vars[] = "state_abbr";
  $vars[] = "future";
  $vars[] = "league";
  return $vars;
}
add_filter( 'query_vars', 'add_state_query_vars_filter', 0 );

function state_check() {
  if ( isset( $_GET['state_abbr'] ) ) {
    setcookie( 'state_abbr', $_GET['state_abbr'], strtotime( '+1 day' ), '/' );
  } else {
    if ( ! isset( $_COOKIE['state_abbr'] ) ) {

      $isValid = false;

      if ( isset( $_SERVER['REMOTE_ADDR'] ) && $_SERVER['REMOTE_ADDR'] !== '::1') {
        $response = json_decode( 
          wp_remote_retrieve_body( 
            wp_remote_get( 'https://api.ipstack.com/' . $_SERVER['REMOTE_ADDR'] . '?access_key=df8f6bf77c6da3a5a45166435f317b92&fields=country_code,region_code' )
          )
        );
        
        if ( isset( $response ) && $response->country_code === 'US' ) {
          $states = get_field( 'states_operation', 'option' );
          $valid_states = [];
          foreach ( $states as $state ) {
            $valid_states[] = $state['value'];
          }
          if ( in_array( $response->region_code, $valid_states ) ) {
            setcookie( 'state_abbr', $response->region_code, strtotime( '+3 day' ), '/' );
            $isValid = true;
          } else {
            setcookie( 'state_abbr', $response->region_code, strtotime( '+7 day' ), '/' );
            $isValid = true;
          }
        }
      }
      
      if ( !$isValid ) {
        setcookie( 'state_abbr', 'XX', strtotime( '+7 day' ), '/' );
      }
    }
  }
}
add_action( 'init', 'state_check' );


function get_user_state() {
  $betting_states = get_field( 'states_operation', 'option' );
  $valid_states = [];
  $label = '';
  $user_state = '';
  foreach ( $betting_states as $state ) {
    $valid_states[ $state['label'] ] = $state['value'];
  }
  if ( isset( $_COOKIE['state_abbr'] ) && in_array( $_COOKIE['state_abbr'], $valid_states) ) {
    $user_state = $_COOKIE['state_abbr'];
  }
  return $user_state;
}

function display_sportsbook( $sb, $user_state ) { ?>
  <ul>
      <li class="sbl-sportsbook">
          <div class="sbl-item">
              <img src="<?php echo $sb['image_url']; ?>" alt="<?php echo $sb['image_alt']; ?>">
          </div>
      </li>
      <li class="sbl-offers">
          <div class="sbl-item"><h3><?php echo $sb['summary']; ?></h3></div>
      </li>
      <li class="sbl-details">
          <div class="sbl-item"><?php echo $sb['details']; ?></div>
      </li>
      <li class="sbl-link">
          <div class="sbl-item">
              <?php if ( $user_state !== '' ) : ?>
              <a href="<?php echo $sb['link']; ?>" type="button" class="uk-button uk-button-primary">Bet Now</a>
              <?php else: ?>
              <button type="button" class="uk-button uk-button-primary">Bet Now <small>Choose State</small></button>
              <div uk-dropdown="mode: click; pos: bottom-justify; boundary: .sbl-item; offset: 5">
                  <ul class="uk-nav uk-dropdown-nav">
                  <?php foreach ( $sb['links'] as $state_code => $state_display ) : ?>
                      <li><a href="<?php echo esc_url( site_url( '/best-books/?state_abbr=' . $state_code ) ); ?>"><?php echo $state_display; ?></a></li>
                  <?php endforeach; ?>
                  </ul>
              </div>
              <?php endif; ?>
              <?php if ( $sb['review'] !== '' ) : ?>
              <span class="uk-display-block uk-margin-small-top">
                  <a href="<?php echo $sb['review']; ?>" class="uk-button-text uk-text-bold">Full Review</a>
              </span>
              <?php endif; ?>
          </div>
      </li>
  </ul>
  <?php
}

function get_state_from_code( $code ) {
  $states = ['AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'District of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'];
  return $states[ $code ];
}

function sportsbook_promos() {
  $post = get_post();
  $selected_promo = '';
  if ( isset( $post ) && $post->post_type === 'sportsbooks_reviews' ) {
    $selected_promo = strtolower( $post->post_title );
  }
  $user_state = get_user_state();
  $sportsbooks = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'order_by' => 'menu_order',
    'order' => 'asc'
  ];
  query_posts( $sportsbooks );

  while ( have_posts() ) : the_post();
    $sportsbook_title = strtolower( get_the_title() );
    if ( 
      $selected_promo === '' ||
      ( $selected_promo !== '' && $selected_promo === $sportsbook_title )
    ) {
      $image = get_field('sb_image');
      $promos = get_field( 'promos' );
      $summary = get_field('sb_promotion');
      $details = get_field( 'sb_details' );
      $has_review = get_field( 'isReviewTrue' );
      $review_url = get_field( 'review_link_url' );
      $link = '';
      $state_code = '';
      $state_display = '';
      $links = [];
      
      if ( isset( $promos ) ) :
        foreach ( $promos as $promo ) :
          $display = get_state_from_code( $promo['state'] );
          if ( $user_state === $promo['state'] ) {
            $summary = $promo['summary'];
            $details = $promo['details'];
            $link = $promo['link'];
            $state_code = $promo['state'];
            $state_display = $display;
          }
          $links[ $promo['state'] ] = $display;
        endforeach;
      endif;
      $sb = [
        'link' => $link,
        'links' => $links,
        'state_code' => $state_code,
        'state_display' => $state_display,
        'summary' => $summary,
        'details' => $details,
        'image_url' => isset($image) ? $image['url'] : '',
        'image_alt' => isset($image) ? $image['alt'] : '',
        'review' => ($has_review) ? $review_url : ''
      ];
      if (
        $user_state === '' || 
        ( $user_state !== '' && $user_state === $sb['state_code'] )
      ) {
        display_sportsbook($sb, $user_state);
      }
    }
  endwhile; 
  wp_reset_query();
}
add_action( 'sportsbook_promos', 'sportsbook_promos' );

function odds_nav( $curr_league = 'nfl' ) {
  $curr_league = strtolower( $curr_league );
  $leagues = [ 'nfl', 'nba', 'mlb' ];
  ?>
  <div class="uk-width-expand@m">
    <ul class="uk-subnav uk-subnav-pill uk-subnav-divider odds-localnav" uk-margin>
      <?php foreach ( $leagues as $league ) : ?>
      <li<?php echo ( $league === $curr_league ) ? ' class="uk-active"' : ''; ?>><a href="<?php echo esc_url( site_url(  $league . '/odds-betting-lines' ) ); ?>"><?php echo strtoupper( $league ); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php
}
add_action( 'odds_nav', 'odds_nav', 10, 1 );

function get_all_sportsbook_states() {
  $all_states = [];
  $sportsbooks = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'order_by' => 'menu_order',
    'order' => 'asc'
  ];
  query_posts( $sportsbooks );
  while ( have_posts() ) {
    the_post();
    if ( have_rows( 'promos' ) ) {
      while ( have_rows( 'promos' ) ) {
        the_row();
        if ( ! array_key_exists( get_sub_field( 'state' ), $all_states ) ) {
          $all_states[ get_sub_field( 'state' ) ] = get_state_from_code( get_sub_field( 'state' ) );
        }
      }
    }
  }
  wp_reset_query();
  return $all_states;
}

function odds_location( $curr_league = 'nfl' ) {
  $curr_league = strtolower( $curr_league );
  $valid_states = get_all_sportsbook_states();
  ?>
  <div class="uk-width-auto@m">
    <div class="button-select-wrapper">            
      <?php
      if ( isset( $_COOKIE['state_abbr'] ) && array_key_exists( $_COOKIE['state_abbr'], $valid_states) ) : ?>
        <button type="button" class="uk-button uk-button-outline"><?php echo $valid_states[ $_COOKIE['state_abbr'] ]; ?></button>
      <?php else : ?>
        <button type="button" class="uk-button uk-button-outline">Choose Betting Location</button>
      <?php endif; ?>
      <div uk-dropdown="mode: click">
        <ul class="uk-nav uk-dropdown-nav">
          <?php foreach ( $valid_states as $state_code => $full_state_name ) : ?>
            <?php $url = 'checking-location.php?key=odds&league=' . $curr_league . '&state_abbr=' . $state_code ?>
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
add_action( 'odds_location', 'odds_location', 10, 1 );

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
  $request_url = 'https://api.sportsdata.io/v3/' . $league . '/news-rotoballer/json/RotoBallerPremiumNewsByDate/' . $api_date;
  $news_request = wp_remote_get( $request_url , $header_npk );
  $news_body    = json_decode( wp_remote_retrieve_body( $news_request ) );
  $team_request = wp_remote_get( 'https://api.sportsdata.io/v3/' . $league . '/scores/json/teams', $header_dak );
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
