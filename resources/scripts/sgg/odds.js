jQuery(document).ready(function ($) {
  function updateOddsValueDisplay(type = 'Spread') {
    $('.sb-value-spread').hide();
    $('.sb-value-moneyline').hide();
    $('.sb-value-total').hide();
    if (type === 'Moneyline') {
      $('.sb-value-moneyline').show();
    } else if (type === 'Total') {
      $('.sb-value-total').show();
    } else {
      $('.sb-value-spread').show();
    }
  }

  if ($('#odds-list-body').length) {
    updateOddsValueDisplay();
  }

  if ($('#odds-type-selection').length) {
    $('#odds-type-selection').on('change', function() {
      updateOddsValueDisplay(this.value);
    })
  }

  function updateOddsData(selection) {
    $.post(
      SGGAPI.ajax_url, {
        action: 'odds_table_data',
        nonce: SGGAPI.nonce,
        league: SGGAPI.league,
        selection: selection
      },
      function (data) {
        $('#odds-list-body').html(data);
        updateOddsValueDisplay($('#odds-type-selection :selected').val());
      }
    );
  }

  if ($('#odds-schedule-selection').length) {
    $('#odds-schedule-selection').on('change', function() {
      updateOddsData(this.value)
    })
  }
});