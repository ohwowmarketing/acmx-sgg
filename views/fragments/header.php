<?php
global $post;
$post_slug = $post->post_name;

?>
<!--<div><?php echo $post_slug; ?></div>-->
<?php if ($post_slug === 'header-test' || is_page([ 810 ])) : ?>

<div id="bet-now" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <div class="bet-now-header">
            <div class="bet-now-sb-logo">
                <img src="<?php echo get_template_directory_uri(); ?>/resources/images/sportsbooks/logos/fanduel.png" alt="FanDuel" />
            </div>
        </div>
        <img src="https://sgg.local/wp-content/uploads/2020/07/site-SGG-logo.png" class="modal-logo">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Sportsbook</h2>
        <p>Grab the code <strong>SGGPA250</strong> and we'll match your  first deposit up to $250</p>
        <button class="uk-button uk-button-primary uk-button-small" type="button">Continue</button>
    </div>
</div>
<header class="hero-sb">
    <div class="uk-container">
        <h1>Best Sports Betting Sites</h1>
        <div uk-grid class="uk-grid-collapse uk-child-width-expand@m uk-text-center">
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