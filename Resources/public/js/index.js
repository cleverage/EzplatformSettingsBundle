jQuery(function($) {
    //Choose SiteAccess and Show menu
    $('#SiteaccessType_siteaccess').change(function () {
        $( '#content_form_config' ).empty();

        if ( $(this).children("option:selected").val() === '' || $(this).children("option:selected").val() === null) {
            return;
        }

        var form = $(this).closest('form');
        var valueSelected = $(this).children("option:selected").text();

        var data = {};
        data['siteaccess'] = valueSelected;

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: data,
            success: function (data) {
                $( '#content_form_config' ).append(data.html);
            }
        });
    });

    //Clear Cache Button
    $('#content_clear_cache').click(function () {
        $('.clear_masev_cache div.text-content').html('<span>In progress</span>');
        var element_button = $(this);
        element_button.prop('disabled', true);
        var path = $(this).attr('data-path');
        var method = $(this).attr('data-method');

        $.ajax({
            url: path,
            type: method,
            success: function (data) {
                if (data['success'] === true) {
                    $('.clear_masev_cache div.text-content').html('<span>Finished with success</span>');
                }
                else {
                    $('.clear_masev_cache div.text-content').html('<span class="error-message">' + data['error'] + '</span>');
                }
                element_button.prop('disabled', false);
            }
        });
    });
});