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
                        <input type="text" name="newsTitle" id="newsTitle" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="centered column">
                    <div class="field">
                        <label for="newsLetter"></label>
                        <textarea id="newsLetter" name="newsLetter">&lt;p&gt;Do you have some news?&lt;/p&gt;</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="ui divider"></div>
            </div>
            <div class="field">
                <div class="ui blue button" id="btnPostNewsLetter">POST</div>
            </div>
        </div>
    </form>
</div>
