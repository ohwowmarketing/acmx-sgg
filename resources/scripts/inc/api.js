jQuery(document).ready(function($) {
  if ($('#ats-table').length) {
    var table = $('#ats-table');
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'api_spread',
        nonce: SGGAPI.nonce,
        league: table.data('league')
      },
      function(rows) {
        $('tbody', table).html(rows);
      }
    );
  }
});