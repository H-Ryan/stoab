<?php
/**
 * User: Samuil
 * Date: 09-11-2015
 * Time: 7:47 PM.
 */
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
include '../../db/dbConfig.php';
include '../../db/dbConnection.php';
include './../functions.php';
include '../../email/Emails.php';
include '../SMS_Service.php';

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}
$data = [];
$db = null;
$emailer = new Emails();
$tolkSubject = 'Tolkning i Kristianstad AB - Avbokning.';
if (isset($_POST['orderNumber']) && isset($_POST['employee'])) {
    $orderNumber = $_POST['orderNumber'];
    $employeeNumber = $_POST['employee'];
    $canceled = 'IC';
    $emptyTolk = null;

    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $query = 'SELECT * FROM t_order WHERE o_orderNumber=:orderNumber';
        $statement = $con->prepare($query);
        $statement->bindParam(':orderNumber', $orderNumber);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if ($statement->rowCount() > 0) {
            $order = $statement->fetch();
            $interpType = getFullTolkningType($order->o_interpretationType);
            $timeStart = convertTime($order->o_startTime);
            $timeEnd = convertTime($order->o_endTime);

            if ($order->o_tolkarPersonalNumber != null) {
                $query = 'SELECT t.t_tolkNumber, u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,
                 u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,
                 u.u_extraInfo, t.* FROM t_tolkar AS t, t_users AS u WHERE (u.u_role = 3 OR u.u_role = 1)
                 AND t.t_active = 1 AND t.t_personalNumber=:tolkPersonalNumber AND u.u_personalNumber =:tolkPersonalNumber';
                $statement = $con->prepare($query);
                $statement->bindParam(':tolkPersonalNumber', $order->o_tolkarPersonalNumber);
                $statement->execute();
                $statement->setFetchMode(PDO::FETCH_OBJ);
                if ($statement->rowCount() > 0) {
                    $tolk = $statement->fetch();
                    $query = 'UPDATE t_order SET o_tolkarPersonalNumber =:tolkEmpty, o_state=:canceled WHERE o_orderNumber=:orderNumber;';
                    $statement = $con->prepare($query);
                    $statement->bindParam(':tolkEmpty', $emptyTolk);
                    $statement->bindParam(':orderNumber', $orderNumber);
                    $statement->bindParam(':canceled', $canceled);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                    if ($statement->rowCount() > 0) {
                        $query = 'INSERT INTO t_orderLog (o_orderNumber, o_modifyPersonalNumber, o_involvedPersonalNumber, '
                            .'o_ipAddress ,o_state) VALUES (:orderNumber, :modifyPN, :involvedPN, :ipAddress, :state)';
                        $statement = $con->prepare($query);
                        $ipAddress = getRealIpAddress();
                        $statement->bindParam(':orderNumber', $orderNumber);
                        $statement->bindParam(':modifyPN', $employeeNumber);
                        $statement->bindParam(':involvedPN', $order->o_kundNumber);
                        $statement->bindParam(':ipAddress', $ipAddress);
                        $statement->bindParam(':state', $canceled);
                        $statement->execute();
                        if ($statement->rowCount() > 0) {
                            //Send email
                            $messageToOldTolkCancel = "<!DOCTYPE html><html>
                            <head>
                                <meta http-equiv='Content-Type' content='text/html' charset='utf-8'>
                            </head>
                            <body>
                            <div>
                                <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                                Hej!<br/>Här kommer avboknings bekräftelse. Du blivit avbokat från följande tolk uppdrag.</p>
                            </div>
                            <hr style='width: 80%; margin-left: 10%;'/>
                            <table style='width: 80%; margin-left: 10%; margin-right: 10%; text-align: center;
                                    font-family: verdana, arial, sans-serif; font-size: 14px; color: #333333;
                                    border-radius: 5px; border: 1px solid #999999;' cellpadding='10'>
                                <thead>
                                <tr>
                                    <th style='background-color: #ff9900; font-size: 18px; padding: 8px;
                                                border-radius: inherit; border: 1px solid black;'>
                                        Uppdrag: " .$order->o_orderNumber."
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Datum:</span> " .$order->o_date."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Starttid:</span> " .$timeStart."</p>
                                        <p><span style='font-weight:bold;'>Sluttid:</span> " .$timeEnd."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Plats:</span> " .$order->o_address."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Postnummer:</span> " .$order->o_zipCode."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Ort:</span> " .$order->o_city."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Typ av uppdrag:</span> " .$interpType."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Språk:</span> " .$order->o_language."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Klient:</span> " .$order->o_client."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Kontaktperson:</span> " .$order->o_orderer."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Telefonnr:</span> " .$order->o_tel."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Mobile:</span> " .$order->o_mobile."</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #e9e9e9; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>E-postadress:</span> " .$order->o_email."</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <hr style='width: 80%;margin-left: auto; margin-right: auto;'/>
                            <div>
                                <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                                    Tack för samarbetet.
                                </p>
                            </div>
                            <hr style='width: 80%;
                                            margin-left: 10%;'/>
                            <footer style='margin-left: 10%; width:80%'>
                            <h2>Tolkning i Kristianstad AB</h2>
                            <p><label style='font-weight:bold;'>POSTADRESS:</label> BOX 21, 291 21 Kristianstad</p>
                            <p><label style='font-weight:bold;'>ADRESS:</label> Industrigatan 2A, 291 36 Kristianstad</p>

                            <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:kundtjanst@c4tolk.se'> kundtjanst@c4tolk.se</a></p>

                            <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.c4tolk.se'> www.c4tolk.se</a></p>

                            <p><label style='font-weight:bold;'>TELEFON:</label> 010 516 42 10</p>

                            <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 556951-0802</p>

                            </footer>
                            </body>
                            </html>";
                            $emailer->send_email($tolk->u_email, $tolk->u_firstName.' '.$tolk->u_lastName, $tolkSubject, $messageToOldTolkCancel);

                            //Send SMS
                            $smsService = new SMS_Service();
                            $text = "Hej,
                                Uppdrag ($orderNumber) har förändrats eller Avbrutits.
                                Var vänlig kontrollera din e-post.
                                OBS! Du kan inte svara på detta meddelande.
                                Mvh Tolkning i Kristianstad AB";
                            $smsService->setTo($tolk->u_mobile);
                            $smsService->setText($text);
                            $data['smsURL'] = $smsService->generateSMS()->sendSMS();
                            $data['error'] = 0;
                        } else {
                            $data['error'] = 1;
                            $data['messageHeader'] = 'Error';
                            $data['errorMessage'] = 'Problem with Order log.';
                        }
                    } else {
                        $data['error'] = 1;
                        $data['messageHeader'] = 'Error';
                        $data['errorMessage'] = 'The order has already been canceled.';
                    }
                } else {
                    $data['error'] = 1;
                    $data['messageHeader'] = 'Error';
                    $data['errorMessage'] = 'Problem with selecting the tolk assigned to this order.';
                }
            } else {
                $data['error'] = 1;
                $data['messageHeader'] = 'Error';
                $data['errorMessage'] = 'There is no interpreter assigned to this assignment';
            }
        } else {
            $data['error'] = 1;
            $data['messageHeader'] = 'Error';
            $data['errorMessage'] = 'Problem with selecting this order.';
        }
    } catch (PDOException $e) {
        $data['error'] = 1;
        $data['messageHeader'] = 'Error';
        $data['errorMessage'] = 'Problem with query.';
    }
} else {
    $data['error'] = 1;
    $data['messageHeader'] = 'Error';
    $data['errorMessage'] = 'Missing parameters.';
}
if ($db != null) {
    $db->disconnect();
}

echo json_encode($data);
