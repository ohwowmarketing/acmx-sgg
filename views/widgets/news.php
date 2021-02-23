<?php
$league = 'nba';
if ( isset( $_GET['league'] ) ) {
    $league = strtolower( $_GET['league'] );
}
?>
<div class="uk-card uk-card-default uk-card-body" data-card="news">
    <h1 class="uk-card-title"><?php the_field( 'widget_title_news', 'option' ); ?></h1>
    <ul uk-tab="animation: uk-animation-fade">
        <li<?php echo ( $league === 'nfl' ) ? ' class="uk-active"' : ''; ?>><a href="#" id="nfl-news">NFL</a></li>
        <li<?php echo ( $league === 'nba' ) ? ' class="uk-active"' : ''; ?>><a href="#" id="nba-news">NBA</a></li>
        <li<?php echo ( $league === 'mlb' ) ? ' class="uk-active"' : ''; ?>><a href="#" id="mlb-news">MLB</a></li>
    </ul>
    <div class="uk-switcher uk-margin">
        <div id="news-holder"></div>
        <div id="news-loading" uk-spinner="ratio: 0.5"></div>
    </div>
</div>