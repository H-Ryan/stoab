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
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/grid.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/form.css"/>

    <link rel="stylesheet" type="text/css" href="lib/semantic/semantic.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/custom/form.css"/>

    <script src="js/jquery.js"></script>
    <script src="js/jquery-migrate-1.2.1.js"></script>
    <script src="js/jquery.equalheights.js"></script>

    <script src="lib/semantic/semantic.min.js"></script>
    <script src="lib/jq-validate/jquery.validate.min.js"></script>
    <script src="lib/jq-validate/additional-methods.min.js"></script>
    <script src="js/custom/jobb.js"></script>
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
        <div class="bg_1 wrap_3 wrap_4">
            <div class="container">
                <div class="row">
                    <div class="grid_6">
                        <div class="wrap_18">
                            <h2 class="header_2 indent_5">
                                Jobba med oss
                            </h2>

                            <p class="text_7 color_6">
                                <b>Är du tolk eller vill du bli tolk hos STÖ AB</b><br/><br/>
                                Vi söker auktoriserade och utbildade tolkar för kontakt- och distanstolkning. Våra
                                tolkar anlitas på timbasis vid behov. Arvodet varierar beroende på typen av uppdrag.
                            </p>
                        </div>
                        <div class="wrap_18">
                            <h2 class="header_2 indent_5">
                                Tolktaxan
                            </h2>

                            <p class="text_7 color_6">
                                Tillämpningsområde Enligt förordningen (1979:291) om tolktaxa Gäller taxan för tolkning
                                vid allmän domstol.<br/><br/>
                            </p>
                            <a class="btn_2" href=http://tolktjanst.com/PDF/Tolktaxan.pdf target="_blank">Klick här</a>
                        </div>
                        <div class="wrap_18">
                            <h2 class="header_2 indent_5">
                                Polisens belastningsregister
                            </h2>

                            <p class="text_7 color_6">
                                För att du ska få bli registrerad som tolk hos oss krävs det att vi får ta del av ditt
                                belastningsregister. Blanketten du ska fylla i finner du nedan.
                                <br/><br/>
                            </p>
                            <a class="btn_2"
                               href="https://polisen.se/Global/www%20och%20Intrapolis/Blanketter/Registerutdrag/Enkla%20blanketter%20i%20Acrobat%204.0/RPS_442_3_1401_Utdrag_ur_BR_for_enskild_paragraf_9_1st_Skriv_ut_och_fyll_i.pdf"
                               target="_blank">Klick här</a>
                        </div>
                    </div>
                    <div class="grid_6">
                        <div class="wrap_18">
                            <h2 class="header_2 indent_2">
                                Intresseanmälan
                            </h2>

                            <form id="job-form">
                                <fieldset class="ui basic segment">
                                    <div class="row">
                                        <div class="grid_3">
                                            <label>
                                                <input type="text" class="" name="name" placeholder="Namn:" value=""/>
                                            </label>
                                        </div>
                                        <div class="grid_3">
                                            <label>
                                                <input type="text" name="personalNumber" placeholder="Personnummer:"
                                                       value=""/>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="grid_2">
                                            <p class="text_7 color_6">Kön:</p>

                                            <div class="ui radio checkbox">
                                                <label for="genderWoman">Kvinna</label>
                                                <input id="genderWoman" type="radio" name="gender" checked=""
                                                       value="Kvinna">
                                            </div>
                                            <div class="ui radio checkbox">
                                                <label for="genderMan">Man</label>
                                                <input id="genderMan" type="radio" name="gender" value="Man">
                                            </div>
                                        </div>
                                        <div class="grid_2">
                                            <p class="text_7 color_6">Jag har:</p>

                                            <div class="ui radio checkbox">
                                                <label for="taxA">A-skatt</label>
                                                <input id="taxA" type="radio" name="tax" checked="" value="A-skatt">
                                            </div>
                                            <div class="ui radio checkbox">
                                                <label for="taxF">F-skatt</label>
                                                <input id="taxF" type="radio" name="tax" value="F-skatt">
                                            </div>
                                        </div>

                                        <div class="grid_2">
                                            <p class="text_7 color_6">Egen bil:</p>

                                            <div class="ui radio checkbox">
                                                <label for="carYes">Ja</label>
                                                <input id="carYes" type="radio" name="car" checked="" value="Ja">
                                            </div>
                                            <div class="ui radio checkbox">
                                                <label for="carNo">Nej</label>
                                                <input id="carNo" type="radio" name="car" value="Ney">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_2">
                                            <label>
                                                <input type="text" name="email" placeholder="E-post:" value=""/>
                                            </label>
                                        </div>
                                        <div class="grid_2">
                                            <label>
                                                <input type="text" name="phoneHome" placeholder="Telefon (bostad):"
                                                       value="" class="phone-group"/>
                                            </label>
                                        </div>
                                        <div class="grid_2">
                                            <label>
                                                <input type="text" name="phoneMobile" placeholder="Mobiltelefon:"
                                                       value="" class="phone-group"/>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_6">
                                            <label>
                                                <input type="text" name="address" placeholder="Gatuadress:" value=""/>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_3">
                                            <label>
                                                <input type="text" name="postNumber" placeholder="Postnummer:"
                                                       value=""/>
                                            </label>
                                        </div>
                                        <div class="grid_3">
                                            <label>
                                                <input type="text" name="city" placeholder="Postort:" value=""/>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="ui basic segment">
                                    <h2 class="header_2 indent_2">
                                        Språk och kompetens:
                                    </h2>

                                    <p class="text_5 color_6"> Du kan ange flera språk. Börja med ditt
                                        förstahandsspråk.</p>

                                    <div class="row">
                                        <div class="grid_3">
                                            <label>
                                                <input type="text" id="languageOne" name="languageOne"
                                                       placeholder="Språk 1:" value=""/>
                                            </label>
                                        </div>
                                        <div class="grid_3">
                                            <label>
                                                <select class="ui fluid dropdown" name="langCompetenceOne">
                                                    <option value="">Kompetens:</option>
                                                    <option value="AT- Auktoriserad tolk">AT- Auktoriserad tolk</option>
                                                    <option value="ST - Auktoriserad sjukvårdstolk">ST - Auktoriserad
                                                        sjukvårdstolk
                                                    </option>
                                                    <option value="RT - Auktoriserad rättstolk">RT - Auktoriserad
                                                        rättstolk
                                                    </option>
                                                    <option value="ST &amp; RT - Auktoriserad sjukvårds- och rättstolk">
                                                        ST
                                                        &amp; RT
                                                        -
                                                        Auktoriserad sjukvårds- och rättstolk
                                                    </option>
                                                    <option value="GT - Godkänd Tolk">GT - Godkänd tolk
                                                    </option>
                                                    <option value="ÖT - Övriga Tolk">ÖT - Övrig tolk</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_3">
                                            <label>
                                                <input type="text" id="languageTwo" name="languageTwo"
                                                       placeholder="Språk 2:" value=""/>
                                            </label>
                                        </div>
                                        <div class="grid_3">
                                            <label>
                                                <select class="ui fluid dropdown" name="langCompetenceTwo">
                                                    <option value="">Kompetens:</option>
                                                    <option value="AT- Auktoriserad tolk">AT- Auktoriserad tolk</option>
                                                    <option value="ST - Auktoriserad sjukvårdstolk">ST - Auktoriserad
                                                        sjukvårdstolk
                                                    </option>
                                                    <option value="RT - Auktoriserad rättstolk">RT - Auktoriserad
                                                        rättstolk
                                                    </option>
                                                    <option value="ST &amp; RT - Auktoriserad sjukvårds- och rättstolk">
                                                        ST
                                                        &amp; RT
                                                        -
                                                        Auktoriserad sjukvårds- och rättstolk
                                                    </option>
                                                    <option value="GT - Godkänd Tolk">GT - Godkänd tolk
                                                    </option>
                                                    <option value="ÖT - Övriga Tolk">ÖT - Övrig tolk</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_3">
                                            <label>
                                                <input type="text" id="languageThree" name="languageThree"
                                                       placeholder="Språk 3:" value=""/>
                                            </label>
                                        </div>
                                        <div class="grid_3">
                                            <label>
                                                <select class="ui fluid dropdown" name="langCompetenceThree">
                                                    <option value="">Kompetens:</option>
                                                    <option value="AT- Auktoriserad tolk">AT- Auktoriserad tolk</option>
                                                    <option value="ST - Auktoriserad sjukvårdstolk">ST - Auktoriserad
                                                        sjukvårdstolk
                                                    </option>
                                                    <option value="RT - Auktoriserad rättstolk">RT - Auktoriserad
                                                        rättstolk
                                                    </option>
                                                    <option value="ST &amp; RT - Auktoriserad sjukvårds- och rättstolk">
                                                        ST
                                                        &amp; RT
                                                        -
                                                        Auktoriserad sjukvårds- och rättstolk
                                                    </option>
                                                    <option value="GT - Godkänd Tolk">GT - Godkänd tolk
                                                    </option>
                                                    <option value="ÖT - Övriga Tolk">ÖT - Övrig tolk</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_3">
                                            <label>
                                                <input type="text" id="languageFour" name="languageFour"
                                                       placeholder="Språk 4:" value=""/>
                                            </label>
                                        </div>
                                        <div class="grid_3">
                                            <label>
                                                <select class="ui fluid dropdown" name="langCompetenceFour">
                                                    <option value="">Kompetens:</option>
                                                    <option value="AT- Auktoriserad tolk">AT- Auktoriserad tolk</option>
                                                    <option value="ST - Auktoriserad sjukvårdstolk">ST - Auktoriserad
                                                        sjukvårdstolk
                                                    </option>
                                                    <option value="RT - Auktoriserad rättstolk">RT - Auktoriserad
                                                        rättstolk
                                                    </option>
                                                    <option value="ST &amp; RT - Auktoriserad sjukvårds- och rättstolk">
                                                        ST
                                                        &amp; RT
                                                        -
                                                        Auktoriserad sjukvårds- och rättstolk
                                                    </option>
                                                    <option value="GT - Godkänd Tolk">GT - Godkänd tolk
                                                    </option>
                                                    <option value="ÖT - Övriga Tolk">ÖT - Övrig tolk</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_6">
                                            <div class="ui divider"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="grid_6">
                                            <div>
                                                Ja, jag samtycker till och är fullt införstådd
                                                med att den information jag skickar kan registreras
                                                och lagras av Semantix enligt personuppgiftslagen (1998:204).
                                            </div>
                                        </div>
                                        <div class="grid_6">
                                            <label for="terms"><input id="terms" type="checkbox" name="terms"/> Ja, jag
                                                samtycker</label>
                                        </div>
                                    </div>
                                    <div class="btn-wrap">
                                        <button type="button" class="btn_3" id="btnReset">Rensa</button>
                                        <button type="button" class="btn_3" id="btnSubmit">Skicka</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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