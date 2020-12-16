<?php
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
      
      if ( is_array( $promos )) :
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
  die();
}
add_action( 'wp_ajax_sportsbook_promos', 'sportsbook_promos_ajax' );
add_action( 'wp_ajax_nopriv_sportsbook_promos', 'sportsbook_promos_ajax' );

function sportsbook_promos() {
  echo  '<div id="sportsbook-promos-container"></div>';
}
add_action( 'sportsbook_promos', 'sportsbook_promos' );
