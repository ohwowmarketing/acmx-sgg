jQuery(document).ready(function($) {
  if (jQuery('#ats-table').length) {
    jQuery.post(
      SGGAPI.ajax_url,
      {
        action: 'api_spread',
        nonce: SGGAPI.nonce,
        league: jQuery('#ats-table').data('league')
      },
      function(rows) {
        jQuery('#ats-table tbody').html(rows);
        jQuery('#table-loading').hide();
        jQuery('#ats-table').show();
      }
    );
  }
});