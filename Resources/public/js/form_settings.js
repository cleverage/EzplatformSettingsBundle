jQuery(function ($) {
    function toggleForm(groupId, switchInfo) {
        var input = $("#input-" + groupId);
        if (switchInfo == true) {
            input.prop("disabled", false);
            input.focus();
        } else {
            input.prop("disabled", "disabled");
        }

        var buttons_group = $("#buttons-" + groupId);
        if (switchInfo == true) {
            buttons_group.show();
            $("#button-edit-" + groupId).hide();
        } else {
            buttons_group.hide();
            $("#button-edit-" + groupId).show();
        }
    }

    //Hide element if it's not selected tab
    $("a.settings-group-item").click(function () {
        $(".tab-pane").addClass("hide");

        var tab = $(this).attr('id');
        $("#" + tab + ".tab-pane").removeClass("hide");
        $('html').animate({scrollTop: 0}, 'fast');
    });

    //Show element on click Edit TextArea, Text
    $(".show-form-edit").click(function () {
        event.preventDefault();
        if (typeof $(this).data('input-id') != "undefined") {
            toggleForm($(this).data('input-id'), true);
        }
    });

    //Hide element on cancel
    $('button.btn-cancel').click(function () {
        /*var container = $(this).parents('.element-setting');*/
        toggleForm($(this).data('input-id'), false);

        //container.children('form.editable-text').addClass('hide');
    });

    $('button.btn-submit, button.btn-danger').click(function (event) {
        event.preventDefault();

        var groupId = $(this).data('input-id');
        var action = $(this).data('action');
        var input = $("#input-" + groupId);
        var container = $(this).parents('form');

        if (action === 'remove') {
            input.val(input.data('default'));
        }
        value = $("#input-" + groupId).val();

        var data = {};
        data['siteaccess'] = $(this).attr("data-site");
        data['schema_name'] = $(this).attr("data-schema-name");
        data['path_update'] = $(this).attr("data-path");
        data['input_id'] = $(this).attr("data-input-id");
        data['type_element'] = $(this).attr("type-element");
        data['type_form'] = $(this).data("field-type");
        data['value'] = value;


        //@todo remove displayed value from browse form
        jQuery.ajax({
            url: data['path_update'],
            type: "POST",
            data: data,
            success: function (data) {
                if (data['success'] === true) {
                    toggleForm(groupId, false);
                    if (data['result'] == null && data['typeForm'] == 'browse') {
                        var element = jQuery('#browse-value-'+groupId);
                        element.html('');
                    }
                    container.find('span.badge-success').show().delay(2000).fadeOut();
                } else {
                    var error = data['error'];
                    container.find('div.error-message').children('span').html(error);
                }
            }
        });
    });
});


// Use browse tab for select element
(function (global, doc, eZ, React, ReactDOM, jQuery) {
    const btns = document.querySelectorAll('.btn--open-udw');
    const udwContainer = document.getElementById('react-udw');
    const token = document.querySelector('meta[name="CSRF-Token"]').content;
    const siteaccess = document.querySelector('meta[name="SiteAccess"]').content;
    const closeUDW = () => ReactDOM.unmountComponentAtNode(udwContainer);
    const onConfirm = (data, content) => {
        var identifier_replace = data['replace_value_id'];
        data["value"] = content[0].id;
        data["type_form"] = "browse";
        var input_id = data['input_id'];
        var name = content[0]['ContentInfo']['Content']['Name'];
        jQuery.ajax({
            url: data['path_update'],
            type: "POST",
            data: data,
            success: function (data) {
                var element = jQuery('#browse-value-'+input_id);
                if (data['success'] === true) {
                    if (name.length > 0) {
                        element.html('<a href="'+data.result.url+'" target="_blank">'+data.result.name+'</a>');
                    }
                } else {
                    var error = data['error'];
                }
            }
        });

        closeUDW();
    };
    const openUDW = (event) => {
        event.preventDefault();

        var data = {};

        var source = event.srcElement.parentNode;

        data['siteaccess'] = source.getAttribute("data-site");
        data['schema_name'] = source.getAttribute("data-schema-name");
        data['path_update'] = source.getAttribute("data-path");
        data['type_element'] = source.getAttribute("data-type");
        data['replace_value_id'] = source.getAttribute("data-replace-value-id");
        data['input_id'] = source.getAttribute("data-input-id");

        var config = JSON.parse(udwContainer.getAttribute('data-filter-subtree-udw-config'));
        var startingLocationId = source.getAttribute("data-start-location-id");
        if (typeof startingLocationId !== 'undefined') {
            config.startingLocationId = parseInt(startingLocationId);
        }

        ReactDOM.render(React.createElement(eZ.modules.UniversalDiscovery, {
            onConfirm: onConfirm.bind(this, data),
            onCancel: closeUDW,
            ...config
        }), udwContainer);
    };

    btns.forEach(btn => btn.addEventListener('click', openUDW, false));
})(window, document, window.eZ, window.React, window.ReactDOM, window.jQuery);