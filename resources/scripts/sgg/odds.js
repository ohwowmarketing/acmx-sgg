jQuery(document).ready(function ($) {
  function displayQuickOddsSpinner() {
    var spinner =
      "<div uk-spinner='ratio: 0.6' class='odds-header-spinner'></div>"
    $('.quick-odds ul.uk-slider-items').html(spinner);
  }

  function getQuickOdds(league = '') {
    displayQuickOddsSpinner()
    if (league === '') {
      league = SGGAPI.league.toUpperCase();
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
        items.html(data);
        try {
          UIkit.update((element = items), (type = 'update'))
        } catch (e) {
          console.log(e);
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

    $('#odds-header-league').html(league)
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
      SGGAPI.ajax_url, {
        action: 'odds_table_data',
        nonce: SGGAPI.nonce,
        league: SGGAPI.league,
        selection: selection,
      },
      function (data) {
        $('#odds-list-body').html(data);
        updateOddsValueDisplay($('#odds-type-selection :selected').val());


        console.log(data);
      }      
    )
  }

  $('body').on('DOMSubtreeModified', '#dateOdds', function () {
    if ($('#dateOdds').html() !== '') {
      var currDate = new Date();
      var fullDate = $('#dateOdds').html() + ', ' + currDate.getFullYear();
      var isoDate = new Date(fullDate).toISOString().split('T')[0];

      console.log(isoDate);

      updateOddsData(isoDate);
    }
  });

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
    updateOddsValueDisplay();
  }
});
