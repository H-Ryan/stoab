<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3200)) {
    session_unset();
    session_destroy();
}
$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 3200) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}
if (empty($_SESSION['personal_number'])) {
    header('Location: index.php');
}
try {
    include "../src/db/dbConfig.php";
    include_once "../src/db/dbConnection.php";
    include_once "../src/misc/functions.php";
    $db = new dbConnection(HOST, DATABASE, USER, PASS);
    $con = $db->get_connection();

    $query = "SELECT u_firstName, u_lastName FROM t_users WHERE u_personalNumber=:personalNumber";
    $statement = $con->prepare($query);
    $statement->bindParam(":personalNumber", $_SESSION['personal_number']);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_OBJ);
    $user = null;
    if ($statement->rowCount() > 0) {
        $user = $statement->fetch();
    }
    $statement = $con->query("SELECT l_languageName FROM t_languages ORDER BY l_languageName");
    $statement->setFetchMode(PDO::FETCH_OBJ);
    $languages = array();
    while ($row = $statement->fetch()) {
        $languages[] = $row->l_languageName;
    }
    $statement = $con->query("SELECT c_cityName FROM t_city ORDER BY c_cityName");
    $statement->setFetchMode(PDO::FETCH_OBJ);
    $cities = array();
    while ($row = $statement->fetch()) {
        $cities[] = $row->c_cityName;
    }

} catch (PDOException $e) {
    return $e->getMessage();
} ?>
    <!DOCTYPE html>
    <html>
    <head lang="en">
        <title>Control Panel</title>
        <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link rel="stylesheet" href="../lib/stoab/stoab.min.css"/>
        <link rel="stylesheet" href="../css/mod-sam/main.css"/>
        <link rel="stylesheet" href="../css/mod-sam/form.css"/>
        <link rel="stylesheet" href="css/main.css"/>
        <link rel="stylesheet" href="../lib/date/jquery-ui.min.css"/>

        <script type="text/javascript" src="../js/jquery.js"></script>
        <script type="text/javascript" src="../lib/date/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../lib/jq-validate/jquery.validate.min.js"></script>
        <script type="text/javascript" src="../lib/jq-validate/additional-methods.min.js"></script>
        <script type="text/javascript" src="../lib/stoab/stoab.min.js"></script>
        <script type="text/javascript" src="../lib/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>

        <!--[if lt IE 9]>
        <div style=' clear: both; text-align:center; position: relative;'>
            <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
                <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0"
                     height="42" width="820"
                     alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."/>
            </a>
        </div>
        <script src="../js/html5shiv.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="../css/ie.css">
        <![endif]-->
    </head>
    <body>
    <div class="ui active page dimmer">
        <div class="ui large text loader">Loading</div>
    </div>
    <div class="ui left vertical labeled icon menu sidebar">
        <a class="active teal item" data-tab="first"><i class="search icon"></i>Sök tolk</a>
        <a class="teal item" data-tab="second"><i class="book icon"></i>Beställa</a>
        <a class="teal item" data-tab="third"><i class="tasks icon"></i>Hantera order</a>
        <a class="teal item" data-tab="fourth"><i class="history icon"></i>Orderhistorik</a>
        <a class="teal item disabled" aria-disabled="true" data-tab="fifth"><i class="warning sign icon"></i>Hantera
            kunder</a>
        <a class="teal item" data-tab="sixth"><i class="comments outline icon"></i>Dashboard</a>

        <div class="ui item">
            <div class="header item"><i class="newspaper icon"></i>Nyhetsbrev</div>
            <div class="menu">
                <a class="teal item" data-tab="seventh">Lägg till nyhetsbrevet</a>
                <a class="teal item" data-tab="eight">Hantera nyhetsbrev</a>
                <a class="teal item" data-tab="ninth">Hantera bilder</a>
            </div>
        </div>
        <div class="ui grid">
            <div class="row">
                <div class="mobile tablet only sixteen wide column">
                    <button type="button" class="ui red right labeled icon small button logout-btn">
                        Logga Ut <i class="sign out right icon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="pusher" style="background: #CAD9FF;">
        <div class="ui grid">
            <div class="row">
                <div class="computer only column">
                    <div class="ui fixed top attached inverted blue borderless menu">
                        <div class="left item">
                            <button type="button" class="ui inverted toggle button">Menu</button>
                        </div>
                        <div class="item">
                            <a id="updatesInfo">
                                <span style="color:white; text-decoration-style: solid; text-blink: true">OBS!</span>
                            </a>
                        </div>
                        <div class="right item">
                            <button type="button" class="right labeled icon small ui red button logout-btn">
                                Logga Ut <i class="sign out right icon"></i>
                            </button>
                        </div>
                        <div class="right item">
                            Anställd: <span
                                id="employeeName"><?php echo $user->u_firstName . " " . $user->u_lastName; ?></span>
                        </div>
                    </div>
                </div>
                <div class="mobile only column">
                    <div class="ui fixed top attached inverted blue borderless menu">
                        <div class="left item">
                            <button type="button" class="ui inverted toggle button">Menu</button>
                        </div>
                        <div class="item">
                            <span
                                class="name"><?php echo "Anställd: " . $user->u_firstName . " " . $user->u_lastName; ?></span>
                        </div>
                    </div>
                </div>
                <div class="tablet only column">
                    <div class="ui fixed top attached inverted blue borderless menu">
                        <div class="left item">
                            <button type="button" class="ui inverted toggle button">Menu</button>
                        </div>
                        <div class="right item">
                            <span
                                class="name"><?php echo "Anställd: " . $user->u_firstName . " " . $user->u_lastName; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="main">
            <div class="ui grid">
                <div class="row">
                    <div class="column">
                        <div class="ui active tab" data-tab="first">
                            <?php include('src/partials/tolk-search.php'); ?>
                        </div>
                        <div class="ui tab" data-tab="second">
                            <?php include('src/partials/register-order.php'); ?>
                        </div>
                        <div class="ui tab" data-tab="third">
                            <?php include('src/partials/manage-orders.php'); ?>
                        </div>
                        <div class="ui tab" data-tab="fourth">
                            <?php include('src/partials/order-history.php'); ?>
                        </div>
                        <div class="ui tab" data-tab="fifth">
                            <?php include('src/partials/manage-customers.php'); ?>
                        </div>
                        <div class="ui tab" data-tab="sixth">
                            <?php include('src/partials/dashboard.php'); ?>
                        </div>
                        <div class="ui tab" data-tab="seventh">
                            <?php include('src/partials/newsletter-add.html'); ?>
                        </div>
                        <div class="ui tab" data-tab="eight">
                            <?php include('src/partials/newsletter-manage.php'); ?>
                        </div>
                        <div class="ui tab" data-tab="ninth">
                            <?php include('src/partials/image-upload.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui modal modalUpdatesInfo">
        <div class="center aligned header">
            OBS!
        </div>
        <div class="content">
            <div class="image">
                <i class="attention sign icon"></i>
            </div>
            <div class="description">
                <div class="ui left aligned inverted header">
                    Updates
                </div>
                <div>
                    <p>Hallå, Enligt din begäran, har jag gjort några ändringar på webbplatsen. Den mest kritiska är det nya sättet att hantera order.</p>
                    <p>Strömflödet i systemet processen är sådan att när du öppnar "Huvudsida" kommer en annan webbläsarfönster öppnas; det är "Hantera order" sida.</p>
                    <p>När du klickar på knappen "Hantera order", "Hantera sidan" uppdateras med den nyvalda orderinformation. Om det var stängd, kommer den att återupptas igen.</p>
                    <p>Om knappen inte verkar fungera följa dessa <a href="https://support.google.com/chrome/answer/95472?hl=sv" target="_blank">instruktioner</a>, i syfte att tillåta popup-fönster från Tolktjast hemsida din webbläsare.</p>
                    <p>Efter har du utfört en operation på en beställning (dvs. tilldela, avbryta, etc.), måste du vänta för sidan att ladda sig själv innan du väljer en annan för att klara.</p>
                    <p>Om du av någon anledning, det finns mer än ett "Hantera order" sidor öppna, vara noga med att stänga dem alla, i syfte att undvika misstag under verksamheten.</p>

                    <p>BR, SD</p>

                </div>
            </div>
        </div>
        <div class="actions">
            <div class="ui fluid green ok basic button">
                <i class="checkmark icon"></i>OK
            </div>
        </div>
    </div>
    </body>
    </html>
<?php $db->disconnect(); ?>