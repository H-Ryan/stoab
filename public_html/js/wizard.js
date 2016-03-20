(function ($) {

    'use strict';

    /*
     Wizard book interpreter
     */

    var formModal = $('#bookInterpreterModal'),
        wizard = $('#wizard'),
        wizardForm = wizard.find('form'),
        messageError = $('#interpretationOrderError'),
        $wizardFinish = wizard.find('ul.pager li.finish'),
        $wizardValidator = wizardForm.validate({
            highlight: function (element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).remove();
            },
            errorPlacement: function (error, element) {
                element.parent().append(error);
            }
        });

    $wizardFinish.on('click', function (ev) {
        ev.preventDefault();
        if (wizardForm.valid()) {
            $.ajax({
                type: "POST",
                url: "./src/misc/orderOnetimer.php",
                data: wizardForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    $wizardFinish.addClass('disabled');
                },
                complete: function (data) {
                    if (typeof data.responseJSON === 'object') {
                        if (data.responseJSON.error === 0) {
                            messageError.addClass('hidden');
                            wizard.find("a[href*='#customerOrGuest']").trigger('click');
                            wizardForm[0].reset();
                            $wizardFinish.removeClass('disabled');
                            formModal.modal('hide');
                            return true;
                        }
                        messageError.removeClass('hidden');
                        $wizardFinish.removeClass('disabled');
                    }
                }
            });
        }
    });

    formModal.on('hidden.bs.modal', function () {
        wizard.find("a[href*='#customerOrGuest']").trigger('click');
        wizardForm[0].reset();
        messageError.addClass('hidden');
        $wizardFinish.removeClass('disabled');
    });
    formModal.on('hidden', function () {
        messageError.addClass('hidden');
        $(this).removeData('modal');
    });
    wizard.bootstrapWizard({
        tabClass: 'wizard-steps',
        nextSelector: 'ul.pager li.next',
        previousSelector: 'ul.pager li.previous',
        firstSelector: null,
        lastSelector: null,
        onNext: function (tab, navigation, index, newindex) {
            var validated = wizardForm.valid();
            if (!validated) {
                $wizardValidator.focusInvalid();
                return false;
            }
        },
        onTabClick: function (tab, navigation, index, newindex) {
            if (newindex == index + 1) {
                return this.onNext(tab, navigation, index, newindex);
            } else if (newindex > index + 1) {
                return false;
            }
            return true;
        },
        onTabChange: function (tab, navigation, index, newindex) {
            var totalTabs = navigation.find('li').size() - 1;
            $wizardFinish[newindex != totalTabs ? 'addClass' : 'removeClass']('hidden');
            wizard.find(this.nextSelector)[newindex == totalTabs ? 'addClass' : 'removeClass']('hidden');
        }
    });

    /*
     Wizard register client
     */

    var registerWizard = $('#registerWizard'),
        registerFormModal = $('#registerFormModal'),
        registerWizardForm = $('#registeringForm'),
        registerWizardFinish = registerWizard.find('ul.pager li.finish'),
        registerError = $('#registerError'),
        registerWizardValidator = registerWizardForm.validate({
            highlight: function (element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).remove();
            },
            errorPlacement: function (error, element) {
                element.parent().append(error);
            }
        });

    registerWizardFinish.on('click', function (ev) {
        ev.preventDefault();
        if (registerWizardForm.valid()) {
            $.ajax({
                type: "POST",
                url: "./src/services/register-customer.php",
                data: registerWizardForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    registerWizardFinish.addClass('disabled');
                },
                complete: function (data) {
                    if (typeof data.responseJSON === 'object') {
                        if (data.responseJSON.error === 0) {
                            registerError.addClass('hidden');
                            registerWizard.find("a[href*='#company']").trigger('click');
                            registerWizardForm[0].reset();
                            registerWizardFinish.removeClass('disabled');
                            registerFormModal.modal('hide');
                            return true;
                        }
                        registerError.removeClass('hidden');
                        registerWizardFinish.removeClass('disabled');
                    }
                }
            });
        }
    });

    registerFormModal.on('hidden.bs.modal', function () {
        registerWizard.find("a[href*='#company']").trigger('click');
        registerWizardForm[0].reset();
        registerError.addClass('hidden');
        registerWizardFinish.removeClass('disabled');
    });
    registerFormModal.on('hidden', function () {
        registerError.addClass('hidden');
        $(this).removeData('modal');
    });
    registerWizard.bootstrapWizard({
        tabClass: 'wizard-steps',
        nextSelector: 'ul.pager li.next',
        previousSelector: 'ul.pager li.previous',
        firstSelector: null,
        lastSelector: null,
        onNext: function (tab, navigation, index, newindex) {
            var validated = registerWizardForm.valid();
            if (!validated) {
                registerWizardValidator.focusInvalid();
                return false;
            }
        },
        onTabClick: function (tab, navigation, index, newindex) {
            if (newindex == index + 1) {
                return this.onNext(tab, navigation, index, newindex);
            } else if (newindex > index + 1) {
                return false;
            }
            return true;
        },
        onTabChange: function (tab, navigation, index, newindex) {
            var totalTabs = navigation.find('li').size() - 1;
            registerWizardFinish[newindex != totalTabs ? 'addClass' : 'removeClass']('hidden');
            registerWizard.find(this.nextSelector)[newindex == totalTabs ? 'addClass' : 'removeClass']('hidden');
        }
    });

    /*
     Wizard interpreter job application
     */
    var interpreterJobWizard = $('#interpreterJobWizard'),
        interpreterJobModal = $('#interpreterModalForm'),
        interpreterJobFinish = interpreterJobWizard.find('ul.pager li.finish'),
        interpreterJobForm = $('#interpreterJobForm'),
        interpreterJobError = $('#interpreterJobError'),
        interpreterJobValidator = interpreterJobForm.validate({
            highlight: function (element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).remove();
            },
            errorPlacement: function (error, element) {
                element.parent().append(error);
            }
        });

    interpreterJobFinish.on('click', function (ev) {
        ev.preventDefault();
        var validated = interpreterJobForm.valid();
        if (validated) {
            $.ajax({
                type: "POST",
                url: "./src/services/interpreter-job-application.php",
                data: interpreterJobForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    interpreterJobFinish.addClass('disabled');
                },
                complete: function (data) {
                    if (typeof data.responseJSON === 'object') {
                        if (data.responseJSON.error === 0) {
                            interpreterJobError.addClass('hidden');
                            interpreterJobWizard.find("a[href*='#w2-account']").trigger('click');
                            interpreterJobForm[0].reset();
                            interpreterJobFinish.removeClass('disabled');
                            interpreterJobModal.modal('hide');
                            return true;
                        }
                        interpreterJobError.removeClass('hidden');
                        interpreterJobFinish.removeClass('disabled');
                    }
                }
            });
        }
    });

    interpreterJobModal.on('hidden.bs.modal', function () {
        interpreterJobWizard.find("a[href*='#w2-account']").trigger('click');
        interpreterJobForm[0].reset();
        interpreterJobError.addClass('hidden');
        interpreterJobFinish.removeClass('disabled');
    });
    interpreterJobModal.on('hidden', function () {
        interpreterJobError.addClass('hidden');
        $(this).removeData('modal');
    });

    interpreterJobWizard.bootstrapWizard({
        tabClass: 'wizard-steps',
        nextSelector: 'ul.pager li.next',
        previousSelector: 'ul.pager li.previous',
        firstSelector: null,
        lastSelector: null,
        onNext: function (tab, navigation, index, newindex) {
            var validated = interpreterJobForm.valid();
            if (!validated) {
                interpreterJobValidator.focusInvalid();
                return false;
            }
        },
        onTabClick: function (tab, navigation, index, newindex) {
            if (newindex == index + 1) {
                return this.onNext(tab, navigation, index, newindex);
            } else if (newindex > index + 1) {
                return false;
            }
            return true;
        },
        onTabChange: function (tab, navigation, index, newindex) {
            var totalTabs = navigation.find('li').size() - 1;
            interpreterJobFinish[newindex != totalTabs ? 'addClass' : 'removeClass']('hidden');
            interpreterJobWizard.find(this.nextSelector)[newindex == totalTabs ? 'addClass' : 'removeClass']('hidden');
        }
    });

    /*
     Wizard translator job application
     */
    var translatorJobWizard = $('#translatorJobWizard'),
        translatorJobModal = $('#translatorModalForm'),
        translatorJobFinish = translatorJobWizard.find('ul.pager li.finish'),
        translatorJobForm = $('#translatorJobForm'),
        translatorJobError = $('#translatorJobError'),
        translatorJobValidator = translatorJobForm.validate({
            highlight: function (element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).remove();
            },
            errorPlacement: function (error, element) {
                element.parent().append(error);
            }
        });

    translatorJobFinish.on('click', function (ev) {
        ev.preventDefault();
        var validated = translatorJobForm.valid();
        if (validated) {
            $.ajax({
                type: "POST",
                url: "./src/services/translator-job-application.php",
                data: translatorJobForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    translatorJobFinish.addClass('disabled');
                },
                complete: function (data) {
                    if (typeof data.responseJSON === 'object') {
                        if (data.responseJSON.error === 0) {
                            translatorJobError.addClass('hidden');
                            translatorJobWizard.find("a[href*='#w2-personal']").trigger('click');
                            translatorJobForm[0].reset();
                            translatorJobFinish.removeClass('disabled');
                            translatorJobModal.modal('hide');
                            return true;
                        }
                        translatorJobError.removeClass('hidden');
                        translatorJobFinish.removeClass('disabled');
                    }
                }
            });
        }
    });

    translatorJobModal.on('hidden.bs.modal', function () {
        translatorJobWizard.find("a[href*='#w2-personal']").trigger('click');
        translatorJobForm[0].reset();
        translatorJobError.addClass('hidden');
        translatorJobFinish.removeClass('disabled');
    });
    translatorJobModal.on('hidden', function () {
        translatorJobError.addClass('hidden');
        $(this).removeData('modal');
    });

    translatorJobWizard.bootstrapWizard({
        tabClass: 'wizard-steps',
        nextSelector: 'ul.pager li.next',
        previousSelector: 'ul.pager li.previous',
        firstSelector: null,
        lastSelector: null,
        onNext: function (tab, navigation, index, newindex) {
            var validated = translatorJobForm.valid();
            if (!validated) {
                translatorJobValidator.focusInvalid();
                return false;
            }
        },
        onTabClick: function (tab, navigation, index, newindex) {
            if (newindex == index + 1) {
                return this.onNext(tab, navigation, index, newindex);
            } else if (newindex > index + 1) {
                return false;
            }
            return true;
        },
        onTabChange: function (tab, navigation, index, newindex) {
            var totalTabs = navigation.find('li').size() - 1;
            translatorJobFinish[newindex != totalTabs ? 'addClass' : 'removeClass']('hidden');
            $('#w3').find(this.nextSelector)[newindex == totalTabs ? 'addClass' : 'removeClass']('hidden');
        }
    });

}).apply(this, [jQuery]);

jQuery.validator.addMethod("personalNumber", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^\d{8}-?\d{4}$/.test(value);
}, 'Please enter a valid personal number.');