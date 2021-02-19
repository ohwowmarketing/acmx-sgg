<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
            <!-- Start Content -->
                
                <div class="uk-card uk-card-default uk-card-body" data-card="careers">
                    <h3 class="uk-card-title"><?php the_title(); ?></h3>
                    
                    <ul uk-accordion="collapsible: false; offset: 160">
                        <?php while ( have_rows( 'career_opportunities' ) ) : the_row(); ?>
                        <li>
                            <a class="uk-accordion-title" href="#"><?php the_sub_field( 'job_title' ); ?></a>
                            <div class="uk-accordion-content">
                                <?php the_sub_field( 'job_description' ); ?>
                            </div>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            
            <!-- End Content -->
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <!-- Start Content -->
                <div class="uk-card uk-card-default uk-card-body" data-card="Gtag">
                    <?php (!function_exists('dynamic_sidebar')) || !dynamic_sidebar('guides_tag') ? null : null ; ?>
                </div>

                <?php 

                    get_template_part( widget.'news' );

                ?>
                <!-- End Content -->                
            </div>

        </div>
    </div>
</main>