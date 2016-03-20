<?php
/**
 * User: Samuil
 * Date: 23-06-2015
 * Time: 11:43 AM
 */
?>
<div class="ui piled segment">
    <div class="ui stackable grid">
        <div class="row">
            <div class="five wide column">
                <div class="ui header">Interna anteckningar:</div>
                <div class="ui horizontal divider"></div>
                <div class="ui segment" style="height: 400px; max-height: 400px; overflow-y: auto;">
                    <div class="ui feed internalFeed"></div>
                </div>
                <div class="ui teal labeled icon button btnNoteInternal">
                    Skicka en anteckning
                    <i class="add icon"></i>
                </div>
            </div>
            <div class="five wide column">
                <div class="ui header">Anteckningar om kunderna:</div>
                <div class="ui horizontal divider"></div>
                <div class="ui segment" style="height: 400px; max-height: 400px; overflow-y: auto;">
                    <div class="ui feed customerFeed"></div>
                </div>
                <div class="ui teal labeled icon button btnNoteCustomer">
                    Skicka en anteckning
                    <i class="add icon"></i>
                </div>
            </div>
            <div class="five wide column">
                <div class="ui header">Anteckningar om tolkar:</div>
                <div class="ui horizontal divider"></div>
                <div class="ui segment" style="height: 400px; max-height: 400px; overflow-y: auto;">
                    <div class="ui feed interpreterFeed"></div>
                </div>
                <div class="ui teal labeled icon button btnNoteInterpreter">
                    Skicka en anteckning
                    <i class="add icon"></i>
                </div>
            </div>
            <div class="one wide column">
                <button type="button" class="ui inverted small blue disabled button" id="btnAddTolkComment">
                    Add
                </button>
            </div>
        </div>
    </div>
    <div class="ui small modal resultPostErrorModal">
        <div class="header">Fel</div>
        <div class="content">
            <div class="description">
                <p class="ui text"></p>
            </div>
        </div>
        <div class="actions">
            <div class="ui positive button">OK</div>
        </div>
    </div>
    <div class="ui small modal addMessageModal">
        <div class="header"></div>
        <div class="content">
            <div class="description">
                <form class="ui form noteTextForm">
                    <div class="field">
                        <label class="ui teal ribbon label" for="noteText"><i class="write icon"></i>Note:</label>
                        <textarea name="noteText" id="noteText"></textarea>
                    </div>
                </form>
            </div>
        </div>
        <div class="actions">
            <div class="ui black negative button">
                Avboka
            </div>
            <div class="ui positive right labeled icon button">
                Skicka
                <i class="checkmark icon"></i>
            </div>
        </div>
    </div>
    <div class="ui modal commentOnTolk firstStep">
        <i class="close icon"></i>
        <div class="header">
            Lägg till kommentarer om en tolk
        </div>
        <div class="content">
            <div class="description">
                <form class="ui form">
                    <fieldset>
                        <div class="ui centered grid">
                            <div class="four wide column computer only"></div>
                            <div class="eight wide column">
                                <div class="required field">
                                    <label for="tolkNumber">Tolkens nummer:</label>
                                    <input type="text" name="tolkNumber" id="tolkNumber"
                                           placeholder="XXXX"/>
                                </div>
                                <div class="ui error message">
                                    <i class="close icon"></i>

                                    <div class="header"></div>
                                    <div class="ui text"></div>
                                </div>
                            </div>
                            <div class="four wide column computer only"></div>
                        </div>
                        <div class="centered field">
                            <button type="button" class="ui blue button btnVerify">Verifiera</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="ui modal commentOnTolk secondStep">
        <i class="close icon"></i>
        <div class="header">
            Lägg till kommentarer om en tolk
        </div>
        <div class="content">
            <div class="description">
                <table class="ui unstackable celled striped table confirmedTolkTable">
                    <thead>
                    <tr>
                        <th colspan="3">
                            <div class="ui segment">
                                <div class="ui center aligned header">Tolkens uppgifter</div>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div class="ui header">Tolkens nummer:</div>
                        </td>
                        <td class="tolkInfoNumber">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui header">Tolkens namn:</div>
                        </td>
                        <td class="tolkInfoName">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui header">Tolkens e-post:</div>
                        </td>
                        <td class="tolkInfoEmail">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui header">Tolkens telefonnummer:</div>
                        </td>
                        <td class="tolkInfoTelephone">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui header">Tolkens mobilnummer:</div>
                        </td>
                        <td class="tolkInfoTelephone">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui header">Tolkens stad:</div>
                        </td>
                        <td class="tolkInfoCity">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="actions">
            <div class="ui orange button btnBack">Back</div>
            <div class="ui primary button btnContinue">Continue</div>
        </div>
    </div>
    <div class="ui modal commentOnTolk thirdStep">
        <i class="close icon"></i>
        <div class="header">
            Lägg till kommentarer om en tolk
        </div>
        <div class="content">
            <div class="description">
                <form class="ui form">
                    <fieldset>
                        <input type="hidden" name="tolkNumberComment" id="tolkNumberComment" />
                        <div class="field">
                            <label for="tolkKommentar">Kommentar:</label>
                            <textarea name="comment" id="tolkKommentar"></textarea>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="actions">
            <div class="ui orange button btnBack">Back</div>
            <div class="ui primary button btnPost">Post</div>
        </div>
    </div>

</div>
<script type="application/javascript" src="js/dash.js"></script>