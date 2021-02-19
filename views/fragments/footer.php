<footer data-globals="footer">
    <nav class="footer-directory">
        <div class="uk-container uk-container-xlarge">
            <div uk-grid class="uk-grid-large uk-flex-between uk-flex-middle uk-light">
                
                <div class="uk-width-1-1 uk-width-large@l">
                    <div class="uk-panel">
                        <?php $footerLogo = get_field('footer_logo', 'option');
                        echo wp_get_attachment_image( $footerLogo['id'], 'full' ); ?>
                        <p><?php the_field('footer_copy', 'option'); ?></p>
                        <ul class="uk-subnav uk-subnav-divider">
                            <li><a href="<?php echo __( site_url('/privacy-policy') ) ?>">Privacy Policy</a></li>
                            <li><a href="<?php echo __( site_url('/terms-of-service') ) ?>">Terms of Service</a></li>
                            <li><a href="<?php echo __( site_url('/responsible-gambling') ) ?>">Responsible Gambling</a></li>
                        </ul>
                        <p>
                            <a href="https://twitter.com/sgamblingguides" class="uk-icon-button uk-margin-small-right footer-social-icon" uk-icon="icon: twitter; ratio: 1.4"></a>
                            <!-- <a href="#" class="uk-icon-button uk-margin-small-right footer-social-icon" uk-icon="icon: facebook; ratio: 1.4"></a> -->
                            <a href="https://www.instagram.com/sportsgamblingguides/" class="uk-icon-button footer-social-icon" uk-icon="icon: instagram; ratio: 1.4"></a>
                        </p>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-expand@l">
                    <ul uk-accordion="" uk-grid class="uk-child-width-auto@m uk-grid-divider uk-flex-right@l">
                        <li class="uk-open">
                            <a href="#" class="uk-accordion-title">Sports Gambling Guides</a>
                            <div class="uk-accordion-content">
                                <ul class="uk-nav uk-column-1-2@m">
                                    <li><a href="<?php echo esc_html( home_url('/') ); ?>">Home</a></li>
                                    <li><a href="<?php echo esc_html( site_url('/best-books') ); ?>">Best Books</a></li>
                                    <li><a href="<?php echo esc_html( site_url('/odds') ); ?>">Live Odds</a></li>
                                    <li><a href="<?php echo esc_html( site_url('/contact') ); ?>">Contact Us</a></li>
                                    <li><a href="<?php echo esc_html( site_url('/sitemap') ); ?>">Sitemap</a></li>
                                </ul>
                            </div>
                        </li>                       
                        <li class="uk-open">
                            <a href="#" class="uk-accordion-title">Sports League</a>
                            <div class="uk-accordion-content">
                                <ul class="uk-nav">
                                    <li><a href="<?php echo esc_html( site_url('/nfl') ) ?>">NFL Sports Gambling Data</a></li>
                                    <li><a href="<?php echo esc_html( site_url('/nba') ) ?>">NBA Sports Gambling Data</a></li>
                                    <li><a href="<?php echo esc_html( site_url('/mlb') ) ?>">MLB Sports Gambling Data</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="uk-open">
                            <a href="#" class="uk-accordion-title">Sports Odds</a>
                            <div class="uk-accordion-content">
                                <ul class="uk-nav">
                                    <li><a href="<?php echo esc_html( site_url('/nfl/odds') ) ?>">NFL Odds & Betting Lines</a></li>
                                    <li><a href="<?php echo esc_html( site_url('/nba/odds') ) ?>">NBA Odds & Betting Lines</a></li>
                                    <li><a href="<?php echo esc_html( site_url('/mbl/odds') ) ?>">MLB Odds & Betting Lines</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </nav>
    <section class="footer">
        <div class="uk-container uk-container-xlarge">
            <div uk-grid class="uk-flex-between uk-flex-middle uk-light">

                <div class="uk-width-1-1 uk-width-auto@s">
                    <div class="uk-panel">
                        <?php echo '&copy; '. date('Y') .' '. get_bloginfo() .'. All Rights Reserved.'; ?>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-auto@s">
                    <div class="uk-flex uk-flex-middle">
                        <span class="icon-twentyone" title=""></span>
                        <img src="<?php echo _uri.'/resources/images/img-copyright-NCPG.png'; ?>" alt="NCPG">
                        <span>
                            <a href="https://sportsdata.io/" target="_blank">
                                <img src="https://sportsdata.io/assets/images/badges/sportsdataio_light_100.png?v=1" alt="Sports Data Provider">
                            </a>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </section>
</footer>