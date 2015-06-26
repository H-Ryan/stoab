/**
 * Created by Samuil on 23-06-2015.
 */
var lastModified = 0,
    addMessageModal = $(".modal.addMessageModal")
resultPostErrorModal = $(".modal.resultPostErrorModal"),
    firstModal = $('.commentOnTolk.firstStep'),
    secondModal = $('.commentOnTolk.secondStep'),
    thirdModal = $('.commentOnTolk.thirdStep'),
    verifyTolkForm = $('.commentOnTolk.first .form'),
    tolkCommentForm = $('.commentOnTolk.third .form'),
    noteTextForm = $('.form.noteTextForm'),
    btnNoteInternal = $('.button.btnNoteInternal'),
    btnNoteCustomer = $('.button.btnNoteCustomer'),
    btnNoteInterpreter = $('.button.btnNoteInterpreter'),
    internalFeed = $('.feed.internalFeed'),
    customerFeed = $('.feed.customerFeed'),
    interpreterFeed = $('.feed.interpreterFeed'),
    dashboardMenuItem = $(".vertical.sidebar.menu").find("[data-tab='sixth']");

$(function () {
    $.ajax({
        url: 'src/misc/getAllNotes.php',
        dataType: 'JSON',
        data: {isUpdate: 0},
        cache: true,
        type: 'GET',
        beforeSend: function () {
            internalFeed.addClass('loading');
            customerFeed.addClass('loading');
            interpreterFeed.addClass('loading');
        },
        success: function (data) {
            if (data.error === 0) {
                lastModified = data.lastModified;
                $.each(data.data.internal, function (i, v) {
                    postFeedEvent(internalFeed, v);
                });
                $.each(data.data.customers, function (i, v) {
                    postFeedEvent(customerFeed, v);
                });
                $.each(data.data.interpreters, function (i, v) {
                    postFeedEvent(interpreterFeed, v);
                });
                internalFeed.removeClass('loading');
                customerFeed.removeClass('loading');
                interpreterFeed.removeClass('loading');
            }
        }
    });
});
$(document).ready(function () {
    /**
     * Collaboration board with notes
     */
        //Open modal form
    btnNoteInternal.click(function () {
        addMessageModal.children(".header").text("Lägg till en anteckning för dina kollegor!");
        addMessageModal.modal({
            closable: false, transition: 'vertical flip', allowMultiple: true,
            onDeny: function () {
                noteTextForm.get(0).reset();
            },
            onApprove: function () {
                var close = null;
                $.ajax({
                    url: 'src/misc/postNote.php',
                    dataType: 'JSON',
                    async: false,
                    data: {
                        postedBy: $("#employeeName").text(),
                        message: noteTextForm.find("textarea").val(),
                        category: 1
                    },
                    type: 'POST',
                    success: function (data) {
                        if (data.error === 0) {
                            postFeedEvent(internalFeed, data.newEvent);
                            noteTextForm.get(0).reset();
                            close = true;
                        } else {
                            addMessageModal.find(".actions .button").addClass("disabled");
                            resultPostErrorModal.find(".content .description>p").text(data.message);
                            resultPostErrorModal.modal({
                                closable: false, transition: 'vertical flip', allowMultiple: true,
                                onApprove: function () {
                                    addMessageModal.find(".actions .button").removeClass("disabled");
                                }
                            }).modal('show');
                            close = false;
                        }
                    }
                });
                if (!close)
                    return false;
            }
        }).modal('show');
    });
    btnNoteCustomer.click(function () {
        addMessageModal.children(".header").text("Lägg till en anteckning om en kund!");
        addMessageModal.modal({
            closable: false, transition: 'vertical flip', allowMultiple: true,
            onDeny: function () {
                noteTextForm.get(0).reset();
            },
            onApprove: function () {
                var close = false;
                $.ajax({
                    url: 'src/misc/postNote.php',
                    dataType: 'JSON',
                    async: false,
                    data: {
                        postedBy: $("#employeeName").text(),
                        message: noteTextForm.find("textarea").val(),
                        category: 2
                    },
                    type: 'POST',
                    success: function (data) {
                        if (data.error === 0) {
                            postFeedEvent(customerFeed, data.newEvent);
                            noteTextForm.get(0).reset();
                            close = true;
                            console.log(close);
                        } else {
                            addMessageModal.find(".actions .button").addClass("disabled");
                            resultPostErrorModal.find(".content .description>p").text(data.message);
                            resultPostErrorModal.modal({
                                closable: false, transition: 'vertical flip', allowMultiple: true,
                                onApprove: function () {
                                    addMessageModal.find(".actions .button").removeClass("disabled");
                                }
                            }).modal('show');
                        }
                    }
                });
                if (!close)
                    return false;
            }
        }).modal('show');
    });
    btnNoteInterpreter.click(function () {
        addMessageModal.children(".header").text("Lägg till en anteckning om tolk!");
        addMessageModal.modal({
            closable: false, transition: 'vertical flip', allowMultiple: true,
            onDeny: function () {
                noteTextForm.get(0).reset();
            },
            onApprove: function () {
                var close = false;
                $.ajax({
                    url: 'src/misc/postNote.php',
                    dataType: 'JSON',
                    async: false,
                    data: {
                        postedBy: $("#employeeName").text(),
                        message: noteTextForm.find("textarea").val(),
                        category: 3
                    },
                    type: 'POST',
                    success: function (data) {
                        if (data.error === 0) {
                            postFeedEvent(interpreterFeed, data.newEvent);
                            noteTextForm.get(0).reset();
                            close = true;
                        } else {
                            addMessageModal.find(".actions .button").addClass("disabled");
                            resultPostErrorModal.find(".content .description>p").text(data.message);
                            resultPostErrorModal.modal({
                                closable: false, transition: 'vertical flip', allowMultiple: true,
                                onApprove: function () {
                                    addMessageModal.find(".actions .button").removeClass("disabled");
                                }
                            }).modal('show');
                        }
                    }
                });
                if (!close)
                    return false;
            }
        }).modal('show');
    });

    dashboardMenuItem.click(function () {
        $(this).children('.icon').remove();
    });

    //Update
    setInterval(function () {
        $.ajax({
            url: 'src/misc/getAllNotes.php',
            dataType: 'JSON',
            data: {isUpdate: 1, lastModified: lastModified},
            cache: true,
            type: 'GET',
            success: function (data) {
                if (data.error === 0) {
                    if (!dashboardMenuItem.hasClass('active')) {
                        dashboardMenuItem.append($('<i class="blue mail icon"></i>'));
                    }
                    lastModified = data.lastModified;
                    internalFeed.addClass('loading');
                    customerFeed.addClass('loading');
                    interpreterFeed.addClass('loading');
                    internalFeed.empty();
                    customerFeed.empty();
                    interpreterFeed.empty();

                    $.each(data.data.internal, function (i, v) {
                        postFeedEvent(internalFeed, v);
                    });
                    $.each(data.data.customers, function (i, v) {
                        postFeedEvent(customerFeed, v);
                    });
                    $.each(data.data.interpreters, function (i, v) {
                        postFeedEvent(interpreterFeed, v);
                    });
                    internalFeed.removeClass('loading');
                    customerFeed.removeClass('loading');
                    interpreterFeed.removeClass('loading');
                }
            }
        });
    }, 5000);

    /**
     * ###############################
     * Post comment about interpreters
     */
    $('.modal.commentOnTolk')
        .modal({
            closable: false,
            allowMultiple: false
        });

    //Show events
    firstModal.modal('attach events', '#btnAddTolkComment');
    firstModal.modal('attach events', '.commentOnTolk.secondStep .btnBack');

    secondModal.modal('attach events', '.commentOnTolk.firstStep .button');
    secondModal.modal('attach events', '.commentOnTolk.thirdStep .btnBack');

    thirdModal.modal('attach events', '.commentOnTolk.secondStep .btnContinue');


    //Transitions
    firstModal.modal('setting', 'transition', "horizontal flip");
    secondModal.modal('setting', 'transition', "horizontal flip");
    thirdModal.modal('setting', 'transition', "horizontal flip");

    //On close
    firstModal.children('.close.icon').click(function () {
        verifyTolkForm.get(0).reset();
    });
    secondModal.children('.close.icon').click(function () {
        verifyTolkForm.get(0).reset();
    });
    thirdModal.children('.close.icon').click(function () {
        verifyTolkForm.get(0).reset();
        tolkCommentForm.get(0).reset();
    });

});

function postFeedEvent(feed, data) {
    var event = $('<div class="event"></div>'),
        content = $('<div class="content"></div>'),
        summary = $('<div class="summary"></div>'),
        date = $('<div class="date"></div>'),
        extraText = $('<div class="extra text"></div>');
    event.append($('<div class="label"><i class="circular inverted blue comment icon"></i></div>'));
    summary.append($('<a>' + data.postedBy + '</a> posted a note on'));
    date.text(data.postedOn);
    summary.append(date);
    extraText.append($('<p class="ui text">' + data.message + '</p>'));
    content.append(summary);
    content.append(extraText);
    event.append(content);
    feed.prepend(event);
}