<?php         
switch ( $post->post_parent ) { 
    case '23': $league = 'nfl'; break;
    case '25': $league = 'nba'; break;
    case '27': $league = 'mlb'; break;
    default: $league = ''; break;
}
?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge" id="Contents">
            
        <div class="uk-card uk-card-default uk-card-body" data-card="futures-widget">

            <header class="uk-flex uk-flex-middle">
                <h1 class="uk-card-title"><?php echo get_the_title( $post->post_parent ) . ' Futures'; ?></h1>
                <form hidden id="futures-select" class="uk-form" action="<?php the_permalink(); ?>" method="get" data-league="<?php echo $league; ?>" data-future="<?php echo isset( $_GET['future'] ) ? $_GET['future'] : '' ; ?>">
                    <div class="uk-inline">
                        <div id="select-loading" uk-spinner="ratio: 0.5"></div>
                        <select name="future" id="future" class="uk-select uk-width-auto@m"></select>
                    </div>
                </form>
            </header>

            <div class="uk-position-relative">
                <div class="uk-overflow-auto">
                    <table id="futures-table" class="uk-table uk-table-divider" data-league="<?php echo $league; ?>" data-future="<?php echo isset( $_GET['future'] ) ? $_GET['future'] : '' ; ?>"></table>
                    <div id="table-loading" uk-spinner></div>
                </div>
            </div>

        </div>

    </div>
</main>