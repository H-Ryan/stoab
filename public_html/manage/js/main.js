"use strict";
$(document).ready(function () {
    $.fn.form.settings.rules.oneOf = function(value, fieldIdentifiers) {
        var $form = $(this);

        return !!value || fieldIdentifiers.split(',').some(function(fieldIdentifier) {
                return $form.find('#' + fieldIdentifier).val() ||
                    $form.find('[name="' + fieldIdentifier +'"]').val() ||
                    $form.find('[data-validate="'+ fieldIdentifier +'"]').val();

            });
    };

    var orderForm = $('.ui.form.orderForm'),
        companySearchForm = $('.ui.form.company-search'),
        companySearchBtn = $('.btnSearchCompany'),
        tolkSearchFrom = $('.ui.form.tolk-search'),
        orderHistoryFilterForm = $('.ui.form.orderFilterForm'),
        orderManageFilterForm = $('.ui.form.orderFilterFormManage'),
        modalResend = $('.ui.basic.modal.modalResend'),
        emailSendForm = $('#formSendToFinance'),
        emailResendResult = $(".modal.emailResendResult"),
        modalOrderHistory = $(".modal.order-history"),
        modalUpdatesInfo = $(".modal.modalUpdatesInfo"),
        manageWindow = null,
        startHour = $("#starttid"),
        endHour = $("#sluttid"),
        startMinute = $("#starttid1"),
        endMinute = $("#sluttid1");

    $('.ui.fluid.accordion').accordion();

    $(".table.orderManage").on("click", ".button.btn_manage_order", function (event) {
        var btn = $(this);
        var btnId = btn.attr('id');
        $.ajax({
            type: "POST",
            url: "../src/misc/orderInfo.php",
            data: {orderId: btnId},
            dataType: "json",
            beforeSend: function () {
                btn.addClass('loading');
            }
        }).done(function (data) {
            if (manageWindow) {
                if (!manageWindow.closed)
                    manageWindow.location.reload();
                else {
                    //manageWindow = window.open("http://c4tolk.com/manage/manage.php", "_blank");
                    manageWindow = window.open("http://localhost/ws/stoab/public_html/manage/manage.php", "_blank");
                }
            }
            else {
                //manageWindow = window.open("http://c4tolk.com/manage/manage.php", "_blank");
                manageWindow = window.open("http://localhost/ws/stoab/public_html/manage/manage.php", "_blank");
            }

            btn.removeClass('loading');
        });
        btn.removeClass('loading');
    });
    //modalUpdatesInfo
    $('#updatesInfo').click(function () {
        modalUpdatesInfo.modal({closable: false}).modal('show');
    });
    modalOrderHistory.on("click", "#resendToTolk", function () {
        modalResend.modal({
            closable: false,
            onDeny: function () {
                return true;
            },
            onApprove: function () {
                $.ajax({
                    type: "POST",
                    url: "../src/misc/resendTolkConfirm.php",
                    data: emailSendForm.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        emailSendForm.addClass('loading');
                    }
                }).done(function (data) {
                    if (data.error == 0) {
                        emailResendResult.find(".content .description .header").text("The email has been successfuly sent to the interpreter!");
                    } else {
                        emailResendResult.find(".content .description .header").text(data.errorMessage);
                    }
                    emailResendResult.modal('show');
                    emailSendForm.removeClass('loading');
                });
            }
        });
        modalResend.modal('show');
    });
    modalOrderHistory.on("click", "#resendToClient", function () {
        modalResend.modal({
            closable: false,
            onDeny: function () {
                return true;
            },
            onApprove: function () {
                $.ajax({
                    type: "POST",
                    url: "../src/misc/resendClientAboutTolkAssign.php",
                    data: emailSendForm.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        $("#formSendToFinance").addClass('loading');
                    }
                }).done(function (data) {
                    if (data.error == 0) {
                        emailResendResult.find(".content>.description>.header").text("The email has been successfuly sent to the client!");
                    } else {
                        emailResendResult.find(".content>.description>.header").text(data.errorMessage);
                    }
                    emailResendResult.modal('show');
                    emailSendForm.removeClass('loading');
                });
            }
        });
        modalResend.modal('show');
    });

    $('.left.sidebar').first().sidebar('attach events', '.toggle.button').sidebar('setting', 'transition', 'slide along');

    $("#btnSendToFinance").click(function () {
        $.ajax({
            type: "POST",
            url: "../src/misc/sendToFinance.php",
            data: $("#formSendToFinance").serialize(),
            dataType: "json",
            beforeSend: function () {
                $("#btnSendToFinance").addClass('loading');
            }
        }).done(function (data) {
            var modal = $(".modal.order-history");
            var successElem = modal.find(".content .segment>.ui.positive.message");
            var errorElem = modal.find(".content .segment>.ui.error.message");
            if (data.error == 0) {
                errorElem.hide();
                successElem.children("p").text(data.positiveMessage);
                successElem.children('.header').text(data.messageHeader);
                successElem.show();
            } else {
                successElem.hide();
                errorElem.children("p").text(data.errorMessage);
                errorElem.children('.header').text(data.messageHeader);
                errorElem.show();
            }
            $("#btnSendToFinance").removeClass('loading');
        });
        return false;
    });

    orderManageFilterForm.form({
        inline: true,
        on: 'blur',
        transition: "slide down",
        fields: {
            orderNumber: {
                identifier: 'orderNumber',
                optional: true,
                rules: [
                    {
                        type: 'length[6]',
                        prompt: 'Ordernummer måste vara minst 6 tecken.'
                    },
                    {
                        type: 'maxLength[6]',
                        prompt: 'Ordernummer måste vara minst 6 tecken.'
                    }
                ]
            },
            clientNumber: {
                identifier: 'clientNumber',
                optional: true,
                rules: [
                    {
                        type: 'length[5]',
                        prompt: 'Kund nummer måste vara minst 5 tecken.'
                    },
                    {
                        type: 'maxLength[5]',
                        prompt: 'Kund nummer måste vara minst 5 tecken.'
                    },
                    {
                        type: 'integer',
                        prompt: 'Kund nummer innehåller ogiltiga tecken.'
                    }
                ]
            }
        }
    });


    orderHistoryFilterForm.form({
        inline: true,
        on: 'blur',
        transition: "slide down",
        fields: {
            orderNumber: {
                identifier: 'orderNumber',
                optional: true,
                rules: [
                    {
                        type: 'length[6]',
                        prompt: 'Ordernummer måste vara minst 6 tecken.'
                    },
                    {
                        type: 'maxLength[6]',
                        prompt: 'Ordernummer måste vara minst 6 tecken.'
                    }
                ]
            },
            tolkNumber: {
                identifier: 'tolkNumber',
                optional: true,
                rules: [
                    {
                        type: 'length[4]',
                        prompt: 'Tolk nummer måste vara minst 4 tecken.'
                    },
                    {
                        type: 'maxLength[4]',
                        prompt: 'Tolk nummer måste vara minst 4 tecken.'
                    },
                    {
                        type: 'integer',
                        prompt: 'Tolk nummer innehåller ogiltiga tecken.'
                    }
                ]
            },
            clientNumber: {
                identifier: 'clientNumber',
                optional: true,
                rules: [
                    {
                        type: 'length[5]',
                        prompt: 'Kund nummer måste vara minst 5 tecken.'
                    },
                    {
                        type: 'maxLength[5]',
                        prompt: 'Kund nummer måste vara minst 5 tecken.'
                    },
                    {
                        type: 'integer',
                        prompt: 'Kund nummer innehåller ogiltiga tecken.'
                    }
                ]
            }
        }
    });

    jQuery.validator.setDefaults({
        debug: true,
        success: "valid"
    });

    $("#btnRemoveFilterHistory").on("click", function () {
        $(".btn-update-history").trigger("click");
    });

    $('.button.btnFilterHistory').on("click", function () {
        if (orderHistoryFilterForm.filter(":visible").form("validate form")) {
            $.ajax({
                type: "POST",
                url: "../src/misc/filterOrderHistory.php",
                data: orderHistoryFilterForm.serialize(),
                cache: false,
                dataType: "json",
                beforeSend: function () {
                    $('.ui.dimmable .dimmer').dimmer('toggle');
                }
            }).done(function (data) {
                orderHistoryFilterForm.form('reset');
                orderHistoryFilterForm.get(0).reset();
                var tBody = $('.orderHistory tbody:visible');
                tBody.find('tr').remove();

                if (data.error == 0) {
                    if (data.orders.length > 0) {
                        var paginationContainer = $(".page-history");
                        paginationContainer.find("a").remove();

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
                                case "IC":
                                    infoMsg = 'Fortfarande pågår';
                                    btnColor = 'orange';
                                    break;
                                case "R":
                                    infoMsg = 'Rapporterad';
                                    btnColor = 'green';
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
                                "<form class='ui form' id='" + orders[i].o_orderNumber + "'>" +
                                "<input type='hidden' name='orderId' value='" + orders[i].o_orderNumber + "'>" +
                                "<button type='button' class='ui " + btnColor + " fluid button btn-info'>" + infoMsg + "</button>" +
                                "</form>" +
                                "</td>" +
                                "</tr>");
                            $(".typeTip").popup();
                        }
                        var extraInfoCont = $('.modal.order-history');
                        extraInfoCont
                            .modal({
                                closable: false, onDeny: function () {
                                    return false;
                                },
                                onApprove: function () {
                                    var modal = $(".modal.order-history");
                                    modal.find(".content .segment>.ui.positive.message").hide();
                                    modal.find(".content .segment>.ui.error.message").hide();
                                }
                            })
                            .modal('setting', 'transition', 'vertical flip');

                        $('.button.btn-info').on("click", function () {

                            var id = $(this).parent("form").attr('id');
                            var btnInfo = $(this);
                            btnInfo.addClass('loading');
                            $.ajax({
                                type: "POST",
                                url: "../src/misc/orderMoreInfo.php",
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
                                    if (data.order.o_tolkarPersonalNumber) {
                                        tolkBody.find('tr').remove();
                                        tolkBody.append(
                                            "<tr>" +
                                            "<td>" + data.tolk.u_firstName + " " + data.tolk.u_lastName + "</td>" +
                                            "<td>" + data.tolk.t_tolkNumber + "</td>" +
                                            "<td>" + data.tolk.u_tel + "</td>" +
                                            "<td>" + data.tolk.u_mobile + "</td>" +
                                            "<td>" + data.tolk.u_city + "</td>" +
                                            "</tr>");
                                        var sendToFinance = $("#formSendToFinance");
                                        sendToFinance.find("#orderNumber").val(data.order.o_orderNumber);
                                        sendToFinance.find("#tolkNumber").val(data.order.o_tolkarPersonalNumber);
                                        $("#btnSendToFinance").removeClass("disabled");
                                    } else {
                                        $("#btnSendToFinance").addClass("disabled");
                                    }
                                    if (data.order.o_state !== 'B') {
                                        $("#resendToTolk").addClass("disabled");
                                        $("#resendToClient").addClass("disabled");
                                    } else {
                                        $("#resendToTolk").removeClass("disabled");
                                        $("#resendToClient").removeClass("disabled");
                                    }
                                    extraInfoCont.modal('show');
                                }
                                btnInfo.removeClass('loading');
                            });
                        });
                    } else {
                        tBody.append("<tr><td colspan='9'><div class='ui text'>Inga order matchar din sökning parametrar.</div></td></tr>");
                    }
                } else {
                    tBody.append("<tr><td colspan='9'><div class='ui text'>Inga order matchar din sökning parametrar.</div></td></tr>");
                }
                $('.ui.dimmable .dimmer').dimmer('toggle');
                $("#btnRemoveFilterHistory").removeClass('disabled');
            });
        }
    });
    $("#btnRemoveFilterManage").on("click", function () {
        $(".btn-update-manage").trigger("click");
    });

    $('#btnFilterManage').on("click", function () {
        if (orderManageFilterForm.filter(":visible").form("validate form")) {
            var omData = orderManageFilterForm.filter(":visible").serializeArray()
            omData.push({name: 'sortOption', value: $('#sortOptionManage').val()});
            $.ajax({
                type: "POST",
                url: "../src/misc/filterOrderManage.php",
                data: omData,
                cache: false,
                dataType: "json",
                beforeSend: function () {
                    $('.ui.dimmable .dimmer').dimmer('toggle');
                }
            }).done(function (data) {
                orderManageFilterForm.form('reset');
                orderManageFilterForm.get(0).reset();
                if (data.error == 0) {
                    var tBody = $('.orderManage tbody:visible');
                    tBody.find('tr').remove();
                    $("#btnRemoveFilterManage").removeClass('disabled');
                    if (data.orders.length > 0) {
                        var paginationContainer = $(".page-manage");
                        paginationContainer.find("a").remove();
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
                                case "IC":
                                    infoMsg = 'Fortfarande pågår';
                                    btnColor = 'orange';
                                    break;
                                case "R":
                                    infoMsg = 'Rapporterad';
                                    btnColor = 'green';
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
                                "<button type='button' id='" + orders[i].o_orderNumber + "' class='ui " + btnColor + " fluid button btn_manage_order'>" + infoMsg + "</button>" +
                                "</form>" +
                                "</td>" +
                                "</tr>");
                            $(".typeTip").popup();
                        }
                    } else {
                        tBody.append("<tr><td colspan='9'><div class='ui text'>Inga order matchar din sökning parametrar.</div></td></tr>");
                    }
                    $('.ui.dimmable .dimmer').dimmer('toggle');
                }
            });
        }
    });

    $("#newsPrescript").change(function () {
        var charCount = $(this).val().length;
        var chars = $("#chars");
        if (charCount > 200) {
            chars.css("color", "red");
        } else {
            chars.css("color", "black");
        }
        chars.text(charCount);

    });

    $(".typeTip").popup();
    tinymce.init({
        selector: '#newsLetter, #newsLetterManage',
        plugins: [
            "advlist autolink autoresize lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste emoticons"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | emoticons | link image media"
    });
    var feed = $(".feed");
    feed.on("click", ".btnNewsManageView", function () {
        $('#newsLetterModifyForm').slideUp(500);
        $("#containerUpdateNews").addClass('basic');
        var pressedBtn = $(this);
        $.ajax({
            type: "GET",
            url: "../src/misc/getNewsletterInfo.php",
            dataType: "json",
            beforeSend: function () {
                pressedBtn.addClass('loading');
            },
            data: pressedBtn.closest(".newsManageIDForm").serialize()
        }).done(function (data) {
            if (data.error === 0) {
                $('.modal.preview .header').html(data.header);
                $('.modal.preview #newsContent').html(data.content);

                $('.modal.preview').modal('show');
            }
            pressedBtn.removeClass('loading');
        });
        return false;

    });

    feed.on("click", ".btnNewsManageEdit", function () {
        var pressedBtn = $(this),
            container = $("#containerUpdateNews");
        $.ajax({
            type: "GET",
            url: "../src/misc/getNewsletterInfo.php",
            dataType: "json",
            beforeSend: function () {
                pressedBtn.addClass('loading');
                container.addClass('loading');
            },
            data: pressedBtn.closest(".newsManageIDForm").serialize()
        }).done(function (data) {
            if (data.error === 0) {
                $('#newsIdManage').val(data.id);
                $('#newsTitleManage').val(data.title.replace(/<(?:.|\n)*?>/gm, ''));
                $('#newsPrescriptManage').html(data.prescript.replace(/<(?:.|\n)*?>/gm, ''));
                tinyMCE.get('newsLetterManage').setContent(data.content);

                $('#newsLetterModifyForm').slideDown(500);
            }
            pressedBtn.removeClass('loading');
            container.removeClass('loading').removeClass('basic');
        });
        return false;
    });

    feed.on('click', '.btnNewsManageDelete', function () {
        $('#newsLetterModifyForm').slideUp(500);
        $("#containerUpdateNews").addClass('basic');
        var deleteModal = $('.modal.newsManageDeleteAction');
        var btnAction = deleteModal.find('.btnManageActionYes');
        var id = $(this).closest(".newsManageIDForm").children("[name='newsID']").val();
        btnAction.data('id', id);
        deleteModal.modal('show');
    });

    $("#btnPreviewNewsManage").on("click", function () {
        $('.modal.previewManage .header').text($("#newsTitleManage").val() + " - Publicerat: Idag");
        $('.modal.previewManage #newsContent').html(tinyMCE.get('newsLetterManage').getContent());

        $('.modal.previewManage').modal('show');
    });

    $('#btnUpdateNewsLetterManage').on('click', function () {
        var updateModal = $('.modal.newsManageUpdateAction');
        updateModal.modal('show');
    });

    $('.modal.newsManageUpdateAction .btnManageActionYes').on('click', function () {
        var pressedBtn = $(this);
        $.ajax({
            type: "POST",
            url: "../src/misc/updateNews.php",
            dataType: "json",
            beforeSend: function () {
                pressedBtn.addClass('loading');
            },
            data: {
                newsId: $('#newsIdManage').val(),
                newsTitle: $("#newsTitleManage").val(),
                newsPrescript: $("#newsPrescriptManage").val(),
                newsContent: tinyMCE.get('newsLetterManage').getContent()
            }
        }).done(function (data) {
            if (data.error === 0) {
                $('#newsLetterModifyForm').slideUp(500);
                $("#containerUpdateNews").addClass('basic');
                pressedBtn.removeClass('loading');
                updateNewsletterEntries();
            }
            $('.small.news .content .description').text(data.message);
            $('.small.news').modal('show');
        });
        return false;
    });

    $('.modal.newsManageDeleteAction .btnManageActionYes').on('click', function () {
        var pressedBtn = $(this);
        $.ajax({
            type: "POST",
            url: "../src/misc/deleteNews.php",
            dataType: "json",
            beforeSend: function () {
                pressedBtn.addClass('loading');
            },
            data: {newsID: pressedBtn.data('id')}
        }).done(function (data) {
            $('.small.news.modal .content .description').text(data.message);
            $('.small.news.modal').modal('show');
            pressedBtn.removeClass('loading');
            updateNewsletterEntries();
        });
        return false;
    });

    $("#btnPreviewNews").on("click", function () {
        $('.modal.preview .header').text($("#newsTitle").val() + " - Publicerat: Idag");
        $('.modal.preview #newsContent').html(tinyMCE.get('newsLetter').getContent());

        $('.modal.preview').modal('show');
    });

    $("#btnPostNewsLetter").on("click", function () {
        $.ajax({
            type: "POST",
            url: "../src/misc/addNews.php",
            dataType: "json",
            beforeSend: function () {
                $("#btnPostNewsLetter").addClass('loading');
            },
            data: {
                newsTitle: $("#newsTitle").val(),
                newsPrescript: $("#newsPrescript").val(),
                newsContent: tinyMCE.get('newsLetter').getContent()
            }
        }).done(function (data) {
            if (data.error === 0) {
                $("#newsTitle").val("");
                $("#newsPrescript").val("");
                tinyMCE.get('newsLetter').setContent("");
                updateNewsletterEntries();
            }
            $("#btnPostNewsLetter").removeClass('loading');
            $('.small.news.modal .content .description').text(data.message);
            $('.small.news.modal').modal('show');
        });
        return false;
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

    $('.ui.fluid.dropdown').dropdown();
    $('.ui.search.dropdown').dropdown({fullTextSearch: true});
    $('.ui.search.dropdown.sortLanguageOption').dropdown({
        fullTextSearch: true,
        onChange: function (value, text, $selectedItem) {
            $.ajax({
                type: "GET",
                url: "../src/misc/nextManagePage.php",
                data: {pageNum: $("#updateCurrMPage").val(), sortOption: 2, lang: value},
                cache: true,
                dataType: "json",
                beforeSend: function () {
                    $('.ui.dimmable .manageDim').dimmer('toggle');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    var tBody = $('.orderManage tbody:visible');
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
                                case "IC":
                                    infoMsg = 'Fortfarande pågår';
                                    btnColor = 'orange';
                                    break;
                                case "R":
                                    infoMsg = 'Rapporterad';
                                    btnColor = 'green';
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
                                "<button type='button' id='" + orders[i].o_orderNumber + "' class='ui " + btnColor + " fluid button btn_manage_order'>" + infoMsg + "</button>" +
                                "</form>" +
                                "</td>" +
                                "</tr>");
                            $(".typeTip").popup();
                        }
                    } else {
                        tBody.append("<tr><td><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
                    }
                    $('.ui.dimmable .manageDim').dimmer('toggle');
                }
            });
        }
    });
    $('#sortOptionManage').dropdown({
        onChange: function (value, text, $selectedItem) {
            $.ajax({
                type: "GET",
                url: "../src/misc/nextManagePage.php",
                data: {pageNum: $("#updateCurrMPage").val(), sortOption: value},
                cache: true,
                dataType: "json",
                beforeSend: function () {
                    $('.ui.dimmable .manageDim').dimmer('toggle');
                }
            }).done(function (data) {
                if (data.error == 0) {
                    var tBody = $('.orderManage tbody:visible');
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
                                case "IC":
                                    infoMsg = 'Fortfarande pågår';
                                    btnColor = 'orange';
                                    break;
                                case "R":
                                    infoMsg = 'Rapporterad';
                                    btnColor = 'green';
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
                                "<button type='button' id='" + orders[i].o_orderNumber + "' class='ui " + btnColor + " fluid button btn_manage_order'>" + infoMsg + "</button>" +
                                "</form>" +
                                "</td>" +
                                "</tr>");
                            $(".typeTip").popup();
                        }
                    } else {
                        tBody.append("<tr><td><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
                    }
                    $('.ui.dimmable .manageDim').dimmer('toggle');
                }
            });
        }
    });

    $('.logout-btn').click(function () {
        $.ajax({type: "POST", url: "logout.php"}).done(function () {
            window.location = "index.php";
        });
        return false;
    });

    $('.button.btn-info').on("click", function () {
        var extraInfoCont = $('.modal.order-history');
        var id = $(this).parent("form").attr('id');
        var btnInfo = $(this);
        btnInfo.addClass('loading');
        $.ajax({
            type: "POST",
            url: "../src/misc/orderMoreInfo.php",
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
                if (data.order.o_state !== 'B') {
                    $("#resendToTolk").addClass("disabled");
                    $("#resendToClient").addClass("disabled");
                } else {
                    $("#resendToTolk").removeClass("disabled");
                    $("#resendToClient").removeClass("disabled");
                }
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
                    var sendToFinance = $("#formSendToFinance");
                    sendToFinance.find("#orderNumber").val(data.order.o_orderNumber);
                    sendToFinance.find("#tolkNumber").val(data.order.o_tolkarPersonalNumber);
                    $("#btnSendToFinance").removeClass("disabled");
                } else {
                    $("#btnSendToFinance").addClass("disabled");
                }
                extraInfoCont.modal('show');
            }
            btnInfo.removeClass('loading');
        });
    });

    $("#date").datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, minDate: 0,
        onSelect: function() {
            orderForm.form('validate form');
        }});
    $("#dateFilter").datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, maxDate: 0});

    $(".orgTip").popup({inline: true, transition: "scale"});
    $(".dateTip").popup({inline: true, transition: "scale"});
    $('.tolk-type').popup({inline: true, transition: "scale"});

    $('.ui.radio.checkbox').checkbox();
    $('.menu .item').tab();
    $(".regOrganization").change(function () {
        var selectedOrg = $(".regOrganization option:selected").text();
        var selectedValue = orderForm.form('get value', 'regOrganization');
        if (selectedValue != "") {
            $("#organization").val(selectedOrg);
            $.ajax({
                type: "POST",
                url: "../src/misc/getOrgInfo.php",
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

    $('.page-manage').on("click", ".item", function () {
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
            var currPage = $('.page-manage').find(".active.item");
            if ($(this).hasClass('previousMPage')) {

                var previousPage = currPage.prevAll('.item').not('.previousMPage').first();
                if (!$.isEmptyObject(previousPage) && previousPage.hasClass("item")) {
                    currPage.removeClass("active");
                    previousPage.addClass("active");
                    pageNum = previousPage.text();
                } else {
                    pageNum = currPage.text();
                }
            } else if ($(this).hasClass('nextMPage')) {
                var nextPage = currPage.nextAll('.item').not('.nextMPage').first();
                if (!$.isEmptyObject(nextPage) && nextPage.hasClass("item")) {
                    currPage.removeClass("active");
                    nextPage.addClass("active");
                    pageNum = nextPage.text();
                } else {
                    pageNum = currPage.text();
                }
            }
        }
        $("#updateCurrMPage").val(pageNum);
        $.ajax({
            type: "GET",
            url: "../src/misc/nextManagePage.php",
            data: {pageNum: pageNum, sortOption: $('#sortOptionManage').val()},
            cache: true,
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .manageDim').dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                var tBody = $('.orderManage tbody:visible');
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
                            case "IC":
                                infoMsg = 'Fortfarande pågår';
                                btnColor = 'orange';
                                break;
                            case "R":
                                infoMsg = 'Rapporterad';
                                btnColor = 'green';
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
                            "<button type='button' id='" + orders[i].o_orderNumber + "' class='ui " + btnColor + " fluid button btn_manage_order'>" + infoMsg + "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                        $(".typeTip").popup();
                    }
                } else {
                    tBody.append("<tr><td><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
                }
                $('.ui.dimmable .manageDim').dimmer('toggle');
            }
        });
    });

    $('.btn-update-manage').click(function () {
        $.ajax({
            type: "POST",
            url: "../src/misc/currentOrders.php",
            data: $(".order_manage").serialize(),
            cache: false,
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .manageDim').dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                $("#btnRemoveFilterManage").addClass('disabled');
                $.ajax({
                    type: "GET",
                    url: "../src/misc/getCurrentOrOldOrderNum.php",
                    data: {isManage: true},
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
                            case "IC":
                                infoMsg = 'Fortfarande pågår';
                                btnColor = 'orange';
                                break;
                            case "R":
                                infoMsg = 'Rapporterad';
                                btnColor = 'green';
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
                            "<button type='button' id='" + orders[i].o_orderNumber + "' class='ui " + btnColor + " fluid button btn_manage_order'>" + infoMsg + "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                        $(".typeTip").popup();
                    }
                } else {
                    tBody.append("<tr><td><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
                }

                $('.ui.dimmable .manageDim').dimmer('toggle');
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

    $('.page-history').on("click", ".item", function () {
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
            var currPage = $('.page-history').find(".active.item");
            if ($(this).hasClass('previousHPage')) {

                var previousPage = currPage.prevAll('.item').not('.previousHPage').first();
                if (!$.isEmptyObject(previousPage) && previousPage.hasClass("item")) {
                    currPage.removeClass("active");
                    previousPage.addClass("active");
                    pageNum = previousPage.text();
                } else {
                    pageNum = currPage.text();
                }
            } else if ($(this).hasClass('nextHPage')) {
                var nextPage = currPage.nextAll('.item').not('.nextHPage').first();
                if (!$.isEmptyObject(nextPage) && nextPage.hasClass("item")) {
                    currPage.removeClass("active");
                    nextPage.addClass("active");
                    pageNum = nextPage.text();
                } else {
                    pageNum = currPage.text();
                }
            }
        }
        $("#updateCurrHPage").val($(this).text());
        $.ajax({
            type: "GET",
            url: "../src/misc/nextHistoryPage.php",
            data: {pageNum: pageNum},
            dataType: "json",
            cache: true,
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
                            case "IC":
                                infoMsg = 'Fortfarande pågår';
                                btnColor = 'orange';
                                break;
                            case "R":
                                infoMsg = 'Rapporterad';
                                btnColor = 'green';
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
                            "<form class='ui form' id='" + orders[i].o_orderNumber + "'>" +
                            "<input type='hidden' name='orderId' value='" + orders[i].o_orderNumber + "'>" +
                            "<button type='button' class='ui " + btnColor + " fluid button btn-info'>" + infoMsg + "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                        $(".typeTip").popup();
                    }
                    var extraInfoCont = $('.modal.order-history');
                    extraInfoCont.modal({
                            closable: false, onDeny: function () {
                                return false;
                            },
                            onApprove: function () {
                                var modal = $(".modal.order-history");
                                modal.find(".content .segment>.ui.positive.message").hide();
                                modal.find(".content .segment>.ui.error.message").hide();
                            }
                        })
                        .modal('setting', 'transition', 'vertical flip');

                    $('.button.btn-info').on("click", function () {

                        var id = $(this).parent("form").attr('id');
                        var btnInfo = $(this);
                        btnInfo.addClass('loading');
                        $.ajax({
                            type: "POST",
                            url: "../src/misc/orderMoreInfo.php",
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
                                if (data.order.o_state !== 'B') {
                                    $("#resendToTolk").addClass("disabled");
                                    $("#resendToClient").addClass("disabled");
                                } else {
                                    $("#resendToTolk").removeClass("disabled");
                                    $("#resendToClient").removeClass("disabled");
                                }
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
                                    var sendToFinance = $("#formSendToFinance");
                                    sendToFinance.find("#orderNumber").val(data.order.o_orderNumber);
                                    sendToFinance.find("#tolkNumber").val(data.order.o_tolkarPersonalNumber);
                                    $("#btnSendToFinance").removeClass("disabled");
                                } else {
                                    $("#btnSendToFinance").addClass("disabled");
                                }
                                extraInfoCont.modal('show');
                            }
                            btnInfo.removeClass('loading');
                        });
                    });
                } else {
                    tBody.append("<tr><td><div class='ui text'>För närvarande, har du inte några order.</div></td></tr>");
                }

                $('.ui.dimmable .dimmer').dimmer('toggle');
            }
        });
    });

    $('.btn-update-history').click(function () {
        $.ajax({
            type: "POST",
            url: "../src/misc/pastOrders.php",
            data: $(".order_history").serialize(),
            cache: false,
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .dimmer').dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                $("#btnRemoveFilterHistory").addClass('disabled');
                $.ajax({
                    type: "GET",
                    url: "../src/misc/getCurrentOrOldOrderNum.php",
                    data: {isManage: false},
                    cache: true,
                    dataType: "json"
                }).done(function (data) {
                    if (data.error == 0) {
                        var num = data.numOfOrders;
                        if (num > 10) {
                            var paginationContainer = $(".page-history");
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
                            case "IC":
                                infoMsg = 'Fortfarande pågår';
                                btnColor = 'orange';
                                break;
                            case "R":
                                infoMsg = 'Rapporterad';
                                btnColor = 'green';
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
                            "<form class='ui form' id='" + orders[i].o_orderNumber + "'>" +
                            "<input type='hidden' name='orderId' value='" + orders[i].o_orderNumber + "'>" +
                            "<button type='button' class='ui " + btnColor + " fluid button btn-info'>" + infoMsg + "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                        $(".typeTip").popup();
                    }
                    var extraInfoCont = $('.modal.order-history');
                    extraInfoCont.modal({
                            closable: false, onDeny: function () {
                                return false;
                            },
                            onApprove: function () {
                                var modal = $(".modal.order-history");
                                modal.find(".content .segment>.ui.positive.message").hide();
                                modal.find(".content .segment>.ui.error.message").hide();
                            }
                        })
                        .modal('setting', 'transition', 'vertical flip');

                    $('.button.btn-info').on("click", function () {
                        var id = $(this).parent("form").attr('id');
                        var btnInfo = $(this);
                        btnInfo.addClass('loading');
                        $.ajax({
                            type: "POST",
                            url: "../src/misc/orderMoreInfo.php",
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
                                if (data.order.o_state !== 'B') {
                                    $("#resendToTolk").addClass("disabled");
                                    $("#resendToClient").addClass("disabled");
                                } else {
                                    $("#resendToTolk").removeClass("disabled");
                                    $("#resendToClient").removeClass("disabled");
                                }
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
                                    var sendToFinance = $("#formSendToFinance");
                                    sendToFinance.find("#orderNumber").val(data.order.o_orderNumber);
                                    sendToFinance.find("#tolkNumber").val(data.order.o_tolkarPersonalNumber);
                                    $("#btnSendToFinance").removeClass("disabled");
                                } else {
                                    $("#btnSendToFinance").addClass("disabled");
                                }
                                extraInfoCont.modal('show');
                            }
                            btnInfo.removeClass('loading');
                        });
                    });
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
        });
    });

    tolkSearchFrom.form({
        inline: true,
        on: 'blur',
        transition: "slide down",
        onSuccess: function () {
            $.ajax({
                type: "POST",
                url: "../src/misc/searchTolks.php",
                data: tolkSearchFrom.filter(":visible").serialize(),
                cache: false,
                dataType: "json",
                beforeSend: function () {
                    $('.btnSearchTolk').addClass('loading');
                }
            }).done(function (data) {
                var tolkTable = $('.searchTolkResult .tolks .tolksTable');
                tolkTable.find('tbody').empty();
                if (data.error == 0) {
                    if (data.tolks.length > 0) {
                        var tolks = data.tolks;
                        for (var i = 0; i < tolks.length; i++) {
                            tolkTable.find('tbody').append(
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
                                "<input type='hidden' name='tolkPN' value='" + tolks[i].u_personalNumber + "'>" +
                                "<button type='button' onclick='tolkMoreInfo(event)' class='ui blue fluid button btnTolkInfo'>Mer Info</button>" +
                                "</form>" +
                                "</td>" +
                                "</tr>");
                        }
                        tolkTable.show();
                    } else {
                        tolkTable.hide();
                        $('.searchTolkResult .tolks').append("<div class='ui segment'><div class='ui text'>För närvarande, har du inte några order.</div></div>");
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
        },
        fields: {
            language: {
                identifier: 'language',
                optional: true,
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Välj ett av de språk från rullgardinsmenyn.'
                    }
                ]
            }
        }
    });
    $('.btnSearchTolk').click(function (e) {
        e.preventDefault();
        tolkSearchFrom.filter(":visible").form('validate form');
        tolkSearchFrom.form('clear');
        tolkSearchFrom.get(0).reset();
    });

    setInterval(function () {
        var lang = tolkSearchFrom.form('get value', 'language');

        if (!lang) {
            $(".searchCity").addClass('disabled');
            $(".searchState").addClass('disabled');

            //#tolkNum #tolkName
        } else {
            $(".searchCity").removeClass('disabled');
            $(".searchState").removeClass('disabled');
        }
    }, 500);

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
            tolk_type: {
                identifier: 'tolk_type',
                rules: [
                    {
                        type: 'checked',
                        prompt: 'Fält tolk nivå krävs.'
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

    /*$(".orderForm input, .orderForm select").on('input', function () {
     $(this).valid();
     });*/

    $(".button.next-btn").click(function () {
        if (orderForm.form('is valid')) {
            switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset:visible').next());
        }
    });

    $(".button.back-btn").click(function () {
        switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset:visible').prev());
    });


    $('.button.reset-btn').click(function () {
        orderForm.form('clear');
        $('#organizationID').val("0000000000");
        $('#clientID').val("100000");
        $('#ordererID').val($("#loggedPersonID").text());
        $("#date").datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, minDate: 0,
            onSelect: function() {
                orderForm.form('validate form');
            }});
        $('#customer').find(':input').prop('disabled', true);
        $('#comment').find(':input').prop('disabled', true);
        if (!orderForm.find('fieldset').first().is(":visible")) {
            switchFromTo(orderForm.find('fieldset:visible'), orderForm.find('fieldset').first());
        }
    });
    $('.button.order-btn').click(function (e) {
        e.preventDefault();
        if (orderForm.form('is valid')) {
            $.ajax({
                type: "POST",
                url: "../src/misc/orderRegular.php",
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
                        orderForm.form('clear');
                        $('#organizationID').val("0000000000");
                        $('#clientID').val("100000");
                        $('#ordererID').val($("#loggedPersonID").text());
                        $("#date").datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, minDate: 0,
                            onSelect: function() {
                                orderForm.form('validate form');
                            }});
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

    $('.page.dimmer').hide();
    $('#customer').find(':input').prop('disabled', true);
    $('#comment').find(':input').prop('disabled', true);
});

function switchFromTo(from, to) {
    $(from).transition({
        animation: 'vertical flip', duration: '500ms',
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

function tolkMoreInfo(event) {
    var moreInfoForm = $(event.target).parents(".form");
    $.ajax({
        type: "GET",
        url: "../src/misc/tolkMoreInfo.php",
        data: moreInfoForm.serialize(),
        dataType: "json",
        beforeSend: function () {
            $(event.target).addClass('loading');
        }
    }).done(function (data) {
        if (data.error == 0) {
            var langs = data.langs,
                orders = data.orders,
                kunds = data.customers,
                modal = $(".modal.tolkMoreInfoModal"),
                table1 = modal.find(".content").find(".description").find(".tolkExtraInfo").find('tbody'),
                table2 = modal.find(".content").find(".description").find("#tolkCurrentJobs").find(".tolkExtraInfoOrder").find('tbody');//
            table1.empty();
            $.each(langs, function () {
                table1.append("<tr><td>" + this.t_sprakName + "</td><td>" + this.t_rate + "</td><td>" + this.t_customerRate + "</td></tr>");
            });
            table2.empty();
            if (typeof orders != "undefined") {
                $.each(orders, function (key, value) {
                    table2.append(
                        "<tr>" +
                        "<td>" + value.o_orderNumber + "</td>" +
                        "<td>" + kunds[key].k_organizationName + "</td>" +
                        "<td>" + value.o_orderer + "</td>" +
                        "<td>" + value.o_language + "</td>" +
                        "<td class='typeTip' data-content='" + getFullTolkningType(value.o_interpretationType) + "'>" + value.o_interpretationType + "</td>" +
                        "<td>" + value.o_date + "</td>" +
                        "<td>" + convertTime(value.o_startTime) + "</td>" +
                        "<td>" + convertTime(value.o_endTime) + "</td>" +
                        "</tr>");
                });
            }
            modal.modal('show');
        } else {

        }
        $(event.target).removeClass('loading');
    });

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
        endH.parent('.dropdown').find('*[data-value="'+ $(this).val() +'"]').addClass('disabled');
    });
    if (startH.val() === endH.val()) {
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

function updateNewsletterEntries() {
    var newsContainer = $('#newsContainer');
    var feed = newsContainer.children('.feed');
    $.ajax({
        type: "GET",
        url: "../src/misc/getAllNews.php",
        dataType: "json",
        beforeSend: function () {
            newsContainer.addClass("loading");
        }
    }).done(function (data) {
        if (data.error === 0) {
            feed.empty();
            if (data.records.length > 0) {
                for (var i = 0; i < data.records.length; i++) {
                    var event = $('<div class="event" style="border: solid 1px #d3d3d3; margin-bottom: 5px"></div>');

                    var formManage = $('<form class="newsManageIDForm"></form>');
                    formManage.append($('<input type="hidden" name="newsID"value="' + data.records[i].id + '"/>'));
                    formManage.append($(' <div class="ui grid"> <div class="row"> <div class="column"> <div class="three ui vertical fluid inverted buttons"> <div class="mini ui blue inverted button btnNewsManageView">View </div> <div class="mini ui orange inverted button btnNewsManageEdit">Edit </div> <div class="mini ui red inverted button btnNewsManageDelete">Delete </div> </div> </div> </div> </div>'));

                    var label = $('<div class="label"></div>');
                    label.append($('<i class="mail outline icon"></i>'));
                    label.append(formManage);

                    var context = $('<div class="content"></div>');
                    var summary = $('<div class="summary"></div>');
                    summary.html(data.records[i].header);
                    var content = $('<div class="extra text">' + data.records[i].prescript + '</div>');
                    context.append(summary);
                    context.append(content);
                    context.append($('<div class="meta"></div>'));

                    event.append(label);
                    event.append(context);

                    feed.append(event);
                }
            } else {
                newsContainer.append($("<div><p class='ui text'>Just nu har vi inte några nyheter!</p></div>"));
            }
        }
        newsContainer.removeClass('loading');
    });
    return false;
}
