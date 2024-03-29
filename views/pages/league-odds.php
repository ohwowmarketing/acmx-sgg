<?php 
switch ( $post->post_parent ) { 
    case '23': $league = 'nfl'; break;
    case '25': $league = 'nba'; break;
    // case '27': $league = 'mlb'; break;
    default: $league = ''; break;
}
$content = [ 'post_type' => 'page', 'page_id' => 8 ];
query_posts( $content );
?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge" id="Contents">
        <div class="uk-card uk-card-default uk-card-body" data-card="betting-odds">
            <h1 class="uk-card-title"><?php the_title(); ?></h1>
            <?php do_action( 'odds_table', $league ); ?>
        </div>
        <div class="uk-card uk-card-default uk-card-body" data-card="content">
        <?php while ( have_posts() ) : the_post();
            the_content(); 
        endwhile; wp_reset_query(); ?>
        </div>
    </div>
</main>