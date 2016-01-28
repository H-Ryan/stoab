<?php
/**
 * User: Samuil
 * Date: 16-02-2015
 * Time: 21:46 PM
 */
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
include "../db/dbConfig.php";
include "../db/dbConnection.php";
include "functions.php";
require "../email/Emails.php";
$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit ("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='" . $_SERVER['SCRIPT_NAME'] . "'>try again</a>.");
}

$data = array();
if (isset($_POST['orderer']) && isset($_POST['organizationNumber']) && isset($_POST['clientNumber']) && isset($_POST['client']) && isset($_POST['language']) && isset($_POST['type'])
    && isset($_POST['tolk_type']) && isset($_POST['date']) && isset($_POST['start_hour']) && isset($_POST['start_minute'])
    && isset($_POST['end_hour']) && isset($_POST['end_minute']) && isset($_POST['contactPerson'])
    && isset($_POST['organization']) && isset($_POST['email']) && (isset($_POST['telephone']) || isset($_POST['mobile']))
    && isset($_POST['address']) && isset($_POST['post_code']) && isset($_POST['city'])
) {
    $orderer = $_POST['orderer'];
    $organizationNumber = $_POST['organizationNumber'];
    $clientNumber = $_POST['clientNumber'];
    $client = $_POST['client'];
    $language = $_POST['language'];
    $type = $_POST['type'];
    $tolk_type = $_POST['tolk_type'];
    $date = $_POST['date'];
    $start_hour = $_POST['start_hour'];
    $start_minute = $_POST['start_minute'];
    $end_hour = $_POST['end_hour'];
    $end_minute = $_POST['end_minute'];
    $contactPerson = $_POST['contactPerson'];
    $organization = $_POST['organization'];
    $email = $_POST['email'];
    $telephone = "";
    if (isset($_POST['telephone'])) {
        $telephone = $_POST['telephone'];
    }
    $mobile = "";
    if (isset($_POST['mobile'])) {
        $mobile = $_POST['mobile'];
    }
    $address = $_POST['address'];
    $post_code = $_POST['post_code'];
    $city = $_POST['city'];
    $message = "";
    if (isset($_POST['message'])) {
        $message = $_POST['message'];
    }
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $query = "SELECT l_languageName FROM t_languages WHERE l_languageID=:languageID";
        $statement = $con->prepare($query);
        $statement->bindParam(":languageID", $language);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $obj = $statement->fetch();

        if ($statement->rowCount() > 0) {
            $language = $obj->l_languageName;
        }

        function compareOrderNumber($number, $connection)
        {
            $query = "SELECT o_orderNumber FROM t_order WHERE o_orderNumber=:orderNumber";
            $statement = $connection->prepare($query);
            $statement->bindParam(":orderNumber", $number);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ);
            $obj = $statement->fetch();
            if ($statement->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }

        $orderNumber = genOrderNumber();
        while (compareOrderNumber($orderNumber, $con)) {
            $orderNumber = genOrderNumber();
        }
        $query = "INSERT INTO t_order (o_orderNumber, o_state, o_kunderPersonalNumber, o_kundNumber, "
            . "o_orderer, o_client, o_email, o_tel, o_mobile, o_address, o_zipCode, o_city, "
            . "o_language, o_tolkNiva, o_date, o_startTime, o_endTime, o_interpretationType,"
            . "o_creationTime, o_comments) VALUES (:orderNumber, :state, :organizationNumber, :clientNumber , :contactPerson, "
            . ":client, :email, :telephone, :mobile, :address, :zipCode, :city, :interpLanguage, :tolkType, "
            . ":interpDate, :startTime, :endTime, :interpType, :creationTime, :comment )";
        $state = 'O';
        $startTime = ((intval($start_hour) * 4) + intval($start_minute));
        $endTime = ((intval($end_hour) * 4) + intval($end_minute));
        $statement = $con->prepare($query);
        $statement->bindParam(":orderNumber", $orderNumber);
        $statement->bindParam(":state", $state);
        $statement->bindParam(":organizationNumber", $organizationNumber);
        $statement->bindParam(":clientNumber", $clientNumber);
        $statement->bindParam(":contactPerson", $contactPerson);
        $statement->bindParam(":client", $client);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":telephone", $telephone);
        $statement->bindParam(":mobile", $mobile);
        $statement->bindParam(":address", $address);
        $statement->bindParam(":zipCode", $post_code);
        $statement->bindParam(":city", $city);
        $statement->bindParam(":interpLanguage", $language);
        $statement->bindParam(":tolkType", $tolk_type);
        $statement->bindParam(":interpDate", $date);
        $statement->bindParam(":startTime", $startTime);
        $statement->bindParam(":endTime", $endTime);
        $statement->bindParam(":interpType", $type);
        $statement->bindParam(":creationTime", date("Y-m-d H:i:s"));
        $statement->bindParam(":comment", $message);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $query = "INSERT INTO t_enGangsKunder (e_orderNumber, e_organizationName) "
                . "VALUES (:orderNumber, :organizationName)";
            $statement = $con->prepare($query);
            $statement->bindParam(":orderNumber", $orderNumber);
            $statement->bindParam(":organizationName", $organization);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $query = "INSERT INTO t_orderLog (o_orderNumber, o_modifyPersonalNumber, o_involvedPersonalNumber, "
                    . "o_ipAddress ,o_state) VALUES (:orderNumber, :modifyPN, :involvedPN, :ipAddress, :state)";
                $statement = $con->prepare($query);
                $statement->bindParam(":orderNumber", $orderNumber);
                $statement->bindParam(":modifyPN", $orderer);
                $statement->bindParam(":involvedPN", $clientNumber);
                $statement->bindParam(":ipAddress", getRealIpAddress());
                $statement->bindParam(":state", $state);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $tolkType = array(
                        'ÖT' => 'Övriga Tolk',
                        'GT' => 'Godkänd Tolk',
                        'AT' => 'Auktoriserad Tolk',
                        'ST' => 'Sjukvårdstolk',
                        'RT' => 'Rättstolk',
                        'NI' => 'Inte viktigt'
                    );
                    $tolkType = $tolkType[$tolk_type];
                    $interpType = getFullTolkningType($type);
                    $timeStart = convertTime($startTime);
                    $timeEnd = convertTime($endTime);
                    $bodyToClient = "<!DOCTYPE html><html>
                    <head lang='en'>
                        <meta charset='UTF-8'>
                        <title></title>
                    </head>
                    <body style='color: #000000; text-align: center;'>
                    <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                        Tack för din beställning!<br/><br/>
                        Vänlingen kontrollera uppgifterna. Vi återkommer med bekräftelse så fort vi bokat rätt tolk till er.<br/>

                    </p>
                    <hr style='width: 80%;
                                    margin-left: 10%;'/>
                    <h2 style='text-align: center; margin-top: 5%;'>Beställning:</h2>

                    <h2 style='text-align: center; margin-top: 5%;'>Uppdragsnr: $orderNumber</h2>
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
                            <th style='background-color: #599CFF;
                                    font-size: 18px;
                                    padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; '>Kontaktperson/Fakturering:
                            </th>
                            <th style='background-color: #599CFF;
                                    font-size: 18px;
                                    padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; '>Uppdrag:
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr style='background-color: #d4e3e5;
                                    border-radius: 5px;'>
                            <td style='padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; width: 50%;'>
                                <div style='vertical-align:top; margin-top:5%;'>
                                    <div>
                                        <span style='font-weight:bold;'>Beställare:</span>
                                        <p>$contactPerson</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Företag/ Organisation:</span>
                                        <p>$organization</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>E-postadress:</span>
                                        <p>$email</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Telefon:</span>
                                        <p>$telephone</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Mobil:</span>
                                        <p>$mobile</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Plats:</span>
                                        <p>$address</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Postnummer:</span>
                                        <p>$post_code</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Ort:</span>
                                        <p>$city</p>
                                    </div>
                                </div>
                            </td>
                            <td style='padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; width: 50%;'>
                                <div style='vertical-align:top; margin-top:5%;'>
                                    <div>
                                        <span style='font-weight:bold;'>Klient: </span>
                                        <p>$client</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Språk: </span>
                                        <p>$language</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Typ tolkning: </span>
                                        <p>$interpType</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Datum: </span>
                                        <p>$date</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Starttid: </span>
                                        <p>$timeStart</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Sluttid: </span>
                                        <p>$timeEnd</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr style='width: 80%; margin-left: 10%;'/>
                    <div>
                        <p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
                            Om informationen ovan är felaktig eller om du vill ändra något, vänligen kontakta oss.
                            Med vänliga hälsningar STÖ AB.
                        </p>
                    </div>
                    <hr style='width: 80%; margin-left: auto;margin-right: auto;'/>
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
                    $bodyToCompany = "<!DOCTYPE html><html>
                    <head lang='en'>
                        <meta charset='UTF-8'>
                        <title></title>
                    </head>
                    <body style='color: #000000; text-align: center;'>
                    <hr style='width: 80%;
                                    margin-left: 10%;'/>
                    <h2 style='text-align: center; margin-top: 5%;'>Beställning:</h2>

                    <h2 style='text-align: center; margin-top: 5%;'>Uppdragsnr: " . $orderNumber . "</h2>
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
                            <th style='background-color: #599CFF;
                                    font-size: 18px;
                                    padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; '>Kontaktperson/Fakturering:
                            </th>
                            <th style='background-color: #599CFF;
                                    font-size: 18px;
                                    padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; '>Uppdrag:
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr style='background-color: #d4e3e5;
                                    border-radius: 5px;'>
                            <td style='padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; width: 50%;'>
                                <div style='vertical-align:top; margin-top:5%;'>
                                    <div>
                                        <span style='font-weight:bold;'>Beställare: </span>
                                        <p>$contactPerson</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Företag/ Organisation: </span>
                                        <p>$organization</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>E-postadress: </span>
                                        <p>$email</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Telefon:</span>
                                        <p>$telephone</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Mobil: </span>
                                        <p>$mobile</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Plats: </span>
                                        <p>$address</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Postnummer: </span>
                                        <p>$post_code</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Ort: </span>
                                        <p>$city</p>
                                    </div>
                                </div>
                            </td>
                            <td style='padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; width: 50%;'>
                                <div style='vertical-align:top; margin-top:5%;'>
                                    <div>
                                        <span style='font-weight:bold;'>Klient: </span>
                                        <p>$client</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Språk: </span>
                                        <p>$language</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Typ av tolkning: </span>
                                        <p>$interpType</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Datum: </span>
                                        <p>$date</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Starttid: </span>
                                        <p>$timeStart</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Sluttid: </span>
                                        <p>$timeEnd</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr style='width: 80%; margin-left: 10%;'/>
                    </body>
                    </html>";
                    $emailer = new Emails();

                    $subjectClient = "STÖ AB - Bokning";
                    $subjectCompany = "Ny order";
                    if ($organizationNumber != '0000000000') {
                        $query = "SELECT k_email FROM t_kunder WHERE k_kundNumber=:clientNumber AND k_personalNumber=:organizationNumber";

                        $statement = $con->prepare($query);
                        $statement->bindParam(":organizationNumber", $organizationNumber);
                        $statement->bindParam(":clientNumber", $clientNumber);
                        $statement->execute();
                        $statement->setFetchMode(PDO::FETCH_OBJ);
                        if ($statement->rowCount() > 0) {
                            $kund = $statement->fetch();
                            if ($kund->k_email === $email) {
                                $emailer->send_email($email, $contactPerson, $subjectClient, $bodyToClient);
                                $emailer->send_email("info@tolktjanst.se", "STÖ AB", $subjectCompany, $bodyToCompany);
                            } else {
                                $emailer->send_email($email, $contactPerson, $subjectClient, $bodyToClient);
                                $emailer->send_email($kund->k_email, $contactPerson, $subjectClient, $bodyToClient);
                                $emailer->send_email("info@tolktjanst.se", "STÖ AB", $subjectCompany, $bodyToCompany);
                            }
                        }
                    } else {
                        $emailer->send_email($email, $contactPerson, $subjectClient, $bodyToClient);
                        $emailer->send_email("info@tolktjanst.se", "STÖ AB", $subjectCompany, $bodyToCompany);
                    }

                    $data['error'] = 0;
                    $data['email'] = $email;
                    echo json_encode($data);
                } else {
                    $data['error'] = 1;
                    $data['header'] = "Order Log Error";
                    $data['errorMessage'] = "There was a problem with logging the order in the OrderLog";
                    echo json_encode($data);
                }
            } else {
                $data['error'] = 1;
                $data['header'] = "One Time Customer Error";
                $data['errorMessage'] = "There was a problem with adding the the One time customer.";
                echo json_encode($data);
            }

        } else {
            $data['error'] = 1;
            $data['header'] = "Order Creation Error";
            $data['errorMessage'] = "There was a problem with adding the Order in the Database!";
            echo json_encode($data);
        }

    } catch (PDOException $e) {
        return $e->getMessage();
    }
    if ($db != null) {
        $db->disconnect();
    }
} else {
    $data['error'] = 1;
    $data['header'] = "Fields Missing Error";
    $data['errorMessage'] = "Some of the required fields are missing!";
    echo json_encode($data);
}