(function($) {

    // Perform AJAX login on form submit
    jQuery('form#AuthPanelLoginUser').on('submit', function(e){
        jQuery('form#AuthPanelLoginUser .status').show().text(ajax_login_object.loadingmessage);
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': jQuery('form#AuthPanelLoginUser #username').val(), 
                'password': jQuery('form#AuthPanelLoginUser #password').val(), 
                'security': jQuery('form#AuthPanelLoginUser #security').val() },
            success: function(data){
                jQuery('form#AuthPanelLoginUser .status').text(data.message);
                if (data.loggedin == true){
                    document.location.href = ajax_login_object.redirecturl;
                }
            }
        });
        e.preventDefault();
    });


})(jQuery);
