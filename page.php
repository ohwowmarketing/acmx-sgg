<?php
get_header();

    global $post;

    switch ( $post->ID ) {

        // Pages
        case '10':   $pageName = 'about'; break;
        case '12':   $pageName = 'faqs'; break;
        case '14':   $pageName = 'contact'; break;
        case '21':   $pageName = 'sitemap'; break;

        case '552':  $pageName = 'guides'; break;
        case '839':  $pageName = 'careers'; break;

        case '2635':  $pageName = 'agreement'; break;
        case '2743':  $pageName = 'promos'; break;

        // case '2178': // Logout
        case '2174': // Register
        case '2182': // Password Reset
        case '2180': // Account
        case '2176': // Members
        case '2172': // Login
        case '2170': // User
        case '2162': // Newsletter
        case '2053': $pageName = 'cappers'; break;
        
        // Cappers Profile
        case '2166': // Authentication
        case '2168': $pageName = 'cappers-auth'; break;

        // Legal
        case '3':
        case '17':
        case '19':   $pageName = 'legal'; break;

        // Leagues Pages
        case '6':    $pageName = 'best-books'; break;
        case '8':    $pageName = 'league-odds'; break;

        // News
        case '292':   
        case '294':
        case '296':  $pageName = 'league-news'; break;
        
        // Leagues
        case '23':
        case '25':
        case '27':   $pageName = 'league-data'; break;

        // Leagues Odds Lines
        case '29':
        case '37':
        case '45':   $pageName = 'league-odds'; break;

        // Leagues Futures
        case '31':
        case '39':
        case '47':   $pageName = 'league-futures'; break;

        // Leagues ATS Standings
        case '33':
        case '41':
        case '49':   $pageName = 'league-ats'; break;

        // Leagues Injuries
        case '35':
        case '43':
        case '51':   $pageName = 'league-injuries'; break;

        default:
            $pageName = "home";
            break;        

    }

    if ( ! post_password_required() ) :

        get_template_part( _page.$pageName );

    else : ?>

        <main class="main" role="main">
            <section class="uk-section uk-section-muted">
                <div class="uk-container uk-container-small uk-height-meidum uk-flex uk-flex-middle uk-flex-center uk-text-center">
                    
                    <article>
                        <span uk-icon="icon: lock; ratio: 5"></span>
                        <hr class="uk-divider-small uk-margin-auto">
                        <?php the_content(); ?>
                    </article>

                </div>
            </section>
        </main>    

    <?php endif;

get_footer();