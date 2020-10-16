(function($) {

    // Cookie Sessions
    // Control the Homepage Video Popup
    if(document.readyState === 'complete') {
        jQuery.getScript('https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.min.js', function(){

            // Session Cookie
            $promo = Cookies.get('sgg-video-promotion');
            if ( ! $promo ) {

                jQuery('.uk-navbar-nav .wp-video-popup').trigger('click');
                Cookies.set('sgg-video-promotion', 'true', { expires: 7 });

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