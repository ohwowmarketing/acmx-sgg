jQuery(document).ready(function ($) {
  if ($('#ats-table').length) {
    $.post(
      SGGAPI.ajax_url, {
        action: 'api_spread',
        nonce: SGGAPI.nonce,
        league: $('#ats-table').data('league')
      },
      function (rows) {
        $('#ats-table tbody').html(rows);
        $('#table-loading').attr('hidden', '');
        $('#ats-table').show(function () {
          $('#ats-table').removeAttr('hidden');
        });
      }
    );
  }
});