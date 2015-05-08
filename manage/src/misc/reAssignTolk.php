<?php
/**
 * User: Samuil
 * Date: 22-02-2015
 * Time: 11:36 AM
 */
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
include "../../../src/db/dbConfig.php";
include "../../../src/db/dbConnection.php";
include "../../../src/misc/functions.php";
include "../../../src/email/Emails.php";

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit ("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='" . $_SERVER['SCRIPT_NAME'] . "'>try again</a>.");
}
$emailer = new Emails();
$data = array();
if (isset($_POST['tolkNumber']) && isset($_POST['orderNumber']) && isset($_POST['employee'])) {
    $tolkNumber = $_POST['tolkNumber'];
    $orderNumber = $_POST['orderNumber'];
    $employeeNumber = $_POST['employee'];
    $booked = "B";
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $query = "SELECT * FROM t_order WHERE o_orderNumber=:orderNumber";
        $statement = $con->prepare($query);
        $statement->bindParam(":orderNumber", $orderNumber);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if ($statement->rowCount() > 0) {
            $order = $statement->fetch();
            $query = "SELECT u.u_email, u.u_firstName, u.u_lastName, t.* FROM t_tolkar AS t, t_users AS u WHERE u.u_role = 3"
                . " AND t.t_active = 1 AND t.t_personalNumber=:tolkPersonalNumber AND u.u_personalNumber = t.t_personalNumber";
            $statement = $con->prepare($query);
            $statement->bindParam(":tolkPersonalNumber", $order->o_tolkarPersonalNumber);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ);
            if ($statement->rowCount() > 0) {
                $oldTolk = $statement->fetch();
                $query = "UPDATE t_order SET o_tolkarPersonalNumber = (SELECT t_personalNumber FROM t_tolkar WHERE t_tolkNumber=:tolkNumber), o_state=:booked WHERE o_orderNumber=:orderNumber;";
                $statement = $con->prepare($query);
                $statement->bindParam(":tolkNumber", $tolkNumber);
                $statement->bindParam(":orderNumber", $orderNumber);
                $statement->bindParam(":booked", $booked);
                $statement->execute();
                $statement->setFetchMode(PDO::FETCH_OBJ);
                if ($statement->rowCount() > 0) {
                    $query = "SELECT * FROM t_order WHERE o_orderNumber=:orderNumber";
                    $statement = $con->prepare($query);
                    $statement->bindParam(":orderNumber", $orderNumber);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                    if ($statement->rowCount() > 0) {
                        $orderUpdated = $statement->fetch();
                        $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,"
                            . " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,"
                            . " u.u_extraInfo, t.* FROM t_tolkar AS t, t_users AS u WHERE u.u_role = 3"
                            . " AND t.t_active = 1 AND t.t_tolkNumber=:tolkNumber AND u.u_personalNumber = t.t_personalNumber";
                        $statement = $con->prepare($query);
                        $statement->bindParam(":tolkNumber", $tolkNumber);
                        $statement->execute();
                        $statement->setFetchMode(PDO::FETCH_OBJ);
                        if ($statement->rowCount() > 0) {
                            $tolk = $statement->fetch();
                            $query = "INSERT INTO t_orderLog (o_orderNumber, o_modifyPersonalNumber, o_involvedPersonalNumber, "
                                . "o_ipAddress ,o_state) VALUES (:orderNumber, :modifyPN, :involvedPN, :ipAddress, :state)";
                            $statement = $con->prepare($query);
                            $statement->bindParam(":orderNumber", $orderNumber);
                            $statement->bindParam(":modifyPN", $employeeNumber);
                            $statement->bindParam(":involvedPN", $order->o_kundNumber);
                            $statement->bindParam(":ipAddress", getRealIpAddress());
                            $statement->bindParam(":state", $booked);
                            $statement->execute();
                            if ($statement->rowCount() > 0) {
                                $interpType = getFullTolkningType($orderUpdated->o_interpretationType);
                                $timeStart = convertTime($orderUpdated->o_startTime);
                                $timeEnd = convertTime($orderUpdated->o_endTime);

                                $oldTolk_subject = 'STÖ AB - Avbokning.';
                                $tolk_subject = "STÖ AB - Uppdrag.";
                                $customer_subject = "STÖ AB - Beviljade nytt tolk.";

                                $messageToTolkAssign = "<!DOCTYPE html><html>
                            <head>
                                <meta http-equiv='Content-Type' content='text/html' charset='utf-8'>
                            </head>
                            <body>
                            <div>
                                <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                                    Hej.<br/>Du har tilldelats ett tolkuppdrag. Detaljer om tolkuppdraget finns nedan.</p>
                            </div>
                            <hr style='width: 80%; margin-left: 10%;'/>
                            <table style='width: 80%; margin-left: 10%; margin-right: 10%; text-align: center;
                                    font-family: verdana, arial, sans-serif; font-size: 14px; color: #333333;
                                    border-radius: 5px; border: 1px solid #999999;' cellpadding='10'>
                                <thead>
                                <tr>
                                    <th style='background-color: #599CFF; font-size: 18px; padding: 8px;
                                                border-radius: inherit; border: 1px solid black;'>
                                        Uppdrag: " . $order->o_orderNumber . "
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Datum:</span> " . $order->o_date . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Starttid:</span> " . $timeStart . "</p>
                                        <p><span style='font-weight:bold;'>Sluttid:</span> " . $timeEnd . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Plats:</span> " . $order->o_address . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Postnummer:</span> " . $order->o_zipCode . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Ort:</span> " . $order->o_city . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Typ av uppdrag:</span> " . $interpType . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Språk:</span> " . $order->o_language . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Klient:</span> " . $order->o_client . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Kontaktperson:</span> " . $order->o_orderer . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Telefonnr:</span> " . $order->o_tel . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Mobile:</span> " . $order->o_mobile . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>E-postadress:</span> " . $order->o_email . "</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <hr style='width: 80%;margin-left: auto; margin-right: auto;'/>
                            <div>
                                <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                                    Om du har några frågor eller funderingar kan du kontakta oss.
                                </p>
                            </div>
                            <hr style='width: 80%;
                                            margin-left: 10%;'/>
                            <footer style='margin-left: 10%; width:80%'>
                            <h2>STÖ Sarvari Tolkning och Översättning AB</h2>

                            <p><label style='font-weight:bold;'>ADRESS:</label> Nya Boulevarden 10, 291 31 Kristianstad </p>

                            <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:info@tolktjanst.se'> info@tolktjanst.se</a></p>

                            <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.tolktjanst.com'> www.tolktjanst.com</a></p>

                            <p><label style='font-weight:bold;'>TELEFON:</label> 010 166 10 10</p>

                            <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 556951-0802</p>

                            </footer>
                            </body>
                            </html>";
                                $messageToCustomerAssign = "<!DOCTYPE html><html>
                            <head>
                                <meta http-equiv='Content-Type' content='text/html' charset='utf-8'>
                            </head>
                            <body>
                            <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                                Hej!<br />Vi beviljar annan tolk  till följande tolkuppdrag.
                            </p>
                            <hr style='width: 80%;
                                            margin-left: 10%;'/>
                            <h2 style='text-align: center; margin-top: 5%;'>Tolkuppdrag</h2>

                            <h2 style='text-align: center; margin-top: 5%;'>Uppdrag Nummer: " . $orderUpdated->o_orderNumber . "</h2>
                            <table style='width: 80%;
                                            margin-left: 10%;
                                            margin-right: 10%;
                                            text-align: center;
                                            font-family: verdana, arial, sans-serif;
                                            font-size: 14px;
                                            color: #333333;
                                            border: 1px solid #999999;
                                            border-radius: 5px;' cellpadding='10'>
                                <thead>
                                <tr>
                                    <th style='background-color: #599CFF; font-size: 18px;padding: 8px;
                                                border-radius: inherit; border: 1px solid black;'>Uppdrag
                                    </th>
                                    <th style='background-color: #599CFF; font-size: 18px;padding: 8px;
                                                border-radius: inherit; border: 1px solid black;'>Tolk
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style='background-color: #d4e3e5;padding: 8px;border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Datum:</span> " . $orderUpdated->o_date . "</p>

                                        <p><span style='font-weight:bold;'>Starttid:</span> " . $timeStart . "</p>

                                        <p><span style='font-weight:bold;'>Sluttid:</span> " . $timeEnd . "</p>

                                        <p><span style='font-weight:bold;'>Plats:</span> " . $orderUpdated->o_address . "</p>

                                        <p><span style='font-weight:bold;'>Postnummer:</span> " . $orderUpdated->o_zipCode . "</p>

                                        <p><span style='font-weight:bold;'>Ort:</span> " . $orderUpdated->o_city . "</p>

                                        <p><span style='font-weight:bold;'>Typ av uppdrag:</span> " . $interpType . "</p>

                                        <p><span style='font-weight:bold;'>Språk:</span> " . $orderUpdated->o_language . "</p>

                                        <p><span style='font-weight:bold;'>Klient:</span> " . $orderUpdated->o_client . "</p>

                                        <p><span style='font-weight:bold;'>Kontaktperson:</span> " . $orderUpdated->o_orderer . "</p>

                                        <p><span style='font-weight:bold;'>Telefonnr:</span> " . $orderUpdated->o_tel . "</p>

                                        <p><span style='font-weight:bold;'>Mobile:</span> " . $orderUpdated->o_mobile . "</p>

                                        <p><span style='font-weight:bold;'>E-postadress:</span> " . $orderUpdated->o_email . "</p>
                                    </td>
                                    <td style='background-color: #d4e3e5;padding: 8px;border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Namn:</span> " . $tolk->u_firstName . " " . $tolk->u_lastName . "</p>

                                        <p><span style='font-weight:bold;'>Telefonnr:</span> " . $tolk->u_tel . "</p>

                                        <p><span style='font-weight:bold;'>Mobile:</span> " . $tolk->u_mobile . "</p>

                                        <p><span style='font-weight:bold;'>E-postadress:</span> " . $tolk->u_email . "</p>

                                        <p><span style='font-weight:bold;'>Hemort:</span> " . $tolk->u_city . "</p>
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
                            <h2>STÖ Sarvari Tolkning och Översättning AB</h2>

                            <p><label style='font-weight:bold;'>ADRESS:</label> Nya Boulevarden 10, 291 31 Kristianstad </p>

                            <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:info@tolktjanst.se'> info@tolktjanst.se</a></p>

                            <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.tolktjanst.com'> www.tolktjanst.com</a></p>

                            <p><label style='font-weight:bold;'>TELEFON:</label> 010 166 10 10</p>

                            <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 556951-0802</p>

                            </footer>
                            </body>
                            </html>";
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
                                    <th style='background-color: #599CFF; font-size: 18px; padding: 8px;
                                                border-radius: inherit; border: 1px solid black;'>
                                        Uppdrag: " . $order->o_orderNumber . "
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Datum:</span> " . $order->o_date . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Starttid:</span> " . $timeStart . "</p>
                                        <p><span style='font-weight:bold;'>Sluttid:</span> " . $timeEnd . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Plats:</span> " . $order->o_address . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Postnummer:</span> " . $order->o_zipCode . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Ort:</span> " . $order->o_city . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Typ av uppdrag:</span> " . $interpType . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Språk:</span> " . $order->o_language . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Klient:</span> " . $order->o_client . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Kontaktperson:</span> " . $order->o_orderer . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Telefonnr:</span> " . $order->o_tel . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>Mobile:</span> " . $order->o_mobile . "</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                                        <p><span style='font-weight:bold;'>E-postadress:</span> " . $order->o_email . "</p>
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
                            <h2>STÖ Sarvari Tolkning och Översättning AB</h2>

                            <p><label style='font-weight:bold;'>ADRESS:</label> Nya Boulevarden 10, 291 31 Kristianstad </p>

                            <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:info@tolktjanst.se'> info@tolktjanst.se</a></p>

                            <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.tolktjanst.com'> www.tolktjanst.com</a></p>

                            <p><label style='font-weight:bold;'>TELEFON:</label> 010 166 10 10</p>

                            <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 556951-0802</p>

                            </footer>
                            </body>
                            </html>";

                                if ($order->o_kunderPersonalNumber != '0000000000') {
                                    $query = "SELECT k_email, k_organizationName FROM t_kunder WHERE k_kundNumber=:clientNumber AND k_personalNumber=:organizationNumber";
                                    $statement = $con->prepare($query);
                                    $statement->bindParam(":organizationNumber", $order->o_kunderPersonalNumber);
                                    $statement->bindParam(":clientNumber", $order->o_kundNumber);
                                    $statement->execute();
                                    $statement->setFetchMode(PDO::FETCH_OBJ);
                                    if ($statement->rowCount() > 0) {
                                        $kund = $statement->fetch();
                                        if ($kund->k_email === $order->o_email) {
                                            $emailer->send_email($tolk->u_email, $tolk->u_firstName . " " . $tolk->u_lastName, $tolk_subject, $messageToTolkAssign);
                                            $emailer->send_email($order->o_email, $order->o_orderer, $customer_subject, $messageToCustomerAssign);
                                            $emailer->send_email($oldTolk->u_email, $oldTolk->u_firstName . " " . $oldTolk->u_lastName, $oldTolk_subject, $messageToOldTolkCancel);
                                        } else {
                                            $emailer->send_email($tolk->u_email, $tolk->u_firstName . " " . $tolk->u_lastName, $tolk_subject, $messageToTolkAssign);
                                            $emailer->send_email($order->o_email, $order->o_orderer, $customer_subject, $messageToCustomerAssign);
                                            $emailer->send_email($kund->k_email, $kund->k_organizationName, $customer_subject, $messageToCustomerAssign);
                                            $emailer->send_email($oldTolk->u_email, $oldTolk->u_firstName . " " . $oldTolk->u_lastName, $oldTolk_subject, $messageToOldTolkCancel);
                                        }
                                        $data["error"] = 0;
                                        echo json_encode($data);
                                    } else {
                                        session_regenerate_id();
                                        $data["error"] = 1;
                                        $data["messageHeader"] = "Error";
                                        $data["errorMessage"] = "Problem with fetching the Kund information.";
                                        echo json_encode($data);
                                    }
                                } else {
                                    $emailer->send_email($tolk->u_email, $tolk->u_firstName . " " . $tolk->u_lastName, $tolk_subject, $messageToTolkAssign);
                                    $emailer->send_email($order->o_email, $order->o_orderer, $customer_subject, $messageToCustomerAssign);
                                    $emailer->send_email($oldTolk->u_email, $oldTolk->u_firstName . " " . $oldTolk->u_lastName, $oldTolk_subject, $messageToOldTolkCancel);

                                    $data["error"] = 0;
                                    echo json_encode($data);
                                }
                            } else {
                                $data["error"] = 1;
                                $data["messageHeader"] = "Error";
                                $data["errorMessage"] = "Problem with Order log.";
                                echo json_encode($data);
                            }
                        }
                    } else {
                        $data["error"] = 1;
                        $data["messageHeader"] = "Error";
                        $data["errorMessage"] = "Problem with fetching the Interpreter.";
                        echo json_encode($data);
                    }
                } else {
                    $data["error"] = 1;
                    $data["messageHeader"] = "Error";
                    $data["errorMessage"] = "Problem with fetching the Order information.";
                    echo json_encode($data);
                }
            } else {
                $data["error"] = 1;
                $data["messageHeader"] = "Error";
                $data["errorMessage"] = "Problem fetching the old Tolk.";
                echo json_encode($data);
            }
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "Error";
            $data["errorMessage"] = "Problem with assigning the Interpreter to this order.";
            echo json_encode($data);
        }
    } catch (PDOException $e) {
        if ($db != null) {
            $db->disconnect();
        }
        return $e->getMessage();
    }
    if ($db != null) {
        $db->disconnect();
    }
}