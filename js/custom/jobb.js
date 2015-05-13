/**
 * Created by Samuil on 01-04-2015.
 */
$(document).ready(function () {
    "use strict";
    var formDimmer = $(".ui.dimmer").dimmer({
        closable: false
    });
    $('.ui.radio.checkbox').checkbox();
    $('select.dropdown').dropdown();

    var jobForm = $('#job-form');
    var resultModal = $('.ui.modal');
    var validator = jobForm.validate({
        errorPlacement: function (error, element) {
            if (element.is($("select[name*='langCompetence']"))) {
                error.appendTo(element.closest('form [class*="grid_"]'));
            } else {
                error.appendTo(element.closest('form div'));
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
        highlight: function (element, errorClass, validClass) {
            $(element).closest('[class*="grid_"]').removeClass(validClass).addClass(errorClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('[class*="grid_"]').removeClass(errorClass).addClass(validClass);
        },
        groups: {
            phone_group: "phoneHome phoneMobile"
        },
        rules: {
            firstName: {
                required: true,
                maxlength: 45,
                minlength: 3
            },
            lastName: {
                required: true,
                maxlength: 45,
                minlength: 3
            },
            personalNumber: {
                required: true,
                personalNumber: true
            },
            gender: {
                required: true
            },
            tax: {
                required: true
            },
            car: {
                required: true
            },
            email: {
                required: true,
                maxlength: 150,
                email: true
            },
            phoneHome: {
                require_from_group: [1, ".phone-group"],
                minlength: 9,
                maxlength: 11,
                digits: true
            },
            phoneMobile: {
                require_from_group: [1, ".phone-group"],
                minlength: 9,
                maxlength: 11,
                digits: true
            },
            address: {
                required: true,
                maxlength: 100,
                minlength: 5
            },
            postNumber: {
                required: true,
                digits: true,
                maxlength: 5,
                minlength: 5
            },
            city: {
                required: true
            },
            terms: {
                required: true
            },
            languageOne: {
                required: true
            },
            langCompetenceOne: {
                required: true
            },
            langCompetenceTwo: {
                required: function () {
                    return $("#languageTwo").val().length > 3;
                }
            },
            langCompetenceThree: {
                required: function () {
                    return $("#languageThree").val().length > 3;
                }
            },
            langCompetenceFour: {
                required: function () {
                    return $("#languageFour").val().length > 3;
                }
            }
        },
        messages: {
            firstName: {
                required: "Skriv ditt förnamn.",
                maxlength: "Fält förnamn bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält förnamn bör<br />innehålla mer än {0} tecken."
            },
            lastName: {
                required: "Skriv ditt efternamn.",
                maxlength: "Fält efternamn bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält efternamn bör<br />innehålla mer än {0} tecken."
            },
            personalNumber: {
                required: "Skriv ditt personnummer.",
                personalNumber: "Ditt personnummer måste vara i det här formatet \"ÅÅÅÅMMDD-NNNN\""
            },
            gender: {
                required: "Välja ditt kön."
            },
            tax: {
                required: "Välja ditt jrkebevis."
            },
            car: {
                required: "Välj din bil status."
            },
            email: {
                required: "Skriv ditt e-postadress.",
                maxlength: "Fält e-postadress bör<br />innehålla mindre än {0} tecken.",
                email: "Din e-postadress måste vara i det här formatet \"DITTNAMN@EXEMPEL.COM\"."
            },
            phoneHome: {
                require_from_group: "Du måste ange antingen hemnummer eller mobilnummer eller både.",
                maxlength: "Fältet telefon bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fältet telefon bör<br />innehålla mer än {0} tecken.",
                digits: "Det ska feiyll nummer."
            },
            phoneMobile: {
                require_from_group: "Du måste ange antingen hemnummer eller mobilnummer eller både.",
                maxlength: "Fältet mobil bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fältet mobil bör<br />innehålla mer än {0} tecken.",
                digits: "Det ska feiyll nummer."
            },
            address: {
                required: "Skriv ditt adress.",
                maxlength: "Fält adress bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält adress bör<br />innehålla mer än {0} tecken."
            },
            postNumber: {
                required: "Skriv ditt post nummer.",
                digits: "Det ska feiyll nummer.",
                maxlength: "Fält post nummer bör<br />innehålla mindre än {0} tecken.",
                minlength: "Fält post nummer bör<br />innehålla mer än {0} tecken."
            },
            city: {
                required: "Skriv ditt stad."
            },
            terms: {
                required: "Du måste trycka på pul och samtycka."
            },
            languageOne: {
                required: "Du måste välja minst ett språk."
            },
            langCompetenceOne: {
                required: "Välj ett språk från listan."
            },
            langCompetenceTwo: {
                required: "Välj ett språk från listan."
            },
            langCompetenceThree: {
                required: "Välj ett språk från listan."
            },
            langCompetenceFour: {
                required: "Välj ett språk från listan."
            }

        }
    });
    jQuery.extend(jQuery.validator.messages, {
        require_from_group: "Fyll i minst ett av dessa områden."
    });
    jQuery.validator.addMethod("personalNumber", function (value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /^\d{8}-?\d{4}$/.test(value);
    }, 'Please enter a valid personal number.');

    $("#job-form input, select, textarea").on('input', function () {
        $(this).valid();
    });


    $("#btnSubmit").on("click", function () {
        jobForm.validate();
        if (jobForm.valid()) {
            $("#btnSubmit").css('background-color', '#f1f045');
            $.ajax({
                type: "POST",
                url: "src/services/job.php",
                data: jobForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    formDimmer.dimmer('show');
                }
            }).done(function (data) {
                if (data.error === false) {
                    resultModal.find(".header").text("Klart");
                    resultModal.find(".content").text("Ditt meddelande är sänt!");
                    jobForm.get(0).reset();
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
    $("#btnReset").on("click", function () {
        jobForm.get(0).reset();
        validator.resetForm();
        jobForm.find('[class*="grid_"]').removeClass("error");
    });
});