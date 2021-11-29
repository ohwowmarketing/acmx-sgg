(function($) {

    // Add Autocomplete Off to the Registration
    jQuery('.um-register').find('form').attr('autocomplete','off');

    // // Make sure all fields in Registration are empty
    jQuery('.um-register form').find('input:text, input:password')
                               .each(function(){
                                    jQuery(this).val('');
                               });

    // Accept & Read Terms
    // if ( jQuery('.um-terms-conditions-content').css('display') == 'none' ) {
    //     jQuery('.um-register #um-submit-btn').prop("disabled", true);
    // }

    // Double Check Checkbox if Checked Properly
    jQuery('.um-register #um-submit-btn').prop("disabled", true);
    jQuery('.um-register .um-field-checkbox').on('click', function() {
        // jQuery(this).find('input').prop('checked', true);
        
        if ( jQuery('input[name=use_terms_conditions_agreement]').is(':checked') && !jQuery(this).hasClass('active') ) {
            jQuery('.um-register #um-submit-btn').prop("disabled", false);
        } else {
            jQuery('.um-register #um-submit-btn').prop("disabled", true);
        }
    });

    // jQuery('.um-toggle-terms').on('click', function() {
    //     // jQuery('.um-register .um-field-checkbox').find('input').prop('checked', true);
    //     // Make sure user scroll-down to bottom
    //     jQuery('.um-terms-conditions-content').scroll(function() {
    //         var textarea_height = $(this)[0].scrollHeight;
    //         var scroll_height = textarea_height - $(this).innerHeight();
            
    //         var scroll_top = $(this).scrollTop();
            
    //         if ( scroll_top == scroll_height ) {
    //           jQuery('.um-register #um-submit-btn').prop("disabled", false);
    //         }
    //     });
    // });

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
        UIkit.notification('You have successfully changed your password.', { status: 'primary', pos:'top-right', timeout: 3500 });
    }
 
    // Registration Success
    if ( $.urlParam('reg') == 'success' ) {
        UIkit.notification('Thank you for signing up! Your account is now active.', { status: 'primary', pos:'top-right', timeout: 3500 });
    }

    // Registration Success
    if ( $.urlParam('act') == 'deleted_successful' ) {
        UIkit.notification('Your account has been deleted.', { status: 'primary', pos:'top-right', timeout: 3500 });
    }

    // Check Email
    if ( $.urlParam('updated') == 'checkemail' ) {
        UIkit.notification('We have sent you a password reset link to your E-mail. Please check your inbox.', { status: 'primary', pos:'top-right', timeout: 3500 });
    }

    // Notification
    // var $authorSRC = jQuery('#msgnotif').data('authorsrc');
    // jQuery('#msgnotif').on('click', function() {

    //     jQuery('.uk-notification').addClass('CapperAlertMsg');

    //     jQuery('.CapperAlertMsg .uk-notification-message').prepend(jQuery('<img>',{id:'CapperAuthor',width:'40',height:'40',alt:'CapperAuthor',src:$authorSRC}));
    //     // if($('#CapperAuthor').length < 0){
    //     // }
    //     console.log($('#CapperAuthor').length);

    // });

    var data = {
        action: 'is_user_logged_in'
    };

    jQuery.post(ajaxurl, data, function(response) {
        if(response == 'yes') {
            UIkit.notification('Welcome! You are now logged-in.', { status: 'primary', pos:'top-right', timeout: 3500 });
        } else {
            // UIkit.notification('See Yah! You are now logged-out.', { status: 'primary', pos:'top-right', timeout: 3500 });
        }
    });


    // Change Link to Profile Avatar
    if ( jQuery('.um-account.um-editing').length ) {
        var profileLink = jQuery('.um-account.um-editing').find('.um-account-meta-img a').attr('href').replace(/\/$/, '');
        jQuery('.um-account.um-editing').find('.um-account-meta-img a').attr('href',profileLink+'?um_action=edit');
    }

})(jQuery);

