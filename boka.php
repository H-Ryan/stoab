<?php
include_once "src/db/dbConnection.php";
include_once "src/db/dbConfig.php";
?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <title>
            STÖ AB - Sarvari tolkning och översättning / Bokning
        </title>
        <meta charset="utf-8">
        <meta name="format-detection" content="telephone=no"/>
        <meta name="description" content=""/>
        <meta name="keywords" content=""/>

        <link rel="icon" href="images/favicon.ico" type="image/x-icon">


        <link rel="stylesheet" href="lib/date/jquery-ui.min.css"/>
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster"/>
        <link rel="stylesheet" href="css/grid.css">

        <link rel="stylesheet" href="css/form.css"/>
        <link rel="stylesheet" href="lib/stoab/stoab.min.css"/>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/mod-sam/main.css"/>
        <link rel="stylesheet" href="css/mod-sam/form.css"/>
        <link rel="stylesheet" href="css/mod-sam/boka.css"/>

        <script src="js/jquery.js"></script>
        <script src="js/jquery-migrate-1.2.1.js"></script>
        <script src="js/jquery.equalheights.js"></script>
        <script type="text/javascript" src="lib/date/jquery-ui.min.js"></script>
        <script type="text/javascript" src="lib/jq-validate/jquery.validate.min.js"></script>
        <script type="text/javascript" src="lib/jq-validate/additional-methods.min.js"></script>
        <script type="text/javascript" src="lib/stoab/stoab.min.js"></script>
        <script type="text/javascript" src="js/custom/tolkning.js"></script>
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
            <div class="row wrap_8"></div>
            <div class="container ui stackable two column grid">
                <div class="column">
                    <div class="ui basic horizontal segment">
                        <h2 class="header_2 indent_1">Tolkning</h2>
                        <p class="text_3">
                            <i class="fa fa-chevron-right"></i> När människor från olika länder
                            och kulturer möts är det
                            ofrånkomligt att de ibland inte talar samma språk. Då behövs en
                            tolk som behärskar båda parternas språk och som hjälper dem att
                            förstå varandra. I Sverige används tolkar främst när invandrare
                            har behov av kontakt med svenska myndigheter. Det kan röra sig om
                            statliga verk som Migrationsverket, Försäkringskassan,
                            Arbetsförmedlingen eller Skatteverket, men det kan även röra sig
                            om sjukhus, socialkontor, skolor,
                            flyktingförläggningar, arbetsförmedlingar och domstolar. Tolkar
                            används även vid vissa internationella konferenser, för att
                            deltagare från olika länder så bra som möjligt ska kunna förstå
                            varandra.
                            <br/><br/>
                            Tolkning kan ske genom att tolken befinner sig på plats, något vi kallar
                            kontakttolkning, men den kan även genomföras per telefon, vilket vi
                            kallar telefontolkning. En kontakttolk är normalt att föredra,
                            eftersom den även kan tolka kroppsspråket. Om en tolk med rätt
                            språkfärdigheter inte finns att tillgå kan istället tolkningen
                            genomföras via telefon.
                        </p>
                        <p>

                        </p>
                        <img src="images/bb1.jpg"/>
                    </div>
                </div>
                <div class="column">
                    <div class="ui basic horizontal segment">
                        <?php
                        try {
                            $db = new dbConnection(HOST, DATABASE, USER, PASS);
                            $con = $db->get_connection();
                        } catch (PDOException $e) {
                            return $e->getMessage();
                        }
                        ?>
                        <form class="ui fluid form tolkForm">
                            <fieldset id="order">
                                <input type="hidden" name="organizationNumber" value="0000000000">
                                <input type="hidden" name="orderer" value="100000">
                                <input type="hidden" name="clientNumber" value="100000">

                                <h3>Beställ Tolk<br/>1. Uppdrag</h3>

                                <div class="field">
                                    <label for="client">Klient:</label>
                                    <input id="client" name="client" type="text" placeholder="Klient" autofocus=""/>
                                </div>
                                <div class="required field">
                                    <label for="language">Språk:</label>
                                    <select id="language" name="language" class="ui search dropdown">
                                        <option value=''>Språk</option>
                                        <?php
                                        try {
                                            $statement = $con->query("SELECT * FROM t_languages ORDER BY l_languageName");
                                            $statement->setFetchMode(PDO::FETCH_OBJ);
                                            while ($row = $statement->fetch()) {
                                                echo "<option value='" . $row->l_languageID . "'>" . $row->l_languageName . "</option>";
                                            }
                                        } catch (PDOException $e) {
                                            return $e->getMessage();
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="ui basic segment">
                                    <div class="required fields">
                                        <label for="type">Typ av tolkning.</label>

                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <div class="two column row">
                                                    <div class="left floated column">
                                                        <div class="field">
                                                            <div class="ui radio checkbox">
                                                                <label for="KT">Kontakttolkning</label>
                                                                <input id="KT" type="radio" name="type" value="KT" checked>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="left floated column">
                                                        <div class="field">
                                                            <div class="ui radio checkbox">
                                                                <label for="TT">Telefontolkning</label>
                                                                <input id="TT" type="radio" name="type" value="TT">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="two column row">
                                                    <div class="left floated column">
                                                        <div class="field">
                                                            <div class="ui radio checkbox">
                                                                <label for="KP">Kontaktperson</label>
                                                                <input id="KP" type="radio" name="type" value="KP">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="field">
                                                            <div class="ui radio checkbox">
                                                                <label for="SH">Studiehandledning</label>
                                                                <input id="SH" type="radio" name="type" value="SH">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="one column row">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <label for="SS">Språkstöd</label>
                                                            <input id="SS" type="radio" name="type" value="SS">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" value="NI" name="tolk_type"/>
                                    </div>
                                </div>
                                <div class="required field">
                                    <label for="date">Datum</label>
                                    <input id="date" type="text" title="Datum" name="date"
                                           placeholder="YYYY-MM-DD" value="<?php echo date("Y-m-d"); ?>"/>
                                </div>
                                <div class="two fields">
                                    <div class="field">
                                        <label for="startTime">Starttid</label>

                                        <div id="startTime" class="two fields">
                                            <div class="field">
                                                <select title="Starttid" name="start_hour" id="starttid"
                                                        class="ui fluid dropdown">
                                                    <?php
                                                    for ($i = 0; $i < 3; $i++) {
                                                        for ($j = 0; $j < 10; $j++) {
                                                            if ($i == 2 && $j == 4)
                                                                break;
                                                            elseif ($i == 1 && $j == 2)
                                                                echo "<option selected='selected' value='" . intval($i . $j) . "'>$i$j</option>";
                                                            else
                                                                echo "<option value='" . intval($i . $j) . "'>$i$j</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="field">
                                                <select title="Starttid" name="start_minute" id="starttid1"
                                                        class="ui fluid dropdown">
                                                    <option value="0" selected>00</option>
                                                    <option value="1">15</option>
                                                    <option value="2">30</option>
                                                    <option value="3">45</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label for="endTime">Sluttid</label>

                                        <div id="endTime" class="two fields">
                                            <div class="field">
                                                <select title="Sluttid" name="end_hour" id="sluttid"
                                                        class="ui fluid dropdown">
                                                    <?php
                                                    for ($i = 0; $i < 3; $i++) {
                                                        for ($j = 0; $j < 10; $j++) {
                                                            if ($i == 2 && $j == 4)
                                                                break;
                                                            elseif ($i == 1 && $j == 3)
                                                                echo "<option selected='selected' value='" . intval($i . $j) . "'>$i$j</option>";
                                                            else
                                                                echo "<option value='" . intval($i . $j) . "'>$i$j</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="field">
                                                <select title="Sluttid1" name="end_minute" id="sluttid1"
                                                        class="ui fluid dropdown">
                                                    <option value="0" selected>00</option>
                                                    <option value="1">15</option>
                                                    <option value="2">30</option>
                                                    <option value="3">45</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="field">
                                    <button type="button"
                                            class="ui blue right labeled icon button next-btn">
                                        <i class="right arrow icon"></i>
                                        Nästa
                                    </button>
                                </div>
                            </fieldset>
                            <fieldset id="customer">
                                <h3>Beställ Tolk<br/>2. Kontaktperson / Fakturering</h3>

                                <div class="required field">
                                    <label for="contactPerson">Beställare:</label>
                                    <input id="contactPerson" name="contactPerson" type="text"
                                           placeholder="Beställare"/>
                                </div>
                                <div class="required field">
                                    <label for="organization">Företag/ Organisation:</label>
                                    <input id="organization" name="organization" type="text"
                                           placeholder="Företag/ Organisation"/>
                                </div>
                                <div class="required field">
                                    <label for="email">E-postadress:</label>
                                    <input id="email" name="email" type="email" placeholder="E-post"/>
                                </div>
                                <div class="two fields">
                                    <div class="required field">
                                        <label for="telephone">Telefon:</label>
                                        <input class="phone-group" id="telephone" name="telephone" type="text"
                                               placeholder="Telefon"/>
                                    </div>
                                    <div class="required field">
                                        <label for="mobile">Mobil:</label>
                                        <input class="phone-group" id="mobile" name="mobile" type="text"
                                               placeholder="Mobil"/>
                                    </div>
                                </div>
                                <div class="required field">
                                    <label for="address">Plats för tolkning:</label>
                                    <input id="address" name="address" type="text" placeholder="Plats"/>
                                </div>
                                <div class="two fields">
                                    <div class="required field">
                                        <label for="post_code">Postnummer:</label>
                                        <input id="post_code" name="post_code" type="text"
                                               placeholder="Postnummer"/>
                                    </div>
                                    <div class="required field">
                                        <label for="city">Ort:</label>
                                        <select id="city" name="city" class="ui search dropdown">
                                            <option value=''>Ort</option>
                                            <?php
                                            try {
                                                $statement = $con->query("SELECT * FROM t_city ORDER BY c_cityName");
                                                $statement->setFetchMode(PDO::FETCH_OBJ);
                                                while ($row = $statement->fetch()) {
                                                    if ($row->c_cityName === "Hässleholm") {
                                                        echo "<option value='" . $row->c_cityName . "' selected>" . $row->c_cityName . "</option>";
                                                    } else {
                                                        echo "<option value='" . $row->c_cityName . "'>" . $row->c_cityName . "</option>";
                                                    }
                                                }
                                            } catch (PDOException $e) {
                                                return $e->getMessage();
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="two fields">
                                    <div class="field">
                                        <button type="button"
                                                class="ui blue orange labeled icon button back-btn">
                                            Tillbaka <i class="left arrow icon"></i>
                                        </button>
                                    </div>
                                    <div class="field">
                                        <button type="button"
                                                class="ui blue right labeled icon button next-btn">
                                            <i class="right arrow icon"></i>
                                            Nästa
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset id="comment">
                                <h3>Beställ Tolk<br/>3. Skicka</h3>

                                <div class="field">
                                    <label for="message">Kommentar</label>
                                    <textarea id="message" name="message"></textarea>
                                </div>
                                <div class="two fields">
                                    <div class="field">
                                        <button type="button"
                                                class="ui blue orange labeled icon button back-btn">
                                            Tillbaka <i class="left arrow icon"></i>
                                        </button>
                                    </div>
                                    <div class="field">
                                        <button id="order-btn" type="button"
                                                class="ui blue right labeled icon button book-btn">
                                            <i class="right arrow icon"></i> Boka
                                        </button>
                                    </div>
                                </div>
                                <div class="ui error message">
                                    <i class="close icon"></i>

                                    <div class="ui header">Header</div>
                                    <p>Message</p>
                                </div>
                            </fieldset>
                        </form>
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
<?php $db->disconnect();