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

  function updateUserSpecificSettings() {
    $.post(
      SGGAPI.ajax_url, {
        action: 'odds_user_settings',
        nonce: SGGAPI.nonce,
      },
      function (json_data) {
        if (json_data.length) {
          const data = JSON.parse(json_data);
          if (data.state) {
            $('#odd-location-btn').html(data.state);
            data.sportsbooks.map((sportsbook) => {
              $(`.odds-data-sportsbook-${sportsbook.id}`).each(function () {
                $(this).append('<span class="sb-extlink-hover"><svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg><span>Bet Now</span></span>');
                $(this).prop('href', sportsbook.link)
              });
              $(`.odds-data-sportsbook-title-${sportsbook.id}`).html(`<a href="${sportsbook.link}"><img src="${sportsbook.logo.url}" width="120" height="40" alt="${sportsbook.name}" /></a>`);
            });
          }
        }
      }
    );
  }

  if ($('#odds-list-body').length) {
    if ($('#odds-type-selection').length) {
      $('#odds-type-selection').on('change', function() {
        updateOddsValueDisplay(this.value);
      })
    }

    if ($('#odds-schedule-selection').length) {
      $('#odds-schedule-selection').on('change', function() {
        updateOddsData(this.value)
      })
    }
    updateOddsValueDisplay();
    updateUserSpecificSettings();
  }
});