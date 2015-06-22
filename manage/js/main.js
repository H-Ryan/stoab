$(document).ready(function () {
    "use strict";
    var orderForm = $('.ui.form.orderForm'),
        tolkSearchFrom = $('.ui.form.tolk-search'),
        orderHistoryFilterForm = $("#orderFilterForm");
    $('.ui.fluid.accordion').accordion();

    orderHistoryFilterForm.form({
            orderNumber: {
                identifier  : 'orderNumber',
                optional   : true,
                rules: [
                    {
                        type   : 'length[6]',
                        prompt : 'Ordernummer måste vara minst 6 tecken.'
                    },
                    {
                        type   : 'maxLength[6]',
                        prompt : 'Ordernummer måste vara minst 6 tecken.'
                    }
                ]
            },
            tolkNumber: {
                identifier  : 'tolkNumber',
                optional   : true,
                rules: [
                    {
                        type   : 'length[4]',
                        prompt : 'Tolk nummer måste vara minst 4 tecken.'
                    },
                    {
                        type   : 'maxLength[4]',
                        prompt : 'Tolk nummer måste vara minst 4 tecken.'
                    },
                    {
                        type   : 'integer',
                        prompt : 'Tolk nummer innehåller ogiltiga tecken.'
                    }
                ]
            },
            clientNumber: {
                identifier : 'clientNumber',
                optional   : true,
                rules: [
                    {
                        type   : 'length[6]',
                        prompt : 'Kund nummer måste vara minst 6 tecken.'
                    },
                    {
                        type   : 'maxLength[6]',
                        prompt : 'Kund nummer måste vara minst 6 tecken.'
                    },
                    {
                        type   : 'integer',
                        prompt : 'Kund nummer innehåller ogiltiga tecken.'
                    }
                ]
            }
        },{
            inline: true,
            on: 'change',
            transition: "slide down",
            onSuccess: function () {
                $.ajax({
                    type: "POST",
                    url: "src/misc/filterOrderHistory.php",
                    data: orderHistoryFilterForm.serialize(),
                    cache: false,
                    dataType: "json",
                    beforeSend: function () {
                        $('.ui.dimmable .dimmer').dimmer('toggle');
                    }
                }).done(function (data) {
                    orderHistoryFilterForm.form('reset');
                    orderHistoryFilterForm.get(0).reset();
                    if (data.error == 0) {
                        var tBody = $('.orderHistory tbody');
                        tBody.find('tr').remove();

                        if (data.orders.length > 0) {
                            $("#btnRemoveFilterHistory").removeClass('disabled');

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
                            $('.modal.order-history')
                                .modal('setting', 'transition', 'vertical flip');

                            $('.button.btn-info').on("click",function() {
                                var extraInfoCont = $('.modal.order-history');
                                var id =$(this).parent("form").attr('id');
                                $(this).addClass('loading');
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
                            tBody.append("<tr><td><div class='ui text'>Inga order matchar din sökning parametrar.</div></td></tr>");
                        }
                        $('.ui.dimmable .dimmer').dimmer('toggle');
                    }
                });
            }
        });

    jQuery.validator.setDefaults({
        debug: true,
        success: "valid"
    });
    $("#btnRemoveFilterHistory").click(function() {
        $( ".btn-update-history" ).trigger( "click" );
    });

    $('#btnFilterHistory').click(function () {
        orderHistoryFilterForm.form("validate form");
    });


    $("#newsPrescript").change(function() {
        var charCount = $(this).val().length;
        var chars =  $("#chars");
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
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
    var feed = $(".feed");
    feed.on("click", ".btnNewsManageView", function() {
        $('#newsLetterModifyForm').slideUp(500);
        $("#containerUpdateNews").addClass('basic');
        var pressedBtn = $(this);
        $.ajax({
            type: "GET",
            url: "src/misc/getNewsletterInfo.php",
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

    feed.on("click", ".btnNewsManageEdit", function() {
        var pressedBtn = $(this),
            container = $("#containerUpdateNews");
        $.ajax({
            type: "GET",
            url: "src/misc/getNewsletterInfo.php",
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

    $("#btnPreviewNewsManage").on("click", function() {
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
            url: "src/misc/updateNews.php",
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
            url: "src/misc/deleteNews.php",
            dataType: "json",
            beforeSend: function () {
                pressedBtn.addClass('loading');
            },
            data: {newsID: pressedBtn.data('id') }
        }).done(function (data) {
            $('.small.news.modal .content .description').text(data.message);
            $('.small.news.modal').modal('show');
            pressedBtn.removeClass('loading');
            updateNewsletterEntries();
        });
        return false;
    });


    $("#btnPreviewNews").on("click", function() {
        $('.modal.preview .header').text($("#newsTitle").val() + " - Publicerat: Idag");
        $('.modal.preview #newsContent').html(tinyMCE.get('newsLetter').getContent());

        $('.modal.preview').modal('show');
    });

    $("#btnPostNewsLetter").on("click", function () {
        $.ajax({
            type: "POST",
            url: "src/misc/addNews.php",
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

    $('.button.btn-info').on("click",function() {
        var extraInfoCont = $('.modal.order-history');
        var id =$(this).parent("form").attr('id');
        $(this).addClass('loading');
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
            }
            $('#' + id).find('.button').removeClass('loading');
        });
    });

    $("#date").datepicker({dateFormat: 'yy-mm-dd', minDate: 0 });
    $("#dateFilter").datepicker({dateFormat: 'yy-mm-dd' });

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

    $('.page-manage .item').on("click", function() {
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
            var currPage = $('.page-manage').find(".active.item");
            if($(this).hasClass('previousMPage')){

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
            url: "src/misc/nextManagePage.php",
            data: { pageNum: pageNum },
            cache: true,
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .manageDim').dimmer('toggle');
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
            url: "src/misc/currentOrders.php",
            data: $(".order_manage").serialize(),
            cache: false,
            dataType: "json",
            beforeSend: function () {
                $('.ui.dimmable .manageDim').dimmer('toggle');
            }
        }).done(function (data) {
            if (data.error == 0) {
                $.ajax({
                    type: "GET",
                    url: "src/misc/getCurrentOrOldOrderNum.php",
                    data: { isManage: true },
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

    $('.page-history .item').on("click", function() {
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
            var currPage = $('.page-history').find(".active.item");
            if($(this).hasClass('previousHPage')){

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
            url: "src/misc/nextHistoryPage.php",
            data: { pageNum: pageNum },
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
                    $('.modal.order-history')
                        .modal('setting', 'transition', 'vertical flip');

                    $('.button.btn-info').on("click",function() {
                        var extraInfoCont = $('.modal.order-history');
                        var id =$(this).parent("form").attr('id');
                        $(this).addClass('loading');
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
                $("#btnRemoveFilterHistory").addClass('disabled');
                $.ajax({
                    type: "GET",
                    url: "src/misc/getCurrentOrOldOrderNum.php",
                    data: { isManage: false },
                    cache: true,
                    dataType: "json"
                }).done(function (data) {
                    if (data.error == 0) {
                        var num = data.numOfOrders;
                        if (num > 10) {
                            var paginationContainer = $(".page-history");
                            paginationContainer.find("a").remove();
                            console.log(num);
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
                            "<form class='ui form' id='" + orders[i].o_orderNumber + "'>" +
                            "<input type='hidden' name='orderId' value='" + orders[i].o_orderNumber + "'>" +
                            "<button type='button' class='ui " + btnColor + " fluid button btn-info'>" + infoMsg + "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                        $(".typeTip").popup();
                    }
                    $('.modal.order-history')
                        .modal('setting', 'transition', 'vertical flip');

                    $('.button.btn-info').on("click",function() {
                        var extraInfoCont = $('.modal.order-history');
                        var id =$(this).parent("form").attr('id');
                        $(this).addClass('loading');
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
                $('.button.btn-update-history').prop("disabled", true).addClass("disabled");
                setTimeout(function () {
                    var btnRefresh = $('.btn-update-history');
                    btnRefresh.prop("disabled", false).removeClass("disabled");
                }, 3000);
            }
        });
    });

    tolkSearchFrom.form({
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
                container.empty();
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
    $('.btnSearchTolk').click(function (e) {
        tolkSearchFrom.form('validate form');
        tolkSearchFrom.form('clear');
        tolkSearchFrom.get(0).reset();
    });

    setInterval(function() {
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

    $(".orderForm input, .orderForm select").on('input', function () {
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
                orderForm.form('clear');
                orderForm.get(0).reset();
                orderForm.form('reset');
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

function updateNewsletterEntries() {
    var newsContainer = $('#newsContainer');
    var feed = newsContainer.children('.feed');
    $.ajax({
        type: "GET",
        url: "src/misc/getAllNews.php",
        dataType: "json",
        beforeSend: function() {
            newsContainer.addClass("loading");
        }
    }).done(function (data) {
        if (data.error === 0) {
            feed.empty();
            if (data.records.length > 0) {
                for(var i = 0; i < data.records.length; i++) {
                    var event = $('<div class="event" style="border: solid 1px #d3d3d3; margin-bottom: 5px"></div>');

                    var formManage = $('<form class="newsManageIDForm"></form>');
                    formManage.append($('<input type="hidden" name="newsID"value="'+data.records[i].id+'"/>'));
                    formManage.append($(' <div class="ui grid"> <div class="row"> <div class="column"> <div class="three ui vertical fluid inverted buttons"> <div class="mini ui blue inverted button btnNewsManageView">View </div> <div class="mini ui orange inverted button btnNewsManageEdit">Edit </div> <div class="mini ui red inverted button btnNewsManageDelete">Delete </div> </div> </div> </div> </div>'));

                    var label = $('<div class="label"></div>');
                    label.append($('<i class="mail outline icon"></i>'));
                    label.append(formManage);

                    var context = $('<div class="content"></div>');
                    var summary = $('<div class="summary"></div>');
                    summary.html(data.records[i].header);
                    var content = $('<div class="extra text">' +data.records[i].prescript+ '</div>');
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