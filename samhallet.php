<?php

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();

?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <title>
        STÖ AB - Sarvari tolkning och översättning / Kvalitet
    </title>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/grid.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/isotope.css"/>
    <script src="js/jquery.js"></script>
    <script src="js/jquery-migrate-1.2.1.js"></script>
    <script src="js/jquery.equalheights.js"></script>
    <script src='js/isotope.min.js'></script>
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
            <div class="row">
                <div class="preffix_2 grid_8">
                    <br/>

                    <h2 class="header_1 wrap_3 color_3">
                        Samhället
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="grid_12">
                    <div class="box_1">
                        <p class="text_3">
                            Genom det arbete vi gör inom företaget stödjer vi social integration och det mångkulturella
                            samhället. Vi medverkar till sociala möten mellan personer som inte kan förstå varandra,
                            vilket bidrar till att acceptans, respekt och förståelse ökar.
                            <br/><br/>
                            Vi ska även genom ett strategiskt kvalitets-och ansvarsarbete upprätthålla en transparent
                            standard där tillit genomsyras.
                        </p>
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