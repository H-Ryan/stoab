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
                    <fieldset id="order">
                        <h3>Beställ Tolk<br/>1. Uppdrag</h3>
                        <input type="hidden" name="organizationNumber" value="0000000000">
                        <input type="hidden" name="clientNumber" value="100000">

                        <div class="field">
                            <label for="client">Klient:</label>
                            <input id="client" name="client" type="text" placeholder="Klient" autofocus=""/>
                        </div>
                        <div class="required field">
                            <label for="language">Språk:</label>
                            <select id="language" name="language" class="ui search dropdown">
                                <option selected value=''>Språk</option>
                                <<?php
                                try {
                                    $statement = $con->query("SELECT * FROM t_languages ORDER BY l_languageName");
                                    $statement->setFetchMode(PDO::FETCH_OBJ);
                                    while ($row = $statement->fetch()) {
                                        echo "<option value='" . $row->l_languageID . "'>" . $row->l_languageName . "</option>";
                                    }
                                } catch (PDOException $e) {
                                    return $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <div class="ui basic segment">
                            <div class="two fields">
                                <div class="required field">
                                    <label for="type">Typ av tolkning.</label>
                                    <div class="ui segment">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="KT">Kontakttolkning</label>
                                                <input id="KT" type="radio" name="type" value="KT" checked>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="TT">Telefontolkning</label>
                                                <input id="TT" type="radio" name="type" value="TT">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="KP">Kontaktperson</label>
                                                <input id="KP" type="radio" name="type" value="KP">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="SH">Studiehandledning</label>
                                                <input id="SH" type="radio" name="type" value="SH">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="SS">Språkstöd</label>
                                                <input id="SS" type="radio" name="type" value="SS">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="required field">
                                    <label for="tolk-type">Tolk nivå</label>
                                    <div class="ui segment">
                                        <div class="grouped fields">
                                            <div class="inline fields">
                                                <div class="field">
                                                    <div class="ui radio checkbox tolk-type">
                                                        <label for="ll">ÖT</label>
                                                        <input id="ll" value="ÖT" type="radio" name="tolk_type">
                                                    </div>
                                                    <div class="ui popup"><span>Övriga Tolk</span></div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui radio checkbox tolk-type">
                                                        <label for="gg">GT</label>
                                                        <input id="gg" value="GT" type="radio" name="tolk_type">
                                                    </div>
                                                    <div class="ui popup"><span>Godkänd Tolk</span></div>
                                                </div>
                                            </div>
                                            <div class="inline fields">
                                                <div class="field">
                                                    <div class="ui radio checkbox tolk-type">
                                                        <label for="hh">AT</label>
                                                        <input id="hh" value="AT" type="radio" name="tolk_type">
                                                    </div>
                                                    <div class="ui popup"><span>Auktoriserad Tolk</span></div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui radio checkbox tolk-type">
                                                        <label for="jj">ST</label>
                                                        <input id="jj" value="ST" type="radio" name="tolk_type">
                                                    </div>
                                                    <div class="ui popup"><span>Sjukvårdstolk</span></div>
                                                </div>
                                            </div>
                                            <div class="inline fields">
                                                <div class="field">
                                                    <div class="ui radio checkbox tolk-type">
                                                        <label for="kk">RT</label>
                                                        <input id="kk" value="RT" type="radio" name="tolk_type">
                                                    </div>
                                                    <div class="ui popup"><span>Rättstolk</span></div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui radio checkbox tolk-type">
                                                        <label for="ff">NI</label>
                                                        <input id="ff" value="NI" type="radio" name="tolk_type" checked>
                                                    </div>
                                                    <div class="ui popup"><span>Inte viktigt</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="required field">
                            <label for="date">Datum</label>
                            <input id="date" type="text" title="Datum" name="date" placeholder="YYYY-MM-DD"
                                   value="<?php echo date("Y-m-d"); ?>"/>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <label for="startTime">Starttid</label>

                                <div id="startTime" class="two fields">
                                    <div class="field">
                                        <select title="Starttid" name="start_hour" id="starttid" class="ui search fluid dropdown">
                                            <?php
                                            for ($i = 0; $i < 3; $i++) {
                                                for ($j = 0; $j < 10; $j++) {
                                                    if ($i == 2 && $j == 4)
                                                        break;
                                                    elseif ($i == 1 && $j == 2)
                                                        echo "<option selected='selected' value='" . intval($i . $j) . "'>$i$j</option>";
                                                    else
                                                        echo "<option value='" . intval($i . $j) . "'>$i$j</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <select title="Starttid" name="start_minute" id="starttid1" class="ui search fluid dropdown">
                                            <option value="0" selected>00</option>
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
                                        <select title="Sluttid" name="end_hour" id="sluttid" class="ui search fluid dropdown">
                                            <?php
                                            for ($i = 0; $i < 3; $i++) {
                                                for ($j = 0; $j < 10; $j++) {
                                                    if ($i == 2 && $j == 4)
                                                        break;
                                                    elseif ($i == 1 && $j == 2)
                                                        echo "<option selected='selected' value='" . intval($i . $j) . "'>$i$j</option>";
                                                    else
                                                        echo "<option value='" . intval($i . $j) . "'>$i$j</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <select title="Sluttid1" name="end_minute" id="sluttid1" class="ui search fluid dropdown">
                                            <option value="0">00</option>
                                            <option value="1" selected>15</option>
                                            <option value="2">30</option>
                                            <option value="3">45</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <button type="button"
                                    class="ui blue right labeled icon button next-btn">
                                <i class="right arrow icon"></i>
                                Nästa
                            </button>
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
                            <select id="regOrganization" name="regOrganization" class="ui search dropdown regOrganization">
                                <option selected value=''>Organisation</option>
                                <<?php
                                try {
                                    $statement = $con->query("SELECT DISTINCT k_organizationName, k_kundNumber FROM t_kunder");
                                    $statement->setFetchMode(PDO::FETCH_OBJ);
                                    while ($row = $statement->fetch()) {
                                        echo "<option value='" . $row->k_kundNumber . "'>" . $row->k_organizationName . "</option>";
                                    }
                                } catch (PDOException $e) {
                                    return $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <div class="required field">
                            <label for="organization">Företag/ Organisation:</label>
                            <input id="organization" name="organization" type="text" placeholder="Företag/ Organisation"/>
                        </div>
                        <div class="required field">
                            <label for="email">E-postadress:</label>
                            <input id="email" name="email" type="email" placeholder="E-post"/>
                        </div>
                        <div class="two fields">
                            <div class="required field">
                                <label for="telephone">Telefon:</label>
                                <input id="telephone" name="telephone" type="text" placeholder="Telefon" class="phone-group"/>
                            </div>
                            <div class="required field">
                                <label for="telephone">Mobil:</label>
                                <input id="telephone" name="mobile" type="text" placeholder="Mobil" class="phone-group"/>
                            </div>
                        </div>
                        <div class="required field">
                            <label for="address">Plats:</label>
                            <input id="address" name="address" type="text" placeholder="Plats"/>
                        </div>
                        <div class="two fields">
                            <div class="required field">
                                <label for="post_code">Postnummer:</label>
                                <input id="post_code" name="post_code" type="text" placeholder="Postnummer"/>
                            </div>
                            <div class="required field">
                                <label for="city">Ort:</label>
                                <select id="city" name="city" class="ui search dropdown">
                                    <option value=''>Ort</option>
                                    <?php
                                    try {
                                        $statement = $con->query("SELECT * FROM t_city ORDER BY c_cityName");
                                        $statement->setFetchMode(PDO::FETCH_OBJ);
                                        while ($row = $statement->fetch()) {
                                            if ($row->c_cityName === "Hässleholm") {
                                                echo "<option value='" . $row->c_cityName . "' selected>" . $row->c_cityName . "</option>";
                                            } else {
                                                echo "<option value='" . $row->c_cityName . "'>" . $row->c_cityName . "</option>";
                                            }
                                        }
                                    } catch (PDOException $e) {
                                        return $e->getMessage();
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <button type="button"
                                        class="ui blue orange labeled icon button back-btn">
                                    Tillbaka <i class="left arrow icon"></i>
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
                        <div class="two fields">
                            <div class="field">
                                <button type="button"
                                        class="ui blue orange labeled icon button back-btn">
                                    Tillbaka <i class="left arrow icon"></i>
                                </button>
                            </div>
                            <div class="field">
                                <button type="button"
                                        class="ui blue right labeled icon button order-btn">
                                    <i class="right arrow icon"></i> Boka
                                </button>
                            </div>
                        </div>
                        <div class="ui error message">
                            <div class="header">Fel</div>
                            <p>Några av de former åkrar är ogiltiga.</p>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
