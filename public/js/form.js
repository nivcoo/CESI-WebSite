/**
 * Récupéré sur le CMS que je maintiens https://github.com/MineWeb/MineWebCMS/blob/development/app/webroot/js/form.js
 */

function initForms() {
    $('form[data-ajax="true"]').unbind("submit");

    $('form[data-ajax="true"]').on("submit", function (e) {
        form = $(this);

        e.preventDefault();

        if (form.find("input[type='submit']").length == 0) {
            var submit = form.find("button[type='submit']");
        } else {
            var submit = form.find("input[type='submit']");
        }

        if (form.attr('data-custom-div-msg') == undefined || form.attr('data-custom-div-msg').length == 0) {
            if (form.find('.ajax-msg') === undefined || form.find('.ajax-msg').length == 0) {
                form.prepend('<div class="ajax-msg"></div>');
            }
            var div_msg = form.find('.ajax-msg');
        } else {
            var div_msg = $(form.attr('data-custom-div-msg'));
        }

        div_msg.empty().html('<div class="alert alert-info"><a class="close" data-dismiss="alert">×</a>' + LOADING_MSG + ' ...</div>').fadeIn(500);

        var submit_btn_content = form.find('button[type=submit]').html();
        submit.html(LOADING_MSG + '...').attr('disabled', 'disabled').fadeIn(500);

        // Data
        if (form.attr('data-custom-function') == undefined || form.attr('data-custom-function').length == 0) {

            var array = form.serialize();
            array = array.split('&');

            form.find('input[type="checkbox"]').each(function () {
                if (!$(this).is(':checked')) {
                    array.push($(this).attr('name') + '=off');
                }
            });

            var inputs = {};
            var formData = new FormData(form[0]);

            var i = 0;
            for (var key in args = array) {
                input = args[i];
                input = input.split('=');
                input_name = decodeURI(input[0]);

                /*console.log('Name :', input_name+"\n");
                console.log('Type :', form.find('input[name="'+input_name+'"]').attr('type')+"\n");
                console.log('Value :', form.find('input[name="'+input_name+'"]').val()+"\n\n")*/

                if (form.find('input[name="' + input_name + '"]').attr('type') == "text" || form.find('input[name="' + input_name + '"]').attr('type') == "hidden" || form.find('input[name="' + input_name + '"]').attr('type') == "email" || form.find('input[name="' + input_name + '"]').attr('type') == "password" || form.find('input[name="' + input_name + '"]').attr('type') == "number") {
                    inputs[input_name] = form.find('input[name="' + input_name + '"]').val(); // je récup la valeur comme ça pour éviter la sérialization
                } else if (form.find('input[name="' + input_name + '"]').attr('type') == "radio") {
                    inputs[input_name] = form.find('input[name="' + input_name + '"][type="radio"]:checked').val();
                } else if (form.find('input[name="' + input_name + '"]').attr('type') == "checkbox") {
                    if (form.find('input[name="' + input_name + '"]:checked').val() !== undefined) {
                        inputs[input_name] = 1;
                    } else {
                        inputs[input_name] = 0;
                    }
                } else if (form.find('textarea[name="' + input_name + '"]').attr('id') == "editor") {
                    inputs[input_name] = tinymce.get('editor').getContent();
                } else if (form.find('textarea[name="' + input_name + '"]').length > 0) {
                    inputs[input_name] = form.find('textarea[name="' + input_name + '"]').val();
                } else if (form.find('select[name="' + input_name + '"]').val() !== undefined) {
                    inputs[input_name] = form.find('select[name="' + input_name + '"]').val();
                }

                formData.append(input_name, inputs[input_name]);

                i++;
            }

            // ReCaptcha
            if (typeof grecaptcha !== "undefined" && typeof grecaptcha.getResponse() !== "undefined") {
                inputs['recaptcha'] = grecaptcha.getResponse();
                var recaptcha = true;
            } else {
                var recaptcha = false;
            }

            //

            if (form.attr('data-upload-image') == "true") {
                var contentType = false;
                var processData = false;
                inputs = (window.FormData) ? formData : null;
            }


        } else {
            inputs = window[form.attr('data-custom-function')](form);

        }

        if (form.attr('data-checkData') !== undefined && form.attr('data-checkData').length > 0) {
            var check = window[form.attr('data-checkData')](inputs);

            if (typeof (check) == "object" && !check.statut) {
                if (recaptcha) {
                    grecaptcha.reset();
                }

                div_msg.html('<div class="alert alert-danger"><b>' + ERROR_MSG + ' : </b>' + check.msg + '</div>');
                submit.html(submit_btn_content).attr('disabled', false).fadeIn(500);
                return;
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: form.attr('action'),
            data: inputs,
            method: 'post',
            contentType: (contentType === undefined) ? 'application/x-www-form-urlencoded; charset=UTF-8' : contentType,
            processData: (processData === undefined) ? 'application/x-www-form-urlencoded; charset=UTF-8' : processData,
            success: function (data) {
                if (typeof data != 'object') {
                    try {
                        var json = JSON.parse(data);
                    } catch (e) { // si c'est pas du JSON
                        console.log(e);

                        if (recaptcha) {
                            grecaptcha.reset();
                        }

                        div_msg.html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><i class="fa fa-times"></i> <b>' + ERROR_MSG + ' :</b> ' + INTERNAL_ERROR_MSG + '</i></div>');
                        submit.html(submit_btn_content).attr('disabled', false).fadeIn(500);
                    }
                } else {
                    json = data;
                }
                if (json.success === true) {
                    if (form.attr('data-success-msg') === undefined || form.attr('data-success-msg') == "true") {
                        div_msg.html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><i class="fa fa-exclamation"></i> <b>' + SUCCESS_MSG + ' :</b> ' + json.data + '</i></div>').fadeIn(500);
                    }
                    if (form.attr('data-callback-function') !== undefined) {
                        window[form.attr('data-callback-function')](inputs, json);
                    }
                    if (form.attr('data-redirect-url') !== undefined) {
                        document.location.href = form.attr('data-redirect-url') + '?no-cache=' + (new Date()).getTime();
                    }
                    submit.html(submit_btn_content).attr('disabled', false).fadeIn(500);
                } else if (json.success === false) {

                    if (recaptcha) {
                        grecaptcha.reset();
                    }

                    let error = "<ul>";
                    for (const value of json.data) {
                        error += "<li>" + value + "</li>";
                    }
                    error += "</ul>";

                    div_msg.html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><i class="fa fa-times"></i> <b>' + ERROR_MSG + ' :</b> '
                        +
                        error
                        +
                        '</i></div>'
                    ).fadeIn(500);
                    submit.html(submit_btn_content).attr('disabled', false).fadeIn(500);
                } else {

                    if (recaptcha) {
                        grecaptcha.reset();
                    }

                    div_msg.html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><i class="fa fa-times"></i> <b>' + ERROR_MSG + ' :</b> ' + INTERNAL_ERROR_MSG + '</i></div>');
                    submit.html(submit_btn_content).attr('disabled', false).fadeIn(500);
                }
            },
            error: function (xhr) {
                if (recaptcha) {
                    grecaptcha.reset();
                }

                if (xhr.status == "403") {
                    div_msg.html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><i class="fa fa-times"></i> <b>' + ERROR_MSG + ' :</b> ' + FORBIDDEN_ERROR_MSG + '</i></div>');
                } else {
                    div_msg.html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><i class="fa fa-times"></i> <b>' + ERROR_MSG + ' :</b> ' + INTERNAL_ERROR_MSG + '</i></div>');
                }
                submit.html(submit_btn_content).attr('disabled', false).fadeIn(500);
            }
        });
    });
}

initForms();