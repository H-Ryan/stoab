$(document).ready(function () {
    "use strict";
    var orderForm = $('.ui.form.orderForm');
    var tolkSearchFrom = $('.ui.form.tolk-search');

    jQuery.validator.setDefaults({
        debug: true,
        success: "valid"
    });

    $(".typeTip").popup();

    CKEDITOR.replace( 'newsLetter' );
    //var editor_data = CKEDITOR.instances.editor1.getData();

    var startHour = $("#starttid");
    var endHour = $("#sluttid");
    var startMinute = $("#starttid1");
    var endMinute = $("#sluttid1");
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

    $('.dropdown').dropdown({transition: 'drop'});

    $('.logout-btn').click(function () {
        $.ajax({type: "POST", url: "src/misc/logout.php"}).done(function () {
            window.location = "index.php";
        });
        return false;
    });
    $('.modal.tolk-info')
        .modal('setting', 'transition', 'vertical flip')
        .modal('attach events', '.button.btn-info', 'show');

    $("#date").datepicker({dateFormat: 'yy-mm-dd', minDate: 0 });

    $('.tolk-type').popup({inline: true, transition: "scale"});
    $('.radio.checkbox').checkbox();
    $('.menu .item').tab();

    $(".regOrganization").change(function () {
        var selectedOrg = $(".regOrganization option:selected").text();
        var selectedValue = orderForm.form('get value', 'regOrganization');
        if (selectedValue != "") {
            $("#organization").val(selectedOrg);
            $.ajax({
                type: "POST",
                url: "src/misc/getOrgInfo.php",
                data: {branch_number: selectedValue},
                dataType: "json",
                beforeSend: function () {
                    orderForm.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    var orgInfo = data.orgInfo;
                    if (orgInfo.k_kundNumber !== "100000") {
                        orderForm.form('set values', {
                            organizationNumber: orgInfo.k_personalNumber,
                            clientNumber: orgInfo.k_kundNumber,
                            contactPerson: orgInfo.k_firstName + " " + orgInfo.k_lastName,
                            email: orgInfo.k_email,
                            telephone: orgInfo.k_tel,
                            mobile: orgInfo.k_mobile,
                            address: orgInfo.k_address,
                            post_code: orgInfo.k_zipCode,
                            city: orgInfo.k_city
                        });
                    }
                }
                orderForm.removeClass('loading');
            });
        }
        return false;
    });

    $('.btn-update-manage').click(function () {
        $.ajax({
            type: "POST",
            url: "src/misc/currentOrders.php",
            data: $(".order_manage").serialize(),
            cache: false,
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .dimmer').dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                var tBody = $('.orderManage tbody');
                $('.orderManage').find('tbody').find('tr').remove();
                if (data.orders.length > 0) {
                    var orders = data.orders;
                    var customers = data.customers;
                    for (var i = 0; i < orders.length; i++) {
                        var btnColor = 'orange';
                        var infoMsg = 'Info';
                        var state = orders[i].o_state;
                        switch (state) {
                            case 'O':
                                infoMsg = 'Beställ in Progress';
                                btnColor = 'orange';
                                break;
                            case 'B':
                                infoMsg = 'Färdig';
                                btnColor = 'green';
                                break;
                            case 'EC':
                                infoMsg = 'Avbruten';
                                btnColor = 'red';
                                break;
                        }
                        tBody.append(
                            "<tr>" +
                            "<td>" + orders[i].o_orderNumber + "</td>" +
                            "<td>" + customers[i].k_organizationName + "</td>" +
                            "<td>" + orders[i].o_orderer + "</td>" +
                            "<td>" + orders[i].o_language + "</td>" +
                            "<td class='typeTip' data-content='" + getFullTolkningType(orders[i].o_interpretationType) + "'>" + orders[i].o_interpretationType + "</td>" +
                            "<td>" + orders[i].o_date + "</td>" +
                            "<td>" + convertTime(orders[i].o_startTime) + "</td>" +
                            "<td>" + convertTime(orders[i].o_endTime) + "</td>" +
                            "<td>" +
                            "<form class='ui form' method='post' action='src/misc/orderInfo.php'>" +
                            "<input type='hidden' name='orderId' value='" + orders[i].o_orderNumber + "'>" +
                            "<button type='submit' class='ui " + btnColor + " fluid button btn_manage_order'>" + infoMsg + "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                        $(".typeTip").popup();
                    }
                    $('.modal.tolk-info')
                        .modal('setting', 'transition', 'vertical flip')
                        .modal('attach events', '.button.btn_manage_order', 'show');
                } else {
                    tBody.append("<tr><td><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
                }

                $('.ui.dimmable .dimmer').dimmer('toggle');
                $('.button.btn-update-manage').prop("disabled", true).addClass("disabled");
                setTimeout(function () {
                    var btnRefresh = $('.button.btn-update-manage');
                    btnRefresh.prop("disabled", false).removeClass("disabled");
                }, 3000);
            }
            else {
                var errorElem = $(".ui.error.message");
                $(".login-btn").parents(".login").removeClass("loading").addClass("error");
                errorElem.children("p").text("Error Message");
                errorElem.children('.header').text("Error");
            }
        });
    });

    $('.btn-update-history').click(function () {
        $.ajax({
            type: "POST",
            url: "src/misc/pastOrders.php",
            data: $(".order_history").serialize(),
            cache: false,
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .dimmer').dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                var tBody = $('.orderHistory tbody');
                tBody.find('tr').remove();
                if (data.orders.length > 0 && data.orders.length > 0) {
                    var orders = data.orders;
                    var customers = data.customers;
                    for (var i = 0; i < orders.length; i++) {
                        var btnColor = 'orange';
                        var infoMsg = 'Info';
                        var state = orders[i].o_state;
                        switch (state) {
                            case 'O':
                                infoMsg = 'Beställ in Progress';
                                btnColor = 'orange';
                                break;
                            case 'B':
                                infoMsg = 'Färdig';
                                btnColor = 'green';
                                break;
                            case 'EC':
                                infoMsg = 'Avbruten';
                                btnColor = 'red';
                                break;
                        }
                        tBody.append(
                            "<tr>" +
                            "<td>" + orders[i].o_orderNumber + "</td>" +
                            "<td>" + customers[i].k_organizationName + "</td>" +
                            "<td>" + orders[i].o_orderer + "</td>" +
                            "<td>" + orders[i].o_language + "</td>" +
                            "<td class='typeTip' data-content='" + getFullTolkningType(orders[i].o_interpretationType) + "'>" + orders[i].o_interpretationType + "</td>" +
                            "<td>" + orders[i].o_date + "</td>" +
                            "<td>" + convertTime(orders[i].o_startTime) + "</td>" +
                            "<td>" + convertTime(orders[i].o_endTime) + "</td>" +
                            "<td>" +
                            "<form class='ui form' method='post'>" +
                            "<input type='hidden' name='orderId' value='" + orders[i].o_orderNumber + "'>" +
                            "<button type='submit' class='ui " + btnColor + " fluid button btn_manage_order'>" + infoMsg + "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                        $(".typeTip").popup();
                    }
                    $('.modal.tolk-info')
                        .modal('setting', 'transition', 'vertical flip')
                        .modal('attach events', '.button.btn_manage_order', 'show');
                } else {
                    tBody.append("<tr><td><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
                }

                $('.ui.dimmable .dimmer').dimmer('toggle');
                $('.button.btn-update-history').prop("disabled", true).addClass("disabled");
                setTimeout(function () {
                    var btnRefresh = $('.btn-update-history');
                    btnRefresh.prop("disabled", false).removeClass("disabled");
                }, 3000);
            }
            else {
                var errorElem = $(".ui.error.message");
                $(".login-btn").parents(".login").removeClass("loading").addClass("error");
                errorElem.children("p").text("Error Message");
                errorElem.children('.header').text("Error");
            }
        });
    });

    tolkSearchFrom.form({
        language: {
            identifier: 'language',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Välj ett av de språk från rullgardinsmenyn.'
                }
            ]
        }
    }, {
        inline: true,
        on: 'blur',
        transition: "slide down",
        onSuccess: function () {
            $.ajax({
                type: "POST",
                url: "src/misc/searchTolks.php",
                data: tolkSearchFrom.serialize(),
                cache: false,
                dataType: "json",
                beforeSend: function () {
                    $('.btnSearchTolk').addClass('loading');
                }
            }).done(function (data) {
                var container = $('.tolks');
                container.find('.tolksTable').remove();
                if (data.error == 0) {
                    container.append("<table class='ui collapsing celled table tolksTable'>"
                    + "<thead>"
                    + "<tr>"
                    + "<th class='one wide'>Nummer</th>"
                    + "<th class='three wide'>Namn</th>"
                    + "<th class='two wide'>Län</th>"
                    + "<th class='one wide'>Stad</th>"
                    + "<th class='one wide'>Kön</th>"
                    + "<th class='two wide'>Nivå</th>"
                    + "<th class='two wide'>Rankning</th>"
                    + "<th class='two wide'>E-post</th>"
                    + "<th class='one wide'>Mobil</th>"
                    + "<th class='one wide'>Info</th>"
                    + "</tr>"
                    + "</thead>"
                    + "<tbody>");
                    if (data.tolks.length > 0) {
                        var tolks = data.tolks;
                        for (var i = 0; i < tolks.length; i++) {
                            container.find('.tolksTable').find('tbody').append(
                                "<tr>" +
                                "<td>" + tolks[i].t_tolkNumber + "</td>" +
                                "<td>" + tolks[i].u_firstName + " " + tolks[i].u_lastName + "</td>" +
                                "<td>" + tolks[i].u_state + "</td>" +
                                "<td>" + tolks[i].u_city + "</td>" +
                                "<td>" + tolks[i].t_gender + "</td>" +
                                "<td>" + tolks[i].t_tolkNiva + "</td>" +
                                "<td>" + tolks[i].t_rate + "</td>" +
                                "<td>" + tolks[i].u_email + "</td>" +
                                "<td>" + tolks[i].u_mobile + "</td>" +
                                "<td>" +
                                "<form class='ui form'>" +
                                "<input type='hidden' name='orderId' value='" + tolks[i].u_personalNumber + "'>" +
                                "<button type='button' class='ui blue fluid button btn-info'>Mer Info</button>" +
                                "</form>" +
                                "</td>" +
                                "</tr></tbody></table>");
                        }
                        $('.modal.tolk-info')
                            .modal('setting', 'transition', 'vertical flip')
                            .modal('attach events', '.button.btn-info', 'show');
                    } else {
                        container.append("<div class='ui segment'><div class='ui text'>För närvarande, har du inte några order.</div></div>");
                    }
                } else {
                    var errorElem = $(".ui.error.message");
                    $(".login-btn").parents(".login").removeClass("loading").addClass("error");
                    errorElem.children("p").text("Error Message");
                    errorElem.children('.header').text("Error");
                }
                $('.btnSearchTolk').removeClass('loading');
            });
        },
        onFailure: function () {
            tolkSearchFrom.removeClass("error").removeClass("transition").removeClass("visible");
        }
    });
    $('.btnSearchTolk').click(function () {
        tolkSearchFrom.form('validate form');
    });

    orderForm.validate({
        errorPlacement: function (error, element) {
            if (element.attr("name") == "telephone" || element.attr("name") == "mobile") {
                error.appendTo(element.closest('.fields'));
            } else {
                error.appendTo(element.closest('.field'));
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
            $(element).closest('.field').addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.field').addClass(validClass).removeClass(errorClass);
        },
        groups: {
            phone_group: "telephone mobile"
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
                minlength: 9,
                maxlength: 11
            },
            mobile: {
                require_from_group: [1, ".phone-group"],
                minlength: 9,
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


    jQuery.extend(jQuery.validator.messages, {
        require_from_group: "Fyll i minst ett av dessa områden."
    });

    $(".ui.form.orderForm input, select").on('input', function () {
        $(this).valid();
    });
    $('.button.order-btn').click(function () {
        $(".button.order-btn").removeClass("disabled");
        orderForm.removeClass("error transition visible");
        $.ajax({
            type: "POST",
            url: "../src/misc/orderRegular.php",
            data: orderForm.serialize(),
            dataType: "json",
            beforeSend: function () {
                orderForm.addClass("loading");
            }
        }).done(function (data) {
            if (data.error == 0) {
                orderForm.form('reset');
                orderForm.get(0).reset();
                orderForm.removeClass("loading");
                switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset').first());
            }
            else {
                var errorElem = $(".ui.error.message");
                orderForm.removeClass("loading").addClass("error");
                errorElem.children("p").text("Error Message");
                errorElem.children('.header').text("Error");
            }
        });
    });

    $(".button.next-btn").click(function () {
        orderForm.validate();
        if (orderForm.valid()) {
            switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset:visible').next());
        }
    });

    $(".button.back-btn").click(function () {
        switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset:visible').prev());
    });
});

function switchFromTo(from, to) {
    $(from).transition({
        animation: 'vertical flip', duration: '500ms',
        onComplete: function () {
            $(to).transition({animation: 'vertical flip', duration: '500ms'});
        }
    });
}

function convertTime(value) {
    var minutes = ["00", "15", "30", "45"];
    return ((value - (value % 4)) / 4) + ":" + minutes[(value % 4)];
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

function getFullTolkningType(type) {
    return (type == 'KT') ? 'Kontakttolkning'
        : (type == 'TT') ? 'Telefontolkning'
        : (type == 'KP') ? 'Kontaktperson'
        : (type == 'SH') ? 'Studiehandledning'
        : "Språkstöd";
}