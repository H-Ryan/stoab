<?php
/**
 * User: Samuil
 * Date: 17-04-2015
 * Time: 12:27 PM
 */

?>
    <div class="ui segment">

        <form id="newsLetterForm">
            <div class="ui grid">
                <div class="row">
                    <div class="ten wide centered column">
                        <div class="field">
                            <label for="newsTitle">Title:</label>
                            <input type="text" name="newsTitle" id="newsTitle"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="centered column">
                        <div class="field">
                            <textarea id="newsPrescript" maxlength="200">...</textarea>
                        </div>
                        <div class="row">
                            <div class="centered column">
                                <span id="chars"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="centered column">
                        <div class="field">
                            <div id="editPanel" style="width: 80%; margin-left: auto; margin-right: auto;"></div>
                            <div id="newsLetter">Do you have some news?</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="ui divider"></div>
                </div>
                <div class="two column row">
                    <div class="column">
                        <button type="button" class="ui orange button" id="btnPreviewNews">PREVIEW</button>
                    </div>
                    <div class="column">
                        <button type="button" class="ui blue button" id="btnPostNewsLetter">POST</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="ui small news modal">
        <i class="close icon"></i>

        <div class="header">
            Post news!
        </div>
        <div class="content">
            <div class="description">
            </div>
        </div>
        <div class="actions">
            <div class="centered row">
                <div class="column">
                    <div class="ui positive button">
                        OK
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui modal preview">
        <i class="close icon"></i>

        <div class="content">
            <div class='ui stacked segment' style='height: 400px; max-height: 400px; overflow: auto;'>
                <div class='ui header'>

                </div>
                <div id="newsContent">

                </div>
            </div>
        </div>
        <div class="actions">
            <div class="centered row">
                <div class="column">
                    <div class="ui positive button">
                        OK
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php