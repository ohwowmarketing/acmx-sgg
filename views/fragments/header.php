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
        <img src="<?php echo get_template_directory_uri(); ?>/resources/images/site-SGG-logo.png" class="modal-logo">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title"><span></span></h2>
        <div class="outside">It appears that you are visiting from an area where <span class="primary">SportsGamblingGuides.com</span> does not yet feature this sportsbook.</div>
        <div class="bonus"></div>
        
        <div uk-form-custom="target: true" class='select-container'>
            <select class='uk-select state-select'></select>
            <button type="button" class="uk-button">Change State</button>
        </div>
        <a class="uk-button uk-button-primary uk-button-small continue" type="button">Continue</a>
    </div>
</div>
<header class="hero-sb">
    <div class="uk-container uk-container-xlarge">
        <h1>Best Sports Betting Sites</h1>
        <div uk-grid class="uk-grid-collapse uk-child-width-expand@m uk-text-center">
            <?php do_action( 'sportsbook_header' ); ?>
        </div>
        <div class="sb-info">
            <div uk-grid class="hero-sb-info uk-grid-collapse uk-child-width-expand@m uk-visible@m">
                <div class="uk-width-expand@m">
                    <div class="sb-info-col">
                        <h2><span></span> Review & Signup Offer</h2>
                        <table><tbody></tbody></table>
                    </div>
                </div>
                <div class="uk-width-auto@m">
                    <div class="sb-info-col uk-text-center">
                        <div id="top-loader" class="rating-dial"></div>
                    </div>
                </div>
                <div class="uk-width-expand@m">
                    <div class="sb-info-col">
                        <div class="sb-info-description"><p></p></div>
                    </div>
                </div>
                <div class="uk-width-auto@m">
                    <div class="sb-info-col">
                        <button class="uk-modal-close-default close-info" type="button" uk-close></button>
                    </div>
                </div>
                <div class="uk-width-1-1">
                    <div class="sb-info-row">
                        <div>
                            <a href="#bet-now" data-sbid="" class="uk-button uk-button-primary uk-button-small hero-sb-bet-now" uk-toggle="">Bet Now</a>
                        </div>
                        <div class="sb-info-terms">
                            <p></p>
                            <span>Terms and Conditions Apply</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sb-reviews-link">
                <!-- <a href="#">All Sportsbook Reviews</a> -->
            </div>
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