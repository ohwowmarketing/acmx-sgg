<?php
function get_all_sportsbook_states() {
  $all_states = [];
  $sportsbooks = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ];
  query_posts( $sportsbooks );
  while ( have_posts() ) {
    the_post();
    if ( have_rows( 'promos' ) ) {
      while ( have_rows( 'promos' ) ) {
        the_row();
        if ( ! array_key_exists( get_sub_field( 'state' ), $all_states ) ) {
          $all_states[ get_sub_field( 'state' ) ] = api_state_from_code( get_sub_field( 'state' ) );
        }
      }
    }
  }
  wp_reset_query();
  return $all_states;
}

function display_sportsbook( $sb ) { ?>
  <ul>
      <li class="sbl-sportsbook">
          <div class="sbl-item">
              <a href="<?php echo $sb['link']; ?>"><img src="<?php echo $sb['image_url']; ?>" alt="<?php echo $sb['image_alt']; ?>"></a>
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
              <button type="button" class="uk-button uk-button-primary">Bet Now <small>Choose State</small></button>
              <div uk-dropdown="mode: click; pos: bottom-justify; boundary: .sbl-item; offset: 5">
                  <ul class="uk-nav uk-dropdown-nav">
                  <?php foreach ( $sb['links'] as $state_code => $state_display ) : ?>
                      <li><a href="<?php echo esc_url( site_url( '/state/?state_abbr=' . $state_code ) ); ?>"><?php echo $state_display; ?></a></li>
                  <?php endforeach; ?>
                  </ul>
              </div>
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

function sportsbook_promos_ajax() {
  $sbs = [];
  $post = get_post();
  $selected_promo = '';
  if ( isset( $post ) && $post->post_type === 'sportsbooks_reviews' ) {
    $selected_promo = strtolower( $post->post_title );
  }
  $sportsbooks = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ];
  query_posts( $sportsbooks );

  while ( have_posts() ) {
    the_post();
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
      
      if ( is_array( $promos ) ) {
        foreach ( $promos as $promo ) {
          $display = api_state_from_code( $promo['state'] );
          $links[ $promo['state'] ] = $display;
        }
        $sbs[] = [
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
      }
    }
  }
  wp_reset_query();
  ?>
  <div class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
    <div class="uk-flex uk-flex-between _headings">
      <h1 class="uk-card-title">Best Sportsbooks</h1>
    </div>
    <div class="sportsbooks-lists _alt">
      <?php 
      foreach ( $sbs as $sb ) {
        display_sportsbook( $sb );
      }
      ?>
    </div>
  </div>
  <?php
  die();
}
add_action( 'wp_ajax_sportsbook_promos', 'sportsbook_promos_ajax' );
add_action( 'wp_ajax_nopriv_sportsbook_promos', 'sportsbook_promos_ajax' );

function sportsbook_promos() {
  echo  '<div id="sportsbook-promos-container"><div uk-spinner="ratio: 0.5" class="uk-margin-bottom uk-margin-top"></div></div>';
}
add_action( 'sportsbook_promos', 'sportsbook_promos' );

function star_rating( $val, $max ) {
  $star = '<img src="' . get_template_directory_uri() . '/resources/images/ui/star.svg" class="rating" alt="start-rating" />';
  ?>
    <div class="rating-container">
      <?php for ( $i = 0; $i < $max; $i++) : ?>
        <?php if ( $val >= $i + 1 ) : ?>
          <div class="rating-circle"><?php echo $star; ?></div>
        <?php elseif( $val > $i + 0.25) : ?> 
          <div class="rating-circle half"><?php echo $star; ?></div>
        <?php else : ?>
          <div class="rating-circle empty"><?php echo $star; ?></div>
        <?php endif; ?>
      <?php endfor; ?>
      <div class="rating-numeric"><?php echo $val; ?>/<?php echo number_format( $max, 1 ); ?></div>
  </div>
  <?php
}

function sportsbook_dials_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
  }
  $slugs = [];
  $sportsbooks_query = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'meta_key' => 'header_display',
    'meta_value' => true
  ];
  query_posts( $sportsbooks_query );

  while ( have_posts() ) {
    the_post();
    $slugs[] = ['slug' => get_post_field( 'post_name' ), 'rating' => get_field( 'rating' ) ];
  }
  wp_reset_query();
  echo json_encode( $slugs );
  die();
}
add_action( 'wp_ajax_sportsbook_dials', 'sportsbook_dials_ajax' );
add_action( 'wp_ajax_nopriv_sportsbook_dials', 'sportsbook_dials_ajax' );

function sportsbook_modal_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
  }
  if ( ! $_POST['slug'] ) {
    die( 'Did not receive Sportsbook.' );
  }
  $sportsbook_query = [
    'post_type' => 'sportsbooks',
    'name' => $_POST['slug'],
  ];
  query_posts( $sportsbook_query );
  while ( have_posts() ) {
    the_post();
    $state_specific = get_field('state_affiliate_links');
    $in_state = false;
    $states = [];
    $bonus = get_field('sb_promotion');
    if ( $state_specific ) {
      $promos = get_field('promos');
      foreach ( $promos as $promo ) {
        $states[] = [
          'abbr' => $promo['state'], 
          'full' => api_state_from_code( $promo['state'] ), 
          'bonus' => $promo['summary'],
          'link' => $promo['link']
        ];
      }
    } else {
      $link = get_field('global_affiliate_link');
      $available = get_field('available_states');
      foreach ( $available as $state ) {
        $temp_state = api_state_from_code( $state );
        $states[] = [ 'abbr' => $state, 'full' => $temp_state, 'bonus' => '', 'link' => $link ];
      }
      $promos = [];
    }
    $sb = [
      'in_state' => $in_state,
      'title' => get_the_title(),
      'logo' => get_field('light_transparent_logo'),
      'state_specific' => $state_specific,
      'link' => $link,
      'states' => $states,
      'bonus' => $bonus,
      'promos' => $promos
    ];
    echo json_encode( $sb );
  }
  wp_reset_query();
  die();
}
add_action( 'wp_ajax_sportsbook_modal', 'sportsbook_modal_ajax' );
add_action( 'wp_ajax_nopriv_sportsbook_modal', 'sportsbook_modal_ajax' );

function sportsbook_info_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
  }
  if ( ! $_POST['slug'] ) {
    die( 'Did not receive Sportsbook.' );
  }
  $sportsbook_query = [
    'post_type' => 'sportsbooks',
    'name' => $_POST['slug'],
  ];
  query_posts( $sportsbook_query );
  while ( have_posts() ) {
    the_post();
    $sb = [
      'slug' => get_post_field( 'post_name' ),
      'title' => get_the_title(),
      'rating' => get_field( 'rating' ),
      'ratings' => get_field( 'ratings' ),
      'logo' => get_field( 'light_transparent_logo' ),
      'bonus' => get_field( 'sb_promotion' ),
      'details' => get_field( 'sb_details' ),
      'description' => get_field( 'description' ),
      'state_links' => get_field( 'state_affiliate_links' ),
      'url' => get_field( 'global_affiliate_link' ),
    ];
    echo json_encode( $sb );
  }
  wp_reset_query();
  die();
}
add_action( 'wp_ajax_sportsbook_info', 'sportsbook_info_ajax' );
add_action( 'wp_ajax_nopriv_sportsbook_info', 'sportsbook_info_ajax' );

function get_width_class( $columns, $is_mobile ) {
  $width = 'uk-child-width-1-' . $columns;
  if ( ! $is_mobile ) {
    $width .= '@l';
  }
  return $width;
}

function sportsbook_header() {
  $mobile = get_field('sportsbooks_per_page_mobile', 'option');
  $mobile_width = get_width_class( $mobile, true );
  $desktop = get_field('sportsbooks_per_page_desktop', 'option');
  $desktop_width = get_width_class( $desktop, false );
  
  $sbs = [];
  $sportsbooks_query = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'meta_key' => 'header_display',
    'meta_value' => true
  ];
  query_posts( $sportsbooks_query );

  while ( have_posts() ) {
    the_post();
    $sbs[] = [
      'slug' => get_post_field( 'post_name' ),
      'title' => get_the_title(),
      'rating' => get_field( 'rating' ),
      'ratings' => get_field( 'ratings' ),
      'description' => get_field( 'description' ),
      'logo' => get_field( 'light_transparent_logo' ),
      'background' => get_field( 'background_image' ),
      'state_links' => get_field( 'state_affiliate_links' ),
      'url' => get_field( 'global_affiliate_link' ),
      'states' => get_field( 'available_states' ),
      'has_review' => get_field( 'isReviewTrue' ),
      'review_url' => get_field( 'review_link_url' ),
      'bonus' => get_field( 'sb_promotion' )
    ];
  }
  wp_reset_query(); ?>
  <ul class="uk-slider-items <?php echo $mobile_width . ' uk-child-width-1-2@s uk-child-width-1-3@m ' . $desktop_width; ?>" uk-height-match="target: > li > div > .hero-sb-content > .hero-sb-data">
    <?php foreach ($sbs as $sb) : ?>
    <li>
      <div class="hero-sb-item with-overlay" <?php echo $sb['background'] !== '' ? 'style="background-image: url(' . $sb['background'] . ')"' : ''; ?>>
        <div class="hero-sb-item-full-overlay"></div>
        <div class="hero-sb-content">
          <div class="hero-sb-logo">
            <?php if ($sb['state_links']) : ?>
            <a href="#bet-now" data-sbid="<?php echo $sb['slug']; ?>" class="hero-sb-bet-now">
              <img src="<?php echo $sb['logo']; ?>" alt="<?php echo $sb['title']; ?>" />  
            </a>
            <?php else : ?>
            <a href="<?php echo $sb['url']; ?>">
              <img src="<?php echo $sb['logo']; ?>" alt="<?php echo $sb['title']; ?>" />  
            </a>
            <?php endif; ?>
          </div>
          <div class="hero-sb-data">
            <h4><?php echo $sb['title']; ?></h4>

            <?php if ( $sb['bonus'] ) : ?>
            <p><?php echo $sb['bonus']; ?></p>
            <?php endif; ?>

            <?php if ( $sb['rating'] ) : ?>
              <?php star_rating($sb['rating'], 5); ?>
            <?php endif; ?>
            
            <div class="hero-sb-action">
              <div>
                <?php if ( $sb['has_review'] ) : ?>
                  <a href="<?php echo $sb['review_url']; ?>">Read Review</a>
                <?php endif; ?>
              </div>
              <div class="uk-button-group">
                <button class="uk-button uk-button-primary uk-button-small no-right-br"><a href="#sb-info" data-sbid="<?php echo $sb['slug']; ?>" class="uk-icon sb-more-info" uk-icon="icon: info; ratio: 0.8"></a></button>
                <?php if ($sb['state_links']) : ?>
                <a href="#bet-now" data-sbid="<?php echo $sb['slug']; ?>" class="uk-button uk-button-primary uk-button-small no-left-br hero-sb-bet-now" uk-toggle>BET NOW</a>
                <?php else : ?>
                <a href="<?php echo $sb['url']; ?>" class="uk-button uk-button-primary uk-button-small no-left-br">BET NOW</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php
}
add_action( 'sportsbook_header', 'sportsbook_header' );


function sportsbook_daily_promos() {
  if ( get_field( 'enable_daily_promos', 'option' ) ) :
    $daily_promo_image = get_field( 'daily_promotion_image', 'option' );
    $daily_promos = [];
    $sportsbooks_query = [
      'post_type' => 'sportsbooks',
      'has_password' => false,
      'posts_per_page' => -1,
      'orderby' => 'menu_order',
      'order' => 'ASC',
      'meta_key' => 'header_display',
      'meta_value' => true
    ];
    query_posts( $sportsbooks_query );

    while ( have_posts() ) {
      the_post();
      if ( get_field( 'daily_promo' ) ) {
        $url = NULL;
        if ( get_field( 'daily_promo_custom' ) && get_field( 'daily_promo_custom_link' ) !== '' ) {
          $url = get_field( 'daily_promo_custom_link' );
        } elseif ( ! get_field( 'state_affiliate_links' ) ) {
          $url = get_field( 'global_affiliate_link' );
        }
        $daily_promos[] = [
          'img' => '<img src="' . get_field( 'daily_promo' ) . '" alt="' . get_the_title() . '" />',
          'url' => $url,
          'id' => get_field( 'state_affiliate_links' ) ? get_post_field( 'post_name' ) : NULL
        ];
      }
    }
    wp_reset_query();
    if ( count( $daily_promos ) > 0 ) : ?>
      <?php /* <div uk-grid class="uk-grid-collapse uk-child-width-expand daily-promos-trigger"> 
        <div>
          <a href="#" class="daily-trigger"><img src="<?php echo $daily_promo_image; ?>" style="max-width: 100%" /></a>
        </div>
      </div>
      <div uk-grid class="uk-grid-collapse uk-child-width-expand daily-promos" style="display: none;"> */ ?>
      <div uk-grid class="uk-grid-medium uk-flex-center daily-promos">
        <div class="uk-width-1-1">
          <img src="<?php echo $daily_promo_image; ?>" style="max-width: 100%" alt="SGG Promotions Banner" />
        </div>
        <?php foreach ( $daily_promos as $promo ) : ?>
          <div class="uk-child-width-expand">
            <a href="<?php echo $promo['url'] !== NULL ? $promo['url'] : '#bet-now'; ?>" <?php echo $promo['id'] !== NULL ? 'data-sbid="' . $promo['id'] . '" class="hero-sb-bet-now"' : ''; ?>>
              <?php echo $promo['img']; ?>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif;
  endif;
}
add_action( 'sportsbook_daily_promos', 'sportsbook_daily_promos' );
