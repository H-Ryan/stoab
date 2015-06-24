/**
 * Created by Samuil on 23-06-2015.
 */
$(document).ready(function () {
    var firstModal = $('.commentOnTolk.firstStep'),
        secondModal = $('.commentOnTolk.secondStep'),
        thirdModal = $('.commentOnTolk.thirdStep'),
        verifyTolkForm = $('.commentOnTolk.first .form'),
        tolkCommentForm = $('.commentOnTolk.third .form');

    $('.modal.commentOnTolk')
        .modal({
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

    //Closable
    firstModal.modal('setting', 'closable', false);
    secondModal.modal('setting', 'closable', false);
    thirdModal.modal('setting', 'closable', false);

    //On close
    firstModal.children('.close.icon').click( function() {
        verifyTolkForm.get(0).reset();
    });
    secondModal.children('.close.icon').click( function() {
        verifyTolkForm.get(0).reset();
    });
    thirdModal.children('.close.icon').click( function() {
        verifyTolkForm.get(0).reset();
        tolkCommentForm.get(0).reset();
    });

});