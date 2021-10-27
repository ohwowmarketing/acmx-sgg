<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">
                <div id="guide-content" class="uk-card uk-card-default uk-card-body" data-card="guides">
                    <article class="uk-article uk-margin-bottom">
                    <?php
                        $videoHDR = get_field( 'activate_videohdr' );

                        if ( $videoHDR ) {
                            
                            // the_field( 'embed_videohdr' );

                            $url = get_field( 'embed_videohdr' );
                            parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
                            // echo $my_array_of_vars['v'];

                            echo '<div class="iframe-container">';
                            echo '<iframe src="https://www.youtube-nocookie.com/embed/'.$my_array_of_vars["v"].'?autoplay=1&amp;showinfo=0&amp;rel=0&amp;modestbranding=1&amp;playsinline=1" frameborder="0" allowfullscreen uk-responsive uk-video="automute: false"></iframe>';
                            echo '</div>';

                        } else {

                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail();
                                $description = get_post(get_post_thumbnail_id())->post_excerpt;
                                echo '<div class="uk-text-meta">'. makeUrltoLink($description) .'</div>';
                            }                            
                            
                        }

                        the_title('<h2 class="uk-article-title">','</h2>'); 
                        
                        $author_display = get_field( 'activate_author' );
                        if ( $author_display ) :
                        ?>
                        <address class="uk-grid-collapse uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <?php echo get_avatar( get_the_author_meta('ID'), 40, '', '', [ 'class' => 'uk-border-rounded' ]); ?>
                            </div>
                            <div class="uk-width-expand">
                                <span rel="author" class="uk-text-small uk-link-reset"><?php 
                                    $author_id = get_the_author_meta( 'ID' );
	                                echo get_the_author_meta('display_name', $author_id);  
                                ?></span>
                                <time class="uk-display-block uk-text-meta uk-margin-remove" datetime="<?php echo get_the_date('m-d-Y'); ?>"><?php the_date(); ?></time>
                            </div>
                        </address>
                        <?php
                        endif;

                        the_content();
                    ?>
                    </article>
                </div>
                <div id="sb">
                    <?php // do_action('sportsbook_promos'); ?>
                </div>
            </div>
            <div class="uk-width-1-1 uk-width-large@l">
                <?php get_template_part( widget . 'news' ); ?>
                <?php get_template_part( widget.'instagram' ); ?>
            </div>
        </div>
    </div>
</main>
