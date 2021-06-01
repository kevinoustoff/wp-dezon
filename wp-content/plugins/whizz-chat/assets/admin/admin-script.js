(function ($) {

    jQuery(document).ready(function () {

        if (jQuery('.friend-drawer--onhover').length > 0) {
            jQuery('.friend-drawer--onhover').on('click', function () {
                jQuery('.chat-bubble').hide('slow').show('slow');
            });
        }

    });

})(jQuery);