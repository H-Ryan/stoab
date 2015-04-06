<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();
    session_destroy();
}
$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}
if(empty($_SESSION['organization_number']) && empty($_SESSION['user_number']))
{
    header('Location: bokning.php');
} else {
    try {
        include "src/db/dbConfig.php";
        include_once "src/db/dbConnection.php";
        include_once "src/misc/functions.php";
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $organizationNumber = $_SESSION['organization_number'];
        $clientNumber = $_SESSION['user_number'];
        $statement = $con->prepare("SELECT * FROM t_kunder WHERE k_role=4 AND k_personalNumber=:organizationNumber AND k_kundNumber=:clientNumber");
        $statement->bindParam(":organizationNumber", $organizationNumber);
        $statement->bindParam(":clientNumber", $clientNumber);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $customerInfo = $statement->fetch();
        $statement = $con->prepare("SELECT o_orderNumber, o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state FROM t_order WHERE o_kunderPersonalNumber =:organizationNumber AND o_kundNumber=:clientNumber ORDER BY o_date DESC");
        $statement->bindParam(":organizationNumber", $organizationNumber);
        $statement->bindParam(":clientNumber", $clientNumber);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $orders = array();
        if($statement->rowCount() > 0)
        {
            $i = 0;
            while($order = $statement->fetch()) {
                $orders[$i] = $order;
                $i++;
            }
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
?>
    <!DOCTYPE html>
    <html>
    <head lang="en">
        <meta charset="UTF-8">
        <title>
            Kundpanel
        </title>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="description" content=""/>
        <meta name="keywords" content=""/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link rel="icon" href="images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/grid.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css"/>

        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster"/>

        <link rel="stylesheet" href="lib/semantic/semantic.min.css"/>
        <link rel="stylesheet" href="css/mod-sam/main.css"/>
        <link rel="stylesheet" href="css/mod-sam/form.css"/>
        <link rel="stylesheet" href="css/mod-sam/customer-panel.css"/>
        <link rel="stylesheet" href="lib/date/jquery-ui.min.css"/>

        <script src="js/jquery.js"></script>
        <script src="js/jquery-migrate-1.2.1.js"></script>
        <script src="js/jquery.equalheights.js"></script>
        <script type="text/javascript" src="lib/date/jquery-ui.min.js"></script>
        <script type="text/javascript" src="lib/date/jquery-ui.min.js"></script>
        <script type="text/javascript" src="lib/jq-validate/jquery.validate.min.js"></script>
        <script type="text/javascript" src="lib/jq-validate/additional-methods.min.js"></script>
        <script type="text/javascript" src="lib/semantic/semantic.min.js"></script>
        <script type="text/javascript" src="js/custom/customer-panel.js"></script>
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
                <div class="ui divider"></div>
                <div class="row custom">
                    <div class="ui top blue inverted attached tabular menu">
                        <?php include('src/partials/dashboard-client/menu.php'); ?>
                    </div>
                </div>
                <div class="ui divider"></div>
                <div class="row custom">
                    <div class="ui bottom attached active tab" data-tab="first">
                        <div class="ui segment">
                            <?php include('src/partials/dashboard-client/my-profile.php'); ?>
                        </div>
                    </div>
                    <div class="ui bottom attached tab" data-tab="second">
                        <div class="ui segment">
                            <?php include('src/partials/dashboard-client/order-history.php'); ?>
                        </div>
                    </div>
                    <div class="ui bottom attached tab" data-tab="third">
                        <div class="ui segment">
                            <?php include('src/partials/dashboard-client/make-an-order.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php include("src/partials/shared/footer.html"); ?>
        <script src="js/script.js"></script>
    </div>
    </body>
    </html>
<?php $db->disconnect(); ?>