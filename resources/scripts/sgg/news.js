jQuery(document).ready(function ($) {
  function getNews(league) {
    $('#news-holder').hide();
    $('#news-loading').show();
    $.post(
      SGGAPI.ajax_url, {
        action: 'api_news',
        nonce: SGGAPI.nonce,
        league: league.toUpperCase()
      },
      function (data) {
        $('#news-holder').html(data).show();
        $('#news-loading').hide();
      }
    );
  }
  $('#nba-news').on('click', function () { getNews('nba'); });
  $('#nfl-news').on('click', function () { getNews('nfl'); });
  $('#mlb-news').on('click', function () { getNews('mlb'); });
  getNews(SGGAPI.league);
});