<!--
* User: Samuil
* Date: 29-01-2015
* Time: 10:31 PM
-->
<div class="ui piled segment dimmable">
    <form class="ui form update_order">
        <div class="field">
            <input type="hidden" name="organizationNumber" value="<?php echo $organizationNumber; ?>">
            <input type="hidden" name="clientNumber" value="<?php echo $clientNumber; ?>">
            <input type="hidden" name="currentPage" id="updateCurrPage" value="1"/>
            <button type="button" class="ui center aligned icon circular button refresh_order">
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
                <th class="three wide">Beställare</th>
                <th class="three wide">Språk</th>
                <th class="one wide">Typ</th>
                <th class="two wide">Datum</th>
                <th class="two wide">Starttid</th>
                <th class="two wide">Sluttid</th>
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
                        $infoMsg = 'Ej färdig';
                        $btnColor = 'orange';
                        break;
                    case "B":
                        $infoMsg = 'Färdig';
                        $btnColor = 'green';
                        break;
                    case "EC":
                        $infoMsg = 'Avbokad';
                        $btnColor = 'red';
                        break;
                    case "R":
                        $infoMsg = 'Rapporterad';
                        $btnColor = 'green';
                        break;
                }
                ?>
                <tr>
                    <td><?php echo $orders[$k]->o_orderNumber; ?></td>
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
                            <button type='button'
                                    class="ui fluid <?php echo $btnColor; ?> button btn-info"><?php echo $infoMsg; ?></button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php if($num > 10) {?>
            <div class="ui pagination menu page-customer">
                <a class="icon item previousPage">
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
                <a class="icon item nextPage">
                    <i class="right arrow icon"></i>
                </a>
            </div>
        <?php } ?>
    <?php } else {
        echo "<div class='ui fluid basic segment'><h3 class='ui center alligned header'>För närvarande, har du inte några order.</h3></div>";
    } ?>
</div>
<div class="ui modal tolk-info">
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