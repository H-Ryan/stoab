(function ($) {
    'use strict';

    $('#date').datepicker({
        format: "yyyy-mm-dd",
        startDate: '0',
        autoclose: true,
        todayBtn: 'linked',
        language: 'sv'
    });

    var loginForm = $('#frmLogIn'),
        loginButton = $('#btnLogIn'),
        loginError = $('#loginError'),
        forgotPassForm = $('#frmForgotPass'),
        forgotPassButton = $('#btnResetPassword'),
        forgotPassError = $('#forgotPassError'),
        linkToLogin = $('#linkToLogin'),
        linkToForgotPass = $('#linkToForgotPass'),
        loginBox = $('#loginBox'),
        forgotPassBox = $('#forgotPassBox'),
        interpreterLoginForm = $('#interpreterLoginForm'),
        interpreterLoginBtn = $('#interpreterLoginBtn'),
        interpreterLoginError = $('#interpreterLoginError'),
        interpreterFrmForgotPass = $('#interpreterFrmForgotPass'),
        interpreterResetPasswordBtn = $('#btnInterpreterResetPassword'),
        forgotInterpreterPassError = $('#forgotInterpreterPassError'),
        linkToInterpreterLogin = $('#linkToInterpreterLogin'),
        linkToInterpreterForgotPass = $('#linkToInterpreterForgotPass'),
        interpreterLoginBox = $('#interpreterLoginBox'),
        interpreterForgotPassBox = $('#interpreterForgotPassBox');

    loginButton.on("click", function (e) {
        e.preventDefault();
        var validated = loginForm.valid();
        if (validated) {
            $.ajax({
                type: "POST",
                url: "./src/misc/login.php",
                data: loginForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    loginButton.addClass('disabled');
                },
                complete: function (data) {
                    if (typeof data.responseJSON === 'object') {
                        if (data.responseJSON.error === 0) {
                            loginError.addClass('hidden');
                            loginForm[0].reset();
                            loginButton.removeClass('disabled');
                            window.location = "kundpanel.php";
                            return true;
                        }
                        loginError.removeClass('hidden');
                        loginButton.removeClass('disabled');
                    }
                }
            });
        }
    });

    forgotPassButton.on("click", function (e) {
        e.preventDefault();
        var validated = forgotPassForm.valid();
        if (validated) {
            $.ajax({
                type: "POST",
                url: "./src/misc/forgotPassword.php",
                data: forgotPassForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    forgotPassButton.addClass('disabled');
                },
                complete: function (data) {
                    if (typeof data.responseJSON === 'object') {
                        if (data.responseJSON.error === 0) {
                            forgotPassError.addClass('hidden');
                            forgotPassForm[0].reset();
                            forgotPassButton.removeClass('disabled');
                            linkToLogin.trigger('click');
                            return true;
                        }
                        forgotPassError.removeClass('hidden');
                        forgotPassButton.removeClass('disabled');
                    }
                }
            });
        }
    });

    linkToLogin.on("click", function (e) {
        forgotPassBox.hide();
        loginBox.fadeIn('slow');
    });
    linkToForgotPass.on("click", function (e) {
        loginBox.hide();
        forgotPassBox.fadeIn('slow');
    });

    interpreterLoginBtn.on("click", function (e) {
        e.preventDefault();
        var validated = interpreterLoginForm.valid();
        if (validated) {
            $.ajax({
                type: "POST",
                url: "./src/misc/interpreter/tolk-login.php",
                data: interpreterLoginForm.serialize(),
                dataType: "json",
                beforeSend: function () {
                    interpreterLoginBtn.addClass('disabled');
                },
                complete: function (data) {
                    if (typeof data.responseJSON === 'object') {
                        if (data.responseJSON.error === 0) {
                            interpreterLoginError.addClass('hidden');
                            interpreterLoginForm[0].reset();
                            interpreterLoginBtn.removeClass('disabled');
                            window.location = "tolkpanel.php";
                            return true;
                        }
                        interpreterLoginError.removeClass('hidden');
                        interpreterLoginBtn.removeClass('disabled');
                    }
                }
            });
        }
    });

    interpreterResetPasswordBtn.on("click", function (e) {
        e.preventDefault();
        var validated = interpreterFrmForgotPass.valid();
        if (validated) {
            $.ajax({
                type: "POST",
                url: "./src/misc/interpreter/tolkForgotPassword.php",
                data: interpreterFrmForgotPass.serialize(),
                dataType: "json",
                beforeSend: function () {
                    interpreterResetPasswordBtn.addClass('disabled');
                },
                complete: function (data) {
                    if (typeof data.responseJSON === 'object') {
                        if (data.responseJSON.error === 0) {
                            forgotInterpreterPassError.addClass('hidden');
                            interpreterFrmForgotPass[0].reset();
                            interpreterResetPasswordBtn.removeClass('disabled');
                            linkToInterpreterLogin.trigger('click');
                            return true;
                        }
                        forgotInterpreterPassError.removeClass('hidden');
                        interpreterResetPasswordBtn.removeClass('disabled');
                    }
                }
            });
        }
    });

    linkToInterpreterLogin.on("click", function (e) {
        interpreterForgotPassBox.hide();
        interpreterLoginBox.fadeIn('slow');
    });
    linkToInterpreterForgotPass.on("click", function (e) {
        interpreterLoginBox.hide();
        interpreterForgotPassBox.fadeIn('slow');
    });

}).apply(this, [jQuery]);