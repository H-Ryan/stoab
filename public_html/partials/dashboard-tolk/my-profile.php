<!--
* User: Samuil
* Date: 29-01-2015
* Time: 10:31 PM
-->
<table class="ui tablet stackable collapsing celled striped table">
    <thead>
    <tr>
        <th colspan="2">Min information</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui header">Personnummer:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <p class="text_5"><?php echo $tolkInfo->u_personalNumber; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui center aligned header">Tolknummer:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <p class="text_5"><?php echo $_SESSION['tolk_number'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui center aligned header">För- och efternamn:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <p class="text_5"><?php echo $tolkInfo->u_firstName.' '.$tolkInfo->u_lastName ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui center aligned header">E-postadress:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <p class="text_5"><?php echo $tolkInfo->u_email; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui center aligned header">Lösenord:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <button class="ui fluid blue button btn-change-pass">Ändra ditt lösenord</button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui center aligned header">Telefon:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <p class="text_5"><?php echo $tolkInfo->u_tel; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui center aligned header">Mobil:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <p class="text_5"><?php echo $tolkInfo->u_mobile; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui center aligned header">Adress:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <p class="text_5"><?php echo $tolkInfo->u_address; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui center aligned header">Postnummer:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <p class="text_5"><?php echo $tolkInfo->u_zipCode; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui top attached segment">
                <div class="ui two column stackable grid">
                    <div class="column">
                        <div class="ui horizontal segment">
                            <h4 class="ui center aligned header">Ort:</h4>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui horizontal segment">
                            <p class="text_5"><?php echo $tolkInfo->u_city; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
    <tfoot class="full-width">
        <tr>
            <th colspan="2">
                <p>Kontakta Tolkning i Kristianstad AB om någon av informationen är felaktig.</p>
            </th>
        </tr>
    </tfoot>
</table>
<div class="ui small modal change-pass">
    <div class="ui inverted blue segment">
        <div class="white header">Byt Lösenord <i class="privacy icon"></i></div>
    </div>
    <div class="content">
        <form class="ui small form changePass" onsubmit="return false;">
            <div class="ui grid stackable">
                <div class="row">
                    <div class="centered ten wide column">
                        <div class="ui error message">
                            <div class="header">Fel</div>
                            <p>Fyll i de obligatoriska fälten.</p>
                        </div>
                        <input type="hidden" name="tolkEmail" value="<?php echo $tolkInfo->u_email; ?>">
                        <input type="hidden" name="pNumber" value="<?php echo $personal_number; ?>">
                        <div class="required field">
                            <label for="oldPassword">Nuvarande Lösenord:</label>
                            <input id="oldPassword" name="oldPassword" type="password"/>
                        </div>
                        <div class="required field">
                            <label for="newPass">Nytt Lösenord:</label>
                            <input id="newPass" name="newPass" type="password"/>
                        </div>
                        <div class="required field">
                            <label for="newPassRep">Upprepa Nytt Lösenord:</label>
                            <input id="newPassRep" name="newPassRep" type="password"/>
                        </div>
                        <div class="field">
                            <button type="button" class="ui blue right labeled icon button reset-btn">
                                <i class="right arrow icon"></i>
                                Skicka
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="actions">
    </div>
</div>
