/**
 * Created by Samuil on 21-02-2015.
 */
$( document ).ready(function() {
    "use strict";
    var loginForm = $('.ui.form.loginManage');

    loginForm.form({
        inline: true,
        on: 'blur',
        transition: "slide down",
        onValid: function () {
            $(".button.login-btn").prop('disabled', false);
        },
        fields: {
            email: {
                identifier: 'email',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Vänligen ange en e-post.'
                    },
                    {
                        type: 'email',
                        prompt: 'Den här e-post är inte giltig.'
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
        }
    });
});