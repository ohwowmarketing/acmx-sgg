// Modified & updated by Mark Rivera [Aug 5, 2021]
jQuery(document).ready(function ($) {

    if ( jQuery('#futures-select').length ) {
        jQuery.post( SGGAPI.ajax_url, {

            cache: false,
            action: 'api_market',
            nonce: SGGAPI.nonce,
            league: jQuery('#futures-select').data('league'),
            future: jQuery('#futures-select').data('future')

        }, function (options) {

            jQuery('#futures-select select').html(options);

            if ( SGGAPI.future ) {
            
                jQuery('#futures-select select option[value="' + SGGAPI.future + '"]').prop('selected', true);
            
            } else {

                if ( jQuery('#futures-select').data('league') === 'nfl' ) {
                    jQuery('#futures-select select option[value="38"]').prop('selected', true);
                }

                else if ( jQuery('#futures-select').data('league') === 'nba' ) {
                    jQuery('#futures-select select option[value="36"]').prop('selected', true);
                }

                else if ( jQuery('#futures-select').data('league') === 'mlb' ) {
                    jQuery('#futures-select select option[value="25"]').prop('selected', true);
                }

            }

            jQuery('#select-loading').attr('hidden', '');

            if ( jQuery('#futures-table').data('future') === '' ) {
                var selected = jQuery('#futures-select select :selected').val();
                jQuery('#futures-table').data('future', selected).trigger('datachange');
                jQuery('#futures-table').show();
            }

        });
    }

    jQuery('#futures-select select').on('change', function () {
      window.location = SGGAPI.permalink + '?future=' + this.value
    });

    function getFuturesTable() {

        jQuery.post( SGGAPI.ajax_url, {
            
            cache: false,
            action: 'api_future',
            nonce: SGGAPI.nonce,
            league: jQuery('#futures-table').data('league'),
            future: jQuery('#futures-table').data('future')

        }, function (table) {

            jQuery('#futures-table').html(table);
            jQuery('#table-loading').hide();
            jQuery('#futures-table').show(function () {
                if (jQuery('._notice').is(':visible')) {
                    // console.log('visible');
                } else {
                  jQuery('#futures-select').removeAttr('hidden');
                }
            });

        });

    }

    // Call the function
    if ( jQuery('#futures-table').length ) {
        if (jQuery('#futures-table').data('future') !== '') {
            getFuturesTable();
        } else {
           getFuturesTable();
        }
    }

});