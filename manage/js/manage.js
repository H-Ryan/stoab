/**
 * Created by Samuil on 21-02-2015.
 */
$(document).ready(function () {
    var manageForm = $('.ui.form.assignTolk'),
        resendForm = $("#resendEmailForm"),
        tolkTable = $('.ui.table.tolkTable'),
        modalResend= $('.modal.modalResend'),
        modalAssign = $('.basic.modal.modalAssign'),
        modalReAssign = $('.basic.modal.modalReAssign'),
        modalCancel = $('.basic.modal.modalCancel'),
        btnVerify = $('.ui.button.btnVerify'),
        btnCancel = $('.ui.button.btnCancel'),
        btnAssign = $('.ui.button.btnAssign'),
        btnReAssign = $('.ui.button.btnReAssign'),
        isValid = false;

    $('.close.icon').click(function() {
        $('.ui.message').hide();
    });
    manageForm.form({
        tolkNumber: {
            identifier: 'tolkNumber',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Tolkens nummer krävs.'
                },
                {
                    type: 'integer',
                    prompt: 'Tolkens numret ska vara ett numeriskt värde.'
                },
                {
                    type: 'length[4]',
                    prompt: 'Tolkens numret ska vara exakt fyra tecken.'
                },
                {
                    type: 'maxLength[4]',
                    prompt: 'Tolkens numret ska vara exakt fyra tecken.'
                }

            ]
        }
    }, {
        inline: true,
        on: 'change',
        transition: "slide down",
        onValid: function () {
            btnVerify.prop("disabled", false);
            btnAssign.prop("disabled", false);
            btnReAssign.prop("disabled", false);
        },
        onInvalid: function () {
            btnVerify.prop("disabled", true);
            btnAssign.prop("disabled", true);
            btnReAssign.prop("disabled", true);
        },
        onSuccess: function () {
            $.ajax({
                type: "POST",
                url: "src/misc/tolkInfo.php",
                data: manageForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    tolkTable.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    var tolk = data.tolk;
                    $('.tolkInfoNumber').text(tolk.t_tolkNumber);
                    $('.tolkInfoName').text(tolk.u_firstName + " " + tolk.u_lastName);
                    $('.tolkInfoEmail').text(tolk.u_email);
                    $('.tolkInfoTelephone').text(tolk.u_tel);
                    $('.tolkInfoCity').text(tolk.u_city);
                    isValid = true;
                    tolkTable.removeClass('loading');
                    return false;
                } else {
                    var errorElem = manageForm.find(".ui.error.message");
                    manageForm.removeClass("loading").addClass("error");
                    errorElem.children(".ui.text").text(data.errorMessage);
                    errorElem.children('.header').text(data.messageHeader);
                    tolkTable.removeClass('loading');
                    errorElem.show();
                    isValid = false;
                }
                tolkTable.removeClass('loading');
            });
        },
        onFailure: function () {
            btnVerify.prop("disabled", true);
            btnAssign.prop("disabled", true);
            btnReAssign.prop("disabled", true);
        }
    });
    btnVerify.click(function () {
        manageForm.form('validate form');
        return false;
    });
    btnAssign.on('click', function() {
        manageForm.form('validate form');
        if (isValid) {
            $('.basic.modal.modalAssign').modal('show');
        }
        return false;
    });

    btnReAssign.on('click', function() {
        manageForm.form('validate form');
        if (isValid) {
            $('.basic.modal.modalReAssign').modal('show');
        }
        return false;
    });
    //TODO
    modalCancel.modal({
        closable: false,
        onDeny: function () {
            return true;
        },
        onApprove: function () {
            $.ajax({
                type: "POST",
                url: "src/misc/cancelOrder.php",
                data: manageForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    manageForm.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    manageForm.removeClass('loading');
                    window.location.replace("main.php");
                }
            });
        }
    }).modal('attach events', btnCancel, 'show');
    modalAssign.modal({
        closable: false,
        onDeny: function () {
            return true;
        },
        onApprove: function () {
            $.ajax({
                type: "POST",
                url: "src/misc/assignTolk.php",
                data: manageForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    manageForm.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    manageForm.removeClass('loading');
                    window.location.replace("main.php");
                }
            });
        }
    });
    modalReAssign.modal({
        closable: false,
        onDeny: function () {
            return true;
        },
        onApprove: function () {
            $.ajax({
                type: "POST",
                url: "src/misc/reAssignTolk.php",
                data: manageForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    manageForm.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    manageForm.removeClass('loading');
                    window.location.replace("main.php");
                }
            });
        }
    });

    $("#resendToTolk").on('click', function() {
        modalResend.modal('show');
        modalResend.modal({
            closable: false,
            onDeny: function () {
                return true;
            },
            onApprove: function () {
                $.ajax({
                    type: "POST",
                    url: "src/misc/resendTolkConfirm.php",
                    data: resendForm.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        resendForm.addClass('loading');
                    }
                }).done(function (data) {
                    if (data.error == 0) {
                        var successElem = resendForm.find(".ui.positive.message");
                        resendForm.removeClass("loading").addClass("error");
                        successElem.children("p").text(data.positiveMessage);
                        successElem.children('.header').text(data.messageHeader);
                        successElem.show();
                    } else {
                        var errorElem = resendForm.find(".ui.error.message");
                        resendForm.removeClass("loading").addClass("error");
                        errorElem.children("p").text(data.errorMessage);
                        errorElem.children('.header').text(data.messageHeader);
                        errorElem.show();
                    }
                    resendForm.removeClass('loading');
                });
            }
        });
    });
    $("#resendToClient").on('click', function() {
        modalResend.modal('show');
        modalResend.modal({
            closable: false,
            onDeny: function () {
                return true;
            },
            onApprove: function () {
                $.ajax({
                    type: "POST",
                    url: "src/misc/resendClientConfirm.php",
                    data: resendForm.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        resendForm.addClass('loading');
                    }
                }).done(function (data) {
                    if (data.error == 0) {
                        var successElem = resendForm.find(".ui.positive.message");
                        resendForm.removeClass("loading").addClass("error");
                        successElem.children("p").text(data.positiveMessage);
                        successElem.children('.header').text(data.messageHeader);
                        successElem.show();
                    } else {
                        var errorElem = resendForm.find(".ui.error.message");
                        resendForm.removeClass("loading").addClass("error");
                        errorElem.children("p").text(data.errorMessage);
                        errorElem.children('.header').text(data.messageHeader);
                        errorElem.show();
                    }
                    resendForm.removeClass('loading');
                });
            }
        });
    });
    $("#resendToClientAboutTolk").on('click', function() {
        modalResend.modal('show');
        modalResend.modal({
            closable: false,
            onDeny: function () {
                return true;
            },
            onApprove: function () {
                $.ajax({
                    type: "POST",
                    url: "src/misc/resendClientAboutTolkAssign.php",
                    data: resendForm.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        resendForm.addClass('loading');
                    }
                }).done(function (data) {
                    if (data.error == 0) {
                        var successElem = resendForm.find(".ui.positive.message");
                        resendForm.removeClass("loading").addClass("error");
                        successElem.children("p").text(data.positiveMessage);
                        successElem.children('.header').text(data.messageHeader);
                        successElem.show();
                    } else {
                        var errorElem = resendForm.find(".ui.error.message");
                        resendForm.removeClass("loading").addClass("error");
                        errorElem.children("p").text(data.errorMessage);
                        errorElem.children('.header').text(data.messageHeader);
                        errorElem.show();
                    }
                    resendForm.removeClass('loading');
                });
            }
        });
    });
});