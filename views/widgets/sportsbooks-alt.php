<div class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
    <div class="uk-flex uk-flex-between _headings">
        <h1 class="uk-card-title">Best Sportsbooks</h1>
        <?php if ( ! is_home() && ! is_front_page() ) {
            do_action( 'sportsbook_state_select' );
        } ?>
    </div>

    <div class="sportsbooks-lists _alt">
    <?php do_action('sportsbook_promos'); ?>
    </div>

</div>
