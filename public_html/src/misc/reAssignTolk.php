<?php
/**
 * User: Samuil
 * Date: 22-02-2015
 * Time: 11:36 AM.
 */
//  error_reporting(E_ALL); ini_set('display_errors', 1);
// ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
include '../db/dbConfig.php';
include '../db/dbConnection.php';
include './functions.php';
include '../email/Emails.php';
include 'SMS_Service.php';

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
$db = null;

if (isset($_POST['tolkNumber']) && isset($_POST['orderNumber']) && isset($_POST['employee'])) {
    $tolkNumber = $_POST['tolkNumber'];
    $orderNumber = $_POST['orderNumber'];
    $employeeNumber = $_POST['employee'];
    $booked = 'B';
    $ipAddress = getRealIpAddress();
    $finance_subject = "C4Tolk (Ekonomi) - $orderNumber ";
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
            $query = 'SELECT u.u_email, u.u_firstName, u.u_lastName, t.* FROM t_tolkar AS t, t_users AS u WHERE u.u_role = 3'
                .' AND t.t_active = 1 AND t.t_personalNumber=:tolkPersonalNumber AND u.u_personalNumber = t.t_personalNumber';
            $statement = $con->prepare($query);
            $statement->bindParam(':tolkPersonalNumber', $order->o_tolkarPersonalNumber);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ);
            if ($statement->rowCount() > 0) {
                $oldTolk = $statement->fetch();
                $query = 'UPDATE t_order SET o_tolkarPersonalNumber = (SELECT t_personalNumber FROM t_tolkar WHERE t_tolkNumber=:tolkNumber), o_state=:booked WHERE o_orderNumber=:orderNumber;';
                $statement = $con->prepare($query);
                $statement->bindParam(':tolkNumber', $tolkNumber);
                $statement->bindParam(':orderNumber', $orderNumber);
                $statement->bindParam(':booked', $booked);
                $statement->execute();
                $statement->setFetchMode(PDO::FETCH_OBJ);
                if ($statement->rowCount() > 0) {
                    $query = 'SELECT * FROM t_order WHERE o_orderNumber=:orderNumber';
                    $statement = $con->prepare($query);
                    $statement->bindParam(':orderNumber', $orderNumber);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                    if ($statement->rowCount() > 0) {
                        $orderUpdated = $statement->fetch();
                        $query = 'SELECT t.t_tolkNumber, u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,'
                            .' u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,'
                            .' u.u_extraInfo, t.* FROM t_tolkar AS t, t_users AS u WHERE u.u_role = 3'
                            .' AND t.t_active = 1 AND t.t_tolkNumber=:tolkNumber AND u.u_personalNumber = t.t_personalNumber';
                        $statement = $con->prepare($query);
                        $statement->bindParam(':tolkNumber', $tolkNumber);
                        $statement->execute();
                        $statement->setFetchMode(PDO::FETCH_OBJ);
                        if ($statement->rowCount() > 0) {
                            $tolk = $statement->fetch();
                            $query = 'INSERT INTO t_orderLog (o_orderNumber, o_modifyPersonalNumber, o_involvedPersonalNumber, '
                                .'o_ipAddress ,o_state) VALUES (:orderNumber, :modifyPN, :involvedPN, :ipAddress, :state)';
                            $statement = $con->prepare($query);
                            $statement->bindParam(':orderNumber', $orderNumber);
                            $statement->bindParam(':modifyPN', $employeeNumber);
                            $statement->bindParam(':involvedPN', $tolkNumber);
                            $statement->bindParam(':ipAddress', $ipAddress);
                            $statement->bindParam(':state', $booked);
                            $statement->execute();
                            if ($statement->rowCount() > 0) {
                                $interpType = getFullTolkningType($orderUpdated->o_interpretationType);
                                $timeStart = convertTime($orderUpdated->o_startTime);
                                $timeEnd = convertTime($orderUpdated->o_endTime);

                                $oldTolk_subject = 'C4Tolk - Avbokning.';
                                $tolk_subject = 'C4Tolk - Uppdrag.';
                                $customer_subject = 'C4Tolk - Beviljade nytt tolk';

                                $messageToTolkAssign = "<!DOCTYPE html><html>
                                  <head>
                                      <meta http-equiv='Content-Type' content='text/html' charset='utf-8'>
                                  </head>
                                  <body>
                                  <div>
                                      <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                                          Hej,<br/>Var vänlig kontrollera följande uppdrag om informationen ovan är felaktig, vänligen kontakta oss via telefon.</p>
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
                                          Om informationen ovan är felaktig eller om du vill ändra något, vänligen kontakta oss via telefon.
                                      </p>
                                  </div>
                                  <hr style='width: 80%;
                                                  margin-left: 10%;'/>
                                  <footer style='margin-left: 10%; width:80%'>
                                  <h2>C4 SPRÅKPARTNER AB</h2>
                                  <p><label style='font-weight:bold;'>POSTADRESS:</label> BOX 21, 291 21 Kristianstad</p>
                                  <p><label style='font-weight:bold;'>ADRESS:</label> Nya boulevarden 10 (Våning3), 291 31 Kristianstad</p>

                                  <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:kundtjanst@c4tolk.se'> kundtjanst@c4tolk.se</a></p>

                                  <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.c4tolk.se'> www.c4tolk.se</a></p>

                                  <p><label style='font-weight:bold;'>TELEFON:</label> 010 562 42 10</p>

                                  <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 559071-4134</p>

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

                                  <h2 style='text-align: center; margin-top: 5%;'>Bokningsnummer: " .$orderUpdated->o_orderNumber."</h2>
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
                                              <p><span style='font-weight:bold;'>Datum:</span> " .$orderUpdated->o_date."</p>

                                              <p><span style='font-weight:bold;'>Starttid:</span> " .$timeStart."</p>

                                              <p><span style='font-weight:bold;'>Sluttid:</span> " .$timeEnd."</p>

                                              <p><span style='font-weight:bold;'>Plats:</span> " .$orderUpdated->o_address."</p>

                                              <p><span style='font-weight:bold;'>Postnummer:</span> " .$orderUpdated->o_zipCode."</p>

                                              <p><span style='font-weight:bold;'>Ort:</span> " .$orderUpdated->o_city."</p>

                                              <p><span style='font-weight:bold;'>Typ av uppdrag:</span> " .$interpType."</p>

                                              <p><span style='font-weight:bold;'>Språk:</span> " .$orderUpdated->o_language."</p>

                                              <p><span style='font-weight:bold;'>Klient:</span> " .$orderUpdated->o_client."</p>

                                              <p><span style='font-weight:bold;'>Kontaktperson:</span> " .$orderUpdated->o_orderer."</p>

                                              <p><span style='font-weight:bold;'>Telefonnr:</span> " .$orderUpdated->o_tel."</p>

                                              <p><span style='font-weight:bold;'>Mobile:</span> " .$orderUpdated->o_mobile."</p>

                                              <p><span style='font-weight:bold;'>E-postadress:</span> " .$orderUpdated->o_email."</p>
                                          </td>
                                          <td style='background-color: #e9e9e9;padding: 8px;border: 1px solid #a9c6c9;'>
                                              <p><span style='font-weight:bold;'>Namn:</span> " .$tolk->u_firstName.' '.$tolk->u_lastName."</p>

                                              <p><span style='font-weight:bold;'>Telefonnr:</span> " .$tolk->u_tel."</p>

                                              <p><span style='font-weight:bold;'>Mobile:</span> 0" .$tolk->u_mobile."</p>

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
                                          Om informationen ovan är felaktig eller om du vill ändra något, vänligen kontakta oss via telefon.
                                      </p>
                                  </div>
                                  <hr style='width: 80%;
                                                  margin-left: 10%;'/>
                                  <footer style='margin-left: 10%; width:80%'>
                                  <h2>C4 SPRÅKPARTNER AB</h2>
                                  <p><label style='font-weight:bold;'>POSTADRESS:</label> BOX 21, 291 21 Kristianstad</p>
                                  <p><label style='font-weight:bold;'>ADRESS:</label> Nya boulevarden 10 (Våning3), 291 31 Kristianstad</p>

                                  <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:kundtjanst@c4tolk.se'> kundtjanst@c4tolk.se</a></p>

                                  <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.c4tolk.se'> www.c4tolk.se</a></p>

                                  <p><label style='font-weight:bold;'>TELEFON:</label> 010 562 42 10</p>

                                  <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 559071-4134</p>

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
                                  <h2>C4 SPRÅKPARTNER AB</h2>
                                  <p><label style='font-weight:bold;'>POSTADRESS:</label> BOX 21, 291 21 Kristianstad</p>
                                  <p><label style='font-weight:bold;'>ADRESS:</label> Nya boulevarden 10 (Våning3), 291 31 Kristianstad</p>

                                  <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:kundtjanst@c4tolk.se'> kundtjanst@c4tolk.se</a></p>

                                  <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.c4tolk.se'> www.c4tolk.se</a></p>

                                  <p><label style='font-weight:bold;'>TELEFON:</label> 010 562 42 10</p>

                                  <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 559071-4134</p>

                                  </footer>
                                  </body>
                                  </html>";

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
                                            $emailer->send_email($tolk->u_email, $tolk->u_firstName.' '.$tolk->u_lastName, $tolk_subject, $messageToTolkAssign);
                                            $emailer->send_email($order->o_email, $order->o_orderer, $customer_subject, $messageToCustomerAssign);
                                            $emailer->send_email($oldTolk->u_email, $oldTolk->u_firstName.' '.$oldTolk->u_lastName, $oldTolk_subject, $messageToOldTolkCancel);
                                        } else {
                                            $emailer->send_email($tolk->u_email, $tolk->u_firstName.' '.$tolk->u_lastName, $tolk_subject, $messageToTolkAssign);
                                            $emailer->send_email($order->o_email, $order->o_orderer, $customer_subject, $messageToCustomerAssign);
                                            $emailer->send_email($kund->k_email, $kund->k_organizationName, $customer_subject, $messageToCustomerAssign);
                                            $emailer->send_email($oldTolk->u_email, $oldTolk->u_firstName.' '.$oldTolk->u_lastName, $oldTolk_subject, $messageToOldTolkCancel);
                                        }
                                        $data['error'] = 0;
                                    } else {
                                        $data['error'] = 1;
                                        $data['messageHeader'] = 'Error';
                                        $data['errorMessage'] = 'Problem with fetching the Kund information.';
                                    }
                                } else {
                                    $emailer->send_email($tolk->u_email, $tolk->u_firstName.' '.$tolk->u_lastName, $tolk_subject, $messageToTolkAssign);
                                    $emailer->send_email($order->o_email, $order->o_orderer, $customer_subject, $messageToCustomerAssign);
                                    $emailer->send_email($oldTolk->u_email, $oldTolk->u_firstName.' '.$oldTolk->u_lastName, $oldTolk_subject, $messageToOldTolkCancel);
                                    $data['error'] = 0;
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

                                                    <p><span style='font-weight:bold;'>Mobil:</span> 0" .$tolk->u_mobile."</p>

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
                                    $emailer->send_email('ekonomi@sarvari.se', 'Ekonomi', $finance_subject, $messageToCustomerAssign);
                                }
                                //SMS
                                $smsService = new SMS_Service();
                                $text = 'Hej, '
                                    ."du har ett uppdrag ($orderNumber). "
                                    .'Var vänlig kontrollera din e-post. '
                                    .'OBS! Du kan inte svara på detta meddelande. '
                                    .'Mvh C4 SPRÅKPARTNER AB';
                                $smsService->setTo($tolk->u_mobile);
                                $smsService->setText($text);
                                $data['smsURL'] = $smsService->generateSMS()->sendSMS();

                                $messageToFinance = "<!DOCTYPE html><html>
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

                                              <p><span style='font-weight:bold;'>Mobil:</span> 0" .$tolk->u_mobile."</p>

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
                                $emailer->send_email('ekonomi@sarvari.se', 'Ekonomi', $finance_subject, $messageToFinance);
                            } else {
                                $data['error'] = 1;
                                $data['messageHeader'] = 'Error';
                                $data['errorMessage'] = 'Problem with Order log.';
                            }
                        }
                    } else {
                        $data['error'] = 1;
                        $data['messageHeader'] = 'Error';
                        $data['errorMessage'] = 'Problem with fetching the Interpreter.';
                    }
                } else {
                    $data['error'] = 1;
                    $data['messageHeader'] = 'Error';
                    $data['errorMessage'] = 'This interpreter has already been assigned to this session!';
                }
            } else {
                $data['error'] = 1;
                $data['messageHeader'] = 'Error';
                $data['errorMessage'] = 'Problem fetching the old Tolk.';
            }
        } else {
            $data['error'] = 1;
            $data['messageHeader'] = 'Error';
            $data['errorMessage'] = 'Problem with assigning the Interpreter to this order.';
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

    session_regenerate_id();
    echo json_encode($data);
}
