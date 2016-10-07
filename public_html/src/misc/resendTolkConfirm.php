<?php
/**
 * User: Samuil
 * Date: 18-06-2015
 * Time: 10:59 AM.
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

            $interpType = getFullTolkningType($order->o_interpretationType);
            $timeStart = convertTime($order->o_startTime);
            $timeEnd = convertTime($order->o_endTime);
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
                $tolk_subject = 'Tolkning i Kristianstad AB - Uppdrag.';
                $messageToTolkAssign = "<!DOCTYPE html><html>
                        <head>
                            <meta http-equiv='Content-Type' content='text/html' charset='utf-8'>
                        </head>
                        <body>
                        <div>
                            <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                                Hej.<br/>Du har tilldelats följande tolkuppdrag. Uppgifter följer nedan.</p>
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
                                    <p><span style='font-weight:bold;'>Mobil:</span> " .$order->o_mobile."</p>
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
                                Kontakta oss om ni har några frågor.
                            </p>
                        </div>
                        <hr style='width: 80%;
                                        margin-left: 10%;'/>
                        <footer style='margin-left: 10%; width:80%'>
                        <h2>Tolkning i Kristianstad AB</h2>

                        <p><label style='font-weight:bold;'>ADRESS:</label> Industrigatan 2A, 291 36 Kristianstad</p>

                        <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:info@c4tolk.se'> info@c4tolk.se</a></p>

                        <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.c4tolk.se'> www.c4tolk.se</a></p>

                        <p><label style='font-weight:bold;'>TELEFON:</label> 010 516 42 10</p>

                            <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 559071-4134</p>

                        </footer>
                        </body>
                        </html>";

                $emailer->send_email($tolk->u_email, $tolk->u_firstName.' '.$tolk->u_lastName, $tolk_subject, $messageToTolkAssign);
                $data['error'] = 0;
                $data['messageHeader'] = 'Framgång';
                $data['positiveMessage'] = 'E-postmeddelandet har skickats.';
                echo json_encode($data);
            } else {
                $data['error'] = 1;
                $data['messageHeader'] = 'Error in Database!';
                $data['errorMessage'] = 'There was a problem executing the fetch Query!';
                echo json_encode($data);
            }
        } else {
            $data['error'] = 2;
            $data['messageHeader'] = 'Error in Database!';
            $data['errorMessage'] = 'There was a problem executing the fetch Query!';
            echo json_encode($data);
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    if ($db != null) {
        $db->disconnect();
    }
} else {
    $data['error'] = 3;
    $data['messageHeader'] = 'Fields Missing Error';
    $data['errorMessage'] = 'Some of the required fields are missing!';
    echo json_encode($data);
}
