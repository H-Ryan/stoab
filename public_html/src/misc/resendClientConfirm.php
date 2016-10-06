<?php
/**
 * User: Samuil
 * Date: 18-06-2015
 * Time: 1:38 PM.
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

if (isset($_POST['orderNumber']) && !empty($_POST['orderNumber'])) {
    $orderNum = $_POST['orderNumber'];
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

            $statementTwo = $con->prepare('SELECT * FROM t_enGangsKunder WHERE e_orderNumber =:orderNumber');
            $statementTwo->bindParam(':orderNumber', $orderNum);
            $statementTwo->execute();
            $statementTwo->setFetchMode(PDO::FETCH_OBJ);
            if ($statementTwo->rowCount() > 0) {
                $organizationName = $statementTwo->fetch()->e_organizationName;

                $subjectClient = 'Tolkning i Kristianstad AB - Bokning';

                $orgNum = $order->o_kunderPersonalNumber;
                $clientNum = $order->o_kundNumber;
                $contactPerson = $order->o_orderer;
                $email = $order->o_email;

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

                    <h2 style='text-align: center; margin-top: 5%;'>Uppdragsnr: $order->o_orderNumber</h2>
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
                            <th style='background-color: #ff9900;
                                    font-size: 18px;
                                    padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; '>Kontaktperson/Fakturering:
                            </th>
                            <th style='background-color: #ff9900;
                                    font-size: 18px;
                                    padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; '>Uppdrag:
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr style='background-color: #e9e9e9;
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
                                        <p>$organizationName</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>E-postadress:</span>
                                        <p>$email</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Telefon:</span>
                                        <p>$order->o_tel</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Mobil:</span>
                                        <p>$order->o_mobile</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Plats:</span>
                                        <p>$order->o_address</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Postnummer:</span>
                                        <p>$order->o_zipCode</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Ort:</span>
                                        <p>$order->o_city</p>
                                    </div>
                                </div>
                            </td>
                            <td style='padding: 8px;
                                    border-radius: inherit;
                                    border: 1px solid #a9c6c9; width: 50%;'>
                                <div style='vertical-align:top; margin-top:5%;'>
                                    <div>
                                        <span style='font-weight:bold;'>Klient: </span>
                                        <p>$order->o_client</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Språk: </span>
                                        <p>$order->o_language</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Typ tolkning: </span>
                                        <p>$interpType</p>
                                    </div>
                                    <div>
                                        <span style='font-weight:bold;'>Datum: </span>
                                        <p>$order->o_date</p>
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
                            Med vänliga hälsningar Tolkning i Kristianstad AB.
                        </p>
                    </div>
                    <hr style='width: 80%; margin-left: auto;margin-right: auto;'/>
                    <footer style='margin-left: 10%; width:80%'>
                    <h2>Tolkning i Kristianstad AB</h2>

                    <p><label style='font-weight:bold;'>ADRESS:</label> Industrigatan 2, 291 36 Kristianstad</p>

                    <p><label style='font-weight:bold;'>E-POST:</label> <a href='mailto:info@c4tolk.se'> info@c4tolk.se</a></p>

                    <p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.c4tolk.se'> www.c4tolk.se</a></p>

                    <p><label style='font-weight:bold;'>TELEFON:</label> 010 516 42 10</p>
                            <p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 556951-0802</p>

                            </footer>
                    </body>
                    </html>";

                if ($orgNum != '0000000000') {
                    $query = 'SELECT k_email FROM t_kunder WHERE k_kundNumber=:clientNumber AND k_personalNumber=:organizationNumber';

                    $statement = $con->prepare($query);
                    $statement->bindParam(':organizationNumber', $orgNum);
                    $statement->bindParam(':clientNumber', $clientNum);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                    if ($statement->rowCount() > 0) {
                        $kund = $statement->fetch();
                        if ($kund->k_email === $email) {
                            $emailer->send_email($email, $contactPerson, $subjectClient, $bodyToClient);
                        } else {
                            $emailer->send_email($email, $contactPerson, $subjectClient, $bodyToClient);
                            $emailer->send_email($kund->k_email, $contactPerson, $subjectClient, $bodyToClient);
                        }
                    }
                } else {
                    $emailer->send_email($email, $contactPerson, $subjectClient, $bodyToClient);
                }
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
            $data['error'] = 3;
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
    $data['error'] = 4;
    $data['messageHeader'] = 'Fields Missing Error';
    $data['errorMessage'] = 'Some of the required fields are missing!';
    echo json_encode($data);
}
