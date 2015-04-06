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
                        Kvalitet
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="grid_12">
                    <div class="box_1">
                        <p class="text_3">
                            STÖ AB´s kvalitetspolicy är utvecklad i enlighet med vår affärsidé, vision och våra
                            värderingar. Vi vill med denna policy beskriva hur vi ska agera för att visa respekt
                            gentemot våra samverkansorgan samt strategiskt höja kvaliteten.
                            <br/><br/>
                            Alla medarbetare som är anslutna till företaget har tagit del av denna dokumentation och är
                            överens samt införstådda med hur arbetet ska fortskrida.
                            <br/><br/>
                            Eftersom vi arbetar för framgång på lång sikt har vi valt att fokusera på följande:
                            <br/><br/>
                            <!--<b>Inom företaget:</b><br/>Interna medarbetarna- kontorspersonal<br/>Externa medarbetare - Anlitade tolkar/ översättare/ kontaktpersoner<br/>Våra kunder<br/>
                            <strong>Utom företaget:</strong><br/>Samhället<br/>Miljön-->
                        </p>
                    </div>
                    <div class="text_7 color_2">
                        <ul id="filters">
                            <li><a href="#" data-filter="*">Visa alla</a></li>
                            <li><a href="#" data-filter="c1">Inom företaget</a></li>
                            <li><a href="#" data-filter="c2">Utom företaget</a></li>
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
                                        <h3 class="text_2 color_2"><a href="interna-medarbetare.php">Våra interna
                                                medarbetare</a></h3>

                                        <p class="text_3">
                                            Det är inom företaget vi kan utveckla kvaliteten på våra tjänster och
                                            atmosfär mest. Våra interna medarbetare är kärnan och därför strävar vi
                                            efter att vara en så ansvarsfull arbetsgivare som det är möjligt.
                                        </p>
                                        <a class="btn_2" href="interna-medarbetare.php">Läs mer</a></div>
                                </div>
                            </div>
                            <div class="element-item grid_4 c1">
                                <div class="box_7">
                                    <div class="img-wrap">
                                        <img src="images/index-2_img02.jpg" alt="Image 2"/>
                                    </div>
                                    <div class="caption">
                                        <h3 class="text_2 color_2"><a href="externa-medarbetare.php">Våra externa
                                                medarbetare</a></h3>

                                        <p class="text_3">
                                            Våra anslutna tolkar, översättare och kontaktpersoner är otroligt viktiga
                                            för oss och det arbetar vi i stor utsträckning för att visa dem.
                                        </p>
                                        <a class="btn_2" href="externa-medarbetare.php">Läs mer</a></div>
                                </div>
                            </div>
                            <div class="element-item grid_4 c1">
                                <div class="box_7">
                                    <div class="img-wrap">
                                        <img src="images/index-2_img03.jpg" alt="Image 3"/>
                                    </div>
                                    <div class="caption">
                                        <h3 class="text_2 color_2"><a href="kunder.php">Våra kunder</a></h3>

                                        <p class="text_3">
                                            För att kunna erbjuda det kunden vill ha görs regelbundna
                                            kundnöjdhetsundersökningar.
                                            <br/>Kunders behov är föränderliga och genom att ha en nära relation med dem
                                            kan vi anpassa våra tjänster efter deras önskemål snabbare.
                                        </p>
                                        <a class="btn_2" href="kunder.php">Läs mer</a></div>
                                </div>
                            </div>
                            <div class="element-item grid_4 c2">
                                <div class="box_7">
                                    <div class="img-wrap">
                                        <img src="images/index-2_img04.jpg" alt="Image 4"/>
                                    </div>
                                    <div class="caption">
                                        <h3 class="text_2 color_2"><a href="samhallet.php">Samhället</a></h3>

                                        <p class="text_3">
                                            Genom det arbete vi gör inom företaget stödjer vi social integration och det
                                            mångkulturella samhället. Vi medverkar till sociala möten mellan personer
                                            som inte kan förstå varandra, vilket bidrar till att acceptans, respekt och
                                            förståelse ökar.
                                        </p>
                                        <a class="btn_2" href="samhallet.php">Läs mer</a></div>
                                </div>
                            </div>
                            <div class="element-item grid_4 c2">
                                <div class="box_7">
                                    <div class="img-wrap">
                                        <img src="images/index-2_img05.jpg" alt="Image 5"/>
                                    </div>
                                    <div class="caption">
                                        <h3 class="text_2 color_2"><a href="miljo.php">Miljö</a></h3>

                                        <p class="text_3">
                                            Som en röd tråd genom företaget ska vi värna om miljön. Då vår verksamhet
                                            inte innebär någon betydande miljöpåverkan ska vi istället lägga stor vikt
                                            på hänsynstagandet på miljön i de tjänster som vi erbjuder.
                                        </p>
                                        <a class="btn_2" href="miljo.php">Läs mer</a></div>
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