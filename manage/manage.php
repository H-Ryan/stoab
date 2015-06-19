<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit ("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='" . $_SERVER['SCRIPT_NAME'] . "'>try again</a>.");
}

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
if (empty($_SESSION['personal_number']) &&
    empty($_SESSION['order'])
) {
    header('Location: index.php');
}
$order = $_SESSION['order'];
$tolk = null;
try {
    include "../src/db/dbConfig.php";
    include_once "../src/db/dbConnection.php";
    include_once "../src/misc/functions.php";
    $db = new dbConnection(HOST, DATABASE, USER, PASS);
    $con = $db->get_connection();
    $tolkNumber = "";
    if ($order->o_tolkarPersonalNumber != null) {
        $tolkNumber = $order->o_tolkarPersonalNumber;
        $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,"
            . " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,"
            . " u.u_extraInfo, t.* FROM t_tolkar AS t, t_users AS u WHERE u.u_role = 3"
            . " AND t.t_active = 1 AND t.t_personalNumber=:tolkNumber AND u.u_personalNumber = t.t_personalNumber";
        $statement = $con->prepare($query);
        $statement->bindParam(":tolkNumber", $tolkNumber);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $orders = array();
        if ($statement->rowCount() > 0) {
            $tolk = $statement->fetch();
            $tolkNumber = $tolk->t_tolkNumber;
        }
    }
} catch (PDOException $e) {
    return $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Manage Order: <?php echo $order->o_orderNumber ?></title>
    <link rel="stylesheet" href="../lib/stoab/stoab.min.css"/>
    <link rel="stylesheet" href="../css/mod-sam/main.css"/>
    <link rel="stylesheet" href="../css/mod-sam/form.css"/>
    <link rel="stylesheet" href="css/manage.css"/>

    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../lib/stoab/stoab.min.js"></script>
    <script type="text/javascript" src="js/manage.js"></script>
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
<div id="main">
    <div class="ui centered grid">
        <div class="six wide column">
            <div class="ui piled horizontal segment">
                <form class="ui form assignTolk">
                    <fieldset class="ui basic segment">
                        <input type="hidden" name="orderNumber" value="<?php echo $order->o_orderNumber ?>"/>
                        <input type="hidden" name="employee" value="<?php echo $_SESSION['personal_number'] ?>"/>

                        <div class="ui centered grid">
                            <div class="four wide column computer only"></div>
                            <div class="eight wide column">
                                <div class="required field">
                                    <label for="tolkNumber">Tolkens nummer:</label>
                                    <input type="text" name="tolkNumber" id="tolkNumber"
                                           placeholder="XXXX" value="<?php echo $tolkNumber ?>"/>
                                </div>
                                <div class="ui error message">
                                    <i class="close icon"></i>

                                    <div class="header">Action Forbidden</div>
                                    <div class="ui text">You can only sign up for an account once with a given e-mail
                                        address.
                                    </div>
                                </div>
                            </div>
                            <div class="four wide column computer only"></div>
                        </div>
                        <div class="centered field">
                            <button type="button" class="ui blue button btnVerify">Verifiera</button>
                        </div>
                        <div class="field">
                            <table class="ui two column celled striped table tolkTable">
                                <thead>
                                <tr>
                                    <th colspan="3">
                                        <div class="ui segment">
                                            <div class="ui center aligned header">Tolkens uppgifter</div>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="ui header">Tolkens nummer:</div>
                                    </td>
                                    <td class="tolkInfoNumber">
                                        <?php echo(($tolk != null) ? $tolk->t_tolkNumber : "") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ui header">Tolkens namn:</div>
                                    </td>
                                    <td class="tolkInfoName">
                                        <?php echo(($tolk != null) ? ($tolk->u_firstName . " " . $tolk->u_lastName) : "") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ui header">Tolkens e-post:</div>
                                    </td>
                                    <td class="tolkInfoEmail">
                                        <?php echo(($tolk != null) ? $tolk->u_email : "") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ui header">Tolkens telefonnummer:</div>
                                    </td>
                                    <td class="tolkInfoTelephone">
                                        <?php echo(($tolk != null) ? $tolk->u_tel : "") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ui header">Tolkens mobilnummer:</div>
                                    </td>
                                    <td class="tolkInfoTelephone">
                                        <?php echo(($tolk != null) ? $tolk->u_mobile : "") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ui header">Tolkens stad:</div>
                                    </td>
                                    <td class="tolkInfoCity">
                                        <?php echo(($tolk != null) ? $tolk->u_city : "") ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <button type="button" class="ui orange button btnCancel">Avboka</button>
                            </div>
                            <div class="field">
                                <?php if ($tolk != null) {
                                    echo "<button type='button' class='ui green button btnReAssign'>Överlåta</button>";
                                } else {
                                    echo "<button type='button' class='ui green button btnAssign'>Tilldela</button>";
                                }

                                ?>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="ten wide column">
            <div class="ui piled horizontal segment">
                <table class="ui two column celled striped table tolkTable">
                    <thead>
                    <tr>
                        <th colspan="3">
                            <div class="ui segment">
                                <div class="ui center aligned header">Beställ extra information</div>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div class="ui header">Gatuadress:</div>
                        </td>
                        <td>
                            <?php echo $order->o_address ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui header">Postnummer:</div>
                        </td>
                        <td>
                            <?php echo $order->o_zipCode ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui header">Ort:</div>
                        </td>
                        <td>
                            <?php echo $order->o_city ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui header">Klient:</div>
                        </td>
                        <td>
                            <?php echo $order->o_client ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui header">Kommentar:</div>
                        </td>
                        <td>
                            <?php echo $order->o_comments ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="ui horizontal divider">
                    Skicka bekräftelse e-post
                </div>
                <form class="ui form" id="resendEmailForm">
                    <input type="hidden" name="orderNumber" value="<?php echo $order->o_orderNumber ?>"/>
                    <input type="hidden" name="tolkNumber" value="<?php echo ($order->o_tolkarPersonalNumber != null) ? $order->o_tolkarPersonalNumber : '' ?>"/>
                    <div class="ui grid">
                        <div class="row">
                            <div class="ten wide column">
                                <div class="ui header">
                                    Skicka tolk uppdrag bekräftelse
                                </div>
                                <button type="button" id="resendToTolk" class="ui teal inverted button <?php echo (($tolk == null) ? 'disabled' : '')  ?>">Till tolk</button>
                                <button type="button" id="resendToClientAboutTolk" class="ui purple inverted button <?php echo (($tolk == null) ? 'disabled' : '')  ?>">Till kunden</button>
                            </div>
                            <div class="six wide column">
                                <div class="ui header">
                                    Skicka orderbekräftelse
                                </div>
                                <button type="button" id="resendToClient" class="ui yellow inverted button">Till kunden</button>
                            </div>
                        </div>
                    </div>
                    <div class="ui basic segment">
                        <div class="ui hidden positive message">
                            <i class="close icon"></i>
                            <div class="header"></div>
                            <p class="ui text"></p>
                        </div>
                        <div class="ui hidden error message">
                            <i class="close icon"></i>
                            <div class="header">Fel</div>
                            <p class="ui text"></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="ui basic modal modalCancel">
        <div class="center aligned header">
            Varning
        </div>
        <div class="content">
            <div class="image">
                <i class="warning sign icon"></i>
            </div>
            <div class="description">
                <div class="ui left aligned inverted header">
                    Är du säker på att du vill avbryta denna order?
                </div>
            </div>
        </div>
        <div class="actions">
            <div class="two fluid ui inverted buttons">
                <div class="ui red cancel basic inverted button">
                    <i class="remove icon"></i>Nej
                </div>
                <div class="ui green ok basic inverted button">
                    <i class="checkmark icon"></i>Ja
                </div>
            </div>
        </div>
    </div>
    <div class="ui basic modal modalResend">
        <div class="center aligned header">
            Varning
        </div>
        <div class="content">
            <div class="image">
                <i class="warning sign icon"></i>
            </div>
            <div class="description">
                <div class="ui left aligned inverted header">
                    Är du säker på att du vill skicka det här e-post?
                </div>
            </div>
        </div>
        <div class="actions">
            <div class="two fluid ui inverted buttons">
                <div class="ui red cancel basic inverted button">
                    <i class="remove icon"></i>Nej
                </div>
                <div class="ui green ok basic inverted button">
                    <i class="checkmark icon"></i>Ja
                </div>
            </div>
        </div>
    </div>
    <div class="ui basic modal modalAssign">
        <div class="center aligned header">
            Varning
        </div>
        <div class="content">
            <div class="image">
                <i class="warning sign icon"></i>
            </div>
            <div class="description">
                <div class="ui left aligned inverted header">
                    Är du säker på att du vill tilldela den tolk för denna order?
                </div>
            </div>
        </div>
        <div class="actions">
            <div class="two fluid ui inverted buttons">
                <div class="ui red cancel basic inverted button">
                    <i class="remove icon"></i>Nej
                </div>
                <div class="ui green ok basic inverted button">
                    <i class="checkmark icon"></i>Ja
                </div>
            </div>
        </div>
    </div>
    <div class="ui basic modal modalReAssign">
        <div class="center aligned header">
            Varning
        </div>
        <div class="content">
            <div class="image">
                <i class="warning sign icon"></i>
            </div>
            <div class="description">
                <div class="ui left aligned inverted header">
                    Är du säker på att du vill åter tilldela en tolk för denna order?
                </div>
            </div>
        </div>
        <div class="actions">
            <div class="two fluid ui inverted buttons">
                <div class="ui red cancel basic inverted button">
                    <i class="remove icon"></i>Nej
                </div>
                <div class="ui green ok basic inverted button">
                    <i class="checkmark icon"></i>Ja
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>