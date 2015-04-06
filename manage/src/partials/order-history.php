<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 3:46 PM
 */
$statement = $con->prepare("SELECT o_orderNumber, o_kundNumber,  o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state FROM t_order WHERE o_date <= CURRENT_DATE - 1 ORDER BY o_date DESC");
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
                    <td><?php echo $orders[$k]->o_interpretationType; ?></td>
                    <td><?php echo $orders[$k]->o_date; ?></td>
                    <td><?php echo convertTime($orders[$k]->o_startTime); ?></td>
                    <td><?php echo convertTime($orders[$k]->o_endTime); ?></td>
                    <td>
                        <form class='ui form'>
                            <input type='hidden' name='orderId' value='<?php echo $orders[$k]->o_orderNumber; ?>'>
                            <button type='button' class="ui fluid <?php echo $btnColor; ?> button btn-info"><?php echo $infoMsg; ?></button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else {
        echo "<div class='ui fluid basic segment'><h3 class='ui center alligned header'>Det finns inga aktuella posten historik.</h3></div>";
    } ?>
</div>
<div class="ui modal order-history">
    <div class="ui inverted blue segment">
        <div class="white header">Tolk Info</div>
    </div>
    <div class="content">
    </div>
    <div class="actions center aligned">
        <div class="ui positive right labeled icon button">
            OK <i class="checkmark icon"></i>
        </div>
    </div>
</div>