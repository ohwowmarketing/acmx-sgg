(function($) {

    // Perform AJAX login on form submit
    $('form#AuthPanelLoginUser').on('submit', function(e){
        $('form#AuthPanelLoginUser .status').show().text(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#AuthPanelLoginUser #username').val(), 
                'password': $('form#AuthPanelLoginUser #password').val(), 
                'security': $('form#AuthPanelLoginUser #security').val() },
            success: function(data){
                $('form#AuthPanelLoginUser .status').text(data.message);
                if (data.loggedin == true){
                    document.location.href = ajax_login_object.redirecturl;
                }
            }
        });
        e.preventDefault();
    });


})(jQuery);
