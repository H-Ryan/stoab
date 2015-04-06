<?php

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();

?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <title>
        STÖ AB - Sarvari tolkning och översättning / Hemsida
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

                    <h2 class="header_1 wrap_3 color_10">
                        STUDIEHANDLEDNING PÅ MODERSMÅL
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="grid_12">
                    <div class="box_1">
                        <p class="text_3">
                            Det finns idag ett stort behov av stöd på modersmålet inom skolväsendet. Enligt skolverkets statistik klarar flerspråkiga elever inte skolans krav i samma utsträckning som andra elever. Vi på STÖ fungerar som länken mellan språkstödet/studiehandledaren och skolan.
                            <br/><br/>Vi har därför utvecklat tjänster som följer förordningarna för grund- och gymnasieskolan för att på så sätt matcha det behov som finns idag:
                            <br/><br/>
                            <!--<b>Inom företaget:</b><br/>Interna medarbetarna- kontorspersonal<br/>Externa medarbetare - Anlitade tolkar/ översättare/ kontaktpersoner<br/>Våra kunder<br/>
                            <strong>Utom företaget:</strong><br/>Samhället<br/>Miljön-->
                        </p>
                    </div>
                    <div class="text_7 color_2">
                        <ul id="filters">
                            <li><a href="#" data-filter="*">Visa alla</a></li>
                            <li><a href="#" data-filter="c1">SPRÅKSTÖD</a></li>
                            <li><a href="#" data-filter="c2">STUDIEHANDLEDNING</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg_1 wrap_17">
            <div class="container">
                <div class="row">
                    <div class="grid_12">
                        <div class="isotope row">
                            <div class="element-item grid_4 c1">
                                <div class="box_7">
                                    <div class="img-wrap">
                                        <img src="images/index-2_img01.jpg" alt="Image 1"/>
                                    </div>
                                    <div class="caption">
                                        <h3 class="text_2 color_2"><a href="sprak-assistent.php">SPRÅKSTÖD</a></h3>

                                        <p class="text_3">
                                            Språkstödjaren finns tillgänglig i klassen och fungerar som en elevassistent. Vid behov översätter språkstödjaren vad eleven/ eleverna inte förstår av lärarens undervisning.
                                        </p>
                                        <a class="btn_2" href="sprak-assistent.php">Läs mer</a></div>
                                </div>
                            </div>
                            <div class="element-item grid_4 c2">
                                <div class="box_7">
                                    <div class="img-wrap">
                                        <img src="images/index-2_img02.jpg" alt="Image 2"/>
                                    </div>
                                    <div class="caption">
                                        <h3 class="text_2 color_2"><a href="amnesstod-i-hemsprak.php">STUDIEHANDLEDNING</a></h3>

                                        <p class="text_3">
                                            En studiehandledare på modersmål kompletterar ordinarie lärare. Det innebär att den förstärkta hjälpen i klassen har rätt kompetens för den specifika lektionen.
                                        </p>
                                        <a class="btn_2" href="amnesstod-i-hemsprak.php">Läs mer</a></div>
                                </div>
                            </div>
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