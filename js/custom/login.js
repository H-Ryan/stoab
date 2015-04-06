/**
 * Created by Samuil on 23-01-2015.
 */
$(document).ready(function () {
    "use strict";
    var loginForm = $('.ui.form.login');
    var retreivePass = $('.ui.form.retrievePass');
    var successElem = retreivePass.find(".ui.positive.message");
    var errorElem = retreivePass.find(".ui.error.message");
    var icon = $('.close.icon');
    icon.click(function() {
        $('.ui.error.message').hide();
    });
    icon.click(function() {
        $('.ui.positive.message').hide();
    });

    $('.big.info.circle.icon')
        .popup({
            inline   : true,
            hoverable: true,
            position : 'top center'
        })
    ;

    $('.ui.button.login-btn').on("click", function (e) {
        e.preventDefault();
        loginForm.form('validate form');
        return false;
    });

    loginForm.bind('keypress keydown keyup', function(e){
        if(e.keyCode == 13) {
            e.preventDefault();
            loginForm.form('validate form');
            return false;
        }
    });

    $(".ui.button.retrieve-btn").click(function () {
        retreivePass.form('validate form');
        return false;
    });
    $('.forgotten').click(function () {
        loginForm.form('reset');
        retreivePass.get(0).reset();
        switchFromTo(loginForm, retreivePass);
    });
    $('.back-btn').click(function () {
        retreivePass.form('reset');
        retreivePass.get(0).reset();
        switchFromTo(retreivePass, loginForm);
    });

    loginForm.form({
        number: {
            identifier: 'number',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Ange ett kundnummer.'
                },
                {
                    type: 'length[6]',
                    prompt: 'Ditt kundnummer<br />måste vara minst 6 tecken.'
                },
                {
                    type: 'maxLength[6]',
                    prompt: 'Ditt kundnummer<br />måste vara maximalt 6 tecken.'
                }
            ]
        },
        password: {
            identifier: 'password',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Ange ett lösenord.'
                },
                {
                    type: 'length[6]',
                    prompt: 'Ditt lösenord måste<br />vara minst 6 tecken.'
                }
            ]
        }
    }, {
        inline: true,
        on: 'blur',
        transition: "slide down",
        onValid: function () {
            $(".button.login-btn").prop('disabled', false);
        },
        onSuccess: function () {
            $(".button.login-btn").prop('disabled', false);
            loginForm.removeClass("error").removeClass("transition").removeClass("visible");
            var number = $("#number").val();
            var password = $("#password").val();
            if ($.trim(number).length > 0 && $.trim(password).length > 0) {
                $.ajax({
                    type: "POST",
                    url: "src/misc/login.php",
                    data: loginForm.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        loginForm.addClass("loading");
                    }
                }).done(function (data) {
                    if (data.error == 0) {
                        loginForm.form('reset');
                        loginForm.get(0).reset();
                        window.location = "kundpanel.php";
                        return false;
                    }
                    else {
                        var errorElem = loginForm.find(".ui.error.message");
                        loginForm.removeClass("loading").addClass("error");
                        errorElem.children("p").text(data.errorMessage);
                        errorElem.children('.header').text(data.messageHeader);
                        errorElem.show();
                        return false;
                    }
                });
            }
        },
        onFailure: function () {
            $(".button.login-btn").prop('disabled', true);
            loginForm.removeClass("error");
        }
    });
    retreivePass.form({
            number: {
                identifier: 'number',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Ange ett kundnummer.'
                    },
                    {
                        type: 'length[6]',
                        prompt: 'Ditt kundnummer<br />måste vara minst 6 tecken.'
                    },
                    {
                        type: 'maxLength[6]',
                        prompt: 'Ditt kundnummer<br />måste vara maximalt 6 tecken.'
                    }
                ]
            },
            email: {
                identifier: 'email',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Vänligen ange din en e-postadress.'
                    },
                    {
                        type: 'email',
                        prompt: 'Ogiltig e-postadress.'
                    }
                ]
            }
        },
        {
            inline: true,
            on: 'blur',
            transition: "slide down",
            onValid: function () {
                $(".ui.button.retrieve-btn").prop('disabled', false);
            },
            onSuccess: function () {
                $(".ui.button.retrieve-btn").prop('disabled', false);
                $.ajax({
                    type: "POST",
                    url: "src/misc/forgotPassword.php",
                    data: retreivePass.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        retreivePass.find(".segment").first().addClass("loading");
                    }
                }).done(function (data) {
                    if (data.mailError == null) {
                        if (data.error == 0) {
                            retreivePass.form('reset');
                            retreivePass.get(0).reset();
                            retreivePass.removeClass("error").find(".segment").first().removeClass("loading");
                            successElem.children(".ui.text").text(data.successMessage);
                            successElem.children('.header').text(data.messageHeader);
                            successElem.show();
                            return false;
                        }
                        else {
                            retreivePass.addClass("error").find(".segment").first().removeClass("loading");
                            errorElem.children("p").text(data.errorMessage);
                            errorElem.children('.header').text(data.messageHeader);
                            errorElem.show();
                            return false;
                        }
                    }
                    errorElem.show();
                    retreivePass.addClass("error").find(".segment").first().removeClass("loading");
                    errorElem.children("p").text(data.emailErrorMessage);
                    errorElem.children('.header').text(data.emailErrorHeader);
                    return false;
                });
            },
            onFailure: function () {
                $(".ui.button.retrieve-btn").prop('disabled', true);
                retreivePass.removeClass("error");
            }
        })
    ;
});

function switchFromTo(from, to) {
    $(from).transition({
        animation: 'vertical flip', duration: '500ms',
        onComplete: function () {
            $(to).transition({animation: 'vertical flip', duration: '500ms'});
        }
    });
}