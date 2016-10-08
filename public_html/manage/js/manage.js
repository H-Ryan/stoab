/**
 * Created by Samuil on 21-02-2015.
 */
$(document).ready(function () {
    $.fn.form.settings.rules.oneOf = function (value, fieldIdentifiers) {
        var $form = $(this);

        return !!value || fieldIdentifiers.split(',').some(function (fieldIdentifier) {
                return $form.find('#' + fieldIdentifier).val() ||
                    $form.find('[name="' + fieldIdentifier + '"]').val() ||
                    $form.find('[data-validate="' + fieldIdentifier + '"]').val();

            });
    };
    var manageForm = $('.ui.form.assignTolk'),
        resendForm = $("#resendEmailForm"),
        tolkTable = $('.ui.table.tolkTable'),
        modalResend = $('.modal.modalResend'),
        modalAssign = $('.basic.modal.modalAssign'),
        modalReAssign = $('.basic.modal.modalReAssign'),
        modalCancel = $('.basic.modal.modalCancel'),
        modalTolkCancel = $('.basic.modal.modalTolkCancel'),
        btnVerify = $('.ui.button.btnVerify'),
        btnCancel = $('.ui.button.btnCancel'),
        btnTolkCancel = $('.ui.button.btnTolkCancel'),
        btnAssign = $('.ui.button.btnAssign'),
        btnReAssign = $('.ui.button.btnReAssign'),
        isValid = false,
        comment = $("#comment"),
        btnEditComment = $('#btnEditComment'),
        modalEditComment = $('.modal.modalEditComment'),
        taNewComment = $("#newComment"),
        errMessage = $("#errMessage"),
        editOrderModal = $('.modal.editOrder'),
        btnEditModal = $('#btnEditOrderInfo'),
        editOrderForm = $('#editOrderForm'),
        btnSubmitEditForm = $('#editOrderBtn'),
        startHour = $("#starttid"),
        endHour = $("#sluttid"),
        startMinute = $("#starttid1"),
        endMinute = $("#sluttid1");

    $('.ui.dropdown').dropdown();

    $("#date").datepicker({
        dateFormat: 'yy-mm-dd', firstDay: 1, minDate: 0,
        onSelect: function () {
            editOrderForm.form('validate form');
        }
    });

    btnEditModal.on('click', function () {
        editOrderModal.modal('show');
    });

    btnSubmitEditForm.on('click', function () {
        if (editOrderForm.form('is valid')) {
            $.ajax({
                type: "POST",
                url: "../src/misc/updateOrderInfo.php",
                data: editOrderForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    editOrderModal.addClass("loading");
                }
            }).done(function (data) {
                var errorElem = $("#orderEditErrorField");
                if (typeof data === 'object') {
                    if (data.error == 0) {
                        $.ajax({
                            type: "POST",
                            url: "../src/misc/orderInfo.php",
                            data: {'orderId': $("#orderNumber").val()},
                            dataType: "json"
                        }).done(function (data) {
                            window.location.reload();
                        });
                        return;
                    }
                    editOrderForm.removeClass("loading").addClass("error");
                    errorElem.children("p").text();
                    errorElem.children('.header').text();
                    return;
                }
                editOrderForm.removeClass("loading").addClass("error");
                errorElem.children("p").text('There is a problem in the script');
                errorElem.children('.header').text('PHP error');

            });
        }
    });

    editOrderModal.modal({
        onHide: function () {
            editOrderForm.form('reset');
        }
    });

    editOrderForm.form({
        inline: true,
        delay: true,
        on: 'blur',
        transition: "scale",
        fields: {
            date: {
                identifier: 'date',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fältet Datum krävs.'
                    },
                    {
                        type: 'regExp[/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/]',
                        prompt: 'Fältet innehåller ogiltigt datum.'
                    }
                ]
            },
            start_hour: {
                identifier: 'start_hour',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fel'
                    },
                    {
                        type: 'integer[0..23]',
                        prompt: 'Fel'
                    }
                ]
            },
            start_minute: {
                identifier: 'start_minute',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fel'
                    },
                    {
                        type: 'integer',
                        prompt: 'Fel'
                    }
                ]
            },
            end_hour: {
                identifier: 'end_hour',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fel'
                    },
                    {
                        type: 'integer[0..23]',
                        prompt: 'Fel'
                    }
                ]
            },
            end_minute: {
                identifier: 'end_minute',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fel'
                    },
                    {
                        type: 'integer',
                        prompt: 'Fel'
                    }
                ]
            },
            contactPerson: {
                identifier: 'contactPerson',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fält beställaren krävs.'
                    },
                    {
                        type: 'minLength[3]',
                        prompt: 'Fält Beställaren bör<br />innehålla mer än {ruleValue} tecken.'
                    },
                    {
                        type: 'maxLength[90]',
                        prompt: 'Fält Beställaren bör<br />innehålla mindre än {ruleValue} tecken.'
                    }
                ]
            },
            type: {
                identifier: 'type',
                rules: [
                    {
                        type: 'checked',
                        prompt: 'Fält typ av tolkning krävs.'
                    }
                ]
            },
            email: {
                identifier: 'email',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fält e-postadress krävs.'
                    },
                    {
                        type: 'email',
                        prompt: 'Den här e-post är inte giltig.'
                    }
                ]
            },
            telephone: {
                identifier: 'telephone',
                rules: [
                    {
                        type: 'oneOf[mobile]',
                        prompt: 'Du måste ange antingen hemnummer eller mobilnummer eller både.'
                    }
                ]
            },
            address: {
                identifier: 'address',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fält plats krävs.'
                    },
                    {
                        type: 'minLength[3]',
                        prompt: 'Den plats området bör<br />innehålla mer än {ruleValue} tecken.'
                    },
                    {
                        type: 'maxLength[90]',
                        prompt: 'Den plats området bör<br />innehålla mindre än {ruleValue} tecken.'
                    }
                ]
            }
        }
    });

    modalEditComment.modal({
        closable: false,
        onDeny: function () {
            taNewComment.val(comment.text());
            errMessage.hide();
            return true;
        },
        onApprove: function () {
            $.ajax({
                type: "POST",
                url: "../src/misc/updateComment.php",
                data: {'orderNumber': $("#orderNumber").val(), 'data': taNewComment.val()},
                dataType: "json"
            }).done(function (data) {
                if (data.error == 0) {
                    comment.text(taNewComment.val());
                    errMessage.hide();
                } else {
                    errMessage.show();
                }
            });
            return true;
        }
    }).modal('attach events', btnEditComment, 'show');

    $('.close.icon').click(function () {
        $('.ui.message').hide();
    });
    manageForm.form({
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
                url: "../src/misc/tolkInfo.php",
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
                    $('.tolkInfoMobile').text(tolk.u_mobile);
                    $('.tolkInfoCity').text(tolk.u_city);
                    isValid = true;
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
        },
        fields: {
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
        }
    });
    btnVerify.click(function () {
        manageForm.form('validate form');
        return false;
    });
    btnAssign.on('click', function () {
        manageForm.form('validate form');
        if (isValid) {
            $('.basic.modal.modalAssign').modal('show');
        }
        return false;
    });

    btnReAssign.on('click', function () {
        manageForm.form('validate form');
        if (isValid) {
            $('.basic.modal.modalReAssign').modal('show');
        }
        return false;
    });
    modalCancel.modal({
        closable: false,
        onDeny: function () {
            return true;
        },
        onApprove: function () {
            $.ajax({
                type: "POST",
                url: "../src/misc/cancelOrder.php",
                data: manageForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    manageForm.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    tt
                } else {
                    var errorElem = manageForm.find(".error.message");
                    errorElem.find('.header').text(data.messageHeader);
                    errorElem.find('.text').text(data.errorMessage);
                    errorElem.show();
                }
                manageForm.removeClass('loading');
            });
        }
    }).modal('attach events', btnCancel, 'show');
    modalTolkCancel.modal({
        closable: false,
        onDeny: function () {
            return true;
        },
        onApprove: function () {
            $.ajax({
                type: "POST",
                url: "../src/misc/interpreter/tolkCancel.php",
                data: manageForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    manageForm.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    window.open(data.smsURL, "_blank");
                    refreshWindow();
                } else {
                    var errorElem = manageForm.find(".error.message");
                    errorElem.find('.header').text(data.messageHeader);
                    errorElem.find('.text').text(data.errorMessage);
                    errorElem.show();
                }
                manageForm.removeClass('loading');
            });
        }
    }).modal('attach events', btnTolkCancel, 'show');
    modalAssign.modal({
        closable: false,
        onDeny: function () {
            return true;
        },
        onApprove: function () {
            $.ajax({
                type: "POST",
                url: "../src/misc/assignTolk.php",
                data: manageForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    manageForm.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    window.open(data.smsURL, "_blank");
                    refreshWindow();
                } else {
                    var errorElem = manageForm.find(".error.message");
                    errorElem.find('.header').text(data.messageHeader);
                    errorElem.find('.text').text(data.errorMessage);
                    errorElem.show();
                }
                manageForm.removeClass('loading');
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
                url: "../src/misc/reAssignTolk.php",
                data: manageForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    manageForm.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    window.open(data.smsURL, "_blank");
                    refreshWindow();
                } else {
                    var errorElem = manageForm.find(".error.message");
                    errorElem.find('.header').text(data.messageHeader);
                    errorElem.find('.text').text(data.errorMessage);
                    errorElem.show();
                }
                manageForm.removeClass('loading');
            });
        }
    });

    $("#resendToTolk").on('click', function () {
        modalResend.modal('show');
        modalResend.modal({
            closable: false,
            onDeny: function () {
                return true;
            },
            onApprove: function () {
                $.ajax({
                    type: "POST",
                    url: "../src/misc/resendTolkConfirm.php",
                    data: resendForm.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        resendForm.addClass('loading');
                    }
                }).done(function (data) {
                    if (data.error == 0) {
                        var successElem = resendForm.find(".ui.positive.message");
                        successElem.children("p").text(data.positiveMessage);
                        successElem.children('.header').text(data.messageHeader);
                        successElem.show();
                    } else {
                        var errorElem = resendForm.find(".ui.error.message");
                        errorElem.children("p").text(data.errorMessage);
                        errorElem.children('.header').text(data.messageHeader);
                        errorElem.show();
                    }
                    resendForm.removeClass('loading');
                });
            }
        });
    });
    $("#resendToClient").on('click', function () {
        modalResend.modal('show');
        modalResend.modal({
            closable: false,
            onDeny: function () {
                return true;
            },
            onApprove: function () {
                $.ajax({
                    type: "POST",
                    url: "../src/misc/resendClientConfirm.php",
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
    $("#resendToClientAboutTolk").on('click', function () {
        modalResend.modal('show');
        modalResend.modal({
            closable: false,
            onDeny: function () {
                return true;
            },
            onApprove: function () {
                $.ajax({
                    type: "POST",
                    url: "../src/misc/resendClientAboutTolkAssign.php",
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
    adjustTime(startHour, startMinute, endHour, endMinute);
    startHour.change(function () {
        adjustTime(startHour, startMinute, endHour, endMinute);
    });
    endHour.change(function () {
        adjustTime(startHour, startMinute, endHour, endMinute);
    });
    startMinute.change(function () {
        adjustTime(startHour, startMinute, endHour, endMinute);
    });
    endMinute.change(function () {
        adjustTime(startHour, startMinute, endHour, endMinute);
    });
});

function refreshWindow() {
    $.ajax({
        type: "POST",
        url: "../src/misc/orderInfo.php",
        data: {orderId: $("#orderNumber").val()},
        dataType: "json"
    }).done(function (data) {
        window.location.reload();
    });
}
function convertTime(value) {
    var minutes = ["00", "15", "30", "45"];
    return ((value - (value % 4)) / 4) + ":" + minutes[(value % 4)];
}

function adjustTime(startH, startM, endH, endM) {
    endH.siblings('.menu').find('.item').removeClass('disabled');
    endM.siblings('.menu').find('.item').removeClass('disabled');
    endH.find('option').prop('disabled', false);
    endM.find('option').prop('disabled', false);
    endH.find('option').filter(function (index) {
        return index < startH.val();
    }).each(function () {
        $(this).prop('disabled', true);
        endH.parent('.dropdown').find('*[data-value="' + $(this).val() + '"]').addClass('disabled');
    });
    if (startH.val() === endH.val()) {
        endM.find('option').filter(function (index) {
            return index <= startM.val();
        }).each(function () {
            $(this).prop('disabled', true);
            endM.parent('.dropdown').find('*[data-value="' + $(this).val() + '"]').addClass('disabled');
        });
    }
}
