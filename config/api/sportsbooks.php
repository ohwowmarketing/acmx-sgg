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
          $all_states[ get_sub_field( 'state' ) ] = get_state_from_code( get_sub_field( 'state' ) );
        }
      }
    }
  }
  wp_reset_query();
  return $all_states;
}

function sportsbook_location_selection( $user_state ) {
  $valid_states = get_all_sportsbook_states();
  ?>
  <div uk-grid class="uk-flex uk-flex-right">
    <div class="uk-width-auto@m">
      <div class="button-select-wrapper">
        <button id="odd-location-btn" type="button" class="uk-button uk-button-outline"><?php echo get_state_from_code( $user_state ); ?></button>
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
  </div>
  <?php
}

function display_sportsbook( $sb, $user_state ) { ?>
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
              <?php if ( $user_state !== '' ) : ?>
              <a href="<?php echo $sb['link']; ?>" type="button" class="uk-button uk-button-primary">Bet Now</a>
              <?php else: ?>
              <button type="button" class="uk-button uk-button-primary">Bet Now <small>Choose State</small></button>
              <div uk-dropdown="mode: click; pos: bottom-justify; boundary: .sbl-item; offset: 5">
                  <ul class="uk-nav uk-dropdown-nav">
                  <?php foreach ( $sb['links'] as $state_code => $state_display ) : ?>
                      <li><a href="<?php echo esc_url( site_url( '/state/?state_abbr=' . $state_code ) ); ?>"><?php echo $state_display; ?></a></li>
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

function sportsbook_promos_ajax() {
  $sbs = [];
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
          $display = get_state_from_code( $promo['state'] );
          if ( $user_state === $promo['state'] ) {
            $summary = $promo['summary'];
            $details = $promo['details'];
            $link = $promo['link'];
            $state_code = $promo['state'];
            $state_display = $display;
          }
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
    <?php if ( $user_state !== '') : ?>
      <div class="uk-flex uk-flex-right uk-margin-bottom">
        <?php sportsbook_location_selection( $user_state ); ?>
      </div>
    <?php endif; ?>
    <div class="sportsbooks-lists _alt">
      <?php 
      foreach ( $sbs as $sb ) {
        if ( $user_state === '' || ( $user_state !== '' && $user_state === $sb['state_code'] ) ) {
          display_sportsbook( $sb, $user_state );
        }
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

function sportsbook_state_section_ajax() {
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sgg-nonce') ) {
		die( 'Unable to verify sender.' );
  }
  $user_state = get_user_state();
  if ( $user_state === '' ) {
    die();
  }
  if ( have_rows( 'state_section', 6 ) ) {
    while ( have_rows( 'state_section', 6 ) ) {
      the_row();
      if ( get_sub_field( 'state' ) === $user_state ) {
        the_sub_field( 'section' );
      }
    }
  }
  die();
}
add_action( 'wp_ajax_sportsbook_state_section', 'sportsbook_state_section_ajax' );
add_action( 'wp_ajax_nopriv_sportsbook_state_section', 'sportsbook_state_section_ajax' );

function star_rating( $val, $max ) {
  ?>
    <div class="rating-container">
      <?php for ( $i = 0; $i < $max; $i++) : ?>
        <?php if ( $val >= $i + 1 ) : ?>
          <div class="rating-circle">
            <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
          </div>
        <?php elseif( $val > $i + 0.25) : ?> 
          <div class="rating-circle half">
            <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
          </div>
        <?php else : ?>
          <div class="rating-circle empty">
            <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
          </div>
        <?php endif; ?>
      <?php endfor; ?>
      <div class="rating-numeric"><?php echo $val; ?>/<?php echo $max; ?></div>
  </div>
  <?php
}

function sportsbook_header() {
  $sbs = [];
  $sportsbooks = [
    'post_type' => 'sportsbooks',
    'has_password' => false,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'meta_key' => 'header_display',
    'meta_value' => true
  ];
  query_posts( $sportsbooks );

  while ( have_posts() ) {
    the_post();
    $sbs[] = [
      'title' => get_the_title(),
      'rating' => get_field('rating'),
      'logo' => get_field('light_transparent_logo'),
      'background' => get_field('background_image'),
      'state_links' => get_field('state_affiliate_links'),
      'link' => get_field('global_affiliate_link'),
      'states' => get_field('available_states'),
      'has_review' => get_field( 'isReviewTrue' ),
      'review_url' => get_field( 'review_link_url' ),
      'bonus' => get_field('sb_promotion')
    ];
  }
  wp_reset_query();
  foreach ($sbs as $sb) : ?>
    <div class="hero-sb-item with-overlay" <?php echo $sb['background'] !== '' ? 'style="background-image: url(' . $sb['background'] . ')"' : ''; ?>>
      <div class="hero-sb-item-full-overlay"></div>
      <div class="hero-sb-content">
        <div class="hero-sb-logo">
            <img src="<?php echo $sb['logo']; ?>" alt="<?php echo $sb['title']; ?>" />
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
                    <!-- <button class="uk-button uk-button-primary uk-button-small no-right-br"><a href="#" class="uk-icon" uk-icon="icon: info; ratio: 0.8"></a></button> -->
                    <a href="<?php echo $sb['link'] !== '' ? $sb['link'] : '#bet-now'; ?>" class="uk-button uk-button-primary uk-button-small no-left-br" uk-toggle>BET NOW</a>
                </div>
            </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php 
  /*
  <div class="hero-sb-item with-overlay">
      <div class="hero-sb-content">
          <div class="hero-sb-logo">
              <img src="<?php echo get_template_directory_uri(); ?>/resources/images/sportsbooks/logos/fanduel.png" alt="FanDuel" />
          </div>
          <div class="hero-sb-data">
              <h4>FanDuel</h4>
              <p>
                  Bonus: Get your first bet RISK FREE up to $1,000
              </p>
              <?php star_rating(0, 5); ?>
              <div class="hero-sb-action">
                  <div>
                      <a href="#">Read Review</a>
                  </div>
                  <div class="uk-button-group">
                      <button class="uk-button uk-button-primary uk-button-small no-right-br"><a href="#" class="uk-icon" uk-icon="icon: info; ratio: 0.8"></a></button>
                      <a href="#bet-now" class="uk-button uk-button-primary uk-button-small no-left-br" uk-toggle>BET NOW</a>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="hero-sb-item with-overlay">
      <div class="hero-sb-content">
          <div class="hero-sb-logo">    
              <img src="<?php echo get_template_directory_uri(); ?>/resources/images/sportsbooks/logos/draftkings.png" alt="DraftKings" />
          </div>
          <div class="hero-sb-data">
              <h4>DraftKings</h4>
              <p>Bonus: Deposit BONUS up to $1,000</p>
              <div class="rating-container">
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-numeric">5/5</div>
              </div>
              <div class="hero-sb-action">
                  <div>
                      <a href="#">Read Review</a>
                  </div>
                  <div class="uk-button-group">
                      <button class="uk-button uk-button-primary uk-button-small no-right-br"><a href="#" class="uk-icon" uk-icon="icon: info; ratio: 0.8"></a></button>
                      <button class="uk-button uk-button-primary uk-button-small no-left-br">BET NOW</button>
                  </div>
              </div>
          </div>
          
      </div>
  </div>
  <div class="hero-sb-item with-overlay">
      <div class="hero-sb-content">
          <div class="hero-sb-logo">    
              <img src="<?php echo get_template_directory_uri(); ?>/resources/images/sportsbooks/logos/betmgm.png" alt="BetMGM" />
          </div>
          <div class="hero-sb-data">
              <h4>BetMGM</h4>
              <p>Bonus: Join the Action Today with BetMGM</p>
              <div class="rating-container">
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle half">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-numeric">4.5/5</div>
              </div>
              <div class="hero-sb-action">
                  <div>
                      <a href="#">Read Review</a>
                  </div>
                  <div class="uk-button-group">
                      <button class="uk-button uk-button-primary uk-button-small no-right-br"><a href="#" class="uk-icon" uk-icon="icon: info; ratio: 0.8"></a></button>
                      <button class="uk-button uk-button-primary uk-button-small no-left-br">BET NOW</button>
                  </div>
              </div>
          </div>
          
      </div>
  </div>
  <div class="hero-sb-item with-overlay">
      <div class="hero-sb-content">
          <div class="hero-sb-logo">    
              <img src="<?php echo get_template_directory_uri(); ?>/resources/images/sportsbooks/logos/betrivers.png" alt="Bet Rivers" />
          </div>
          <div class="hero-sb-data">
              <h4>Bet Rivers</h4>
              <p>Bonus: Match your first deposit up to $250</p>
              <div class="rating-container">
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle empty">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-numeric">4/5</div>
              </div>
              <div class="hero-sb-action">
                  <div>
                      <a href="#">Read Review</a>
                  </div>
                  <div class="uk-button-group">
                      <button class="uk-button uk-button-primary uk-button-small no-right-br"><a href="#" class="uk-icon" uk-icon="icon: info; ratio: 0.8"></a></button>
                      <button class="uk-button uk-button-primary uk-button-small no-left-br">BET NOW</button>
                  </div>
              </div>
          </div>
          
      </div>
  </div>
  <div class="hero-sb-item with-overlay">
      <div class="hero-sb-content">
          <div class="hero-sb-logo">    
              <img src="<?php echo get_template_directory_uri(); ?>/resources/images/sportsbooks/logos/unibet.png" alt="Unibet" />
          </div>
          <div class="hero-sb-data">
              <h4>Unibet</h4>
              <p>Bonus: $30 free bets AND a Risk Free Bet up to $600</p>
              <div class="rating-container">
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle half">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-circle empty">
                      <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/star.svg" class="rating" />
                  </div>
                  <div class="rating-numeric">3.5/5</div>
              </div>
              <div class="hero-sb-action">
                  <div>
                      <a href="#">Read Review</a>
                  </div>
                  <div class="uk-button-group">
                      <button class="uk-button uk-button-primary uk-button-small no-right-br"><a href="#" class="uk-icon" uk-icon="icon: info; ratio: 0.8"></a></button>
                      <button class="uk-button uk-button-primary uk-button-small no-left-br">BET NOW</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <?php */
}
add_action( 'sportsbook_header', 'sportsbook_header' );