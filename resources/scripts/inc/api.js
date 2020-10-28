jQuery(document).ready(function($) {
  if ($('#ats-table').length) {
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'api_spread',
        nonce: SGGAPI.nonce,
        league: $('#ats-table').data('league')
      },
      function(rows) {
        $('#ats-table tbody').html(rows);
        $('#table-loading').hide();
        $('#ats-table').show();
      }
    );
  }

  if ($('#futures-select').length) {
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'api_market',
        nonce: SGGAPI.nonce,
        league: $('#futures-select').data('league'),
        future: $('#futures-select').data('future')
      },
      function(options) {
        $('#futures-select select').html(options);
        $('#select-loading').attr('hidden', '');
        if ($('#futures-table').data('future') === '') {
          var selected = $('#futures-select select :selected').val();
          $('#futures-table').data('future', selected).trigger('datachange');
          $('#futures-table').show();
        }
        // $('#table-loading').show();
      }
    );
  }

  $('#futures-select select').on('change', function() {
    window.location = SGGAPI.permalink + '?future=' + this.value;
  });

  function getFuturesTable() {
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'api_future',
        nonce: SGGAPI.nonce,
        league: $('#futures-table').data('league'),
        future: $('#futures-table').data('future')
      },
      function(table) {
        
        $('#futures-table').html(table);
        $('#table-loading').hide();
        $('#futures-table').show(function() {
          if ($('._notice').is(':visible')) {
            console.log('visible');
          } else {
            $('#futures-select').removeAttr('hidden');
            console.log('not visible');
          }   
          
          // console.log('this display');
        });
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