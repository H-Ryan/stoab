<?php
/**
 * User: Samuil
 * Date: 18-06-2015
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
            $query = 'SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,'
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
                            Vänligen kontrollera era uppgifter. Vi har nu bokat en tolk till er för följande uppdrag.
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
                                    <p><span style='font-weight:bold;'>Namn:</span> " .$tolk->u_firstName.' '.$tolk->u_lastName."</p>

                                    <p><span style='font-weight:bold;'>Telefonnr:</span> " .$tolk->u_tel."</p>

                                    <p><span style='font-weight:bold;'>Mobil:</span> 0" .$tolk->u_mobile."</p>

                                    <p><span style='font-weight:bold;'>E-postadress:</span> " .$tolk->u_email."</p>

                                    <p><span style='font-weight:bold;'>Hemort:</span> " .$tolk->u_city."</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <hr style='width: 80%;
                                        margin-left: 10%;'/>
                        <div>
                            <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                                Om informationen ovan är felaktig eller om du vill ändra något, vänligen kontakta oss.
                            </p>
                        </div>
                        <hr style='width: 80%;
                                        margin-left: 10%;'/>
                        <footer style='margin-left: 10%; width:80%'>
                        <h2>C4 SPRÅKPARTNER AB</h2>

                        <p><label style='font-weight:bold;'>ADRESS:</label> Nya boulevarden 10 (Våning 3), 291 31 Kristianstad</p>

                        <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:kundtjanst@c4tolk.se'> kundtjanst@c4tolk.se</a></p>

                        <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.c4tolk.se'> www.c4tolk.se</a></p>

                        <p><label style='font-weight:bold;'>TELEFON:</label> 010 562 42 10</p>

                            <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 559071-4134</p>

                        </footer>
                        </body>
                        </html>";
                $customer_subject = 'C4 SPRÅKPARTNER AB - Uppdragsbekräftelse.';

                if ($order->o_kunderPersonalNumber != '0000000000') {
                    $query = 'SELECT k_email, k_organizationName FROM t_kunder WHERE k_kundNumber=:clientNumber AND k_personalNumber=:organizationNumber';
                    $statement = $con->prepare($query);
                    $statement->bindParam(':organizationNumber', $order->o_kunderPersonalNumber);
                    $statement->bindParam(':clientNumber', $order->o_kundNumber);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                    if ($statement->rowCount() > 0) {
                        $kund = $statement->fetch();
                        if ($kund->k_email === $order->o_email) {
                            $emailer->send_email($order->o_email, $order->o_orderer, $customer_subject, $messageToCustomerAssign);
                        } else {
                            $emailer->send_email($order->o_email, $order->o_orderer, $customer_subject, $messageToCustomerAssign);
                            $emailer->send_email($kund->k_email, $kund->k_organizationName, $customer_subject, $messageToCustomerAssign);
                        }
                        $data['error'] = 0;
                        $data['messageHeader'] = 'Framgång';
                        $data['positiveMessage'] = 'E-postmeddelandet har skickats.';
                        echo json_encode($data);
                    } else {
                        session_regenerate_id();
                        $data['error'] = 1;
                        $data['messageHeader'] = 'Error in Database!';
                        $data['errorMessage'] = 'There was a problem executing the fetch Query!';
                        echo json_encode($data);
                    }
                } else {
                    $emailer->send_email($order->o_email, $order->o_orderer, $customer_subject, $messageToCustomerAssign);
                    $data['error'] = 0;
                    $data['messageHeader'] = 'Framgång';
                    $data['positiveMessage'] = 'E-postmeddelandet har skickats.';
                    echo json_encode($data);
                }
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
