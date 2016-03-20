<?php
/**
 * User: Samuil
 * Date: 18-06-2015
 * Time: 3:20 PM
 */

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
header('Content-type: application/json');
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

$data = [];
$db = null;
if (isset($_POST['organizationNumber']) && isset($_POST['clientNumber']) && isset($_POST['client']) && isset($_POST['language']) && isset($_POST['type'])
    && isset($_POST['tolk_type']) && isset($_POST['date']) && isset($_POST['start_hour']) && isset($_POST['start_minute'])
    && isset($_POST['end_hour']) && isset($_POST['end_minute']) && isset($_POST['contactPerson'])
    && isset($_POST['organization']) && isset($_POST['email']) && (isset($_POST['telephone']) || isset($_POST['mobile']))
    && isset($_POST['address']) && isset($_POST['post_code']) && isset($_POST['city'])
) {

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

        $startTime = ((intval($start_hour) * 4) + intval($start_minute));
        $endTime = ((intval($end_hour) * 4) + intval($end_minute));

        $timeStart = convertTime($startTime);
        $timeEnd = convertTime($endTime);

        $bodyToCompany = "<!DOCTYPE html><html>
                    <head lang='en'>
                        <meta charset='UTF-8'>
                        <title></title>
                    </head>
                    <body style='color: #000000; text-align: center;'>
                    <hr style='width: 80%;
                                    margin-left: 10%;'/>
                    <h2 style='text-align: center; margin-top: 5%;'>Beställning från webbplats:</h2>

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
                    <div style='width:70%;
                                    margin-top:3%;
                                    margin-bottom:1.5%;
                                    margin-left:15%;
                                    background-color: #d4e3e5;
                                    padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9;'>
                        <label style='font-weight:bold;'>Kommentar:</label>

                        <p>$message</p>
                    </div>
                    <hr style='width: 80%; margin-left: 10%;'/>
                    </body>
                    </html>";
        $emailer = new Emails();

        $subjectCompany = "Ny order - (Från webbplats)";

        $query = "SELECT k_email FROM t_kunder WHERE k_kundNumber=:clientNumber AND k_personalNumber=:organizationNumber";

        $data['error'] = ($emailer->send_email("info@tolktjanst.se", "STÖ AB", $subjectCompany, $bodyToCompany)) ? 0 : 1;

    } catch (PDOException $e) {
        $data['error'] = 2;
    }
} else {
    $data['error'] = 3;

}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);