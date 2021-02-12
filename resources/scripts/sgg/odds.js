jQuery(document).ready(function ($) {
  function displayQuickOddsSpinner() {
    var spinner =
      "<div uk-spinner='ratio: 0.6' class='odds-header-spinner'></div>"
    $('.quick-odds ul.uk-slider-items').html(spinner)
  }

  function getQuickOdds(league = '') {
    displayQuickOddsSpinner()
    if (league === '') {
      league = SGGAPI.league.toUpperCase()
    }
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'odds_header_data',
        nonce: SGGAPI.nonce,
        league: league
      },
      function (data) {
        var items = $('.quick-odds ul.uk-slider-items')
        items.html(data)
        try {
          UIkit.update((element = items), (type = 'update'))
        } catch (e) {
          console.log(e)
        }
      }
    )
  }

  if ($('.quick-odds').length) {
    getQuickOdds()
  }

  var selectedLeague = SGGAPI.league.toUpperCase()
  $('#quick-odds-sport').on('change', function () {
    var clickedLeague = $(this).val()
    if (selectedLeague !== clickedLeague) {
      console.log('A new league has been selected: ', clickedLeague)
      selectedLeague = clickedLeague
      $(this).val(selectedLeague)
      getQuickOdds(selectedLeague)
    }

    // $('#odds-header-league').html(league)
  })

  function updateOddsValueDisplay(type = 'Spread') {
    $('.sb-value-spread').hide()
    $('.sb-value-moneyline').hide()
    $('.sb-value-total').hide()
    if (type === 'Moneyline') {
      $('.sb-value-moneyline').show()
    } else if (type === 'Total') {
      $('.sb-value-total').show()
    } else {
      $('.sb-value-spread').show()
    }
  }

  function updateOddsData(selection) {
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'odds_table_data',
        nonce: SGGAPI.nonce,
        league: SGGAPI.league,
        selection: selection
      },
      function (data) {
        $('#odds-list-body').html(data)
        updateOddsValueDisplay($('#odds-type-selection :selected').val())
        updateUserSpecificSettings()
      }
    )
  }

  function updateUserSpecificSettings() {
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'odds_user_settings',
        nonce: SGGAPI.nonce
      },
      function (json_data) {
        if (json_data.length) {
          const data = JSON.parse(json_data)
          if (data.state) {
            $('#odd-location-btn').html(data.state)
            data.sportsbooks.map((sportsbook) => {
              $(`.odds-data-sportsbook-${sportsbook.id}`).each(function () {
                $(this).append(
                  '<span class="sb-extlink-hover"><svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg><span>Bet Now</span></span>'
                )
                $(this).prop('href', sportsbook.link)
              })
              $(`.odds-data-sportsbook-title-${sportsbook.id}`).html(
                `<a href="${sportsbook.link}"><img src="${sportsbook.badge}" width="120" height="40" alt="${sportsbook.name}" /></a>`
              )
            })
          }
        }
      }
    )
  }

  $('body').on('DOMSubtreeModified', '#dateOdds', function () {
    if ($('#dateOdds').html() !== '') {
      var currDate = new Date()
      var fullDate = $('#dateOdds').html() + ', ' + currDate.getFullYear()
      var isoDate = new Date(fullDate).toISOString().split('T')[0]
      updateOddsData(isoDate)
    }
  })

  if ($('#odds-list-body').length) {
    if ($('#odds-type-selection').length) {
      $('#odds-type-selection').on('change', function () {
        updateOddsValueDisplay(this.value)
      })
    }

    if ($('#odds-schedule-selection').length) {
      $('#odds-schedule-selection').on('change', function () {
        updateOddsData(this.value)
      })
    }
    updateOddsValueDisplay()
    updateUserSpecificSettings()
  }
})
