/**
 * Created by Samuil on 13-11-2015.
 */
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

function tolkMoreInfo(event) {
    var moreInfoForm = $(event.target).parents(".form");
    $.ajax({
        type: "GET",
        url: "src/misc/tolkMoreInfo.php",
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
                table2 = modal.find(".content").find(".description").find(".tolkExtraInfoOrder").find('tbody');//
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

function updateNewsletterEntries() {
    var newsContainer = $('#newsContainer');
    var feed = newsContainer.children('.feed');
    $.ajax({
        type: "GET",
        url: "src/misc/getAllNews.php",
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