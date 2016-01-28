<!--
* User: Samuil
* Date: 29-01-2015
* Time: 10:34 PM
-->
<div class="ui grid">
    <div class="centered seven wide column">
        <form class="ui form orderForm" onsubmit="return false;">
            <fieldset id="order">
                <h3>Beställ Tolk<br/>1. Uppdrag</h3>
                <input type="hidden" name="organizationNumber" value="<?php echo $organizationNumber; ?>">
                <input type="hidden" name="orderer" value="<?php echo $clientNumber; ?>">
                <input type="hidden" name="clientNumber" value="<?php echo $clientNumber; ?>">

                <div class="field">
                    <label for="client">Klient:</label>
                    <input id="client" name="client" type="text" placeholder="Klient" autofocus=""/>
                </div>
                <div class="required field">
                    <label for="language">Språk:</label>
                    <select id="language" name="language" class="ui search dropdown">
                        <option value=''>Språk</option>
                        <?php
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
                    <div class="required inline fields">
                        <label for="type">Typ av tolkning.</label>

                        <div class="ui segment">
                            <div class="ui grid">
                                <div class="two column row">
                                    <div class="left floated column">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="KT">Kontakttolkning</label>
                                                <input id="KT" type="radio" name="type" value="KT" checked>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="left floated column">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="TT">Telefontolkning</label>
                                                <input id="TT" type="radio" name="type" value="TT">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="two column row">
                                   <!-- <div class="left floated column">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="KP">Kontaktperson</label>
                                                <input id="KP" type="radio" name="type" value="KP">
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="column">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="SH">Studiehandledning</label>
                                                <input id="SH" type="radio" name="type" value="SH">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <label for="SS">Språkstöd</label>
                                                <input id="SS" type="radio" name="type" value="SS">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="NI" name="tolk_type"/>
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
                                <select title="Starttid" name="start_hour" id="starttid" class="ui fluid dropdown">
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
                                <select title="Starttid" name="start_minute" id="starttid1" class="ui fluid dropdown">
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
                                <select title="Sluttid" name="end_hour" id="sluttid" class="ui fluid dropdown">
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
                                <select title="Sluttid1" name="end_minute" id="sluttid1" class="ui fluid dropdown">
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
                    <input id="contactPerson" name="contactPerson" type="text" placeholder="Beställare"
                           value="<?php echo $customerInfo->k_firstName . " " . $customerInfo->k_lastName ?>"/>
                </div>
                <div class="required field">
                    <label for="organization">Företag/ Organisation:</label>
                    <input id="organization" name="organization" type="text" placeholder="Företag/ Organisation"
                           value="<?php echo $customerInfo->k_organizationName; ?>"/>
                </div>
                <div class="required field">
                    <label for="email">E-postadress:</label>
                    <input id="email" name="email" type="email" placeholder="E-post"
                           value="<?php echo $customerInfo->k_email; ?>"/>
                </div>
                <div class="two fields">
                    <div class="required field">
                        <label for="telephone">Telefon:</label>
                        <input id="telephone" name="telephone" type="text" placeholder="Telefon"
                               value="<?php echo $customerInfo->k_tel; ?>" class="phone-group"/>
                    </div>
                    <div class="required field">
                        <label for="telephone">Mobil:</label>
                        <input id="telephone" name="mobile" type="text" placeholder="Mobil"
                               value="<?php echo $customerInfo->k_mobile; ?>" class="phone-group"/>
                    </div>
                </div>
                <div class="required field">
                    <label for="address">Plats:</label>
                    <input id="address" name="address" type="text" placeholder="Plats"
                           value="<?php echo $customerInfo->k_address; ?>"/>
                </div>
                <div class="two fields">
                    <div class="required field">
                        <label for="post_code">Postnummer:</label>
                        <input id="post_code" name="post_code" type="text" placeholder="Postnummer"
                               value="<?php echo $customerInfo->k_zipCode; ?>"/>
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
                                    if ($row->c_cityName == $customerInfo->k_city) {
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
