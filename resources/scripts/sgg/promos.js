jQuery(document).ready(function ($) {
  if($('#sportsbook-promos-container').length) {
    $.post(
      SGGAPI.ajax_url, {
        action: 'sportsbook_promos',
        nonce: SGGAPI.nonce,
      },
      function (data) {
        $('#sportsbook-promos-container').replaceWith(data);
      }
    );
  }
});