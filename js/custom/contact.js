/**
 * Created by Samuil on 01-04-2015.
 */
$(document).ready(function () {
    var contactForm = $('#contact-form');
    var resultModal = $('.ui.modal');
    var formDimmer = $(".ui.dimmer").dimmer({
        closable: false
    });
    var validator = contactForm.validate({
        errorPlacement: function(error, element) {
            error.appendTo( element.closest('form div') );
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
            $(element).closest('[class*="grid_"]').removeClass(validClass).addClass(errorClass);
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('[class*="grid_"]').removeClass(errorClass).addClass(validClass);
        },
        rules: {
            name: {
                required: true,
                maxlength: 90,
                minlength: 3
            },
            foretagsnamn: {
                required: true,
                maxlength: 90,
                minlength: 3
            },
            phone: {
                required: true,
                number: true,
                minlength: 9,
                maxlength: 11
            },
            subject: {
                required: true,
                maxlength: 90,
                minlength: 3
            },
            email: {
                required: true,
                maxlength: 90,
                email: true
            },
            messageC: {
                required: true,
                maxlength: 90,
                minlength: 6
            }
        },
        messages: {
            name: {
                required: "Skriv ditt namn.",
                maxlength: "Fält namn bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält namn bör<br />innehålla mer än {0} tecken."
            },
            foretagsnamn: {
                required: "Skriv ditt företagsnamn.",
                maxlength: "Fält Företagsnamn bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält Företagsnamn bör<br />innehålla mer än {0} tecken."
            },
            phone: {
                required: "Skriv ditt telefon nummer.",
                number: "Det ska feiyll nummer.",
                maxlength: "Fältet Telefon bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fältet Telefon bör<br />innehålla mer än {0} tecken."
            },
            subject: {
                required: "Skriv ditt e-post ämne.",
                maxlength: "Fältet ämne bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fältet ämne bör<br />innehålla mer än {0} tecken."
            },
            email: {
                required: "Skriv ditt e-post adress.",
                email: "Den här e-post är inte giltig.",
                maxlength: "Fältet e-postadress ska<br />innehålla mindre än {0} tecken."
            },
            messageC: {
                required: "Skriv ditt medelande.",
                maxlength: "Fält medelande bör<br />innehålla mindre än {0} tecken."
            }

        }
    });
    $("#contact-form input, select, textarea").on('input',function(){
        $(this).valid();
    });
    $("#btnSubmit").on("click", function() {
        contactForm.validate();
        if (contactForm.valid()) {
            $("#btnSubmit").css('background-color', '#f1f045');
            $.ajax({
                type: "POST",
                url: "src/services/contact.php",
                data: contactForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    formDimmer.dimmer('show');
                }
            }).done(function (data) {
                if(data.error === false) {
                    resultModal.find(".header").text("Klart");
                    resultModal.find(".content").text("Ditt meddelande är sänt!");
                    contactForm.get(0).reset();
                    validator.resetForm();
                } else {
                    resultModal.find(".header").text(data.emailErrorHeader);
                    resultModal.find(".content").text(data.emailErrorMessage);
                }
                formDimmer.dimmer('hide');
                resultModal.modal("show");
            });
        } else {
            $("#btnSubmit").css('background-color', '#d95c5c');
        }
        return false;
    });
    $("#btnReset").on("click", function() {
        contactForm.get(0).reset();
        validator.resetForm();
        contactForm.find('[class*="grid_"]').removeClass("error");
    });

});