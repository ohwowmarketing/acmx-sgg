jQuery(document).ready(function ($) {
  if ($('#futures-select').length) {
    $.post(
      SGGAPI.ajax_url, {
        action: 'api_market',
        nonce: SGGAPI.nonce,
        league: $('#futures-select').data('league'),
        future: $('#futures-select').data('future')
      },
      function (options) {
        $('#futures-select select').html(options);
        if (SGGAPI.future) {
          $('#futures-select select option[value="' + SGGAPI.future + '"]').prop('selected', true);
        } else {
          if ($('#futures-select').data('league') === 'nfl') {
            $('#futures-select select option[value="38"]').prop('selected', true);
          } else if ($('#futures-select').data('league') === 'nba') {
            $('#futures-select select option[value="36"]').prop('selected', true);
          } else if ($('#futures-select').data('league') === 'mlb') {
            // $('#futures-select select option[value=""]').prop('selected', true);
          }
        }
        $('#select-loading').attr('hidden', '');
        if ($('#futures-table').data('future') === '') {
          var selected = $('#futures-select select :selected').val();
          $('#futures-table').data('future', selected).trigger('datachange');
          $('#futures-table').show();
        }
      }
    );
  }

  $('#futures-select select').on('change', function () {
    window.location = SGGAPI.permalink + '?future=' + this.value;
  });

  function updateUserFuturesSpecificSettings() {
    $.post(
      SGGAPI.ajax_url, {
        action: 'futures_user_settings',
        nonce: SGGAPI.nonce,
      },
      function (json_data) {
        if (json_data.includes('_notice')) {
          location.reload();
        }
        if (json_data.length) {
          const data = JSON.parse(json_data);
          if (data.state) {
            $('#futures-location-btn').html(data.state);
            data.sportsbooks.map((sportsbook) => {
              $(`.futures-data-sportsbook-${sportsbook.id}`).each(function () {
                $(this).append('<span class="sb-extlink-hover"><svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg><span>Bet Now</span></span>');
                $(this).prop('href', sportsbook.link)
              });
              $(`.futures-data-sportsbook-title-${sportsbook.id}`).html(`<a href="${sportsbook.link}"><img src="${sportsbook.badge}" width="120" height="40" alt="${sportsbook.name}" /></a>`);
            });
          }
        }
      }
    );
  }

  function getFuturesTable() {
    $.post(
      SGGAPI.ajax_url, {
        action: 'api_future',
        nonce: SGGAPI.nonce,
        league: $('#futures-table').data('league'),
        future: $('#futures-table').data('future')
      },
      function (table) {

        $('#futures-table').html(table);
        $('#table-loading').hide();
        $('#futures-table').show(function () {
          if ($('._notice').is(':visible')) {
            // console.log('visible');
          } else {
            $('#futures-select').removeAttr('hidden');
          }
        });
        updateUserFuturesSpecificSettings()
      }
    );
  }

  if ($('#futures-table').length) {
    if ($('#futures-table').data('future') !== '') {
      getFuturesTable();
    } else {
      $('#futures-table').on('datachange', getFuturesTable);
    }
  }
}); 