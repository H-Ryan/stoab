/**
 * Created by Samuil on 01-02-2015.
 */
$(document).ready(function () {

    var tolkForm = $('.ui.form.tolkForm');
    var startHour = $("#starttid");
    var endHour = $("#sluttid");
    var startMinute = $("#starttid1");
    var endMinute = $("#sluttid1");

    $('.tolk-type').popup({
        inline: true,
        transition: "scale"
    });
    $('.radio.checkbox').checkbox();
    $('#date').datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, minDate: 0 });
    $('.dropdown').dropdown({transition: 'drop'});

    adjustTime(startHour, startMinute, endHour, endMinute);
    startHour.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });
    endHour.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });
    startMinute.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });
    endMinute.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });

    $('.button.book-btn').click(function () {
        $(".button.book-btn")
            .removeClass("disabled")
            .parents(".ui.form.tolkForm")
            .removeClass("error")
            .removeClass("transition")
            .removeClass("visible");
        $.ajax({
            type: "POST",
            url: "src/misc/orderOnetimer.php",
            data: tolkForm.serialize(),
            dataType: "json",
            beforeSend: function () {
                tolkForm.addClass("loading");
            }
        }).done(function (data) {
            tolkForm.form('reset');
            tolkForm.get(0).reset();
            tolkForm.removeClass("loading");
            if (data.mailError == null) {
                if (data.error == 0) {
                    window.location.replace("index.php");
                    return false;
                }
                else {
                    var errorElem = tolkForm.find(".ui.error.message");
                    tolkForm.removeClass("loading").addClass("error");
                    errorElem.children("p").text(data.errorMessage);
                    errorElem.children('.header').text(data.messageHeader);
                    return false;
                }
            } else {
                var errorElemEmail = tolkForm.find(".ui.error.message");
                tolkForm.removeClass("loading").addClass("error");
                errorElemEmail.children("p").text(data.emailErrorMessage);
                errorElemEmail.children('.header').text(data.emailErrorHeader);
                return false;
            }
        });
        return false;
    });
    jQuery.validator.setDefaults({
        debug: true,
        success: "valid"
    });
    tolkForm.validate({
        errorPlacement: function(error, element) {
            if (element.attr("name") == "telephone" || element.attr("name") == "mobile") {
                error.appendTo( element.closest('.fields') );
            } else {
                error.appendTo( element.closest('.field') );
            }
        },
        ignore: ":hidden:not(select)",
        errorElement: "div",
        errorClass: "error",
        validClass: "valid",
        onfocusout: function (element) {
            this.element(element);
        },
        onkeyup: false,
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.field').addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.field').addClass(validClass).removeClass(errorClass);
        },
        groups: {
            phoneGroup: "telephone mobile"
        },
        rules: {
            client: {
                maxlength: 90,
                minlength: 3
            },
            language: {
                required: true
            },
            type: {
                required: true
            },
            tolk_type: {
                required: true
            },
            date: {
                required: true,
                date: true
            },
            start_hour: {
                required: true,
                digits: true
            },
            start_minute: {
                required: true,
                digits: true
            },
            end_hour: {
                required: true,
                digits: true
            },
            end_minute: {
                required: true,
                digits: true
            },
            contactPerson: {
                required: true,
                minlength: 3,
                maxlength: 90
            },
            organization: {
                required: true,
                minlength: 3,
                maxlength: 60
            },
            email: {
                required: true,
                maxlength: 150,
                email: true
            },
            telephone: {
                require_from_group: [1, ".phone-group"],
                minlength: 8,
                maxlength: 11
            },
            mobile: {
                require_from_group: [1, ".phone-group"],
                minlength: 8,
                maxlength: 11
            },
            address: {
                required: true,
                maxlength: 100,
                minlength: 5
            },
            post_code: {
                required: true,
                digits: true,
                maxlength: 5,
                minlength: 5
            },
            city: {
                required: true
            },
            message: {
                maxlength: 255
            }
        },
        messages: {
            client: {
                maxlength: "Den kund området bör<br />innehålla mindre än {0} tecken.",
                minlength: "Den kund området bör<br />innehålla mer än {0} tecken."
            },
            date: {
                required: "Fältet Datum krävs.",
                date: "Fältet innehåller ogiltigt datum."
            },
            language: {
                required: "Välj ett av de språk från rullgardinsmenyn."
            },
            contactPerson: {
                required: "Fält Beställaren krävs.",
                maxlength: "Fält Beställaren bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält Beställaren bör<br />innehålla mer än {0} tecken."
            },
            organization: {
                required: "Fält Organisation krävs.",
                maxlength: "Fält Organisation bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält Organisation bör<br />innehålla mer än {0} tecken."
            },
            email: {
                required: "Fält e-postadress krävs.",
                email: "Den här e-post är inte giltig.",
                maxlength: "Fältet e-postadress ska<br />innehålla mindre än {0} tecken."
            },
            telephone: {
                regex: "Fält telefon är ogiltig.",
                maxlength: "Fältet Telefon bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fältet Telefon bör<br />innehålla mer än {0} tecken."
            },
            mobile: {
                regex: "Fält Mobil är ogiltig.",
                maxlength: "Fältet Mobil bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fältet Mobil bör<br />innehålla mer än {0} tecken."
            },
            address: {
                required: "Fält plats krävs.",
                maxlength: "Fält Plats bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält Plats bör<br />innehålla mer än {0} tecken."
            },
            post_code: {
                required: "Fält post nummer krävs.",
                digits: "Fält post nummer ska<br />endast innehålla siffror.",
                maxlength: "Fält Postnummer bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält Postnummer bör<br />innehålla mer än {0} tecken."
            },
            city: {
                required: "Välj en av städerna från rullgardinslistan."
            },
            message: {
                maxlength: "Fält plats bör<br />innehålla mindre än {0} tecken."
            },
            end_hour: {
                required: "Fel"
            },
            end_minute: {
                required: "Fel"
            }
        }
    });
    $(".ui.form.tolkForm input, select").on('input',function(){
            $(this).valid();
    });
    $(".button.next-btn").click( function() {
        tolkForm.validate();
        if (tolkForm.valid()) {
            switchFromTo(tolkForm.find('fieldset:visible'), tolkForm.find('fieldset:visible').next());
        }
    });

    $(".button.back-btn").click( function() {
        switchFromTo(tolkForm.find('fieldset:visible'), tolkForm.find('fieldset:visible').prev());
    });

});

function switchFromTo(from, to) {
    $(from).transition({ animation: 'vertical flip', duration: '500ms',
        onComplete: function () { $(to).transition({ animation: 'vertical flip', duration: '500ms' }); }
    });
}

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