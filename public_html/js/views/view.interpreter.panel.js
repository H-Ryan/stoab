"use strict";
$(document).ready(function () {

    $('.modal.tolk-info')
        .modal('setting', 'transition', 'scale');
    $('.ui.checkbox').checkbox();
    $('.menu .item').tab();
    $('.ui.dimmer').dimmer({
        transition: 'vertical flip',
        closable: false
    });
    $(".typeTip").popup();
    $('.dropdown').dropdown({transition: 'drop'});

    var changeForm = $('.ui.form.changePass'),
        reportOutlay = $('#rep_outlay').parent(),
        reportHours = $('#rep_hours').parent(),
        reportMinutes = $('#rep_minutes').parent(),
        reportMileage = $('#rep_mileage'),
        reportTicketCost = $('#rep_ticket_cost'),
        reportOrderForm = $('#reportOrderForm'),
        reportButton = $('#rep_submit_btn'),
        reportingModal = $('.modal.reporting'),
        reportInfoBtn = $('#btn-report-info');

    reportHours.addClass('disabled');
    reportMinutes.addClass('disabled');
    reportMileage.prop('disabled', true);
    reportTicketCost.prop('disabled', true);
    reportButton.on('click', function () {
        reportOrderForm.form("validate form");
        if (reportOrderForm.form('is valid')) {
            $.ajax({
                type: "POST",
                url: "src/misc/interpreter/reportOrder.php",
                data: reportOrderForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    reportButton.addClass('loading');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    reportOrderForm.removeClass('error');
                    reportOrderForm.form('reset');
                    reportOrderForm.get(0).reset();
                    $('.refresh_report_order').trigger('click');
                    $('.refresh_history_order').trigger('click');
                    reportingModal.modal('hide');
                } else {
                    reportOrderForm.addClass('error');
                }
                reportButton.removeClass('loading');
            });
        }

    });
    reportOrderForm.form({
        rep_extra_time: {
            identifier: "rep_extra_time",
            rules: [
                {
                    type: 'empty',
                    prompt: 'Please select extra time'
                }
            ]
        },
        rep_customer_name: {
            identifier: "rep_customer_name",
            rules: [
                {
                    type: 'length[5]',
                    prompt: 'Please enter at least 3 characters'
                },
                {
                    type: 'empty',
                    prompt: 'Please enter the customer\'s name'
                }
            ]
        },
        rep_comments: {
            identifier: "rep_comments",
            optional: true,
            rules: [
                {
                    type: 'length[5]',
                    prompt: 'Please enter at least 5 characters'
                },
                {
                    type: 'maxLength[255]',
                    prompt: 'Please enter at most 255 characters'
                }
            ]
        },
        rep_mileage: {
            identifier: "rep_mileage",
            rules: [
                {
                    type: 'empty',
                    prompt: 'Please enter the mileage'
                },
                {
                    type: 'integer',
                    prompt: 'Please enter a valid number'
                }
            ]
        },
        rep_hours: {
            identifier: "rep_hours",
            rules: [
                {
                    type: 'empty',
                    prompt: 'Please select the hours'
                }
            ]
        },
        rep_minutes: {
            identifier: "rep_minutes",
            rules: [
                {
                    type: 'empty',
                    prompt: 'Please select the minutes'
                }
            ]
        },
        rep_ticket_cost: {
            identifier: "rep_ticket_cost",
            rules: [
                {
                    type: 'empty',
                    prompt: 'Please enter the ticket cost'
                }
            ]
        }
    }, {
        inline: true,
        on: 'blur',
        transition: "slide down"
    });
    reportOutlay.dropdown({
        transition: 'drop',
        onChange: function (value, text, $selectedItem) {
            switch (value) {
                case 0:
                    reportHours.addClass('disabled');
                    reportMinutes.addClass('disabled');
                    reportMileage.prop('disabled', true);
                    reportTicketCost.prop('disabled', true);
                    break;
                case 1:
                    reportHours.removeClass('disabled');
                    reportMinutes.removeClass('disabled');
                    reportMileage.prop('disabled', false);
                    reportTicketCost.prop('disabled', true);
                    break;
                case 2:
                    reportHours.removeClass('disabled');
                    reportMinutes.removeClass('disabled');
                    reportMileage.prop('disabled', true);
                    reportTicketCost.prop('disabled', false);
                    break;
                default:
                    reportHours.addClass('disabled');
                    reportMinutes.addClass('disabled');
                    reportMileage.prop('disabled', true);
                    reportTicketCost.prop('disabled', true);
                    break;
            }
        }
    })
    ;

    $('.tolk-logout-btn').click(function () {
        $.ajax({type: "POST", url: "src/misc/interpreter/tolk-logout.php"}).done(function () {
            window.location.replace("index.php");
            return false;
        });
        return false;
    });

    var pDimmer = $('.ui.dimmable .dimmer');

    var orderHistoryTableBody = $('.orderHistory tbody');

    $('.reported-orders').on("click", ".item", function () {
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
            var currPage = $('.reported-orders').find(".active.item");
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
        $("#updateHistoryPage").val(pageNum);
        $.ajax({
            type: "POST",
            url: "src/misc/interpreter/tolkOrderHistory.php",
            data: $(".update_order_history").serialize(),
            dataType: "json",
            beforeSend: function () {
                pDimmer.dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                orderHistoryTableBody.find('tr').remove();
                if (data.orders.length > 0) {
                    updateOrderHistoryTableBody(orderHistoryTableBody, data.orders);
                }
            }
            pDimmer.dimmer('toggle');
            return false;
        });
    });

    $('.refresh_history_order').on("click", function () {
        $.ajax({
            type: "POST",
            url: "src/misc/interpreter/tolkOrderHistory.php",
            data: $(".update_order_history").serialize(),
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .dimmer').dimmer('toggle');
            }
        }).done(function (data) {

            if (data.error == 0) {
                updatePagination(data.num, $(".reported-orders"));
                updateOrderHistoryTableBody(orderHistoryTableBody, data.orders);

                pDimmer.dimmer('toggle');
                $('.button.refresh_history_order').prop("disabled", true).addClass("disabled");
                setTimeout(function () {
                    var btnRefresh = $('.button.refresh_history_order');
                    btnRefresh.prop("disabled", false).removeClass("disabled");
                }, 3000);
                return false;
            }
            pDimmer.dimmer('toggle');
            return false;
        });
    });

    var currentOrdersTableBody = $('.currentOrders tbody');

    $('.current-orders').on("click", ".item", function () {
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
            var currPage = $('.current-orders').find(".active.item");
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
            url: "src/misc/interpreter/tolkCurrentOrders.php",
            data: $(".update_order_curr").serialize(),
            dataType: "json",
            beforeSend: function () {
                pDimmer.dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                updatePagination(data.num, $(".current-orders"));
                updateCurrentOrderTableBody(currentOrdersTableBody, data.orders);

                pDimmer.dimmer('toggle');
                return false;
            }
            pDimmer.dimmer('toggle');
            return false;
        });
    });

    $('.refresh_curr_order').on("click", function () {
        $.ajax({
            type: "POST",
            url: "src/misc/interpreter/tolkCurrentOrders.php",
            data: $(".update_order_curr").serialize(),
            dataType: "json",
            beforeSend: function () {
                pDimmer.dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                updatePagination(data.num, $(".current-orders"));
                updateCurrentOrderTableBody(currentOrdersTableBody, data.orders);

                pDimmer.dimmer('toggle');
                $('.button.refresh_curr_order').prop("disabled", true).addClass("disabled");
                setTimeout(function () {
                    var btnRefresh = $('.button.refresh_curr_order');
                    btnRefresh.prop("disabled", false).removeClass("disabled");
                }, 3000);
                return false;
            }
            pDimmer.dimmer('toggle');
            return false;
        });
    });

    var reportingOrdersTableBody = $('.reportingOrders tbody');
    $('.reporting-orders').on("click", ".item", function () {
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
            var currPage = $('.reporting-orders').find(".active.item");
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
        $("#updateReportPage").val(pageNum);
        $.ajax({
            type: "POST",
            url: "src/misc/interpreter/tolkOrdersToReport.php",
            data: $(".update_order_curr").serialize(),
            dataType: "json",
            beforeSend: function () {
                pDimmer.dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                updatePagination(data.num, $(".reporting-orders"));
                updateOrdersTpReportTableBody(currentOrdersTableBody, data.orders);

                pDimmer.dimmer('toggle');
                return false;
            }
            pDimmer.dimmer('toggle');
            return false;
        });
    });

    $('.refresh_report_order').on("click", function () {
        $.ajax({
            type: "POST",
            url: "src/misc/interpreter/tolkOrdersToReport.php",
            data: $(".update_report_orders").serialize(),
            dataType: "json",
            beforeSend: function () {
                pDimmer.dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                updatePagination(data.num, $(".reporting-orders"));
                updateOrdersTpReportTableBody(reportingOrdersTableBody, data.orders);

                pDimmer.dimmer('toggle');
                $('.button.refresh_report_order').prop("disabled", true).addClass("disabled");
                setTimeout(function () {
                    var btnRefresh = $('.button.refresh_report_order');
                    btnRefresh.prop("disabled", false).removeClass("disabled");
                }, 3000);
                return false;
            }
            pDimmer.dimmer('toggle');
            return false;
        });
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
                url: "src/misc/tolkResetPassword.php",
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

    $('.ui.button.reset-btn').click(function () {
        changeForm[0].reset();
        return false;
    });

    jQuery.extend(jQuery.validator.messages, {
        require_from_group: "Fyll i minst ett av dessa områden."
    });

    orderHistoryTableBody.on("click", ".button.btn-report-info",function () {
        var reportingInfoModal = $('.modal.order-reporting-info');
        var id = $(this).parent("form").attr('id');
        $(this).addClass('loading');
        $.ajax({
            type: "POST",
            url: "src/misc/interpreter/getReportInfo.php",
            data: $("#" + id).serialize(),
            dataType: "json"
        }).done(function (data) {
            if (data.error == 0) {
                reportingInfoModal.find('.segment').first().find('.header span').text(data.order.o_orderNumber);

                $('#ordererInfoValue').text(data.order.o_orderer);
                $('#clientInfoValue').text(data.order.o_client);
                $('#languageInfoValue').text(data.order.o_language);
                $('#typeInfoValue').text(getFullTolkningType(data.order.o_interpretationType));
                $('#addressInfoValue').text(data.order.o_address);
                $('#cityZipInfoValue').text(data.order.o_city + ", "  + data.order.o_zipCode);
                $('#dateInfoValue').text(data.order.o_date);
                $('#startEndTimeInfo').text(convertTime(data.order.o_startTime) + " - " + convertTime(data.order.o_endTime));

                $('#extraTimeValue').text(getExtraTime(data.report.t_extraTime));
                $('#carDistanceValue').text(data.report.r_carDistance);
                $('#ticketCostValue').text(data.report.r_ticketCost);
                $('#travelTimeValue').text(getFullTolkningType(data.order.o_interpretationType));
                $('#customerNameValue').text(data.report.r_customerName);
                $('#commentsValue').text(data.report.r_comments);
                $('#dateCreatedValue').text(data.report.r_reportTime);
                $('#dateUpdatedValue').text(data.report.r_reportUpdateTime);

                reportingInfoModal.modal('show');
                $('#' + id).find('.button').removeClass('loading');
                return false;
            } else {
                $('#' + id).find('.button').removeClass('loading');
                return false;
            }
        });
    });

    currentOrdersTableBody.on("click", ".button.btn-info",function () {
        var extraInfoCont = $('.modal.order-info');
        var id = $(this).parent("form").attr('id');
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
                extraInfoCont.modal('show');
                $('#' + id).find('.button').removeClass('loading');
                return false;
            } else {
                $('#' + id).find('.button').removeClass('loading');
                return false;
            }
        });
    });

    reportingOrdersTableBody.on("click", "#reportBtn",function (e) {
        e.preventDefault();
        var id = $(this).parent("form").attr('id');
        $(this).addClass('loading');
        $.ajax({
            type: "POST",
            url: "src/misc/orderInfo.php",
            data: $("#" + id).serialize(),
            dataType: "json"
        }).done(function (data) {
            if (data.error == 0) {
                reportingModal.find('.segment').first().find('.header span').text(data.order.o_orderNumber);
                $('#rep_mission_id').val(data.order.o_orderNumber);

                $('#ordererValue').text(data.order.o_orderer);
                $('#clientValue').text(data.order.o_client);
                $('#languageValue').text(data.order.o_language);
                $('#typeValue').text(getFullTolkningType(data.order.o_interpretationType));
                $('#addressValue').text(data.order.o_address);
                $('#cityZipValue').text(data.order.o_city + ", "  + data.order.o_zipCode);
                $('#dateValue').text(data.order.o_date);
                $('#startEndTime').text(convertTime(data.order.o_startTime) + " - " + convertTime(data.order.o_endTime));

                if(data.order.o_interpretationType == "TT") {
                    reportOutlay.addClass('disabled');
                    reportHours.addClass('disabled');
                    reportMinutes.addClass('disabled');
                    reportMileage.addClass('disabled');
                    reportTicketCost.addClass('disabled');
                    //$('#rep_customer_name').addClass('disabled');
                }
                reportingModal.modal('show');
                $('#' + id).find('.button').removeClass('loading');
                return false;
            }
            $('#' + id).find('.button').removeClass('loading');
            return false;
        });
    });
});

function getTravelTime(value) {

}

function getExtraTime(value) {
    switch (value) {
        case 0:
            return "00:00";
            break;
        case 1:
            return "00:15";
            break;
        case 2:
            return "00:30";
            break;
        case 3:
            return "00:45";
            break;
        case 4:
            return "01:00";
            break;
        case 5:
            return "01:15";
            break;
        case 6:
            return "01:30";
            break;
        case 7:
            return "01:45";
            break;
        case 8:
            return "02:00";
            break;
        default:
            return "00:00";
            break;
    }
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
    if (startH.val() === endH.val()) {
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

function updatePagination(num, container) {
    if (num > 10) {
        container.find("a").remove();
        container.append($('<a class="icon item"><i class="left arrow icon"></i></a>'));
        container.append($('<a class="active item" id="mpage1">1</a>'));
        var rem = num % 10;
        if (rem === 0) {
            var numPage = num / 10;
            for (var k = 2; k <= numPage; k++) {
                container.append($('<a class="active item" id="mpage' + k + '">' + k + '</a>'));
            }
        } else {
            var numPageRem = ((num - rem) / 10) + 1;
            for (var j = 2; j <= numPageRem; j++) {
                container.append($('<a class="active item" id="mpage' + j + '">' + j + '</a>'));
            }
        }
        container.append($('<a class="icon item"><i class="right arrow icon"></i></a>'));
    }
}

function updateOrdersTpReportTableBody(tBody, data) {
    tBody.find('tr').remove();
    if (data.length > 0) {
        var orders = data;
        for (var i = 0; i < orders.length; i++) {
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
                "<button type='button' id='reportBtn' class='ui fluid blue button'>Rapportera</button>" +
                "</form>" +
                "</td>" +
                "</tr>");
            $(".typeTip").popup();
        }

    } else {
        tBody.append("<tr><td colspan='8'><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
    }
}

function updateCurrentOrderTableBody(tBody, data) {
    tBody.find('tr').remove();
    if (data.length > 0) {
        var orders = data;
        for (var i = 0; i < orders.length; i++) {
            var btnColor = 'orange';
            var infoMsg = 'Info';
            var state = orders[i].o_state;
            switch (state) {
                case 'B':
                    infoMsg = 'Pågående';
                    btnColor = 'orange';
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

    } else {
        tBody.append("<tr><td colspan='8'><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
    }
}

function updateOrderHistoryTableBody(tBody, data) {
    tBody.find('tr').remove();
    if (data.length > 0) {
        var orders = data;
        for (var i = 0; i < orders.length; i++) {
            var btnColor = 'orange';
            var infoMsg = 'Info';
            var state = orders[i].o_state;
            switch (state) {
                case 'R':
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
                "<button type='button' class='ui " + btnColor + " fluid button btn-report-info'>" + infoMsg + "</button>" +
                "</form>" +
                "</td>" +
                "</tr>");
            $(".typeTip").popup();
        }

    } else {
        tBody.append("<tr><td colspan='8'><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
    }
}