<div class="ui piled segment">
    <!--<div class="ui grid">
        <div class="centered row">
            <div class="seven wide computer sixteen wide mobile ten wide tablet column">
                <form class="ui form company-search">
                    <div class="two fields">
                        <div class="field">
                            <label for="companyNumber">Kundnummer:</label>
                            <input type="text" name="companyNumber" id="companyNumber"/>
                        </div>
                        <div class="field">
                            <button type="button" class="ui inverted blue big button btnSearchCompany">Sök</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>-->
    <div class="ui basic fluid segment searchCompanyResult">
        <div class="ui grid">
            <div class="row">
                <div class="tolks">
                    <table class='ui collapsing unstackable striped celled table tolksTable' style="display: none;">
                        <thead>
                        <tr>
                            <th class='one wide'>Kundnummer</th>
                            <th class='three wide'>Namn</th>
                            <th class='two wide'>Telefon</th>
                            <th class='two wide'>Mobil</th>
                            <th class='two wide'>Kontaktperson</th>
                            <th class='two wide'>Epost</th>
                            <th class='four wide'>Adress</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($customers as $customer) {
                            echo "<tr><td>$customer->Kundnummer</td><td>$customer->Namn</td><td>$customer->Telefon</td><td>$customer->Mobil</td><td>$customer->Kontaktperson</td><td>$customer->Epost</td><td>$customer->Adress</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>