<div class="ui modal reporting">
    <div class="ui inverted blue segment">
        <div class="white header">Rapportering för ordning: <span></span></div>
    </div>
    <div class="content">
        <div class="ui grid">
            <div class="two column row">
                <div class="column">
                    <div class="ui segment">
                        <div class="ui list">
                            <div class="item">
                                <div class="ui header">Beställare</div>
                                <span id="ordererValue"></span>
                                <div class="ui divider"></div>
                            </div>
                            <div class="item">
                                <div class="ui header">Klient</div>
                                <span id="clientValue"></span>
                                <div class="ui divider"></div>
                            </div>
                            <div class="item">
                                <div class="ui header">Språk</div>
                                <span id="languageValue"></span>
                                <div class="ui divider"></div>
                            </div>
                            <div class="item">
                                <div class="ui header">Typ</div>
                                <span id="typeValue"></span>
                                <div class="ui divider"></div>
                            </div>
                            <div class="item">
                                <div class="ui header">Gatuadress</div>
                                <span id="addressValue"></span>
                                <div class="ui divider"></div>
                            </div>
                            <div class="item">
                                <div class="ui header">Ort, postnummer</div>
                                <span id="cityZipValue"></span>
                                <div class="ui divider"></div>
                            </div>
                            <div class="item">
                                <div class="ui header">Datum</div>
                                <span id="dateValue"></span>
                                <div class="ui divider"></div>
                            </div>
                            <div class="item">
                                <div class="ui header">Starttid - Sluttid</div>
                                <span id="startEndTime"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="ui segment">
                        <form class="ui form" id="reportOrderForm">
                            <input type="hidden" name="rep_mission_id" id="rep_mission_id" value="">
                            <input type="hidden" name="tolk_number" value="<?php echo $_SESSION['tolk_number']; ?>">
                            <div class="two fields">
                                <div class="field">
                                    <label for="rep_extra_time">Extra tid</label>
                                    <select id="rep_extra_time" name="rep_extra_time" class="ui fluid dropdown">
                                        <option value="0">00:00</option>
                                        <option value="1">00:15</option>
                                        <option value="2">00:30</option>
                                        <option value="3">00:45</option>
                                        <option value="4">01:00</option>
                                        <option value="5">01:15</option>
                                        <option value="6">01:30</option>
                                        <option value="7">01:45</option>
                                        <option value="8">02:00</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="rep_outlay">Utlägg</label>
                                    <select id="rep_outlay" name="rep_outlay" class="ui fluid dropdown repOutlay">
                                        <option selected value="0">Ingen utlägg</option>
                                        <option value="1">Egen bil</option>
                                        <option value="2">Kollektivtrafik</option>
                                    </select>
                                </div>
                            </div>
                            <label for="rep_travel_time">Restid</label>
                            <div id="rep_travel_time" class="two fields">
                                <div class="field">
                                    <label for="rep_hours">Timmar</label>
                                    <select name="rep_hours" id="rep_hours" class="ui fluid dropdown">
                                        <?php
                                        for ($i = 0; $i < 3; $i++) {
                                            for ($j = 0; $j < 10; $j++) {
                                                if ($i == 2 && $j == 4) {
                                                    break;
                                                } elseif ($i == 0 && $j == 0) {
                                                    echo "<option selected='selected' value='" . intval($i . $j) . "'>$i$j</option>";
                                                } else {
                                                    echo "<option value='" . intval($i . $j) . "'>$i$j</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="rep_minutes">Minuter</label>
                                    <select name="rep_minutes" id="rep_minutes" class="ui fluid dropdown">
                                        <?php
                                        $interv = 5;
                                        for ($i = 0; $i < 12; $i++) {
                                            if ($i == 0) {
                                                echo "<option selected='selected' value='" . intval($i) . "'>00</option>";
                                            } else if ($i == 1) {
                                                echo "<option value='" . intval($i) . "'>05</option>";
                                            } else {
                                                echo "<option value='" . intval($i) . "'>" . $i * $interv . "</option>";
                                            }

                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="two fields">
                                <div class="field">
                                    <label for="rep_mileage">Miltal</label>
                                    <div class="ui right labeled input">
                                        <input type="number" id="rep_mileage" name="rep_mileage" min="0" max="65535">
                                        <div class="ui basic label">
                                            km
                                        </div>
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="rep_ticket_cost">Biljett Kostnad</label>
                                    <div class="ui right labeled input">
                                        <input type="number" id="rep_ticket_cost" name="rep_ticket_cost" min="0"
                                               max="9999">
                                        <div class="ui basic label">
                                            SEK
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label for="rep_customer_name">Kundens namn</label>
                                <input type="text" maxlength="45" id="rep_customer_name" name="rep_customer_name">
                            </div>
                            <div class="field">
                                <label for="rep_comments">Kommentarer</label>
                                <textarea id="rep_comments" maxlength="250" name="rep_comments" rows="5"></textarea>
                            </div>
                            <div class="ui error message" id="reportError">
                                <div class="header">Fel</div>
                                <p>Det uppstod ett fel skickar din rapport!</p>
                            </div>
                            <div class="field">
                                <button type="button" id="rep_submit_btn" class="ui primary right labeled icon button">
                                    Skicka<i class="send icon"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="actions center aligned">
        <div class="ui negative right labeled icon button">
            Stänga <i class="close icon"></i>
        </div>
    </div>
</div>