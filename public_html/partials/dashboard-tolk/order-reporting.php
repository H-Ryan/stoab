<div class="ui piled segment dimmable">
    <form class="ui form update_report_orders">
        <div class="field">
            <input type="hidden" name="personal_number" value="<?php echo $personal_number; ?>">
            <input type="hidden" name="currentPage" id="updateReportPage" value="1"/>
            <button type="button" class="ui center aligned icon circular button refresh_report_order">
                <i class="circular refresh icon"></i>Uppdatera uppdrag rapportering
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
    <?php if (count($orderToReport) > 0) { ?>
        <table class="ui collapsing celled table reportingOrders">
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
            for ($k = 0; $k < count($orderToReport); $k++) { ?>
                <tr>
                    <td><?php echo $orderToReport[$k]->o_orderNumber; ?></td>
                    <td><?php echo $orderToReport[$k]->o_orderer; ?></td>
                    <td><?php echo $orderToReport[$k]->o_language; ?></td>
                    <td class="typeTip"
                        data-content="<?php echo getFullTolkningType($orderToReport[$k]->o_interpretationType); ?>">
                        <?php echo $orderToReport[$k]->o_interpretationType; ?>
                    </td>
                    <td><?php echo $orderToReport[$k]->o_date; ?></td>
                    <td><?php echo convertTime($orderToReport[$k]->o_startTime); ?></td>
                    <td><?php echo convertTime($orderToReport[$k]->o_endTime); ?></td>
                    <td>
                        <form class='ui form' id="repForm<?php echo $orderToReport[$k]->o_orderNumber; ?>">
                            <input type='hidden' name='orderId'
                                   value='<?php echo $orderToReport[$k]->o_orderNumber; ?>'>
                            <button type="button" id="reportBtn" class="ui fluid blue button">Rapportera</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php if ($rNum > 10) { ?>
            <div class="ui pagination menu reporting-orders">
                <a class="icon item previousPage">
                    <i class="left arrow icon"></i>
                </a>
                <a class="active item" id="rpage1">
                    1
                </a>
                <?php
                $rem = $rNum % 10;
                if ($rem == 0) {
                    $numPage = ($rNum / 10);
                    for ($k = 2; $k <= $numPage; $k++) {
                        echo "<a class='item'>$k</a>";
                    }
                } else {
                    $numPage = (($rNum - $rem) / 10) + 1;
                    for ($k = 2; $k <= $numPage; $k++) {
                        echo "<a class='item' id='rpage$k'>$k</a>";
                    }
                }
                ?>
                <a class="icon item nextPage">
                    <i class="right arrow icon"></i>
                </a>
            </div>
        <?php } ?>
    <?php } else {
        echo "<div class='ui fluid basic segment'><h3 class='ui center alligned header'>För närvarande, har du inte några uppdrag.</h3></div>";
    } ?>
</div>