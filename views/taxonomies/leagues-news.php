<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div uk-grid class="uk-grid-small">

            <div class="uk-width-expand@l">
            <!-- Start Content -->
            <?php 
                // views/taxonomies
                get_template_part( widget.'news-article' );
                
                // viewws/widgets
                $betting_states = get_field('states_operation', 'option'); 

                $valid_states = [];
                foreach ( $betting_states as $state ) {
                    $valid_states[$state['label']] = $state['value'];
                }

                // Check if cookie is set and fetching states correctly
                if ( isset($_COOKIE['state_abbr']) && in_array($_COOKIE['state_abbr'], $valid_states) ) {
                    get_template_part( widget.'sportsbooks' ); 
                } else {
                    get_template_part( widget.'sportsbooks-alt' );     
                }

            ?>
            <!-- End Content -->
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <?php 

                    get_template_part( widget.'news' );
                    get_template_part( widget.'guides' );

                ?>
            </div>

        </div>
    </div>
</main>