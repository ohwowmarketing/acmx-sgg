<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
                <div class="uk-card uk-card-default uk-card-body" data-card="guides-full">
                    <h1 class="uk-card-title"><?php the_title(); ?></h1>

                    <?php $guides = ['post_type'=>'sports_guides','post_status'=>'publish','has_password'=>false,'posts_per_page'=>-1,'orderby'=>'menu_order','order'=>'ASC'];
                    query_posts( $guides ); ?>
                    <div uk-grid>
                      <?php while ( have_posts() ) : the_post(); ?>
                        <div class="uk-width-1-2@s uk-width-1-3@m">
                          <figure>
                              <a href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                 <?php echo wp_get_attachment_image( get_post_thumbnail_id(), [ 640, 360, true ] ); ?>
                                <?php else : ?>
                                  <?php the_title(); ?>
                                <?php endif; ?>
                              </a>
                          </figure>
                          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
                      <?php endwhile; wp_reset_query(); ?>
                    </div>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
            <!-- Start Content -->
            <div class="uk-card uk-card-default uk-card-body" data-card="Gtag">
                <?php (!function_exists('dynamic_sidebar')) || !dynamic_sidebar('guides_tag') ? null : null ; ?>
            </div>

            <?php 

                get_template_part( widget.'news' );
                get_template_part( widget.'instagram' );

            ?>
            <!-- End Content -->                
            </div>

        </div>
    </div>
</main>