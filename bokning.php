<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
if (!empty($_SESSION['organization_number']) && !empty($_SESSION['user_number'])) {
    header('Location: kundpanel.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <title>
        STÖ AB - Sarvari tolkning och översättning / Bokning
    </title>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/grid.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/form.css"/>
    <link rel="stylesheet" href="lib/semantic/semantic.min.css" />
    <link rel="stylesheet" href="css/mod-sam/form.css" />
    <link rel="stylesheet" href="css/mod-sam/login.css" />

    <script src="js/jquery.js"></script>
    <script src="js/jquery-migrate-1.2.1.js"></script>
    <script src="js/jquery.equalheights.js"></script>
    <script src='js/modal.js'></script>
    <script src='js/TMForm.js'></script>
    <script src="lib/semantic/semantic.min.js"></script>
    <script src="js/custom/login.js"></script>
    <!--[if lt IE 9]>
    <div style=' clear: both; text-align:center; position: relative;'>
        <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
            <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0"
                 height="42" width="820"
                 alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."/>
        </a>
    </div>
    <script src="js/html5shiv.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="css/ie.css">
    <![endif]-->
</head>
<body>
<div class="page">
    <!--========================================================
                              HEADER
    =========================================================-->
    <?php include("src/partials/shared/header.php"); ?>
    <!--========================================================
                              CONTENT
    =========================================================-->
    <section>
        <div class="container">
            <div class="row wrap_8"></div>
            <div class="ui middle aligned two column stackable grid">
                <div class="column">
                    <form class="ui form login" onsubmit="return false;" autocomplete="off">
                        <fieldset class="segment">
                            <div class="ui error message">
                                <i class="close icon"></i>
                                <div class="ui header">Header</div>
                                <p>Message</p>
                            </div>
                            <h3>Logga in på vår tolktjänst</h3>
                            <h4 class="ui center aligned header">Ange ditt användar-id och
                                lösenord för att logga in.
                                När du är inloggad kan du
                                hantera dina befintliga
                                beställningar.</h4>

                            <div class="required field">
                                <label for="number">Kundnummer:</label>
                                <input id="number" name="number" type="text" placeholder="XXXXXX" autocomplete="off"/>
                            </div>
                            <div class="required field">
                                <label for="password">Lösenord</label>
                                <input id="password" name="password" type="password" placeholder="Lösenord" autocomplete="off">
                            </div>
                            <div class="field">
                                <a class="forgotten">Glömt lösenordet?</a>
                            </div>
                            <div class="field">
                                <button type="button" class="ui right labeled icon blue button login-btn">
                                    <i class="right sign in icon"></i>Logga in
                                </button>
                            </div>
                        </fieldset>
                    </form>
                    <form class="ui form retrievePass" onsubmit="return false;" autocomplete="off">
                        <fieldset>
                            <div class="ui basic segment">
                                <h3 class="ui center aligned header">Glömt dina inloggningsuppgifter?
                                    Vi behöver kontrollera att du är en registrerad användare.</h3>

                                <div class="ui basic fluid segment">
                                    <i class="big info circle icon"
                                       data-content="Ange ditt kundnummer och e-postadress registrerad
                           hos oss och klicka på Hämta. Då kommer vi att skicka dig ditt
                           användarnamn och ett tillfälligt lösenord till din administration."
                                       data-variation="huge"></i>
                                </div>
                                <div class="ui hidden positive message">
                                    <i class="close icon"></i>
                                    <div class="header"></div>
                                    <div class="ui text">

                                    </div>
                                </div>
                                <div class="ui error message">
                                    <i class="close icon"></i>
                                    <div class="header">Fel</div>
                                    <p></p>
                                </div>
                                <div class="required field">
                                    <label for="number">Kundnummer:</label>
                                    <input id="number" name="number" type="text" placeholder="XXXXXX" autocomplete="off"/>
                                </div>
                                <div class="required field">
                                    <label for="email">E-post:</label>
                                    <input id="email" name="email" type="email" placeholder="E-post" autocomplete="off"/>
                                </div>
                                <div class="two fields">
                                    <div class="field">
                                        <button type="button" class="ui left labeled icon orange button back-btn">
                                            <i class="left arrow icon"></i>
                                            Tillbaka
                                        </button>
                                    </div>
                                    <div class="field">
                                        <button type="button" class="ui blue right labeled icon button retrieve-btn">
                                            <i class="right arrow icon"></i>
                                            Hämta
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="ui vertical divider">Eller</div>
                <div class="center aligned column">
                    <a class="ui inverted circular massive icon blue button" href="boka.php">
                        Fortsätt som gäst<i class="angle double right icon"></i></a>
                </div>
            </div>
        </div>
        <?php include("src/partials/shared/follow-us.html") ?>
    </section>
</div>
<!--========================================================
                          FOOTER
=========================================================-->
<?php include("src/partials/shared/footer.html"); ?>
<script src="js/script.js"></script>
</body>
</html>