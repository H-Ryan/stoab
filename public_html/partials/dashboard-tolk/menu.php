<!--
* User: Samuil
* Date: 29-01-2015
* Time: 11:04 PM
-->
<div class="left menu">
    <a class="item" href="index.php">
        <img alt="Logo" src="img/logo2.png" style="width:50px;height:27px;border:0;">
    </a>
    <a class="active item" data-tab="first">Min profil <i class="user icon"></i></a>
    <a class="item" data-tab="second">Kommande uppdrag <i class="book icon"></i></a>
    <a class="item" data-tab="third">Uppdrag rapportering <i class="write icon"></i></a>
    <a class="item" data-tab="fourth">Uppdrag historia<i class="history icon"></i></a>
</div>
<div class="right menu">
    <div class="item">
        <span class="name"><?php echo 'Tolknummer: '.$_SESSION['tolk_number'] ?></span>
    </div>
    <div class="item">
        <button type="button" class="right labeled icon small ui red button tolk-logout-btn">
            Logga ut <i class="sign out right icon"></i>
        </button>
    </div>
</div>
