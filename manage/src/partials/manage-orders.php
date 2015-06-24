<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 3:45 PM
 */
$num = $con->query("SELECT COUNT(*) AS id FROM t_order WHERE o_date >= CURRENT_DATE")->fetchColumn();
$statement = $con->prepare("SELECT o_orderNumber, o_kundNumber,  o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state FROM t_order WHERE o_date >= CURRENT_DATE ORDER BY o_date DESC LIMIT 10");
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
            <div class="mobile only sixteen wide column">
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
                                        <form class='ui disabled form manageStatus' method="post"
                                              action="src/misc/orderInfo.php">
                                            <input type='hidden' name='orderId'
                                                   value='<?php echo $orders[$k]->o_orderNumber; ?>'>
                                            <button type='submit'
                                                    class="ui fluid <?php echo $btnColor; ?> button btn_manage_order"><?php echo $infoMsg; ?></button>
                                        </form>
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
                                    <form class='ui disabled form manageStatus' method="post"
                                          action="src/misc/orderInfo.php">
                                        <input type='hidden' name='orderId'
                                               value='<?php echo $orders[$k]->o_orderNumber; ?>'>
                                        <button type='submit'
                                                class="ui fluid <?php echo $btnColor; ?> button btn_manage_order"><?php echo $infoMsg; ?></button>
                                    </form>
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