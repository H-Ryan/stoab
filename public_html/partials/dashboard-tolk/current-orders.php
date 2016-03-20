<div class="ui piled segment dimmable">
    <form class="ui form update_order_curr">
        <div class="field">
            <input type="hidden" name="personal_number" value="<?php echo $personal_number; ?>">
            <input type="hidden" name="currentPage" id="updateCurrPage" value="1"/>
            <button type="button" class="ui center aligned icon circular button refresh_curr_order">
                <i class="circular refresh icon"></i>Uppdatera kommande uppdrag
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
        <table class="ui collapsing celled table currentOrders">
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
                    case "B":
                        $infoMsg = 'Pågående';
                        $btnColor = 'orange';
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
        <?php if($cNum > 10) {?>
            <div class="ui pagination menu current-orders">
                <a class="icon item previousPage">
                    <i class="left arrow icon"></i>
                </a>
                <a class="active item" id="hpage1">
                    1
                </a>
                <?php
                $rem = $cNum % 10;
                if($rem == 0) {
                    $numPage = ($cNum / 10);
                    for($k = 2; $k <= $numPage; $k++) {
                        echo "<a class='item'>$k</a>";
                    }
                } else {
                    $numPage = (($cNum - $rem) / 10) + 1;
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
        echo "<div class='ui fluid basic segment'><h3 class='ui center alligned header'>För närvarande, har du inte några uppdrag.</h3></div>";
    } ?>
</div>