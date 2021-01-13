jQuery(document).ready(function ($) {
  if ($('#state-best-book-section').length > 0) {
    $.post(
      SGGAPI.ajax_url, {
        action: 'sportsbook_state_section',
        nonce: SGGAPI.nonce
      },
      function (data) {
        if (data) {
          $('#state-best-book-section').html('<div class="uk-card uk-card-default uk-card-body" data-card="content">'+data+'</div>');
        }
      }
    );
  }
});