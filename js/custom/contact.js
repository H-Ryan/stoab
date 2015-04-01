/**
 * Created by Samuil on 01-04-2015.
 */
$(document).ready(function () {
    var contactForm = $('#contact-form');
    var resultModal = $('.ui.modal');
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
                required: "hhhhhhhhh",
                maxlength: "Den kund området bör<br />innehålla mindre än {0} tecken.",
                minlength: "Den kund området bör<br />innehålla mer än {0} tecken."
            },
            foretagsnamn: {
                required: "Fält Företagsnamn krävs.",
                maxlength: "Fält Företagsnamn bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält Företagsnamn bör<br />innehålla mer än {0} tecken."
            },
            phone: {
                required: "ddddddddd",
                number: "Fält telefon är ogiltig.",
                maxlength: "Fältet Telefon bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fältet Telefon bör<br />innehålla mer än {0} tecken."
            },
            subject: {
                required: "",
                maxlength: "Fältet Telefon bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fältet Telefon bör<br />innehålla mer än {0} tecken."
            },
            email: {
                required: "Fält e-postadress krävs.",
                email: "Den här e-post är inte giltig.",
                maxlength: "Fältet e-postadress ska<br />innehålla mindre än {0} tecken."
            },
            messageC: {
                required: "ssssssss",
                maxlength: "Fält plats bör<br />innehålla mindre än {0} tecken."
            }
        }
    });
    $("#contact-form input, select, textarea").on('input',function(){
        $(this).valid();
    });
    $("#btnSubmit").on("click", function() {
        contactForm.validate();
        if (contactForm.valid()) {
            $.ajax({
                type: "POST",
                url: "src/shared/contact.php",
                data: contactForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    contactForm.find(".segment").addClass("loading");
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
                resultModal.modal("show");
            });
            contactForm.find(".segment").removeClass("loading");
            return false;
        }
    });
    $("#btnReset").on("click", function() {
        contactForm.get(0).reset();
        validator.resetForm();
        contactForm.find('[class*="grid_"]').removeClass("error");
    });

});