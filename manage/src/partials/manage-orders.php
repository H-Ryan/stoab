<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 3:45 PM
 */
$num = $con->query("SELECT COUNT(*) AS id FROM t_order WHERE o_date >= CURRENT_DATE OR ((DATE_ADD(o_date, INTERVAL +1 DAY)) = CURRENT_DATE AND TIMESTAMP(DATE_ADD(o_date, INTERVAL +1 DAY), '08:15:00') > NOW())")->fetchColumn();
$statement = $con->prepare("SELECT o_orderNumber, o_kundNumber,  o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state, o_comments FROM t_order WHERE o_date >= CURRENT_DATE OR ((DATE_ADD(o_date, INTERVAL +1 DAY)) = CURRENT_DATE AND TIMESTAMP(DATE_ADD(o_date, INTERVAL +1 DAY), '08:15:00') > NOW()) ORDER BY o_date ASC LIMIT 10");
$statement->execute();
$statement->setFetchMode(PDO::FETCH_OBJ);
$orders = array();
$klient = array();
if ($statement->rowCount() > 0) {
    $i = 0;
    while ($order = $statement->fetch()) {
        $statementTwo = $con->prepare("SELECT k_organizationName FROM t_kunder WHERE k_kundNumber=:clientNumber");
        $statementTwo->bindParam(":clientNumber", $order->o_kundNumber);
        $statementTwo->execute();
        $statementTwo->setFetchMode(PDO::FETCH_OBJ);
        if ($statementTwo->rowCount() > 0) {
            $klient[$i] = $statementTwo->fetch();
            $orders[$i] = $order;
            $i++;
        }
    }
}
?>
<div class="ui piled segment dimmable">
    <div class="ui inverted dimmer manageDim">
        <div class="content">
            <div class="center">
                <div class="ui text loader">Loading</div>
            </div>
        </div>
    </div>
    <div class="ui grid">
        <div class="row">
            <<?php if ($detect->isMobile() || $detect->isTablet()) { ?>
                <div class="mobile tablet only sixteen wide column">
                    <form class="ui form orderFilterFormManage">
                        <div class="field">
                            <label for="orderNumber">Ordernummer:</label>
                            <input name="orderNumber" id="orderNumber"/>
                        </div>
                        <div class="field">
                            <label for="clientNumber">Organisation:
                            </label>
                            <select id="clientNumber" name="clientNumber" class="ui search dropdown">
                                <option selected value=''>Organisation</option>
                                <?php
                                foreach ($kundOrgNums as $kundNum => $orgNam) {
                                    echo "<option value='" . $kundNum . "'>" . $orgNam . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                </div>

            <?php } else { ?>
                <div class="computer only eight wide centered column">
                    <form class="ui form orderFilterFormManage">
                        <div class="two fields">
                            <div class="field">
                                <label for="orderNumber">Ordernummer:</label>
                                <input name="orderNumber" id="orderNumber"/>
                            </div>
                            <div class="field">
                                <label for="clientNumber">Organisation:
                                </label>
                                <select id="clientNumber" name="clientNumber" class="ui search dropdown">
                                    <option selected value=''>Organisation</option>
                                    <?php
                                    foreach ($kundOrgNums as $kundNum => $orgNam) {
                                        echo "<option value='" . $kundNum . "'>" . $orgNam . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="eight wide centered column">
                <button type="button" class="ui inverted blue button" id="btnFilterManage">Lägg till filter</button>
                <button type="button" class="ui inverted orange button disabled" id="btnRemoveFilterManage">Ta bort
                    filter
                </button>
                <div class="ui grid">
                    <div class="two column row">
                        <div class="column">
                            <form class="ui form" id="formManageSort">
                                <div class="field">
                                    <label for="sortOptionManage">Sortera:
                                    </label>
                                    <select id="sortOptionManage" name="sortOptionManage" class="ui dropdown">
                                        <option value="">Sorteringsalternativ</option>
                                        <option value="0">Datum</option>
                                        <option value="1">Brådskande</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="column">
                            <form class="ui form" id="formManageLanguageSort">
                                <div class="field">
                                    <label for="sortLanguageM">Språk:</label>
                                    <select id="sortLanguageM" name="language" class="ui search dropdown sortLanguageOption">
                                        <option selected value=''>Språk</option>
                                        <?php
                                        foreach ($languages as $id => $lang) {
                                            echo "<option value='" . $lang . "'>" . $lang . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui grid">
        <div class="row">
            <?php if ($detect->isMobile() || $detect->isTablet()) { ?>
                <div class="mobile tablet only sixteen wide column">
                    <div style="overflow-x: scroll; overflow-y: hidden;">
                        <?php if (count($orders) > 0) { ?>
                            <table class="ui collapsing unstackable striped celled table orderManage">
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
                                    <th class="one wide">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                for ($k = 0; $k < count($orders); $k++) {
                                    $infoMsg = "info";
                                    $btnColor = "orange";
                                    $state = $orders[$k]->o_state;
                                    switch ($state) {
                                        case "O":
                                            $infoMsg = 'Beställ in Progress';
                                            $btnColor = 'orange';
                                            break;
                                        case "B":
                                            $infoMsg = 'Färdig';
                                            $btnColor = 'yellow';
                                            break;
                                        case "EC":
                                            $infoMsg = 'Avbruten';
                                            $btnColor = 'red';
                                            break;
                                        case "IC":
                                            $infoMsg = 'Fortfarande pågår';
                                            $btnColor = 'orange';
                                            break;
                                        case "R":
                                            $infoMsg = 'Rapporterad';
                                            $btnColor = 'green';
                                            break;
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $orders[$k]->o_orderNumber; ?></td>
                                        <td><?php echo $klient[$k]->k_organizationName; ?></td>
                                        <td><?php echo $orders[$k]->o_orderer; ?></td>
                                        <td><?php echo $orders[$k]->o_language; ?></td>
                                        <td class="typeTip"
                                            data-content="<?php echo getFullTolkningType($orders[$k]->o_interpretationType); ?>">
                                            <?php echo $orders[$k]->o_interpretationType; ?>
                                        </td>
                                        <td><?php echo $orders[$k]->o_date; ?></td>
                                        <td><?php echo convertTime($orders[$k]->o_startTime); ?></td>
                                        <td><?php echo convertTime($orders[$k]->o_endTime); ?></td>
                                        <td>
                                            <button type='button' id="<?php echo $orders[$k]->o_orderNumber; ?>"
                                                    class="ui fluid <?php echo $btnColor; ?> button btn_manage_order"><?php echo $infoMsg; ?></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php } else {
                            echo "<div class='ui fluid basic segment'><h3 class='ui center alligned header'>För närvarande, har du inte några order.</h3></div>";
                        } ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="computer only sixteen wide column">
                    <?php if (count($orders) > 0) { ?>
                        <table class="ui collapsing unstackable striped celled table orderManage">
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
                                <th class="one wide">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            for ($k = 0; $k < count($orders); $k++) {
                                $infoMsg = "info";
                                $btnColor = "orange";
                                $state = $orders[$k]->o_state;
                                switch ($state) {
                                    case "O":
                                        $infoMsg = 'Beställ in Progress';
                                        $btnColor = 'orange';
                                        break;
                                    case "B":
                                        $infoMsg = 'Färdig';
                                        $btnColor = 'yellow';
                                        break;
                                    case "EC":
                                        $infoMsg = 'Avbruten';
                                        $btnColor = 'red';
                                        break;
                                    case "IC":
                                        $infoMsg = 'Fortfarande pågår';
                                        $btnColor = 'orange';
                                        break;
                                    case "R":
                                        $infoMsg = 'Rapporterad';
                                        $btnColor = 'green';
                                        break;
                                }
                                ?>
                                <tr>
                                    <td><?php echo $orders[$k]->o_orderNumber; ?></td>
                                    <td><?php echo $klient[$k]->k_organizationName; ?></td>
                                    <td><?php echo $orders[$k]->o_orderer; ?></td>
                                    <td><?php echo $orders[$k]->o_language; ?></td>
                                    <td class="typeTip"
                                        data-content="<?php echo getFullTolkningType($orders[$k]->o_interpretationType); ?>">
                                        <?php echo $orders[$k]->o_interpretationType; ?>
                                    </td>
                                    <td><?php echo $orders[$k]->o_date; ?></td>
                                    <td><?php echo convertTime($orders[$k]->o_startTime); ?></td>
                                    <td><?php echo convertTime($orders[$k]->o_endTime); ?></td>
                                    <td>
                                        <button type='button' id="<?php echo $orders[$k]->o_orderNumber; ?>"
                                                class="ui fluid <?php echo $btnColor; ?> button btn_manage_order"><?php echo $infoMsg; ?></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } else {
                        echo "<div class='ui fluid basic segment'><h3 class='ui center alligned header'>För närvarande, har du inte några order.</h3></div>";
                    } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php if ($num > 10) { ?>
        <div class="ui pagination menu page-manage">
            <a class="icon item previousMPage">
                <i class="left arrow icon"></i>
            </a>
            <a class="active item" id="mpage1">
                1
            </a>
            <?php
            $rem = $num % 10;
            if ($rem == 0) {
                $numPage = ($num / 10);
                for ($k = 2; $k <= $numPage; $k++) {
                    echo "<a class='item'>$k</a>";
                }
            } else {
                $numPage = (($num - $rem) / 10) + 1;
                for ($k = 2; $k <= $numPage; $k++) {
                    echo "<a class='item' id='mpage$k'>$k</a>";
                }
            }
            ?>
            <a class="icon item nextMPage">
                <i class="right arrow icon"></i>
            </a>
        </div>
    <?php } ?>
    <div class="ui divider"></div>
    <form class="ui form order_manage">
        <div class="field">
            <input type="hidden" name="code" value="<?php echo md5("5%32rfsFrr$%") ?>"/>
            <input type="hidden" name="currentPage" id="updateCurrMPage" value="1"/>
            <button type="button" class="ui center aligned icon circular button btn-update-manage">
                <i class="circular refresh icon"></i>Uppdatera aktuella beställningar.
            </button>
        </div>
    </form>
</div>