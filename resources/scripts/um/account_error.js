;(function($) {

    UIkit.notification({
        pos: "top-right", 
        timeout: 10000, 
        message: "Username/Password is incorrect. Please try again."
    });

    var panel = UIkit.toggle('#form-btn');
    panel.toggle();

    UIkit.switcher('.uk-switcher').show(0);

})(jQuery);