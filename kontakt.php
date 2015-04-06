<?php

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();

?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <title>
        STÖ AB - Sarvari tolkning och översättning / Kontakt
    </title>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/grid.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/form.css"/>

    <link rel="stylesheet" type="text/css" href="lib/semantic/semantic.min.css" />
    <link rel="stylesheet" type="text/css" href="css/custom/form.css" />

    <script src="js/jquery.js"></script>
    <script src="js/jquery-migrate-1.2.1.js"></script>
    <script src="js/jquery.equalheights.js"></script>

    <script src="lib/semantic/semantic.min.js"></script>
    <script src="lib/jq-validate/jquery.validate.min.js"></script>
    <script src="lib/jq-validate/additional-methods.min.js"></script>
    <script src="js/custom/contact.js"></script>
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
        <div class="bg_1 wrap_6 wrap_8">
            <div class="container">
                <div class="row">
                    <div class="grid_6">
                        <div class="wrap_18">
                            <h2 class="header_2 indent_5">
                                Kontakt
                            </h2>
                            <address>
                                <p class="text_7 color_6">
                                    Tveka inte att ta kontakt med oss på STÖ tolktjänster om det är någonting som du undrar över, och inte har fått svar på här på webbplatsen.
                                </p>
                                <div class="grid_6" style="margin-left: 0;">
                                    <div class="row">
                                        <div class="grid_2">
                                            <p class="text_8">
                                                <i class="fa fa-phone-square"></i> <a href="tel:0046101661010"> 010 166 10 10</a><br/>
                                                <br/>

                                                <i class="fa fa-envelope-square"></i> info@tolktjanst.se
                                                <br/><br/>

                                                <i class="fa fa-fax"></i> 0451 25 38 34
                                                <br/><br/>

                                                <i class="fa fa-home"></i> Nya Boulevarden 10 <br/>291 31 Kristianstad
                                                <br/><br/>
                                            </p>
                                        </div>
                                        <div class="grid_4">
                                            <img src="images/kontakt.jpg">
                                        </div>
                                    </div>
                                </div>
                            </address>
                        </div>
                    </div>
                    <div class="grid_6">
                        <div class="wrap_18">
                            <h2 class="header_2 indent_2">
                                Kontaktformulär
                            </h2>
                            <form id="contact-form">
                                <fieldset class="ui basic segment">
                                    <div class="row">
                                        <div class="grid_2">
                                            <label class="name">
                                                <input type="text" class="" name="name" placeholder="Namn:" value="" />
                                            </label>
                                        </div>
                                        <div class="grid_2">
                                            <label class="name">
                                                <input type="text" name="foretagsnamn" placeholder="Företagsnamn:" value="" />
                                            </label>
                                        </div>
                                        <div class="grid_2">
                                            <label class="phone">
                                                <input type="text" name="phone" placeholder="Telefon:" value="" />
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_3">
                                            <label class="name">
                                                <input type="text" name="subject" placeholder="Ämne:" value="" />
                                            </label>
                                        </div>
                                        <div class="grid_3">
                                            <label class="name">
                                                <input type="text" name="email" placeholder="E-post:" value="" />
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_6">
                                            <label class="message">
                                                <textarea name="messageC" placeholder="Meddelande:"></textarea>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="btn-wrap">
                                        <button type="button" class="btn_3"  id="btnReset" >Rensa</button>
                                        <button type="button" class="btn_3" id="btnSubmit" >Skicka</button>
                                    </div>
                                </fieldset>
                                <div class="ui standard small modal">
                                    <i class="close icon"></i>
                                    <div class="header">
                                    </div>
                                    <div class="content">
                                        <div class="description">
                                        </div>
                                    </div>
                                    <div class="actions">
                                        <div class="ui positive button">OK</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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