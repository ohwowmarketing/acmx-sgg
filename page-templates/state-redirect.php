<?php 
/* Template Name: State Redirect */
echo wp_get_referer();
if ( isset( $_GET['state_abbr'] ) ) {
  $valid_states = get_all_sportsbook_states();
  if ( array_key_exists( $_GET['state_abbr'], $valid_states ) ) {
    setcookie( 'state_abbr', $_GET['state_abbr'], strtotime( '+14 day' ), '/' );
  }
}
$redirect = site_url('best-books');
// if ( $_GET['re'] !== 'b' && wp_get_referer() ) {
//   if ( stripos( wp_get_referer(), 'odds' ) !== false ) {
//     $redirect = wp_get_referer();
//   }
// }
wp_safe_redirect( $redirect );
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php
		wp_body_open();
    ?>
    <main id="site-content" role="main">
      <?php if ( have_posts() ) {
        while ( have_posts() ) {
          the_post();
          ?>
          <div class="entry-content">
            <?php the_content(); ?>
		      </div>
		    <?php }
      } ?>
    </main>
    <?php wp_footer(); ?>
  </body>
</html>
      