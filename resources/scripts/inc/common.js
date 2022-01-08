(function($) {    

    //* Mobile Menu
    jQuery(window).on('load', function() {
        jQuery('.uk-nav-mobile').find('.menu-item-has-children').addClass('uk-parent');
    });

    function footerStack() {
        if ( jQuery(window).width() <= 959 ) {
            jQuery('.footer-directory .uk-accordion li').removeClass('uk-open');
            jQuery('.footer-directory .uk-accordion li .uk-accordion-content').attr('hidden', '');
        }

        jQuery(window).on('resize', function() {
            if ( jQuery(window).width() >= 959 ) {
                jQuery('.footer-directory .uk-accordion li').addClass('uk-open');
                jQuery('.footer-directory .uk-accordion li .uk-accordion-content').removeAttr('hidden', '');
            } else if ( jQuery(window).width() <= 959 ) {
                jQuery('.footer-directory .uk-accordion li').removeClass('uk-open');
                jQuery('.footer-directory .uk-accordion li .uk-accordion-content').attr('hidden', '');
            }
        }).resize();
    }
    footerStack();

    // Search News
    jQuery('#searchNews').on('keyup', function() {

        var value = jQuery(this).val().toLowerCase();
        jQuery('.article-news').filter(function() {
            jQuery(this).toggle($(this).text().toLowerCase().indexOf(value) > -1).addClass('uk-margin-remove');
        });

    });

    // Switch Layout for Homepage Guides
    $("button.--switcher").bind("click", function(e) {
        e.preventDefault();

        var theID       = $(this).attr('id'),
            theGuides   = $('.uk-position-relative > .uk-grid'),
            theCard     = $('.guides-lists .uk-card-body');

        if ( $(this).hasClass("active") ) {

            return false;

        } else {

            if ( theID == 'listview' ) {
                $(this).addClass('active');
                $('#gridview').removeClass('active');
                theGuides.removeClass('uk-child-width-1-2@s uk-child-width-1-3@m');
                theGuides.addClass('uk-child-width-1-1');
                theCard.find('p').show();
            } 

            else if (theID == "gridview") {
                $(this).addClass('active');
                $('#listview').removeClass('active');
                theGuides.removeClass('uk-child-width-1-1');
                theGuides.addClass('uk-child-width-1-2@s uk-child-width-1-3@m');
                theCard.find('p').hide();
            }

        }
    });

    // Fill missing alt for WPForms Ajax
    $('img.wpforms-submit-spinner').attr('alt','Spinner Loading');

    // Fill missing ALT text to all banners
    // $("body.single-sports_guides article.uk-article a > img").each(function() {
    //     if (this.alt) {
    //         // Leave the current text as is
    //     } else {
    //         $(this).attr('alt', 'SGG SportsBook Banner');
    //     }
    // });

    // SmoothScroll to content section

    var $promo = location.hash;

    if ( $promo != '#sggpromo' ) {
        jQuery.getScript('https://cdnjs.cloudflare.com/ajax/libs/jquery-smooth-scroll/2.2.0/jquery.smooth-scroll.min.js', function() {        
            jQuery( "#skipToLink" ).on( "click", function() {
                jQuery.smoothScroll({
                    scrollTarget: '#Contents',
                    offset: -126,
                    speed: 1000
                });
            });
            jQuery('#skipToLink').trigger('click');

            var reSmooth = /^#sgg/;
            var id;
            if ( reSmooth.test(location.hash) ) {
                id = '#' + location.hash.replace(reSmooth, '');
                jQuery.smoothScroll({
                    scrollTarget: id,
                    offset: -62,
                    speed: 600
                });
            }

        });
    } else {
        jQuery.getScript('https://cdnjs.cloudflare.com/ajax/libs/jquery-smooth-scroll/2.2.0/jquery.smooth-scroll.min.js', function() {        
            jQuery( "#skipToLink" ).on( "click", function() {
                jQuery.smoothScroll({
                    scrollTarget: '#Contents',
                    offset: -126,
                    speed: 1000
                });
            });
            jQuery('#skipToLink').trigger('click');

            var reSmooth = /^#sgg/;
            var id;
            if ( reSmooth.test(location.hash) ) {
                id = '#' + location.hash.replace(reSmooth, '');
                jQuery.smoothScroll({
                    scrollTarget: id,
                    offset: -15,
                    speed: 500
                });
            }

        });
    }

    // Cookie Sessions
    // The basic check of site fully loaded
    if(document.readyState === 'complete') {

        jQuery.getScript('https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.min.js', function(){

            // Session Cookie
            $kukie = Cookies.get('sgg-accept-cookies');
            if ( ! $kukie ) {
                jQuery('.sgg-accept-cookies').removeAttr('hidden').attr('uk-scrollspy', 'cls: uk-animation-fast uk-animation-slide-bottom; delay: 2500');
                jQuery('.sgg-accept-cookies').find('.uk-alert-accept').on('click', function() {
                    Cookies.set('sgg-accept-cookies', 'true', { expires: 7 });
                    UIkit.alert('.sgg-accept-cookies').close();
                });
            }

        });
    }

}) (jQuery);


// Polling for the sake of my Cookies
var interval = setInterval(function() {
    if(document.readyState === 'complete') {
        clearInterval(interval);
        // done();
    }    
}, 100);

// Search Filter for Team
function searchTeam() {

    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input  = document.getElementById("searchOdds");
    filter = input.value.toUpperCase();
    table  = document.getElementById("odds-list");
    tr     = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for ( i = 0; i < tr.length; i++ ) {

        td = tr[i].getElementsByTagName("td")[0];
        if ( td ) {
            txtValue = td.textContent || td.innerText;
            if ( txtValue.toUpperCase().indexOf(filter) > -1 ) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }

    } // end for

}


