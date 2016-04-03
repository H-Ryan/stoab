<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 3:45 PM
 */
?>
<div class="ui piled segment">
    <div class="ui grid">
        <div class="centered row">
            <div class="seven wide computer sixteen wide mobile ten wide tablet column">
                <form class="ui form orderForm">
                    <fieldset>
                        <h3>Beställ Tolk<br/>1. Uppdrag</h3>
                        <input type="hidden" name="organizationNumber" value="0000000000">
                        <input type="hidden" name="clientNumber" value="100000">
                        <input type="hidden" name="orderer" value="<?php echo $_SESSION['personal_number'] ?>"/>
                        <div class="field">
                            <label for="client">Klient:</label>
                            <input id="client" name="client" type="text" placeholder="Klient" autofocus=""/>
                        </div>
                        <div class="field">
                            <label for="language">Språk:</label>
                            <div class="ui fluid search selection dropdown">
                                <input id="language" type="hidden" name="language">
                                <i class="dropdown icon"></i>
                                <div class="default text">Språk</div>
                                <div class="menu">
                                    <?php
                                    foreach ($languages as $id => $lang) {
                                        echo "<div class=\"item\" data-value=\"$id\">$lang</div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <label for="type">Typ av tolkning.</label>
                                <div class="ui segment">
                                    <div class="grouped fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input id="KT" type="radio" name="type" value="KT"
                                                       tabindex="0" class="hidden">
                                                <label for="KT">Kontakttolkning</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input id="TT" type="radio" name="type" value="TT" tabindex="0"
                                                       class="hidden">
                                                <label for="TT">Telefontolkning</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="KP">Kontaktperson</label>
                                                <input id="KP" type="radio" name="type" value="KP" tabindex="0"
                                                       class="hidden">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="SH">Studiehandledning</label>
                                                <input id="SH" type="radio" name="type" value="SH" tabindex="0"
                                                       class="hidden">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="SS">Språkstöd</label>
                                                <input id="SS" type="radio" name="type" value="SS" tabindex="0"
                                                       class="hidden">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label for="tolk-type">Tolk nivå</label>
                                <div class="ui segment">
                                    <div class="grouped fields">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox tolk-type">
                                                    <label for="ll">ÖT</label>
                                                    <input id="ll" value="ÖT" type="radio" name="tolk_type"
                                                           tabindex="0" class="hidden">
                                                </div>
                                                <div class="ui popup"><span>Övriga Tolk</span></div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox tolk-type">
                                                    <label for="gg">GT</label>
                                                    <input id="gg" value="GT" type="radio" name="tolk_type"
                                                           tabindex="0" class="hidden">
                                                </div>
                                                <div class="ui popup"><span>Godkänd Tolk</span></div>
                                            </div>
                                        </div>
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox tolk-type">
                                                    <label for="hh">AT</label>
                                                    <input id="hh" value="AT" type="radio" name="tolk_type"
                                                           tabindex="0" class="hidden">
                                                </div>
                                                <div class="ui popup"><span>Auktoriserad Tolk</span></div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox tolk-type">
                                                    <label for="jj">ST</label>
                                                    <input id="jj" value="ST" type="radio" name="tolk_type"
                                                           tabindex="0" class="hidden">
                                                </div>
                                                <div class="ui popup"><span>Sjukvårdstolk</span></div>
                                            </div>
                                        </div>
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox tolk-type">
                                                    <label for="kk">RT</label>
                                                    <input id="kk" value="RT" type="radio" name="tolk_type"
                                                           class="hidden">
                                                </div>
                                                <div class="ui popup"><span>Rättstolk</span></div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox tolk-type checked">
                                                    <label for="ff">NI</label>
                                                    <input id="ff" value="NI" checked type="radio" name="tolk_type"
                                                           class="hidden">
                                                </div>
                                                <div class="ui popup"><span>Inte viktigt</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="date">Datum</label>
                            <input id="date" type="text" title="Datum" name="date" placeholder="YYYY-MM-DD"/>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <label for="startTime">Starttid</label>

                                <div id="startTime" class="two fields">
                                    <div class="field">
                                        <select class="ui fluid search selection dropdown" id="starttid"
                                                name="start_hour">
                                            <?php
                                            for ($i = 0; $i < 3; $i++) {
                                                for ($j = 0; $j < 10; $j++) {
                                                    if ($i == 2 && $j == 4) {
                                                        break;
                                                    } elseif ($i == 1 && $j == 2) {
                                                        echo "<option value=\"" . intval($i . $j) . "\">$i$j</option>";
                                                    } else {
                                                        echo "<option value=\"" . intval($i . $j) . "\">$i$j</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <select name="start_minute" id="starttid1"
                                                class="ui fluid dropdown">
                                            <option value="0">00</option>
                                            <option value="1">15</option>
                                            <option value="2">30</option>
                                            <option value="3">45</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label for="endTime">Sluttid</label>

                                <div id="endTime" class="two fields">
                                    <div class="field">
                                        <select class="ui fluid search selection dropdown" id="sluttid" name="end_hour">
                                            <?php
                                            for ($i = 0; $i < 3; $i++) {
                                                for ($j = 0; $j < 10; $j++) {
                                                    if ($i == 2 && $j == 4) {
                                                        break;
                                                    } elseif ($i == 1 && $j == 3) {
                                                        echo "<option value=\"" . intval($i . $j) . "\">$i$j</option>";
                                                    } else {
                                                        echo "<option value=\"" . intval($i . $j) . "\">$i$j</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <select name="end_minute" id="sluttid1"
                                                class="ui fluid dropdown">
                                            <option value="0" selected="selected">00</option>
                                            <option value="1">15</option>
                                            <option value="2">30</option>
                                            <option value="3">45</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <button type="button"
                                        class="ui reset red labeled icon button reset-btn">
                                    Reset <i class="left close icon"></i>
                                </button>
                            </div>
                            <div class="field">
                                <button type="button"
                                        class="ui blue right labeled icon button next-btn">
                                    <i class="right arrow icon"></i>
                                    Nästa
                                </button>
                            </div>
                        </div>

                    </fieldset>
                    <fieldset id="customer">
                        <h3>Beställ Tolk<br/>2. Kontaktperson / Fakturering</h3>

                        <div class="required field">
                            <label for="contactPerson">Beställare:</label>
                            <input id="contactPerson" name="contactPerson" type="text" placeholder="Beställare"/>
                        </div>
                        <div class="field">
                            <label for="regOrganization">Registrerade organisationen:</label>
                            <select id="regOrganization" name="regOrganization"
                                    class="ui fluid search selection dropdown regOrganization">
                                <option selected value=''>Organisation</option>
                                <?php
                                foreach ($kundOrgNums as $kundNum => $orgNam) {
                                    echo "<option value='" . $kundNum . "'>" . $orgNam . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="required field">
                            <label for="organization">Företag/ Organisation:</label>
                            <input id="organization" name="organization" type="text"
                                   placeholder="Företag/ Organisation"/>
                        </div>
                        <div class="required field">
                            <label for="email">E-postadress:</label>
                            <input id="email" name="email" type="email" placeholder="E-post"/>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <label for="telephone">Telefon:</label>
                                <input id="telephone" name="telephone" type="text" placeholder="Telefon"
                                       class="phone-group"/>
                            </div>
                            <div class="field">
                                <label for="telephone">Mobil:</label>
                                <input id="telephone" name="mobile" type="text" placeholder="Mobil"
                                       class="phone-group"/>
                            </div>
                        </div>
                        <div class="required field">
                            <label for="address">Plats för tolkning:</label>
                            <input id="address" name="address" type="text" placeholder="Plats"/>
                        </div>
                        <div class="two fields">
                            <div class="required field">
                                <label for="post_code">Postnummer:</label>
                                <input id="post_code" name="post_code" type="text" placeholder="Postnummer"/>
                            </div>
                            <div class="required field">
                                <label for="city">Ort:</label>
                                <select id="city" name="city" class="ui fluid search selection dropdown">
                                    <option value=''>Ort</option>
                                    <?php
                                    foreach ($cities as $city) {
                                        echo "<option value='" . $city . "'>" . $city . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="three fields">
                            <div class="field">
                                <button type="button"
                                        class="ui blue orange labeled icon button back-btn">
                                    Tillbaka <i class="left arrow icon"></i>
                                </button>
                            </div>
                            <div class="field">
                                <button type="button"
                                        class="ui reset red labeled icon button reset-btn">
                                    Reset <i class="left close icon"></i>
                                </button>
                            </div>
                            <div class="field">
                                <button type="button"
                                        class="ui blue right labeled icon button next-btn">
                                    <i class="right arrow icon"></i>
                                    Nästa
                                </button>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset id="comment">
                        <h3>Beställ Tolk<br/>3. Skicka</h3>

                        <div class="field">
                            <label for="message">Kommentar</label>
                            <textarea id="message" name="message"></textarea>
                        </div>
                        <div class="ui error message" id="orderErrorField">
                            <div class="header">Fel</div>
                            <p id="orderErrorMessage">Några av de former åkrar är ogiltiga.</p>
                        </div>
                        <div class="three fields">
                            <div class="field">
                                <button type="button"
                                        class="ui blue orange labeled icon button back-btn">
                                    Tillbaka <i class="left arrow icon"></i>
                                </button>
                            </div>
                            <div class="field">
                                <button type="button"
                                        class="ui reset red labeled icon button reset-btn">
                                    Reset <i class="left close icon"></i>
                                </button>
                            </div>
                            <div class="field">
                                <button type="button"
                                        class="ui blue right labeled icon button order-btn">
                                    <i class="right arrow icon"></i> Boka
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
