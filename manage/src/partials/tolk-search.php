<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 3:43 PM
 */
?>
<div class="ui piled segment">
    <div class="ui grid">
        <div class="row">
            <div class="column">
                <div class="ui grid">
                    <div class="row">
                        <div class="computer only sixteen wide column">
                            <form class="ui form tolk-search">
                                <div class="fields">
                                    <div class="required three wide field">
                                        <label for="language">Språk:</label>
                                        <select id="language" name="language" class="ui search dropdown searchLanguage">
                                            <option value=''>Språk</option>
                                            <<?php
                                            try {
                                                $statement = $con->query("SELECT * FROM t_languages ORDER BY l_languageName");
                                                $statement->setFetchMode(PDO::FETCH_OBJ);
                                                while ($row = $statement->fetch()) {
                                                    echo "<option value='" . $row->l_languageName . "'>" . $row->l_languageName . "</option>";
                                                }
                                            } catch (PDOException $e) {
                                                return $e->getMessage();
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="three wide field">
                                        <label for="city">Ort:</label>
                                        <select id="city" name="city" class="ui search dropdown searchCity">
                                            <option value=''>Ort</option>
                                            <?php
                                            try {
                                                $statement = $con->query("SELECT * FROM t_city ORDER BY c_cityName");
                                                $statement->setFetchMode(PDO::FETCH_OBJ);
                                                while ($row = $statement->fetch()) {
                                                    echo "<option value='" . $row->c_cityName . "'>" . $row->c_cityName . "</option>";
                                                }
                                            } catch (PDOException $e) {
                                                return $e->getMessage();
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="three wide field">
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
                                    <div class="one wide field">
                                        <div style="position: relative; height: 50px;">
                                            <div class="ui vertical divider">
                                                eller
                                            </div>
                                        </div>
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkNum">Tolk nummer:</label>
                                        <input type="text" name="tolkNum" id="tolkNum" />
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkName">Förnamn:</label>
                                        <input type="text" name="tolkFirstName" id="tolkName" />
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkName">Efternamn:</label>
                                        <input type="text" name="tolkLastName" id="tolkName" />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mobile only sixteen wide column">
                            <form class="ui form tolk-search">
                                <div class="fields">
                                    <div class="required three wide field">
                                        <label for="language">Språk:</label>
                                        <select id="language" name="language" class="ui search dropdown searchLanguage">
                                            <option value=''>Språk</option>
                                            <<?php
                                            try {
                                                $statement = $con->query("SELECT * FROM t_languages ORDER BY l_languageName");
                                                $statement->setFetchMode(PDO::FETCH_OBJ);
                                                while ($row = $statement->fetch()) {
                                                    echo "<option value='" . $row->l_languageName . "'>" . $row->l_languageName . "</option>";
                                                }
                                            } catch (PDOException $e) {
                                                return $e->getMessage();
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="three wide field">
                                        <label for="city">Ort:</label>
                                        <select id="city" name="city" class="ui search dropdown searchCity">
                                            <option value=''>Ort</option>
                                            <?php
                                            try {
                                                $statement = $con->query("SELECT * FROM t_city ORDER BY c_cityName");
                                                $statement->setFetchMode(PDO::FETCH_OBJ);
                                                while ($row = $statement->fetch()) {
                                                    echo "<option value='" . $row->c_cityName . "'>" . $row->c_cityName . "</option>";
                                                }
                                            } catch (PDOException $e) {
                                                return $e->getMessage();
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
                                        <input type="text" name="tolkNum" id="tolkNum" />
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkName">Förnamn:</label>
                                        <input type="text" name="tolkFirstName" id="tolkName" />
                                    </div>
                                    <div class="two wide field">
                                        <label for="tolkName">Efternamn:</label>
                                        <input type="text" name="tolkLastName" id="tolkName" />
                                    </div>
                                </div>
                            </form>
                        </div>
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
                <div class="mobile only sixteen wide column">
                    <div class="tolks" style="overflow-x: scroll; overflow-y: hidden;">

                    </div>
                </div>
                <div class="computer only sixteen wide column">
                    <div class="tolks">

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
