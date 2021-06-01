(function ($) {
    'use strict';
    jQuery(document).ready(function () {

        var admin_ajax_url = email_js_global.ajax_url;

        $(document).on('click', '.sb-reset-template-content', function () {

            var this_obj = $(this);
            var template_id = this_obj.attr('data-template-id');

            if (confirm(email_js_global.confirm_reset)) {

                var classified_data = {
                    template_id: template_id,
                    action: 'sb_email_template_content_reset',
                };
                $.ajax({
                    type: 'POST',
                    data: classified_data,
                    dataType: 'json',
                    url: admin_ajax_url,
                    crossDomain: true,
                    cache: false,
                    async: true,
                }).done(function (response) {

                    if (typeof response.status !== 'undefined' && response.status == 'error') {
                        alert(response.success);
                    }

                    if (typeof response.status !== 'undefined' && response.status == 'success') {
                        window.location.reload(true);
                    }

                });

            }

        });

    });
})(jQuery);