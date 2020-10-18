<?php         
switch ( $post->post_parent ) { 
    case '23': $league = 'nfl'; break;
    case '25': $league = 'nba'; break;
    case '27': $league = 'mlb'; break;
    default: $league = ''; break;
}
?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">    
                <div class="uk-card uk-card-default uk-card-body" data-card="future-odds">
                    <div class="uk-flex uk-flex-between uk-flex-middle">
                        <h1 class="uk-card-title"><?php echo get_the_title( $post->post_parent ) . ' Futures'; ?></h1>
                        <div id="select-loading" style="text-align: right; margin-top: 15px;">
                            <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/loading.gif" />
                        </div>
                        <form id="futures-select" action="<?php the_permalink(); ?>" method="get" data-league="<?php echo $league; ?>" data-future="<?php echo isset( $_GET['future'] ) ? $_GET['future'] : '' ; ?>" style="display: none;">
                            <select name="future" id="future" class="uk-select" style="max-width: 250px;"></select>
                        </form>
                    </div>
                    <div class="uk-position-relative">
                        <div class="uk-overflow-auto">
                            <table id="futures-table" class="uk-table uk-table-responsive uk-table-divider" data-league="<?php echo $league; ?>" data-future="<?php echo isset( $_GET['future'] ) ? $_GET['future'] : '' ; ?>" style="display: none;"></table>
                            <div id="table-loading" style="text-align: center; margin-top: 15px; display: none;">
                                <img src="<?php echo get_template_directory_uri(); ?>/resources/images/ui/loading.gif" />
                            </div>
                        </div>    
                    </div>
                </div>
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