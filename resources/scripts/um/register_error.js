;(function($) {

    UIkit.notification({
        pos: "top-right", 
        timeout: 10000, 
        message: "Check one of the input fields that have error(s). Please fix it & try again."
    });

    var panel = UIkit.toggle('#form-btn');
    panel.toggle();

    UIkit.switcher('.uk-switcher').show(1);
    jQuery('#form-panel').find('.uk-tab li:first').removeClass('uk-active');
    jQuery('#form-panel').find('.uk-tab li').eq(1).addClass('uk-active');

})(jQuery);