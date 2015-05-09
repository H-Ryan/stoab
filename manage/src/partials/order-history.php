<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 3:46 PM
 */
$num = $con->query("SELECT COUNT(*) AS id FROM t_order WHERE o_date <= CURRENT_DATE - 1")->fetchColumn();
$statement = $con->prepare("SELECT o_orderNumber, o_kundNumber,  o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state FROM t_order WHERE o_date <= CURRENT_DATE - 1 ORDER BY o_date DESC  LIMIT 10");
$statement->execute();
$statement->setFetchMode(PDO::FETCH_OBJ);
$orders = array();
$klient = array();
if($statement->rowCount() > 0)
{
    $i = 0;
    while($order = $statement->fetch()) {
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
    <form class="ui form order_history">
        <div class="field">
            <input type="hidden" name="code" value="<?php echo md5("5%32rfsFrr$%") ?>"/>
            <input type="hidden" name="currentPage" id="updateCurrHPage" value="1"/>
            <button type="button" class="ui center aligned icon circular button btn-update-history">
                <i class="circular refresh icon"></i>Uppdatera orderhistorik
            </button>
        </div>
    </form>
    <div class="ui inverted dimmer">
        <div class="content">
            <div class="center">
                <div class="ui text loader">Loading</div>
            </div>
        </div>
    </div>
    <?php if (count($orders) > 0) { ?>
        <table class="ui collapsing celled table orderHistory">
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
                        $btnColor = 'green';
                        break;
                    case "EC":
                        $infoMsg = 'Avbruten';
                        $btnColor = 'red';
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
                        <form class='ui form' id="<?php echo $orders[$k]->o_orderNumber; ?>">
                            <input type='hidden' name='orderId' value='<?php echo $orders[$k]->o_orderNumber; ?>'>
                            <button type='button' class="ui fluid <?php echo $btnColor; ?> button btn-info"><?php echo $infoMsg; ?></button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php if($num > 10) {?>
            <div class="ui pagination menu page-history">
                <a class="icon item previousHPage">
                    <i class="left arrow icon"></i>
                </a>
                <a class="active item" id="hpage1">
                    1
                </a>
                <?php
                    $rem = $num % 10;
                    if($rem == 0) {
                        $numPage = ($num / 10);
                        for($k = 2; $k <= $numPage; $k++) {
                            echo "<a class='item'>$k</a>";
                        }
                    } else {
                        $numPage = (($num - $rem) / 10) + 1;
                        for($k = 2; $k <= $numPage; $k++) {
                            echo "<a class='item' id='hpage$k'>$k</a>";
                        }
                    }
                ?>
                <a class="icon item nextHPage">
                    <i class="right arrow icon"></i>
                </a>
            </div>
        <?php } ?>
    <?php } else {
        echo "<div class='ui fluid basic segment'><h3 class='ui center alligned header'>Det finns inga aktuella posten historik.</h3></div>";
    } ?>
</div>
<div class="ui modal order-history">
    <div class="ui inverted blue segment">
        <div class="white header">Mer information om din beställning: <span></span></div>
    </div>
    <div class="content">
        <div class="ui styled fluid accordion">
            <div class="active title">
                <i class="dropdown icon"></i>
                Orderinformation
            </div>
            <div class="active content">
                <div class="description">
                    <table class="ui celled table orderExtra">
                        <thead>
                        <tr>
                            <th>Gatuadress</th>
                            <th>Postnummer</th>
                            <th>Ort</th>
                            <th>Klient</th>
                            <th>Kommentar</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="title">
                <i class="dropdown icon"></i>
                Tolkinformation
            </div>
            <div class="content">
                <div class="description">
                    <table class="ui celled table tolkExtra">
                        <thead>
                        <tr class="tableTolkRow">
                            <th>Namn</th>
                            <th>Tolknummer</th>
                            <th>Telefonnummer</th>
                            <th>Mobilnummer</th>
                            <th>Hemort</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="actions center aligned">
        <div class="ui positive right labeled icon button">
            OK <i class="checkmark icon"></i>
        </div>
    </div>
</div>