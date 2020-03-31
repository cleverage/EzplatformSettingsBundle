jQuery(function($) {
    //Hide element if it's not selected tab
    $("a.list-group-item").click(function () {
        $(".tab-pane").addClass("hide");

        var tab = $(this).attr('id');
        $("#"+ tab+ ".tab-pane").removeClass("hide");
        $('html').animate({scrollTop:0}, 'fast');
    });

    //Show element on click Edit TextArea, Text
    $(".show-form-edit").click(function () {
        $(this).prev('form.editable-text').removeClass('hide');
        $(this).addClass('hide');
    });

    //Hide element on cancel
    $('button.btn-cancel').click(function () {
        var container = $(this).parents('.element-setting');
        container.children('.show-form-edit').removeClass('hide');
        container.children('form.editable-text').addClass('hide');
    });

    $('button.btn-submit, button.btn-remove').click(function (event) {
        event.preventDefault();

        var type_element = $(this).attr("data-type");
        var container = $(this).parents('.element-setting');
        if (type_element === 'remove')
        {
            value = '';
        }
        else {
            var value = $(this).parents('.form-group').children('.value-element').val();
        }

        var data = {};
        data['siteaccess'] = $(this).attr("data-site");
        data['schema_name'] = $(this).attr("data-schema-name");
        data['path_update'] = $(this).attr("data-path");
        data['type_element'] = type_element;
        data['value'] = value;

        jQuery.ajax({
            url: data['path_update'],
            type: "POST",
            data: data,
            success: function (data) {
                if (data['success'] === true) {
                    container.children('span.current_value').html(value);
                    if (type_element === 'remove')
                    {
                        container.children('.btn-remove').addClass('hide');
                    }
                    else
                    {
                        container.children('.show-form-edit').removeClass('hide');
                        container.children('form.editable-text').addClass('hide');
                    }
                }
                else {
                    var error = data['error'];
                    container.find('div.error-message').children('span').html(error);
                }
            }
        });
    });

});


// Use browse tab for select element
(function(global, doc, eZ, React, ReactDOM, jQuery) {
    const btns = document.querySelectorAll('.btn--open-udw');
    const udwContainer = document.getElementById('react-udw');
    const token = document.querySelector('meta[name="CSRF-Token"]').content;
    const siteaccess = document.querySelector('meta[name="SiteAccess"]').content;
    const closeUDW = () => ReactDOM.unmountComponentAtNode(udwContainer);
    const onConfirm = (data, content) => {

        var identifier_replace = data['replace_value_id'];
        data["value"] = content[0].id;
        
        var name = content[0]['ContentInfo']['Content']['Name'];

        jQuery.ajax({
            url: data['path_update'],
            type: "POST",
            data: data,
            success: function (data) {
                if (data['success'] === true) {
                    if (name.length > 0) {
                        var element = jQuery('span[class*="' + identifier_replace + '"]');
                        element.html(name);
                        element.siblings('.btn-remove').removeClass('hide');
                    }
                }
                else {
                    var error = data['error'];
                    jQuery('span[class*="' + identifier_replace + '"]').siblings('.error-message ').children('span').html(error);
                }
            }
        });

        closeUDW();
    };
    const openUDW = (event) => {
        event.preventDefault();

        var data = {};
        data['siteaccess'] = event.srcElement.getAttribute("data-site");
        data['schema_name'] = event.srcElement.getAttribute("data-schema-name");
        data['path_update'] = event.srcElement.getAttribute("data-path");
        data['type_element'] = event.srcElement.getAttribute("data-type");
        data['replace_value_id'] = event.srcElement.getAttribute("data-replace-value-id");

        var startLocationId = event.srcElement.getAttribute("data-start-location-id");

        ReactDOM.render(React.createElement(eZ.modules.UniversalDiscovery, Object.assign({
            onConfirm: onConfirm.bind(this, data),
            onCancel: closeUDW,
            startingLocationId: startLocationId,
            restInfo: {token, siteaccess},
            multiple: false,
        })), udwContainer);
    };

    btns.forEach(btn => btn.addEventListener('click', openUDW, false));
})(window, document, window.eZ, window.React, window.ReactDOM, window.jQuery);