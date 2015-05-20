<?php

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
include_once "src/db/dbConnection.php";
include_once "src/db/dbConfig.php";
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <title>
        STÖ AB - Sarvari tolkning och översättning
    </title>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/grid.css">
    <link rel="stylesheet" href="lib/stoab/stoab.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/camera.css"/>
    <link rel="stylesheet" href="css/owl.carousel.css"/>
    <script src="js/jquery.js"></script>
    <script src="js/jquery-migrate-1.2.1.js"></script>
    <script src="js/jquery.equalheights.js"></script>
    <!--[if (gt IE 9)|!(IE)]><!-->
    <script src="js/jquery.mobile.customized.min.js"></script>
    <!--<![endif]-->
    <script src="js/camera.js"></script>
    <script src="js/owl.carousel.js"></script>
    <script src="lib/stoab/stoab.min.js"></script>
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
    <?php
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    ?>
    <section>
        <div class="camera-wrapper">
            <div id="camera" class="camera-wrap">
                <div data-src="images/index_slide01.jpg">
                    <div class="fadeIn camera_caption">
                        <h2 class="text_1 color_1">DIN SPRÅKPARTNER</h2>
                        <!--<a class="btn_1" href="#">More info</a>-->
                    </div>
                </div>
                <div data-src="images/index_slide02.jpg">
                    <div class="fadeIn camera_caption">
                        <h2 class="text_1 color_1">Vi Skapar Kommunikation Mellan Språk Och Kulturer</h2>
                    </div>
                </div>
                <div data-src="images/index_slide03.jpg">
                    <div class="fadeIn camera_caption">
                        <h2 class="text_1 color_1">Kvaliteten i företaget utgörs av noggrant utvalda tolkar</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row wrap_1 wrap_5">
                <div class="grid_3">
                    <div class="box_1-1">
                        <div class="icon_1"></div>
                        <h3 class="text_2 color_7 maxheight1"><a href="tolkning.php">Tolkning</a></h3>

                        <p class="text_3 color_2 maxheight">
                            Vi erbjuder tolkning som möjliggör kommunikation när språkbarriärerna blir för stora mellan
                            två eller flera personer. Våra anslutna tolkar har kompetens till och med auktorisation
                            enligt kammarkollegiet.
                        </p>
                        <a class="btn_2" href="tolkning.php">Läs mer</a></div>
                </div>
                <div class="grid_3">
                    <div class="box_1-2">
                        <div class="icon_2"></div>
                        <h3 class="text_2 color_8 maxheight1"><a href="oversattning.php">Översättning</a></h3>

                        <p class="text_3 color_2 maxheight">
                            Vi genomför översättning av all form av dokumentation.
                            Allt från validering av körkort och betyg till översättning av juridiska dokument, dokument
                            från myndigheter, medicinska handlingar osv på alla språk.
                        </p>
                        <a class="btn_2" href="oversattning.php">Läs mer</a></div>
                </div>
                <div class="grid_3">
                    <div class="box_1-3">
                        <div class="icon_3"></div>
                        <h3 class="text_2 color_9 maxheight1"><a href="kontaktperson.php">Kontaktperson /
                                Myndighetsservice</a></h3>

                        <p class="text_3 color_2 maxheight">
                            En kontaktperson visar på alternativ i tillvaron och har framförallt tid att lyssna och vara
                            närvarande. På STÖ länkar vi ihop kontaktpersoner med människor som behöver ett extra stöd i
                            vardagen.
                        </p>
                        <a class="btn_2" href="kontaktperson.php">Läs mer</a></div>
                </div>
                <div class="grid_3">
                    <div class="box_1-4">
                        <div class="icon_4"></div>
                        <h3 class="text_2 color_10 maxheight1"><a href="sprakstod.php">Studiehandledning / Språkstöd</a></h3>

                        <p class="text_3 color_2 maxheight">
                            Ett språkstöd kan vara nyckeln till förståelse inom många verksamheter. Det kan gälla allt
                            ifrån en extra kompetens i skolan till en vägledare i det svenska myndighetssystemet för
                            människor som inte är bekanta med det svenska samhället.
                        </p>
                        <a class="btn_2" href="sprakstod.php">Läs mer</a></div>
                </div>
            </div>
        </div>
        <div class="bg_1 wrap_2 wrap_4">
            <div class="container">
                <div class="row">
                    <div class="preffix_2 grid_8">
                        <h2 class="header_1 wrap_3 color_3">
                            Vi har en genuin drivkraft att vilja hjälpa människor
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="grid_12">
                        <div class="box_1">
                            <p class="text_3">
                                Vi på företaget har en vilja att skapa möten mellan människor från olika kulturer och
                                samhällsklasser. Genom att fungera som en kontaktlänk mellan olika människor, språk och
                                kulturer vill vi bidra till uppbyggnaden av ett internationellt samhälle där acceptans,
                                respekt och förståelse genomsyras.
                                <br/><br/>
                                Våra ledord i företaget är Kvalitetsarbete, Genuin hjälpsamhet och Personligt bemötande.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row wrap_1">
                <div class="grid_6">
                    <div class="wrap_6">
                        <div class="box_2 maxheight2">
                            <!--<div class="put-left"><img src="images/index_img01.png" alt="Image 1"/></div>-->
                            <div class="caption">
                                <h3 class="text_2 color_2"> Nyheter <br /> </h3>
                            </div>
                            <p class="test_3"></p>
                            <div id="newsContainer" style="max-height: 250px; overflow: auto;">
                                <div class="ui small feed">
                                    <?php
                                    $statement = $con->query("SELECT * FROM t_newsLetter ORDER BY n_time DESC LIMIT 0,5 ");
                                    $statement->execute();
                                    $statement->setFetchMode(PDO::FETCH_OBJ);
                                    if ($statement->rowCount() > 0) {
                                        while ($row = $statement->fetch()) { ?>
                                            <div class="event" style="border: solid 1px #d3d3d3; margin-bottom: 5px">
                                                <div class="label">
                                                    <i class="mail outline icon"></i>
                                                </div>
                                                <div class="content">
                                                    <div class="summary">
                                                        <?php
                                                        echo $row->n_title . " - Publicerat: ";
                                                        $date1 = new DateTime($row->n_time);
                                                        $date2 = new DateTime(date("Y-m-d H:i:s"));
                                                        $interval = $date1->diff($date2);
                                                        if ($interval->format('%d') === "0") {
                                                            echo "Idag";
                                                        } else {
                                                            echo $interval->format('%d') . " dagar sedan.";
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="extra text">
                                                        <?php echo $row->n_postScript; ?>
                                                    </div>
                                                    <div class="meta">
                                                        <a class="linkViewMore"
                                                           href="newsletter.php?id=<?php echo $row->n_ID ?>">Läs mer</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                    } else {
                                        echo "<div><p class='text_3'>Just nu har vi inte några nyheter!</p></div>";
                                    }
                                    ?>


                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="grid_6">
                    <div class="wrap_6">
                        <div class="box_2 maxheight2">
                            <div class="put-left"><img src="images/index_img02.png" alt="Image 2"/></div>
                            <div class="caption">
                                <h3 class="text_2 color_2">
                                    Boka tolk <br/><br/>

                                    <i class="fa fa-phone"></i>
                                    <a href="tel:0046101661010">010 166 10 10</a> <br/>
                                    <i class="fa fa-envelope"></i> info@tolktjanst.se
                                </h3>
                                <p class="text_3">
                                    Tveka inte att ta kontakt med oss på STÖ AB om det är någonting som du undrar över.
                                </p>
                            </div>
                            <div class="clearfix"></div>
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