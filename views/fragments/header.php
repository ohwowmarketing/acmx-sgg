<?php
global $post;
$post_slug = $post->post_name;
if ($post_slug === 'header-test') : 

?>
<div id="bet-now" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <div class="bet-now-header">
            <div class="bet-now-sb-logo"></div>
        </div>
        <img src="https://sgg.local/wp-content/uploads/2020/07/site-SGG-logo.png" class="modal-logo">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title"></h2>
        <p></p>
        <a class="uk-button uk-button-primary uk-button-small" type="button">Continue</a>
    </div>
</div>
<header class="hero-sb">
    <div class="uk-container uk-container-xlarge">
        <h1>Best Sports Betting Sites</h1>
        <div uk-grid class="uk-grid-collapse uk-child-width-expand@m uk-text-center">
            <?php do_action( 'sportsbook_header' ); ?>
        </div>
    </div>
</header>
<?php
endif;

if ( is_page([ 2 ]) ) : 

$home_bg = get_field('hero_background');
$home_hc = get_field('hero_content'); ?>

<header class="hero" data-hero="home">
    <div class="uk-background-cover" data-src="<?php echo esc_url( $home_bg['url'] ); ?>" uk-img>
        <div class="uk-container uk-container-xlarge">
            
            <div class="uk-position-cover uk-overlay-primary"></div>
            <div class="uk-width-xlarge uk-position-z-index">
                <?php echo $home_hc; ?>
            </div>

        </div>
    </div>
</header>


<?php elseif ( is_page([ 6 ]) && ! is_front_page() ) :

$books_code = get_field('hero_shortcode');
$books_hc   = get_field('hero_content'); ?>

<header class="hero sportsbook" data-hero="page">
    <div class="uk-container uk-container-xlarge">
        <div uk-grid class="uk-flex-middle">
            
            <div class="uk-width-expand@m">
                <div class="uk-panel">
                    <?php echo $books_hc; ?>
                </div>
            </div>
            <div class="uk-width-xlarge uk-visible@m uk-position-relative">
                <?php echo do_shortcode( $books_code ); ?>
                <div class="uk-text-meta uk-text-center">Click On Each State for More Info</div>
                <div class="uk-text-center _legend">
                    <ul class="uk-list uk-text-meta">
                        <li>SGG Licensed</li>
                        <li>Full Mobile Betting</li>
                        <li>Mobile Betting with Restrictions</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</header>

<?php elseif ( is_page([ 23, 25, 27 ]) ) :

$league_bg = get_field('hero_background');
$league_hc = get_field('hero_content'); ?>

<header class="hero" data-hero="league">
    <div class="uk-background-cover" data-src="<?php echo esc_url( $league_bg['url'] ); ?>" uk-img>
        <div class="uk-container uk-container-xlarge">
            
            <div class="uk-position-cover uk-overlay-primary"></div>
            <div class="uk-width-2xlarge uk-position-z-index">
                <?php echo $league_hc; ?>
            </div>

        </div>
    </div>
</header>

<?php endif; ?>