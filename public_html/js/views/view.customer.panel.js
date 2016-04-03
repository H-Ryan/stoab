$(document).ready(function () {
    "use strict";
    $.fn.form.settings.rules.oneOf = function(value, fieldIdentifiers) {
        var $form = $(this);

        return !!value || fieldIdentifiers.split(',').some(function(fieldIdentifier) {
                return $form.find('#' + fieldIdentifier).val() ||
                    $form.find('[name="' + fieldIdentifier +'"]').val() ||
                    $form.find('[data-validate="'+ fieldIdentifier +'"]').val();

            });
    };
    $(".typeTip").popup();
    var orderForm = $('.ui.form.orderForm');
    var changeForm = $('.ui.form.changePass');
    $('.ui.dropdown').dropdown();
    $('.ui.search.dropdown').dropdown({fullTextSearch: true});
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
    $('.button.btn-info').on("click", function () {
        var extraInfoCont = $('.modal.tolk-info');
        var id = $(this).parent("form").attr('id');
        $(this).addClass('loading');
        $.ajax({
            type: "POST",
            url: "./src/misc/orderMoreInfo.php",
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
                    "<tr><td colspan='5'><div class='ui center aligned header'>" +
                    "Det finns ingen tolk tilldelats för denna ordning ännu." +
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

    $("#date").datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, minDate: 0});

    $('.ui.fluid.accordion').accordion();

    $('.tolk-type').popup({inline: true, transition: "scale"});
    $('.ui.checkbox').checkbox();
    $('.menu .item').tab();
    $('.ui.dimmer').dimmer({
        transition: 'vertical flip',
        closable: false
    });

    $('.page-customer .item').on("click", function () {
        var pageNum = "1";
        if (!$(this).hasClass('icon')) {
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
            if ($(this).hasClass('previousPage')) {

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
            url: "./src/misc/orderHistory.php",
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
                            case "R":
                                infoMsg = 'Rapporterad';
                                btnColor = 'green';
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

                    $('.button.btn-info').on("click", function () {
                        var extraInfoCont = $('.modal.tolk-info');
                        var id = $(this).parent("form").attr('id');
                        $(this).addClass('loading');
                        $.ajax({
                            type: "POST",
                            url: "./src/misc/orderMoreInfo.php",
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
                                    "<tr><td colspan='5'><div class='ui center aligned header'>" +
                                    "Det finns ingen tolk tilldelats för denna ordning ännu." +
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

    $('.refresh_order').on("click", function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "./src/misc/orderHistory.php",
            data: $(".update_order").serialize(),
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .dimmer').dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                $.ajax({
                    type: "GET",
                    url: "./src/misc/getNumOfOrders.php",
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
                                for (var k = 2; k <= numPage; k++) {
                                    paginationContainer.append($('<a class="active item" id="mpage' + k + '">' + k + '</a>'));
                                }
                            } else {
                                var numPageRem = ((num - rem) / 10) + 1;
                                for (var j = 2; j <= numPageRem; j++) {
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
                            case "R":
                                infoMsg = 'Rapporterad';
                                btnColor = 'green';
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

                    $('.button.btn-info').on("click", function () {
                        var extraInfoCont = $('.modal.tolk-info');
                        var id = $(this).parent("form").attr('id');
                        $(this).addClass('loading');
                        $.ajax({
                            type: "POST",
                            url: "./src/misc/orderMoreInfo.php",
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
                                    "<tr><td colspan='5'><div class='ui center aligned header'>" +
                                    "Det finns ingen tolk tilldelats för denna ordning ännu." +
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
        fields: {
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
        },
        inline: true,
        on: 'blur',
        onSuccess: function () {
            changeForm.removeClass("error");
            $.ajax({
                type: "POST",
                url: "./src/misc/resetPassword.php",
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
        onFailure: function () {
            changeForm.removeClass("error");
        }
    });

    orderForm.form({
        inline: true,
        delay: true,
        on: 'blur',
        transition: "scale",
        fields: {
            client: {
                identifier: 'client',
                optional: true,
                rules: [
                    {
                        type: 'minLength[3]',
                        prompt: 'Den kund området bör<br />innehålla mindre än {ruleValue} tecken.'
                    },
                    {
                        type: 'maxLength[90]',
                        prompt: 'Den kund området bör<br />innehålla mer än {ruleValue} tecken.'
                    }
                ]
            },
            language: {
                identifier: 'language',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Välj ett av de språk från rullgardinsmenyn.'
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
            organization: {
                identifier: 'organization',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fält organisation krävs.'
                    },
                    {
                        type: 'minLength[3]',
                        prompt: 'Fält organisation bör<br />innehålla mer än {ruleValue} tecken.'
                    },
                    {
                        type: 'maxLength[90]',
                        prompt: 'Fält organisation bör<br />innehålla mindre än {ruleValue} tecken.'
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
            },
            post_code: {
                identifier: 'post_code',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Fält post nummer krävs.'
                    },
                    {
                        type: 'integer',
                        prompt: 'Fält Postnummer ska<br />endast innehålla siffror.'
                    },
                    {
                        type: 'minLength[5]',
                        prompt: 'Fält Postnummer bör<br />innehålla mer än {ruleValue} tecken.'
                    },
                    {
                        type: 'maxLength[5]',
                        prompt: 'Fält postnummer bör<br />innehålla mindre än {ruleValue} tecken.'
                    }
                ]
            },
            city: {
                identifier: 'city',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Välj en av städerna från rullgardinslistan.'
                    }
                ]
            },
            message: {
                identifier: 'message',
                optional: true,
                rules: [
                    {
                        type: 'minLength[3]',
                        prompt: 'Fält Kommentar bör<br />innehålla mer än {ruleValue} tecken.'
                    },
                    {
                        type: 'maxLength[90]',
                        prompt: 'Fält Kommentar bör<br />innehålla mindre än {ruleValue} tecken.'
                    }
                ]
            }
        }
    });

    $(".button.next-btn").click(function (e) {
        e.preventDefault();
        adjustTime(startHour, startMinute, endHour, endMinute);
        if (orderForm.form('is valid')) {
            switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset:visible').next());
        }
    });

    $(".button.back-btn").click(function (e) {
        e.preventDefault();
        switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset:visible').prev());
    });


    $('.button.reset-btn').on('click', function (e) {
        e.preventDefault();
        orderForm.form('reset');
        $("#date").datepicker();
        $('#customer').find(':input').prop('disabled', true);
        $('#comment').find(':input').prop('disabled', true);
        console.log(!orderForm.find('fieldset').first().is(":visible"));
        if (!orderForm.find('fieldset').first().is(":visible")) {
            switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset').first());
        }
    });
    $('.button.order-btn').click(function (e) {
        e.preventDefault();
        if (orderForm.form('is valid')) {
            $.ajax({
                type: "POST",
                url: "./src/misc/orderRegular.php",
                data: orderForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    orderForm.addClass("loading");
                }
            }).done(function (data) {
                var errorElem = $("#orderErrorField");
                if (typeof data === 'object') {
                    if (data.error == 0) {
                        orderForm.removeClass("loading");
                        orderForm.form('reset');
                        $("#date").datepicker();
                        $('#customer').find(':input').prop('disabled', true);
                        $('#comment').find(':input').prop('disabled', true);
                        switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset').first());
                        return;
                    }
                    orderForm.removeClass("loading").addClass("error");
                    errorElem.children("p").text(data.errorMessage);
                    errorElem.children('.header').text(data.header);
                    return;
                }
                orderForm.removeClass("loading").addClass("error");
                errorElem.children("p").text('There is a problem in the script');
                errorElem.children('.header').text('PHP error');
            });
        }
    });
    $('#customer').find(':input').prop('disabled', true);
    $('#comment').find(':input').prop('disabled', true);
    $('.ui.radio.checkbox').children('input:radio[name=type]').change(function(){
        adjustTime(startHour, startMinute, endHour, endMinute);
    });
});

function switchFromTo(from, to) {
    $(from).transition({
        animation: 'vertical flip', duration: '400ms',
        onComplete: function () {
            $(to).find(':input').prop('disabled', false);
            $(to).transition({animation: 'vertical flip', duration: '500ms'});
        }
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
    var selectedType = $('.ui.radio.checkbox.checked').children('input:radio[name=type]').val();
    if (startH.val() === endH.val()) {
        if (selectedType) {
            if (selectedType === "TT") {
                endH.find('option').filter(function (index) {
                    return index < startH.val();
                }).each(function () {
                    $(this).prop('disabled', true);
                    endH.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
                });

                endM.find('option').filter(function (index) {
                    return parseInt(index, 10) <= parseInt(startM.val(), 10) + 1;
                }).each(function () {
                    $(this).prop('disabled', true);
                    endM.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
                });
                return;
            } else if (selectedType === "KT") {
                endH.find('option').filter(function (index) {
                    return index <= startH.val();
                }).each(function () {
                    $(this).prop('disabled', true);
                    endH.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
                });

                endM.find('option').filter(function (index) {
                    return parseInt(index) <= parseInt(startM.val(), 10) + 3;
                }).each(function () {
                    $(this).prop('disabled', true);
                    endM.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
                });
                return;
            }
        }
        endM.find('option').filter(function (index) {
            return index <= startM.val();
        }).each(function () {
            $(this).prop('disabled', true);
            endM.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
        });
    } else {
        if (selectedType) {
            if (selectedType === "TT") {
                endH.find('option').filter(function (index) {
                    return index < startH.val();
                }).each(function () {
                    $(this).prop('disabled', true);
                    endH.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
                });

                endM.find('option').filter(function (index) {
                    return ((parseInt(endH.val()) * 4) + parseInt(index)) <= ((parseInt(startH.val()) * 4) + parseInt(startM.val()) + 1);
                }).each(function () {
                    $(this).prop('disabled', true);
                    endM.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
                });
                return;
            } else if (selectedType === "KT") {
                endH.find('option').filter(function (index) {
                    return index <= startH.val();
                }).each(function () {
                    $(this).prop('disabled', true);
                    endH.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
                });

                endM.find('option').filter(function (index) {
                    return ((parseInt(endH.val()) * 4) + parseInt(index)) <= ((parseInt(startH.val()) * 4) + parseInt(startM.val()) + 3);
                }).each(function () {
                    $(this).prop('disabled', true);
                    endM.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
                });
                return;
            }
        }
        endM.find('option').filter(function (index) {
            return index <= startM.val();
        }).each(function () {
            $(this).prop('disabled', true);
            endM.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
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