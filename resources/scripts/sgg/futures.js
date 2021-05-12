jQuery(document).ready(function ($) {
  if ($('#futures-select').length) {
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'api_market',
        nonce: SGGAPI.nonce,
        league: $('#futures-select').data('league'),
        future: $('#futures-select').data('future')
      },
      function (options) {
        $('#futures-select select').html(options)
        if (SGGAPI.future) {
          $(
            '#futures-select select option[value="' + SGGAPI.future + '"]'
          ).prop('selected', true)
        } else {
          if ($('#futures-select').data('league') === 'nfl') {
            $('#futures-select select option[value="38"]').prop(
              'selected',
              true
            )
          } else if ($('#futures-select').data('league') === 'nba') {
            $('#futures-select select option[value="36"]').prop(
              'selected',
              true
            )
          } else if ($('#futures-select').data('league') === 'mlb') {
            $('#futures-select select option[value="25"]').prop(
              'selected',
              true
            )
          }
        }
        $('#select-loading').attr('hidden', '')
        if ($('#futures-table').data('future') === '') {
          var selected = $('#futures-select select :selected').val()
          $('#futures-table').data('future', selected).trigger('datachange')
          $('#futures-table').show()
        }
      }
    )
  }

  $('#futures-select select').on('change', function () {
    window.location = SGGAPI.permalink + '?future=' + this.value
  })

  function getFuturesTable() {
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'api_future',
        nonce: SGGAPI.nonce,
        league: $('#futures-table').data('league'),
        future: $('#futures-table').data('future')
      },
      function (table) {
        $('#futures-table').html(table)
        $('#table-loading').hide()
        $('#futures-table').show(function () {
          if ($('._notice').is(':visible')) {
            // console.log('visible');
          } else {
            $('#futures-select').removeAttr('hidden')
          }
        })
        updateUserFuturesSpecificSettings()
      }
    )
  }

  if ($('#futures-table').length) {
    if ($('#futures-table').data('future') !== '') {
      getFuturesTable()
    } else {
      $('#futures-table').on('datachange', getFuturesTable)
    }
  }
})
