<!--
* User: Samuil
* Date: 29-01-2015
* Time: 11:04 PM
-->
<div class="left menu">
    <a class="active item" data-tab="first">Min profil <i class="user icon"></i></a>
    <a class="item" data-tab="second">Orderhistorik <i class="book icon"></i></a>
    <a class="item" data-tab="third">Boka tolk<i class="send icon"></i></a>
</div>
<div class="right menu">
    <div class="item">
        <span class="name"><?php echo "Kundnummer: ".$customerInfo->k_kundNumber; ?></span>
    </div>
    <div class="item">
        <button type="button" class="right labeled icon small ui red button logout-btn">
            Logga ut <i class="sign out right icon"></i>
        </button>
    </div>
</div>

