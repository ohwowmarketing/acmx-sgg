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

function display_sportsbook( $sb, $user_state ) {
  $header_link = ( $sb['link'] !== '' ) ? $sb['link'] : esc_url( site_url( 'best-books' ) );
  ?>
  <ul>
      <li class="sbl-sportsbook">
          <div class="sbl-item">
              <a href="<?php echo $header_link; ?>">
                  <img src="<?php echo $sb['image_url']; ?>" alt="<?php echo $sb['image_alt']; ?>">
              </a>
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
                  <?php foreach ( $sb['links'] as $link_state => $link_url ) : ?>
                      <li><a href="<?php echo $link_url; ?>" target="_blank"><?php echo $link_state; ?></a></li>
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
  $user_state = get_user_state();
  $sportsbooks = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'order' => 'asc'
  ];
  query_posts( $sportsbooks );

  while ( have_posts() ) : the_post();
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
        $links[ $display ] = $promo['link'];
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
    if ($user_state === '' || ($user_state !== '' && $user_state === $sb['state_code'] )) {
      display_sportsbook($sb, $user_state);
    }
  endwhile; 
  wp_reset_query();
}
add_action( 'sportsbook_promos', 'sportsbook_promos' );

function sportsbook_state_select() {
  $betting_states = get_field( 'states_operation', 'option' );
  $valid_states = [];
  foreach ($betting_states as $state) {
      $valid_states[ $state['label'] ] = $state['value'];
  }
  ?>
  <div class="button-select-wrapper">
  <?php if ( isset( $_COOKIE['state_abbr'] ) && in_array( $_COOKIE['state_abbr'], $valid_states) ) : ?>
      <button type="button" class="uk-button uk-button-outline"><?php echo array_search( $_COOKIE['state_abbr'], $valid_states ); ?></button>
  <?php else : ?>
      <button type="button" class="uk-button uk-button-outline">Choose Betting Location</button>
  <?php endif; ?>
      <div uk-dropdown="mode: click">
          <ul class="uk-nav uk-dropdown-nav">
          <?php foreach ( $betting_states as $state ) : ?>
              <li><a href="<?php echo esc_url( site_url( '/best-books/' ) . '?state_abbr=' . $state['value'] ); ?>"><?php echo $state['label'] ?></a></li>
          <?php endforeach; ?>
          </ul>
      </div>
  </div>
  <?php
}
add_action( 'sportsbook_state_select', 'sportsbook_state_select' );

//* Add Sticky Post to FAQ
