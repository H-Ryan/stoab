<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 3:43 PM
 */
?>
<div class="ui piled segment">
    <div class="ui grid">
        <div class="centered row">
            <div class="column">
                <div class="ui grid">
                    <div class="row">
                        <?php if( $detect->isMobile()) { ?>
                        <div class="mobile only sixteen wide column">
                            <form class="ui form tolk-search">
                                <div class="fields">
                                    <div class="required three wide field">
                                        <label for="language">Språk:</label>
                                        <select id="language" name="language" class="ui search dropdown searchLanguage">
                                            <option value=''>Språk</option>
                                            <?php
                                            foreach($languages as $lang) {
                                                echo "<option value='" . $lang . "'>" . $lang . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="three wide field">
                                        <label for="city">Ort:</label>
                                        <select id="city" name="city" class="ui search dropdown searchCity">
                                            <option value=''>Ort</option>
                                            <?php
                                            foreach($cities as $city) {
                                                echo "<option value='" . $city . "'>" . $city . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="two wide field">
                                        <label for="state">Län:</label>
                                        <select id="state" name="state" class="ui search dropdown searchState">
                                            <option value=''>Län</option>
                                            <?php
                                            try {
                                                $states = ["Blekinge län", "Dalarnas län", "Gotlands län",
                                                    "Gävleborgs län", "Hallands län",
                                                    "Jämtlands län", "Jönköpings län",
                                                    "Kalmar län", "Kronobergs län",
                                                    "Norrbottens län", "Skåne län",
                                                    "Stockholms län", "Södermanlands län",
                                                    "Uppsala län", "Värmlands län",
                                                    "Västerbottens län", "Västernorrlands län",
                                                    "Västmanlands län", "Västra Götalands län",
                                                    "Örebro län", "Östergötlands län"];
                                                foreach ($states as $state) {
                                                    echo "<option value='" . $state . "'>" . $state . "</option>";
                                                }
                                            } catch (PDOException $e) {
                                                return $e->getMessage();
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="two wide field">
                                        <div class="ui horizontal divider">
                                            eller
                                        </div>
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkNum">Tolk nummer:</label>
                                        <input type="text" name="tolkNum" id="tolkNum"/>
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkName">Förnamn:</label>
                                        <input type="text" name="tolkFirstName" id="tolkName"/>
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkName">Efternamn:</label>
                                        <input type="text" name="tolkLastName" id="tolkName"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php } else if($detect->isTablet() ) { ?>
                        <div class="tablet only sixteen wide column">
                            <form class="ui form tolk-search">
                                <div class="three fields">
                                    <div class="required field">
                                        <label for="language">Språk:</label>
                                        <select id="language" name="language" class="ui search dropdown searchLanguage">
                                            <option value=''>Språk</option>
                                            <?php
                                            foreach($languages as $lang) {
                                                echo "<option value='" . $lang . "'>" . $lang . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label for="city">Ort:</label>
                                        <select id="city" name="city" class="ui search dropdown searchCity">
                                            <option value=''>Ort</option>
                                            <?php
                                            foreach($cities as $city) {
                                                echo "<option value='" . $city . "'>" . $city . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label for="state">Län:</label>
                                        <select id="state" name="state" class="ui search dropdown searchState">
                                            <option value=''>Län</option>
                                            <?php
                                            try {
                                                $states = ["Blekinge län", "Dalarnas län", "Gotlands län",
                                                    "Gävleborgs län", "Hallands län",
                                                    "Jämtlands län", "Jönköpings län",
                                                    "Kalmar län", "Kronobergs län",
                                                    "Norrbottens län", "Skåne län",
                                                    "Stockholms län", "Södermanlands län",
                                                    "Uppsala län", "Värmlands län",
                                                    "Västerbottens län", "Västernorrlands län",
                                                    "Västmanlands län", "Västra Götalands län",
                                                    "Örebro län", "Östergötlands län"];
                                                foreach ($states as $state) {
                                                    echo "<option value='" . $state . "'>" . $state . "</option>";
                                                }
                                            } catch (PDOException $e) {
                                                return $e->getMessage();
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div style="position: relative; height: 50px;">
                                        <div class="ui horizontal divider">
                                            eller
                                        </div>
                                    </div>
                                </div>
                                <div class="three fields">
                                    <div class="field">
                                        <label for="tolkNum">Tolk nummer:</label>
                                        <input type="text" name="tolkNum" id="tolkNum"/>
                                    </div>
                                    <div class="field">
                                        <label for="tolkName">Förnamn:</label>
                                        <input type="text" name="tolkFirstName" id="tolkName"/>
                                    </div>
                                    <div class="field">
                                        <label for="tolkName">Efternamn:</label>
                                        <input type="text" name="tolkLastName" id="tolkName"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php } else { ?>
                        <div class="computer only sixteen wide column">
                            <form class="ui form tolk-search">
                                <div class="fields">
                                    <div class="required three wide field">
                                        <label for="language">Språk:</label>
                                        <select id="language" name="language"
                                                class="ui fluid search dropdown searchLanguage">
                                            <option value=''>Språk</option>
                                            <?php
                                            foreach($languages as $lang) {
                                                echo "<option value='" . $lang . "'>" . $lang . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="three wide field">
                                        <label for="city">Ort:</label>
                                        <select id="city" name="city" class="ui fluid search dropdown searchCity">
                                            <option value=''>Ort</option>
                                            <?php
                                            foreach($cities as $city) {
                                                echo "<option value='" . $city . "'>" . $city . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="three wide field">
                                        <label for="state">Län:</label>
                                        <select id="state" name="state" class="ui fluid search dropdown searchState">
                                            <option value=''>Län</option>
                                            <?php
                                            try {
                                                $states = ["Blekinge län", "Dalarnas län", "Gotlands län",
                                                    "Gävleborgs län", "Hallands län",
                                                    "Jämtlands län", "Jönköpings län",
                                                    "Kalmar län", "Kronobergs län",
                                                    "Norrbottens län", "Skåne län",
                                                    "Stockholms län", "Södermanlands län",
                                                    "Uppsala län", "Värmlands län",
                                                    "Västerbottens län", "Västernorrlands län",
                                                    "Västmanlands län", "Västra Götalands län",
                                                    "Örebro län", "Östergötlands län"];
                                                foreach ($states as $state) {
                                                    echo "<option value='" . $state . "'>" . $state . "</option>";
                                                }
                                            } catch (PDOException $e) {
                                                return $e->getMessage();
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="one wide field">
                                        <div style="position: relative; height: 50px;">
                                            <div class="ui vertical divider">
                                                eller
                                            </div>
                                        </div>
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkNum">Tolk num:</label>
                                        <input type="text" name="tolkNum" id="tolkNum"/>
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkName">Förnamn:</label>
                                        <input type="text" name="tolkFirstName" id="tolkName"/>
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkName">Efternamn:</label>
                                        <input type="text" name="tolkLastName" id="tolkName"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="centered column">
                            <button type="button" class="ui inverted blue big button btnSearchTolk">Sök</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui basic fluid segment searchTolkResult">
        <div class="ui grid">
            <div class="row">
                <?php  if( $detect->isMobile() || $detect->isTablet() ){ ?>
                <div class="mobile tablet only sixteen wide column">
                    <div class="tolks" style="overflow-x: scroll; overflow-y: hidden;">
                        <table class='ui collapsing unstackable striped celled table tolksTable' style="display: none;">
                            <thead>
                            <tr>
                                <th class='one wide'>Nummer</th>
                                <th class='three wide'>Namn</th>
                                <th class='two wide'>Län</th>
                                <th class='one wide'>Stad</th>
                                <th class='one wide'>Kön</th>
                                <th class='two wide'>Nivå</th>
                                <th class='two wide'>Rankning</th>
                                <th class='two wide'>E-post</th>
                                <th class='one wide'>Mobil</th>
                                <th class='one wide'>Info</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } else { ?>
                <div class="computer only sixteen wide column">
                    <div class="tolks">
                        <table class='ui collapsing unstackable striped celled table tolksTable' style="display: none;">
                            <thead>
                            <tr>
                                <th class='one wide'>Nummer</th>
                                <th class='three wide'>Namn</th>
                                <th class='two wide'>Län</th>
                                <th class='one wide'>Stad</th>
                                <th class='one wide'>Kön</th>
                                <th class='two wide'>Nivå</th>
                                <th class='two wide'>Rankning</th>
                                <th class='two wide'>E-post</th>
                                <th class='one wide'>Mobil</th>
                                <th class='one wide'>Info</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

    </div>
</div>
<div class="ui fullscreen long modal tolkMoreInfoModal">
    <div class="center aligned header">
        Mer Info
    </div>
    <div class="content">
        <div class="small image">
            <i class="info circle icon"></i>
        </div>
        <div class="description">
            <table class="ui unstackable celled table tolkExtraInfo">
                <thead>
                <tr>
                    <th>Språk</th>
                    <th>Rate</th>
                    <th>Customer rate</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <p class="ui header">Current Assignments:</p>
            <table class="ui unstackable celled table tolkExtraInfoOrder">
                <thead>
                <tr>
                    <th class="one wide">Ordernummer</th>
                    <th class="three wide">Avdelning</th>
                    <th class="three wide">Beställare</th>
                    <th class="three wide">Språk</th>
                    <th class="one wide">Typ</th>
                    <th class="two wide">Datum</th>
                    <th class="one wide">Starttid</th>
                    <th class="one wide">Sluttid</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="actions">
        <div class="ui green ok button">
            <i class="checkmark icon"></i>OK
        </div>
    </div>
</div>
