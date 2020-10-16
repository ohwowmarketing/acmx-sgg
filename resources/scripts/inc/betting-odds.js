(function($) {

    // Moment.JS
    jQuery.getScript('https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js', function() {

        var $dateOdds = jQuery('#dateOdds'),
            $moment   = moment(),
            $format   = 'dd, MMMM D';

        function showDate() {
            $dateOdds.html( $moment.format($format) );
        }

        function changeDate(key) {
            $moment.add(key, 'd');
        }

        function displayDate(key) {
            changeDate(key);
            showDate();
        }

        showDate();

        jQuery('.odds-filter ._prevDay').on('click', function() {
            displayDate(-1);
        });

        jQuery('.odds-filter ._nextDay').on('click', function() {
            displayDate(1);
        });

    });

    // Betting Location
    // $("#betting-location .uk-dropdown-nav a").on('click', function() {
        
    //     var dataLink = $(this).attr('data-ulink');
    //     dataOrigin = window.location.href;
    //     window.location.replace(dataOrigin+'?state_abbr='+dataLink);
    //     console.log(dataOrigin+'?state_abbr='+dataLink);

    // });

}) (jQuery);