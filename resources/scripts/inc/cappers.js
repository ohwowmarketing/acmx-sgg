(function($) {

    // Add Autocomplete Off to the Registration
    jQuery('.um-register').find('form').attr('autocomplete','off');

    // // Make sure all fields in Registration are empty
    // jQuery('.um-register form').find(':input')
    //                            .not(':button, :submit, :reset, :hidden')
    //                            .removeAttr('checked')
    //                            .removeAttr('selected')
    //                            .not(':checkbox, :radio, select')
    //                            .val('');

    // Accept & Read Terms
    if ( jQuery('.um-terms-conditions-content').css('display') == 'none' ) {
        jQuery('.um-register #um-submit-btn').prop("disabled", true);
    }

    // Double Check Checkbox if Checked Properly
    jQuery('.um-register .um-field-checkbox').on('click', function() {
        jQuery(this).find('input').prop('checked', true);
        
        if ( jQuery('input[name=use_terms_conditions_agreement]').is(':checked') && !jQuery(this).hasClass('active') ) {
            jQuery('.um-register #um-submit-btn').prop("disabled", false);
        } else {
            jQuery('.um-register #um-submit-btn').prop("disabled", true);
        }
    });

    jQuery('.um-toggle-terms').on('click', function() {
        // jQuery('.um-register .um-field-checkbox').find('input').prop('checked', true);
        // Make sure user scroll-down to bottom
        jQuery('.um-terms-conditions-content').scroll(function() {
            var textarea_height = $(this)[0].scrollHeight;
            var scroll_height = textarea_height - $(this).innerHeight();
            
            var scroll_top = $(this).scrollTop();
            
            if ( scroll_top == scroll_height ) {
              jQuery('.um-register #um-submit-btn').prop("disabled", false);
            }
        });
    });

    // Override Forgot Passowrd
    jQuery('.um-login .um-col-alt-b').find('a.um-link-alt').attr('href','#').attr('uk-switcher-item','2');

    // If reset password active, Get parameter and pop the window
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if ( results == null ) {
            return null;
        } else {
            return results[1] || 0;
        }
    }

    if ( $.urlParam('act') == 'reset_password' ) {
        var panel = UIkit.toggle('#form-btn');
        panel.toggle();

        UIkit.util.on('#form-panel', 'show', function() {
            UIkit.switcher('.uk-switcher').show(2);
            jQuery('#form-panel').find('.uk-tab li:first').removeClass('uk-active');
        });

        UIkit.util.on('#form-panel', 'hide', function() {
            UIkit.switcher('.uk-switcher').show(0);
            jQuery('#form-panel').find('.uk-tab li:first').addClass('uk-active');
        });
    }

    // Password Success
    if ( $.urlParam('updated') == 'password_changed' ) {
        UIkit.notification('You have successfully changed your password.', { status: 'primary', pos:'top-right', timeout: 15000 });
    }
 
    // Registration Success
    if ( $.urlParam('reg') == 'success' ) {
        UIkit.notification('Thank you for signing up! Your account is now active.', { status: 'primary', pos:'top-right', timeout: 15000 });
    }

    // Registration Success
    if ( $.urlParam('act') == 'deleted_successful' ) {
        UIkit.notification('Your account has been deleted.', { status: 'primary', pos:'top-right', timeout: 15000 });
    }

    // Check Email
    if ( $.urlParam('updated') == 'checkemail' ) {
        UIkit.notification('We have sent you a password reset link to your E-mail. Please check your inbox.', { status: 'primary', pos:'top-right', timeout: 15000 });
    }




    // Change Link to Profile Avatar
    if ( jQuery('.um-account.um-editing').length ) {
        var profileLink = jQuery('.um-account.um-editing').find('.um-account-meta-img a').attr('href').replace(/\/$/, '');
        jQuery('.um-account.um-editing').find('.um-account-meta-img a').attr('href',profileLink+'?um_action=edit');
    }




    // Fix Toggle Overlay Login & Register
    // jQuery('#register-btn, #login-btn').on('click', function() {

    //     if (this.id == 'register-btn') {
            
    //         attr1 = jQuery('#login-panel').attr('hidden');
    //         if (typeof attr1 !== typeof undefined && attr1 !== false) {
    //             return true;
    //         } else {
    //             UIkit.toggle('#login-panel').toggle();
    //             return false;
    //         }

    //     } else if (this.id == 'login-btn') {
            
    //         attr2 = jQuery('#register-panel').attr('hidden');
    //         if (typeof attr2 !== typeof undefined && attr2 !== false) {
    //             return true;
    //         } else {
    //             UIkit.toggle('#register-panel').toggle();
    //             return false;
    //         }

    //     }

    // });

    // Perform AJAX login on form submit
    // jQuery('form#AuthPanelLoginUser').on('submit', function(e){
    //     jQuery('form#AuthPanelLoginUser .status').show().text(ajax_login_object.loadingmessage);
    //     jQuery.ajax({
    //         type: 'POST',
    //         dataType: 'json',
    //         url: ajax_login_object.ajaxurl,
    //         data: { 
    //             'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
    //             'username': jQuery('form#AuthPanelLoginUser #username').val(), 
    //             'password': jQuery('form#AuthPanelLoginUser #password').val(), 
    //             'security': jQuery('form#AuthPanelLoginUser #security').val() },
    //         success: function(data){
    //             jQuery('form#AuthPanelLoginUser .status').text(data.message);
    //             if (data.loggedin == true){
    //                 document.location.href = ajax_login_object.redirecturl;
    //             }
    //         }
    //     });
    //     e.preventDefault();
    // });


})(jQuery);


