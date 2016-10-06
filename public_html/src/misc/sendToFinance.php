<?php
/**
 * User: Samuil
 * Date: 26-06-2015
 * Time: 12:59 PM.
 */
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
include '../db/dbConfig.php';
include '../db/dbConnection.php';
include './functions.php';
include '../email/Emails.php';

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}
$emailer = new Emails();
$data = array();

if (isset($_POST['tolkNumber']) && isset($_POST['orderNumber']) && !empty($_POST['tolkNumber']) && !empty($_POST['orderNumber'])) {
    $orderNum = $_POST['orderNumber'];
    $tolkNum = $_POST['tolkNumber'];
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $orderInfo = null;
        $statement = $con->prepare('SELECT * FROM t_order WHERE o_orderNumber =:orderNumber');
        $statement->bindParam(':orderNumber', $orderNum);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if ($statement->rowCount() > 0) {
            $order = $statement->fetch();

            $tolk = null;
            $query = 'SELECT t.t_tolkNumber, u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,'
                .' u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,'
                .' u.u_extraInfo, t.* FROM t_tolkar AS t, t_users AS u WHERE u.u_role = 3'
                .' AND t.t_active = 1 AND t.t_personalNumber=:tolkNumber AND u.u_personalNumber = t.t_personalNumber';
            $statement = $con->prepare($query);
            $statement->bindParam(':tolkNumber', $tolkNum);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ);
            if ($statement->rowCount() > 0) {
                $tolk = $statement->fetch();

                $interpType = getFullTolkningType($order->o_interpretationType);
                $timeStart = convertTime($order->o_startTime);
                $timeEnd = convertTime($order->o_endTime);

                $messageToCustomerAssign = "<!DOCTYPE html><html>
                        <head>
                            <meta http-equiv='Content-Type' content='text/html' charset='utf-8'>
                        </head>
                        <body>
                        <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                            Den följande ordning har markerats som slutförd. Gå vidare till fakturering och redovisning.
                        </p>
                        <hr style='width: 80%;
                                        margin-left: 10%;'/>
                        <h2 style='text-align: center; margin-top: 5%;'>Tolkuppdrag</h2>

                        <h2 style='text-align: center; margin-top: 5%;'>Uppdragsnr: " .$order->o_orderNumber."</h2>
                        <table style='width: 80%;
                                        margin-left: 10%;
                                        margin-right: 10%;
                                        text-align: center;
                                        font-family: verdana, arial, sans-serif;
                                        font-size: 14px;
                                        color: #333333;
                                        border-radius: 5px;
                                        border: 1px solid #999999;' cellpadding='10'>
                            <thead>
                            <tr>
                                <th style='background-color: #ff9900; font-size: 18px;padding: 8px;
                                            border-radius: inherit; border: 1px solid black;'>Uppdrag
                                </th>
                                <th style='background-color: #ff9900; font-size: 18px;padding: 8px;
                                            border-radius: inherit; border: 1px solid black;'>Tolk
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style='background-color: #e9e9e9;padding: 8px;border: 1px solid #a9c6c9;'>
                                    <p><span style='font-weight:bold;'>Kundnummer:</span> " .$order->o_kundNumber."</p>
                                    <p><span style='font-weight:bold;'>Datum:</span> " .$order->o_date."</p>

                                    <p><span style='font-weight:bold;'>Starttid:</span> " .$timeStart."</p>

                                    <p><span style='font-weight:bold;'>Sluttid:</span> " .$timeEnd."</p>

                                    <p><span style='font-weight:bold;'>Plats:</span> " .$order->o_address."</p>

                                    <p><span style='font-weight:bold;'>Postnummer:</span> " .$order->o_zipCode."</p>

                                    <p><span style='font-weight:bold;'>Ort:</span> " .$order->o_city."</p>

                                    <p><span style='font-weight:bold;'>Typ av uppdrag:</span> " .$interpType."</p>

                                    <p><span style='font-weight:bold;'>Språk:</span> " .$order->o_language."</p>

                                    <p><span style='font-weight:bold;'>Klient:</span> " .$order->o_client."</p>

                                    <p><span style='font-weight:bold;'>Kontaktperson:</span> " .$order->o_orderer."</p>

                                    <p><span style='font-weight:bold;'>Telefonnr:</span> " .$order->o_tel."</p>

                                    <p><span style='font-weight:bold;'>Mobil:</span> " .$order->o_mobile."</p>

                                    <p><span style='font-weight:bold;'>E-postadress:</span> " .$order->o_email."</p>
                                </td>
                                <td style='background-color: #e9e9e9;padding: 8px;border: 1px solid #a9c6c9;'>
                                    <p><span style='font-weight:bold;'>Tolknummer:</span> " .$tolk->t_tolkNumber."</p>
                                    <p><span style='font-weight:bold;'>Namn:</span> " .$tolk->u_firstName.' '.$tolk->u_lastName."</p>

                                    <p><span style='font-weight:bold;'>Telefonnr:</span> " .$tolk->u_tel."</p>

                                    <p><span style='font-weight:bold;'>Mobil:</span> " .$tolk->u_mobile."</p>

                                    <p><span style='font-weight:bold;'>E-postadress:</span> " .$tolk->u_email."</p>

                                    <p><span style='font-weight:bold;'>Hemort:</span> " .$tolk->u_city."</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <hr style='width: 80%;
                                        margin-left: 10%;'/>
                        </body>
                        </html>";
                $customer_subject = 'Tolkning i Kristianstad AB - Ekonomi.';

                $emailer->send_email('ekonomi@sarvari.se', 'Ekonomi', $customer_subject, $messageToCustomerAssign);
                $data['error'] = 0;
                $data['messageHeader'] = 'Framgång';
                $data['positiveMessage'] = 'E-postmeddelandet har skickats.';
                echo json_encode($data);
            }
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    if ($db != null) {
        $db->disconnect();
    }
} else {
    $data['error'] = 2;
    $data['messageHeader'] = 'Fields Missing Error';
    $data['errorMessage'] = 'Some of the required fields are missing!';
    echo json_encode($data);
}
