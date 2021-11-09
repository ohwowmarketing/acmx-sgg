<?php 
// Widget 1 : SGG
while ( have_rows( 'IG_widgetOne', 'option' ) ) : the_row(); 

    $toggle = get_sub_field( 'display_toggle', 'option' ); 
    if ( $toggle == 1 ) : ?>
    <div class="uk-card uk-card-default uk-card-body" data-card="instagram">
        <?php
        $txt = get_sub_field('instagram_txt_heading', 'option');
        $bg  = get_sub_field('instagram_bg_heading', 'option');
        $url = get_sub_field('instagram_url_heading', 'option');
        $sc  = get_sub_field('instagram_sc_heading', 'option');        

        if ( !empty($txt) ) : ?>
        <div class="uk-text-center">
            <h3><span uk-icon="icon: instagram;"></span> <br> <a href="<?php echo $url; ?>" target="_blank" rel="follow"><?php echo $txt; ?></a></h3>
        </div>
        <?php elseif ( !empty($bg) ) : ?>
            <div class="uk-text-center uk-margin-bottom">
                <a href="<?php echo $url; ?>" target="_blank" rel="follow">
                    <?php echo wp_get_attachment_image( $bg['id'], [ 300, 9999 ] ); ?>
                </a>
            </div>
        <?php else : ?>
        <div class="uk-text-center">
            <h3><span uk-icon="icon: instagram;"></span> <br> <a href="<?php echo $url; ?>" target="_blank" rel="follow">Follow Us on Instagram</a></h3>
        </div>
        <?php endif; 

        echo do_shortcode( $sc ); ?>
    </div>
    <?php endif;

endwhile; 

// Widget 2 : Off The Board
while ( have_rows( 'IG_widgetTwo', 'option' ) ) : the_row();

    $toggle = get_sub_field( 'display_toggle', 'option' ); 
    if ( $toggle == 1 ) : ?>
    <div class="uk-card uk-card-default uk-card-body" data-card="instagram">
        <?php
        $txt = get_sub_field('instagram_txt_heading', 'option');
        $bg  = get_sub_field('instagram_bg_heading', 'option');
        $url = get_sub_field('instagram_url_heading', 'option');
        $sc  = get_sub_field('instagram_sc_heading', 'option'); 
        if ( !empty($txt) ) : ?>
        <div class="uk-text-center">
            <h3><span uk-icon="icon: instagram;"></span> <br> <a href="<?php echo $url ?>" target="_blank" rel="follow"><?php echo $txt; ?></a></h3>
        </div>
        <?php elseif ( !empty($bg) ) : ?>
            <div class="uk-text-center uk-margin-bottom">
                <a href="<?php echo $url; ?>" target="_blank" rel="follow">
                    <?php echo wp_get_attachment_image( $bg['id'], [ 300, 9999 ] ); ?>
                </a>
            </div>
        <?php else : ?>
        <div class="uk-text-center">
            <h3><span uk-icon="icon: instagram;"></span> <br> <a href="<?php echo $url; ?>" target="_blank" rel="follow">Follow Us on Instagram</a></h3>
        </div>
        <?php endif; 

        echo do_shortcode( $sc ); ?>
    </div>
    <?php endif;

endwhile;