<?php
//! ===
//! Do not edit anything in this file unless you know what you're doing.
//! ===

//* Load All Functions
$fn_config = [
    'config/actions.php',
    'config/assets.php',
    'config/acf.php',
    'config/controller.php',
    'config/yoast.php',
    'config/theme.php',
    'config/editor.php',
    'config/rss.php',
    'config/api.php',
    'config/api/data.php',
    'config/api/sportsbooks.php',
    'config/api/news.php',
    'config/api/odds.php',
    'config/api/against-the-spread.php',
    'config/api/futures.php',
    'config/auth/login.php',
    'config/auth/register.php',
    'config/author.php',
];
foreach ( $fn_config as $config ) {

    if ( ! $files = locate_template( $config ) ) {
        trigger_error( sprintf( _( 'Error location %s for inclusion', 'acmx' ), $config ), E_USER_ERROR );
    }
    require_once $files;

}
unset($config, $files);

//!
//! Global Definitions
//!

define ( '_uri',  get_template_directory_uri() );
define ( '_site', site_url() );

define ( '_styles',  _uri.'/resources/styles/' );
define ( '_scripts', _uri.'/resources/scripts/' );

define ( '_page',    'views/pages/' );
define ( '_single',  'views/singles/' );
define ( '_terms',   'views/taxonomies/' );

define ( '_article', 'views/taxonomies/leagues-news' );
// define ( '_article', 'views/taxonomies/league-article' );

define ( '_promo', 'views/fragments/promo' );
define ( '_nav', 'views/fragments/menu' );
define ( '_hdr', 'views/fragments/header' );
define ( '_ftr', 'views/fragments/footer' );
define ( '_rtr', 'views/fragments/router' );
define ( '_mob', 'views/fragments/mobile' );

define ( '_noie', 'views/attributes/edge' );
define ( '_nojs', 'views/attributes/noscript');
define ( '_kuki', 'views/attributes/cookies');

define ( 'widget',   'views/widgets/' );
define ( 'includes', 'includes/' );
define ( 'auth', 'config/auth/' );
