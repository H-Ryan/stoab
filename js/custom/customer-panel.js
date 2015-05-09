$(document).ready(function () {
    "use strict";
    $(".typeTip").popup();
    var orderForm = $('.ui.form.orderForm');
    var changeForm = $('.ui.form.changePass');
    $('.dropdown').dropdown({transition: 'drop'});
    var startHour = $("#starttid");
    var endHour = $("#sluttid");
    var startMinute = $("#starttid1");
    var endMinute = $("#sluttid1");
    adjustTime(startHour, startMinute, endHour, endMinute);
    startHour.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });
    endHour.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });
    startMinute.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });
    endMinute.change(function() { adjustTime(startHour, startMinute, endHour, endMinute); });

    $('.logout-btn').click(function () {
        $.ajax({type: "POST", url: "src/misc/logout.php"}).done(function () {
            window.location.replace("index.php");
            return false;
        });
        return false;
    });
    $('.modal.change-pass')
        .modal({
            onHide: function () {
                changeForm.form('reset');
                changeForm.get(0).reset();
                changeForm.removeClass("loading");
                return false;
            }
        })
        .modal('setting', 'transition', 'scale')
        .modal('attach events', '.btn-change-pass', 'show');
    $('.modal.tolk-info')
        .modal('setting', 'transition', 'scale');
    //TODO
    $('.button.btn-info').on("click",function() {
        var extraInfoCont = $('.modal.tolk-info');
        var id =$(this).parent("form").attr('id');
        $(this).addClass('loading');
        $.ajax({
            type: "POST",
            url: "src/misc/orderMoreInfo.php",
            data: $("#" + id).serialize(),
            dataType: "json"
        }).done(function (data) {
            if (data.error == 0) {
                extraInfoCont.find('.segment').first().find('.header span').text(data.order.o_orderNumber);
                var orderBody = $('.orderExtra').find('tbody');
                var tolkBody = $('.tolkExtra').find('tbody');
                orderBody.find('tr').remove();
                tolkBody.find('tr').remove();
                orderBody.append(
                    "<tr>" +
                    "<td>" + data.order.o_address + "</td>" +
                    "<td>" + data.order.o_zipCode + "</td>" +
                    "<td>" + data.order.o_city + "</td>" +
                    "<td>" + data.order.o_client + "</td>" +
                    "<td>" + data.order.o_comments + "</td>" +
                    "</tr>");
                tolkBody.append(
                    "<tr><td colspan='5'><div class='ui center aligned header'>"+
                    "Det finns ingen tolk tilldelats för denna ordning ännu."+
                    "</div></td></tr>");
                if (data.order.o_tolkarPersonalNumber != null) {
                    tolkBody.find('tr').remove();
                    tolkBody.append(
                        "<tr>" +
                        "<td>" + data.tolk.u_firstName + " " + data.tolk.u_lastName + "</td>" +
                        "<td>" + data.tolk.t_tolkNumber + "</td>" +
                        "<td>" + data.tolk.u_tel + "</td>" +
                        "<td>" + data.tolk.u_mobile + "</td>" +
                        "<td>" + data.tolk.u_city + "</td>" +
                        "</tr>");
                }
                extraInfoCont.modal('show');
                $('#' + id).find('.button').removeClass('loading');
                return false;
            } else {
                $('#' + id).find('.button').removeClass('loading');
                return false;
            }
        });
    });

    $("#date").datepicker({dateFormat: 'yy-mm-dd', minDate: 0 });

    $('.ui.fluid.accordion').accordion();

    $('.tolk-type').popup({inline: true, transition: "scale"});
    $('.ui.checkbox').checkbox();
    $('.menu .item').tab();
    $('.ui.dimmer').dimmer({
        transition: 'vertical flip',
        closable: false
    });

    $('.page-customer .item').on("click", function() {
        var pageNum = "1";
        if(!$(this).hasClass('icon')) {
            $(this)
                .addClass('active')
                .closest('.ui.menu')
                .find('.item')
                .not($(this))
                .removeClass('active')
            ;
            pageNum = $(this).text();
        } else {
            var currPage = $('.page-customer').find(".active.item");
            if($(this).hasClass('previousPage')){

                var previousPage = currPage.prevAll('.item').not('.previousPage').first();
                if (!$.isEmptyObject(previousPage) && previousPage.hasClass("item")) {
                    currPage.removeClass("active");
                    previousPage.addClass("active");
                    pageNum = previousPage.text();
                } else {
                    pageNum = currPage.text();
                }
            } else if ($(this).hasClass('nextPage')) {
                var nextPage = currPage.nextAll('.item').not('.nextPage').first();
                if (!$.isEmptyObject(nextPage) && nextPage.hasClass("item")) {
                    currPage.removeClass("active");
                    nextPage.addClass("active");
                    pageNum = nextPage.text();
                } else {
                    pageNum = currPage.text();
                }
            }
        }
        $("#updateCurrPage").val(pageNum);
        $.ajax({
            type: "POST",
            url: "src/misc/orderHistory.php",
            data: $(".update_order").serialize(),
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .dimmer').dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                var tBody = $('.orderHistory tbody');
                tBody.find('tr').remove();
                if (data.orders.length > 0) {
                    var orders = data.orders;
                    for (var i = 0; i < orders.length; i++) {
                        var btnColor = 'orange';
                        var infoMsg = 'Info';
                        var state = orders[i].o_state;
                        switch (state) {
                            case 'O':
                                infoMsg = 'Ej färdig';
                                btnColor = 'orange';
                                break;
                            case 'B':
                                infoMsg = 'Färdig';
                                btnColor = 'green';
                                break;
                            case 'EC':
                                infoMsg = 'Avbokad';
                                btnColor = 'red';
                                break;
                        }
                        tBody.append(
                            "<tr>" +
                            "<td>" + orders[i].o_orderNumber + "</td>" +
                            "<td>" + orders[i].o_orderer + "</td>" +
                            "<td>" + orders[i].o_language + "</td>" +
                            "<td class='typeTip' data-content='" + getFullTolkningType(orders[i].o_interpretationType) + "'>" + orders[i].o_interpretationType + "</td>" +
                            "<td>" + orders[i].o_date + "</td>" +
                            "<td>" + convertTime(orders[i].o_startTime) + "</td>" +
                            "<td>" + convertTime(orders[i].o_endTime) + "</td>" +
                            "<td>" +
                            "<form class='ui form' id='" + orders[i].o_orderNumber + "'>" +
                            "<input type='hidden' name='orderId' value='" + orders[i].o_orderNumber + "'>" +
                            "<button type='button' class='ui " + btnColor + " fluid button btn-info'>" + infoMsg + "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                        $(".typeTip").popup();
                    }
                    $('.modal.tolk-info')
                        .modal('setting', 'transition', 'vertical flip');

                    $('.button.btn-info').on("click",function() {
                        var extraInfoCont = $('.modal.tolk-info');
                        var id =$(this).parent("form").attr('id');
                        $(this).addClass('loading');
                        $.ajax({
                            type: "POST",
                            url: "src/misc/orderMoreInfo.php",
                            data: $("#" + id).serialize(),
                            dataType: "json"
                        }).done(function (data) {
                            if (data.error == 0) {
                                extraInfoCont.find('.segment').first().find('.header span').text(data.order.o_orderNumber);
                                var orderBody = $('.orderExtra').find('tbody');
                                var tolkBody = $('.tolkExtra').find('tbody');
                                orderBody.find('tr').remove();
                                tolkBody.find('tr').remove();
                                orderBody.append(
                                    "<tr>" +
                                    "<td>" + data.order.o_address + "</td>" +
                                    "<td>" + data.order.o_zipCode + "</td>" +
                                    "<td>" + data.order.o_city + "</td>" +
                                    "<td>" + data.order.o_client + "</td>" +
                                    "<td>" + data.order.o_comments + "</td>" +
                                    "</tr>");
                                tolkBody.append(
                                    "<tr><td colspan='5'><div class='ui center aligned header'>"+
                                    "Det finns ingen tolk tilldelats för denna ordning ännu."+
                                    "</div></td></tr>");
                                if (data.order.o_tolkarPersonalNumber != null) {
                                    tolkBody.find('tr').remove();
                                    tolkBody.append(
                                        "<tr>" +
                                        "<td>" + data.tolk.u_firstName + " " + data.tolk.u_lastName + "</td>" +
                                        "<td>" + data.tolk.t_tolkNumber + "</td>" +
                                        "<td>" + data.tolk.u_tel + "</td>" +
                                        "<td>" + data.tolk.u_mobile + "</td>" +
                                        "<td>" + data.tolk.u_city + "</td>" +
                                        "</tr>");
                                }
                                extraInfoCont.modal('show');
                                $('#' + id).find('.button').removeClass('loading');
                                return false;
                            } else {
                                $('#' + id).find('.button').removeClass('loading');
                                return false;
                            }
                        });
                    });

                } else {
                    tBody.append("<tr><td><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
                }

                $('.ui.dimmable .dimmer').dimmer('toggle');
                return false;
            }
            return false;
        });
    });

    $('.refresh_order').on("click", function () {
        $.ajax({
            type: "POST",
            url: "src/misc/orderHistory.php",
            data: $(".update_order").serialize(),
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .dimmer').dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                $.ajax({
                    type: "GET",
                    url: "src/misc/getNumOfOrders.php",
                    data: $(".update_order").serialize(),
                    cache: true,
                    dataType: "json"
                }).done(function (data) {
                    if (data.error == 0) {
                        var num = data.numOfOrders;
                        if (num > 10) {
                            var paginationContainer = $(".page-manage");
                            paginationContainer.find("a").remove();
                            paginationContainer.append($('<a class="icon item"><i class="left arrow icon"></i></a>'));
                            paginationContainer.append($('<a class="active item" id="mpage1">1</a>'));
                            var rem = num % 10;
                            if (rem === 0) {
                                var numPage = num / 10;
                                for(var k = 2; k <= numPage; k++) {
                                    paginationContainer.append($('<a class="active item" id="mpage' + k + '">' + k + '</a>'));
                                }
                            } else {
                                var numPageRem = ((num - rem) / 10) + 1;
                                for(var j = 2; j <= numPageRem; j++) {
                                    paginationContainer.append($('<a class="active item" id="mpage' + j + '">' + j + '</a>'));
                                }
                            }
                            paginationContainer.append($('<a class="icon item"><i class="right arrow icon"></i></a>'));
                        }
                    }
                });
                var tBody = $('.orderHistory tbody');
                tBody.find('tr').remove();
                if (data.orders.length > 0) {
                    var orders = data.orders;
                    for (var i = 0; i < orders.length; i++) {
                        var btnColor = 'orange';
                        var infoMsg = 'Info';
                        var state = orders[i].o_state;
                        switch (state) {
                            case 'O':
                                infoMsg = 'Ej färdig';
                                btnColor = 'orange';
                                break;
                            case 'B':
                                infoMsg = 'Färdig';
                                btnColor = 'green';
                                break;
                            case 'EC':
                                infoMsg = 'Avbokad';
                                btnColor = 'red';
                                break;
                        }
                        tBody.append(
                            "<tr>" +
                            "<td>" + orders[i].o_orderNumber + "</td>" +
                            "<td>" + orders[i].o_orderer + "</td>" +
                            "<td>" + orders[i].o_language + "</td>" +
                            "<td class='typeTip' data-content='" + getFullTolkningType(orders[i].o_interpretationType) + "'>" + orders[i].o_interpretationType + "</td>" +
                            "<td>" + orders[i].o_date + "</td>" +
                            "<td>" + convertTime(orders[i].o_startTime) + "</td>" +
                            "<td>" + convertTime(orders[i].o_endTime) + "</td>" +
                            "<td>" +
                            "<form class='ui form' id='" + orders[i].o_orderNumber + "'>" +
                            "<input type='hidden' name='orderId' value='" + orders[i].o_orderNumber + "'>" +
                            "<button type='button' class='ui " + btnColor + " fluid button btn-info'>" + infoMsg + "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                        $(".typeTip").popup();
                    }
                    $('.modal.tolk-info')
                        .modal('setting', 'transition', 'vertical flip');

                    $('.button.btn-info').on("click",function() {
                        var extraInfoCont = $('.modal.tolk-info');
                        var id =$(this).parent("form").attr('id');
                        $(this).addClass('loading');
                        $.ajax({
                            type: "POST",
                            url: "src/misc/orderMoreInfo.php",
                            data: $("#" + id).serialize(),
                            dataType: "json"
                        }).done(function (data) {
                            if (data.error == 0) {
                                extraInfoCont.find('.segment').first().find('.header span').text(data.order.o_orderNumber);
                                var orderBody = $('.orderExtra').find('tbody');
                                var tolkBody = $('.tolkExtra').find('tbody');
                                orderBody.find('tr').remove();
                                tolkBody.find('tr').remove();
                                orderBody.append(
                                    "<tr>" +
                                    "<td>" + data.order.o_address + "</td>" +
                                    "<td>" + data.order.o_zipCode + "</td>" +
                                    "<td>" + data.order.o_city + "</td>" +
                                    "<td>" + data.order.o_client + "</td>" +
                                    "<td>" + data.order.o_comments + "</td>" +
                                    "</tr>");
                                tolkBody.append(
                                    "<tr><td colspan='5'><div class='ui center aligned header'>"+
                                    "Det finns ingen tolk tilldelats för denna ordning ännu."+
                                    "</div></td></tr>");
                                if (data.order.o_tolkarPersonalNumber != null) {
                                    tolkBody.find('tr').remove();
                                    tolkBody.append(
                                        "<tr>" +
                                        "<td>" + data.tolk.u_firstName + " " + data.tolk.u_lastName + "</td>" +
                                        "<td>" + data.tolk.t_tolkNumber + "</td>" +
                                        "<td>" + data.tolk.u_tel + "</td>" +
                                        "<td>" + data.tolk.u_mobile + "</td>" +
                                        "<td>" + data.tolk.u_city + "</td>" +
                                        "</tr>");
                                }
                                extraInfoCont.modal('show');
                                $('#' + id).find('.button').removeClass('loading');
                                return false;
                            } else {
                                $('#' + id).find('.button').removeClass('loading');
                                return false;
                            }
                        });
                    });

                } else {
                    tBody.append("<tr><td><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
                }

                $('.ui.dimmable .dimmer').dimmer('toggle');
                $('.button.refresh_order').prop("disabled", true).addClass("disabled");
                setTimeout(function () {
                    var btnRefresh = $('.button.refresh_order');
                    btnRefresh.prop("disabled", false).removeClass("disabled");
                }, 3000);
                return false;
            }
            else {
                $('#' + id).find('.button').removeClass('loading');
                var errorElem = $(".ui.error.message");
                $(".login-btn").parents(".login").removeClass("loading").addClass("error");
                errorElem.children("p").text("Error Message");
                errorElem.children('.header').text("Error");
            }
            return false;
        });
    });
    changeForm.form({
        oldPassword: {
            identifier: 'oldPassword',
            optional: false,
            rules: [
                {type: 'empty', prompt: 'Detta fält får inte vara tomt.'},
                {type: 'length[6]', prompt: 'Detta fält bör innehålla mer än 6 tecken.'}
            ]
        },
        newPass: {
            identifier: 'newPass',
            optional: false,
            rules: [
                {type: 'empty', prompt: 'Detta fält får inte vara tomt.'},
                {type: 'length[6]', prompt: 'Ditt lösenord bör innehålla mer än 6 tecken.'}
            ]
        },
        newPassRep: {
            identifier: 'newPassRep',
            optional: false,
            rules: [
                {type: 'empty', prompt: 'Detta fält får inte vara tomt.'},
                {type: 'match[newPass]', prompt: 'Fälten matchar inte.'}
            ]
        }
    }, {
        inline: true,
        on: 'blur',
        onSuccess: function () {
            changeForm.removeClass("error");
            $.ajax({
                type: "POST",
                url: "src/misc/resetPassword.php",
                data: changeForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    changeForm.addClass("loading");
                }
            }).done(function (data) {
                if (data.error == 0) {
                    changeForm.get(0).reset();
                    changeForm.removeClass("loading");
                    $('.modal.change-pass').modal('hide');
                }
                else {
                    var errorElem = changeForm.find(".ui.error.message");
                    changeForm.removeClass("loading").addClass("error");
                    errorElem.children("p").text(data.errorMessage);
                    errorElem.children('.header').text(data.messageHeader);
                }
            });
        },
        onFailure: function() {
            changeForm.removeClass("error");
        }
    });
    $('.ui.button.reset-btn').click(function() {
        changeForm.form('validate form');
        return false;
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
            url: "src/misc/orderRegular.php",
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
    $(from).transition({ animation: 'vertical flip', duration: '500ms',
        onComplete: function () { $(to).transition({ animation: 'vertical flip', duration: '500ms' }); }
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