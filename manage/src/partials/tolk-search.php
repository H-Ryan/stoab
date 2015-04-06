<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 3:43 PM
 */
?>
<form class="ui form tolk-search">
    <fieldset class="ui basic segment">
        <div class="four fields">
            <div class="required field">
                <label for="language">Språk:</label>
                <select id="language" name="language" class="ui search dropdown">
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
            <div class="field">
                <label for="city">Ort:</label>
                <select id="city" name="city" class="ui search dropdown">
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
            <div class="field">
                <label for="state">Län:</label>
                <select id="state" name="state" class="ui search dropdown">
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
            <div class="field">
                <div class="ui one column stackable center aligned page grid">
                    <div class="column">
                        <button type="button" class="ui inverted blue big button btnSearchTolk">Sök</button>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</form>
<div class="ui basic fluid segment tolks">
</div>