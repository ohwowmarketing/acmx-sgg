(function($) {

    // Cookie Sessions
    // Control the Homepage Video Popup
    if(document.readyState === 'complete') {
        jQuery.getScript('https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.min.js', function(){

            // Session Cookie
            $promo = Cookies.set('sgg-video-promotion');
            if ( ! $promo ) {

                jQuery('.uk-navbar-nav .wp-video-popup').trigger('click');
                Cookies.set('sgg-video-promotion', 'true', { expires: 7 });

            //     $('.sgg-accept-cookies').removeAttr('hidden').attr('uk-scrollspy', 'cls: uk-animation-fast uk-animation-slide-bottom; delay: 2500');
            //     $('.sgg-accept-cookies').find('.uk-alert-accept').on('click', function() {
            //         Cookies.set('sgg-accept-cookies', 'true', { expires: 7 });
            //         UIkit.alert('.sgg-accept-cookies').close();
            //     });
            }

        });
    }

    // Polling for the sake of my Cookies
    var interval = setInterval(function() {
        if(document.readyState === 'complete') {
            clearInterval(interval);
            // done();
        } 
    }, 100);

})(jQuery);