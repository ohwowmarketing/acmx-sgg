<?php 
// Widget 1 : SGG
while ( have_rows( 'IG_widgetOne', 'option' ) ) : the_row(); 

    $toggle = get_sub_field( 'display_toggle' ); 
    if ( $toggle == 1 ) : ?>
    <div class="uk-card uk-card-default uk-card-body" data-card="instagram">
        <?php
        $txt = get_field('instagram_txt_heading', 'option');
        $bg  = get_field('instagram_bg_heading', 'option');

        if ( !empty($txt) ) : ?>
        <div class="uk-text-center">
            <h3><span uk-icon="icon: instagram;"></span> <br> <a href="https://www.instagram.com/sportsgamblingguides" target="_blank" rel="follow"><?php echo $txt; ?></a></h3>
        </div>
        <?php elseif ( !empty($bg) ) : ?>
            <div class="uk-text-center uk-margin-bottom">
                <?php echo wp_get_attachment_image( $bg['id'], [ 300, 9999 ] ); ?>
            </div>
        <?php else : ?>
        <div class="uk-text-center">
            <h3><span uk-icon="icon: instagram;"></span> <br> <a href="https://www.instagram.com/sportsgamblingguides" target="_blank" rel="follow">Follow Us on Instagram</a></h3>
        </div>
        <?php endif; 

        echo do_shortcode( '[elfsight_instagram_feed id="1"]' ); ?>
    </div>
    <?php endif;

endwhile; 

// Widget 2 : Off The Board
while ( have_rows( 'IG_widgetTwo', 'option' ) ) : the_row();

    $toggle = get_sub_field( 'display_toggle' ); 
    if ( $toggle == 1 ) : ?>
    <div class="uk-card uk-card-default uk-card-body" data-card="instagram">
        <?php
        $txt = get_field('instagram_txt_heading', 'option');
        $bg  = get_field('instagram_bg_heading', 'option');

        if ( !empty($txt) ) : ?>
        <div class="uk-text-center">
            <h3><span uk-icon="icon: instagram;"></span> <br> <a href="https://www.instagram.com/sportsgamblingguides" target="_blank" rel="follow"><?php echo $txt; ?></a></h3>
        </div>
        <?php elseif ( !empty($bg) ) : ?>
            <div class="uk-text-center uk-margin-bottom">
                <?php echo wp_get_attachment_image( $bg['id'], [ 300, 9999 ] ); ?>
            </div>
        <?php else : ?>
        <div class="uk-text-center">
            <h3><span uk-icon="icon: instagram;"></span> <br> <a href="https://www.instagram.com/sportsgamblingguides" target="_blank" rel="follow">Follow Us on Instagram</a></h3>
        </div>
        <?php endif; 

        echo do_shortcode( '[elfsight_instagram_feed id="8"]' ); ?>
    </div>
    <?php endif;

endwhile;