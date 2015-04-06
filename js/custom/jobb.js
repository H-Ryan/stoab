/**
 * Created by Samuil on 01-04-2015.
 */
$(document).ready(function () {
    "use strict";
    $('.ui.radio.checkbox').checkbox();
    $('select.dropdown').dropdown();

    var jobForm = $('#job-form');
    var resultModal = $('.ui.modal');
    var validator = jobForm.validate({
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
        groups: {
            phone_group: "phoneHome phoneMobile"
        },
        rules: {
            name: {
                required: true,
                maxlength: 90,
                minlength: 3
            },
            personalNumber: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 12
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
                required: function(element) { return $("#languageTwo").text().length > 3;}
            },
            langCompetenceThree: {
                required: function(element) { return $("#languageThree").text().length > 3;}
            },
            langCompetenceFour: {
                required: function(element) { return $("#languageFour").text().length > 3;}
            }
        },
        messages: {
            name: {
                required: "",
                maxlength: "",
                minlength: ""
            },
            personalNumber: {
                required: "",
                digits: "",
                minlength: "",
                maxlength: ""
            },
            gender: {
                required: ""
            },
            tax: {
                required: ""
            },
            car: {
                required: ""
            },
            email: {
                required: "",
                maxlength: "",
                email: ""
            },
            phoneHome: {
                require_from_group: "",
                minlength: "",
                maxlength: "",
                digits: ""
            },
            phoneMobile: {
                require_from_group: "",
                minlength: "",
                maxlength: "",
                digits: ""
            },
            address: {
                required: "",
                maxlength: "",
                minlength: ""
            },
            postNumber: {
                required: "",
                digits: "",
                maxlength: "",
                minlength: ""
            },
            city: {
                required: ""
            },
            terms: {
                required: ""
            },
            languageOne: {
                required: ""
            },
            langCompetenceOne: {
                required: ""
            },
            langCompetenceTwo: {
                required: ""
            },
            langCompetenceThree: {
                required: ""
            },
            langCompetenceFour: {
                required: ""
            }
        }
    });
    jQuery.extend(jQuery.validator.messages, {
        require_from_group: "Fyll i minst ett av dessa områden."
    });
    $("#contact-form input, select, textarea").on('input', function(){
        $(this).valid();
    });
    $("#btnSubmit").on("click", function() {
        jobForm.validate();
        if (jobForm.valid()) {
            $.ajax({
                type: "POST",
                url: "src/services/job.php",
                data: jobForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    jobForm.find(".segment").addClass("loading");
                }
            }).done(function (data) {
                if(data.error === false) {
                    resultModal.find(".header").text("Klart");
                    resultModal.find(".content").text("Ditt meddelande är sänt!");
                    jobForm.get(0).reset();
                    validator.resetForm();
                } else {
                    resultModal.find(".header").text(data.emailErrorHeader);
                    resultModal.find(".content").text(data.emailErrorMessage);
                }
                resultModal.modal("show");
            });
            jobForm.find(".segment").removeClass("loading");
            return false;
        }
    });
    $("#btnReset").on("click", function() {
        jobForm.get(0).reset();
        validator.resetForm();
        jobForm.find('[class*="grid_"]').removeClass("error");
    });
});