<div id="bet-now" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <div class="bet-now-header">
            <div class="bet-now-sb-logo"></div>
        </div>
        <img src="<?php echo get_template_directory_uri(); ?>/resources/images/site-SGG-logo.png" alt="<?php bloginfo(); ?>" class="modal-logo">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title"><span></span></h2>
        <div class="bonus"></div>
        <div uk-form-custom="target: true" class='select-container'>
            <select class='uk-select state-select'></select>
            <button type="button" class="uk-button uk-button-primary uk-button-small">Select State</button>
        </div>
        <a class="uk-button uk-button-primary uk-button-small continue" type="button">Continue</a>
    </div>
</div>
<aside class="uk-section uk-section-secondary uk-section-xsmall --module-banner">
    <div class="uk-container uk-text-center uk-animation-scale-up">
        The #1 Source for Social Media Content & Advertising in Sports Gambling
    </div>
</aside>

<?php 
$hidden = true;
if ( !is_singular( 'sgg_promo' ) && !is_page( 2743 ) ) {
    $hidden = false;
} ?>

<header class="hero-sb" <?php echo ($hidden) ? 'hidden' : ''; ?>>
    <div class="uk-container uk-container-xlarge">
        <h1>Best Sports Betting Sites</h1>
        <div class="sportsbook-header-slider-section uk-position-relative uk-visible-toggle uk-text-center" tabindex="-1" uk-slider="sets: true; finite: true">
            <div class="uk-slider-container">
                <?php do_action( 'sportsbook_header' ); ?>
                <a class="uk-position-center-left uk-position-small bg-grad" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                <a class="uk-position-center-right uk-position-small bg-grad" href="#" uk-slidenav-next uk-slider-item="next"></a>
            </div>
        </div>
        <?php // ScrollTo for Guide only 
        if ( is_singular( 'sports_guides' ) ) : ?>
        <div class="uk-flex uk-flex-center __scrollTo">
            <a href="#guide-content" type="button" role="button" class="uk-button uk-button-primary uk-button-small" uk-scroll="offset: 100">
                <?php if ( wp_is_mobile() ) {
                    echo 'Tap to Article';
                } else {
                    echo 'Skip to Article';
                } ?>
            </a>
        </div>
        <?php endif; ?>        
        <div class="sb-info">
            <div uk-grid class="hero-sb-info uk-grid-collapse uk-flex-center uk-flex-middle">
                <div class="uk-width-1-1 uk-width-1-2@s uk-width-1-3@m">
                    <div class="sb-info-col">
                        <h2><span></span> Review & Signup Offer</h2>
                        <table class="uk-table uk-table-responsive"><tbody></tbody></table>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-1-2@s uk-width-1-3@m">
                    <div class="sb-info-col uk-text-center">
                        <div id="top-loader" class="rating-dial"></div>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-1-3@m">
                    <div class="sb-info-col">
                        <div class="sb-info-description"><p></p></div>
                    </div>
                </div>
                <div class="uk-width-2-3@s">
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
                <div class="uk-width-1-3@s">
                    <div class="sb-info-row --logo"></div>
                </div>
                <div class="uk-width-auto">
                    <div class="sb-info-col">
                        <button class="uk-modal-close-default close-info" type="button" uk-close></button>
                    </div>
                </div>
            </div>
            <div class="sb-reviews-link">
                <?php if ( ! is_page([ 2053, 2168, 2166, 2178, 2174, 2182, 2172, 2635 ]) && ! is_singular( 'cappers_corner' ) ) { // Do not show
                    do_action( 'sportsbook_daily_promos' );
                } ?>
            </div>
        </div>
    </div>
</header>

