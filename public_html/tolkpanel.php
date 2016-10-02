<?php
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
error_reporting(E_ALL); ini_set('display_errors', 1);
session_start();

$qInfo = 'SELECT * FROM t_users WHERE (u_role=1 OR u_role=3) AND u_personalNumber=:personal_number';
$qHistoryNum = 'SELECT COUNT(*) AS id FROM t_order
WHERE o_tolkarPersonalNumber =:personal_number AND o_state=:state ORDER BY o_date';
$qHistoryOrders = 'SELECT o_orderNumber, o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state
FROM t_order WHERE o_tolkarPersonalNumber =:personal_number AND o_state=:state ORDER BY o_date DESC LIMIT 10';
$qCurrOrderNum = "SELECT COUNT(*) AS id FROM t_order
WHERE o_tolkarPersonalNumber =:personal_number AND o_state<>:state
AND (o_date >= CURRENT_DATE OR ((DATE_ADD(o_date, INTERVAL +1 DAY)) = CURRENT_DATE
AND TIMESTAMP(DATE_ADD(o_date, INTERVAL +1 DAY), '08:15:00') > NOW()))";
$qCurrentOrders = "SELECT o_orderNumber, o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state
FROM t_order WHERE o_tolkarPersonalNumber =:personal_number AND o_state<>:state
AND (o_date >= CURRENT_DATE OR ((DATE_ADD(o_date, INTERVAL +1 DAY)) = CURRENT_DATE
AND TIMESTAMP(DATE_ADD(o_date, INTERVAL +1 DAY), '08:15:00') > NOW()))
ORDER BY o_date ASC LIMIT 10";
$qReportNum = "SELECT COUNT(*) AS id FROM t_order
WHERE o_tolkarPersonalNumber =:personal_number AND o_state<>:state
AND (o_date <= CURRENT_DATE - 1 OR ((o_date + 1) = CURRENT_DATE AND TIMESTAMP(o_date + 1, '08:15:00') <= NOW()))";
$qReportingOrders = "SELECT o_orderNumber, o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state
FROM t_order WHERE o_tolkarPersonalNumber =:personal_number AND o_state<>:state
AND (o_date <= CURRENT_DATE - 1 OR ((o_date + 1) = CURRENT_DATE
AND TIMESTAMP(o_date + 1, '08:15:00') <= NOW()))
ORDER BY o_date ASC LIMIT 10";
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();
    session_destroy();
}
$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}
if (empty($_SESSION['personal_number']) && empty($_SESSION['tolk_number'])) {
    header('Location: login.php');
} else {
    try {
        include 'src/db/dbConfig.php';
        include_once 'src/db/dbConnection.php';
        include_once 'src/misc/functions.php';
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $state = 'R';
        $personal_number = $_SESSION['personal_number'];
        $tolk_number = $_SESSION['tolk_number'];
        $statement = $con->prepare($qInfo);
        $statement->bindParam(':personal_number', $personal_number);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $tolkInfo = $statement->fetch();
        $statement = $con->prepare($qCurrOrderNum);
        $statement->bindParam(':personal_number', $personal_number);
        $statement->bindParam(':state', $state);
        $statement->execute();
        $cNum = $statement->fetchColumn();
        $statement = $con->prepare($qHistoryNum);
        $statement->bindParam(':personal_number', $personal_number);
        $statement->bindParam(':state', $state);
        $statement->execute();
        $hNum = $statement->fetchColumn();
        $statement = $con->prepare($qReportNum);
        $statement->bindParam(':personal_number', $personal_number);
        $statement->bindParam(':state', $state);
        $statement->execute();
        $rNum = $statement->fetchColumn();
        $statement = $con->prepare($qHistoryOrders);
        $statement->bindParam(':personal_number', $personal_number);
        $statement->bindParam(':state', $state);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $orderHistory = array();
        if ($statement->rowCount() > 0) {
            $i = 0;
            while ($order = $statement->fetch()) {
                $orderHistory[$i] = $order;
                ++$i;
            }
        }
        $statement = $con->prepare($qCurrentOrders);
        $statement->bindParam(':personal_number', $personal_number);
        $statement->bindParam(':state', $state);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $orders = array();
        if ($statement->rowCount() > 0) {
            $i = 0;
            while ($order = $statement->fetch()) {
                $orders[$i] = $order;
                ++$i;
            }
        }
        $statement = $con->prepare($qReportingOrders);
        $statement->bindParam(':personal_number', $personal_number);
        $statement->bindParam(':state', $state);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $orderToReport = array();
        if ($statement->rowCount() > 0) {
            $i = 0;
            while ($order = $statement->fetch()) {
                $orderToReport[$i] = $order;
                ++$i;
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
            Tolkpanel
        </title>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="description" content=""/>
        <meta name="keywords" content=""/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link rel="icon" href="img/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster"/>

        <link rel="stylesheet" href="vendor/stoab/stoab.min.css"/>

        <link rel="stylesheet" href="css/mod-sam/main.css"/>
        <link rel="stylesheet" href="css/mod-sam/form.css"/>
        <link rel="stylesheet" href="css/mod-sam/customer-panel.css"/>
        <link rel="stylesheet" href="vendor/date/jquery-ui.min.css"/>

        <script src="vendor/jquery/jquery.js"></script>
        <script type="text/javascript" src="vendor/date/jquery-ui.min.js"></script>
        <script type="text/javascript" src="vendor/date/jquery-ui.min.js"></script>
        <script type="text/javascript" src="vendor/jq-validate/jquery.validate.min.js"></script>
        <script type="text/javascript" src="vendor/jq-validate/additional-methods.min.js"></script>
        <script type="text/javascript" src="vendor/stoab/stoab.min.js"></script>
        <script type="text/javascript" src="js/views/view.interpreter.panel.js"></script>
        <!--[if lt IE 9]>
        <div style=' clear: both; text-align:center; position: relative;'>
            <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
                <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0"
                     height="42" width="820"
                     alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."/>
            </a>
        </div>
        <script src="vendor/html5shiv.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="css/ie.css">
        <![endif]-->
    </head>
    <body>
    <div class="page">
        <!--========================================================
                                  CONTENT
        =========================================================-->
        <section>
            <div class="container">
                <div class="row custom">
                    <div class="ui top inverted attached tabular menu" style="background: #e9e9e9; padding: 0 0 2px 2px; color: #424242 !important">
                        <?php include 'partials/dashboard-tolk/menu.php'; ?>
                    </div>
                </div>
                <div class="row custom">
                    <div class="ui bottom attached active tab" data-tab="first">
                        <div class="ui segment">
                            <?php include 'partials/dashboard-tolk/my-profile.php'; ?>
                        </div>
                    </div>
                    <div class="ui bottom attached tab" data-tab="second">
                        <div class="ui segment">
                            <?php include 'partials/dashboard-tolk/current-orders.php'; ?>
                        </div>
                    </div>
                    <div class="ui bottom attached tab" data-tab="third">
                        <div class="ui segment">
                            <?php include 'partials/dashboard-tolk/order-reporting.php'; ?>
                        </div>
                    </div>
                    <div class="ui bottom attached tab" data-tab="fourth">
                        <div class="ui segment">
                            <?php include 'partials/dashboard-tolk/order-history.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Report Modal -->
        <?php include 'partials/dashboard-tolk/reportInfo.php'; ?>
        <!-- Report Modal -->
        <?php include 'partials/dashboard-tolk/report.php'; ?>
        <!-- Order Information Modal -->
        <?php include 'partials/dashboard-tolk/order-information.php'; ?>
    </div>
    <?php include 'partials/shared/footer-kund.html'; ?>
    </body>
    </html>
<?php $db->disconnect(); ?>
