'use strict';
$(document).ready(function () {

    $('#date').datepicker({
        format: "yyyy-mm-dd",
        startDate: '0',
        autoclose: true,
        todayBtn: 'linked',
        language: 'sv'
    });

});

(function ($) {
    var startHour = $("#starttid"),
        endHour = $("#sluttid"),
        startMinute = $("#starttid1"),
        endMinute = $("#sluttid1");

    adjustTime(startHour, startMinute, endHour, endMinute);
    startHour.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });
    endHour.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });
    startMinute.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });
    endMinute.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });

    $('#orderTranslationForm:not([data-type=advanced])').validate({
        submitHandler: function(form) {

            var $form = $(form),
                $messageSuccess = $('#translationOrdertSuccess'),
                $messageError = $('#translationOrdertError'),
                $submitButton = $(this.submitButton),
                file_data = $('#attachment').prop('files')[0],
                form_data = new FormData();
            $submitButton.button('loading');

            form_data.append('clientNumber', $form.find('#clientNumber').val());
            form_data.append('name',  $form.find('#name').val());
            form_data.append('phone', $form.find('#phone').val());
            form_data.append('email', $form.find('#email').val());
            form_data.append('by', $form.find('input:radio[name=by]:checked').val());
            form_data.append('ddate', $form.find('input:radio[name=ddate]:checked').val());
            form_data.append('fromLang', $form.find('#fromLang').val());
            form_data.append('toLang', $form.find('#toLang').val());
            form_data.append('comment', $form.find('#comment').val());
            form_data.append('attachment', file_data);
            // Ajax Submit
            $.ajax({
                type: 'POST',
                url: $form.attr('action'),
                data: form_data,
                processData: false,
                contentType: false,
                complete: function(data) {

                    if (typeof data.responseJSON === 'object') {
                        if (data.responseJSON.error === false) {

                            $messageSuccess.removeClass('hidden');
                            $messageError.addClass('hidden');

                            // Reset Form
                            $form.find('.form-control')
                                .val('')
                                .blur()
                                .parent()
                                .removeClass('has-success')
                                .removeClass('has-error')
                                .find('label.error')
                                .remove();

                            if (($messageSuccess.offset().top - 100) < $(window).scrollTop()) {
                                $('html, body').animate({
                                    scrollTop: $messageSuccess.offset().top - 100
                                }, 300);
                            }

                            $submitButton.button('reset');

                            return;

                        }
                    }

                    $messageError.removeClass('hidden');
                    $messageSuccess.addClass('hidden');

                    if (($messageError.offset().top - 80) < $(window).scrollTop()) {
                        $('html, body').animate({
                            scrollTop: $messageError.offset().top - 80
                        }, 300);
                    }

                    $form.find('.has-success')
                        .removeClass('has-success');

                    $submitButton.button('reset');

                }
            });
        }
    });

    $('#orderTranslationForm, #contactForm[data-type=advanced]').validate({
        onkeyup: false,
        onclick: false,
        onfocusout: true,
        rules: {
            'captcha': {
                captcha: true
            },
            'checkboxes[]': {
                required: true
            },
            'radios': {
                required: true
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox') {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

}).apply(this, [jQuery]);


$('#clearform').on('click', function () {
    $("#orderTranslationForm").validate().resetForm();  // clear out the validation errors	
});

function adjustTime(startH, startM, endH, endM) {
    endH.find('option').prop('disabled', false);
    endM.find('option').prop('disabled', false);
    endH.find('option').filter(function (index) {
        return index < startH.val();
    }).each(function () {
        $(this).prop('disabled', true)
    });
    if (startH.val() === endH.val()){
        endM.find('option').filter(function (index) {
            return index <= startM.val();
        }).each(function () {
            $(this).prop('disabled', true)
        });
    }
}