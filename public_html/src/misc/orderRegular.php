<?php
/**
 * User: Samuil
 * Date: 16-02-2015
 * Time: 21:46 PM
 */
ini_set("session.use_only_cookies", true);
ini_set("session.use_trans_sid", false);
session_start();
include "../db/dbConfig.php";
include "../db/dbConnection.php";
include "functions.php";
require "../email/Emails.php";
$referrer = $_SERVER['HTTP_REFERER'];
if ( ! empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit ("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='" . $_SERVER['SCRIPT_NAME'] . "'>try again</a>.");
}
function compareOrderNumber($number, $connection)
{
    $query     = "SELECT o_orderNumber FROM t_order WHERE o_orderNumber=:orderNumber";
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

$data = [];
$db   = null;
if (isset($_POST['orderer']) && isset($_POST['organizationNumber']) && isset($_POST['clientNumber']) && isset($_POST['client'])
    && isset($_POST['language']) && isset($_POST['type']) && isset($_POST['date']) && isset($_POST['start_hour']) && isset($_POST['start_minute'])
    && isset($_POST['end_hour']) && isset($_POST['end_minute']) && isset($_POST['contactPerson'])
    && isset($_POST['organization']) && isset($_POST['email']) && (isset($_POST['telephone']) || isset($_POST['mobile']))
    && isset($_POST['address']) && isset($_POST['post_code']) && isset($_POST['city'])
) {
    $orderer            = $_POST['orderer'];
    $organizationNumber = $_POST['organizationNumber'];
    $clientNumber       = $_POST['clientNumber'];
    $client             = $_POST['client'];
    $language           = $_POST['language'];
    $type               = $_POST['type'];
    $tolk_type          = isset($_POST['tolk_type']) ? $_POST['tolk_type'] : "";
    $date               = $_POST['date'];
    $start_hour         = $_POST['start_hour'];
    $start_minute       = $_POST['start_minute'];
    $end_hour           = $_POST['end_hour'];
    $end_minute         = $_POST['end_minute'];
    $contactPerson      = $_POST['contactPerson'];
    $organization       = $_POST['organization'];
    $email              = $_POST['email'];
    $telephone          = "";
    if (isset($_POST['telephone'])) {
        $telephone = $_POST['telephone'];
    }
    $mobile = "";
    if (isset($_POST['mobile'])) {
        $mobile = $_POST['mobile'];
    }
    $address   = $_POST['address'];
    $post_code = $_POST['post_code'];
    $city      = $_POST['city'];
    $message   = "";
    if (isset($_POST['message'])) {
        $message = $_POST['message'];
    }
    try {
        $db        = new dbConnection(HOST, DATABASE, USER, PASS);
        $con       = $db->get_connection();
        $query     = "SELECT l_languageName FROM t_languages WHERE l_languageID=:languageID";
        $statement = $con->prepare($query);
        $statement->bindParam(":languageID", $language);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $obj = $statement->fetch();

        if ($statement->rowCount() > 0) {
            $language = $obj->l_languageName;
        }

        $orderNumber = genOrderNumber();
        while (compareOrderNumber($orderNumber, $con)) {
            $orderNumber = genOrderNumber();
        }
        $query     = "INSERT INTO t_order (o_orderNumber, o_state, o_kunderPersonalNumber, o_kundNumber, "
                     . "o_orderer, o_client, o_email, o_tel, o_mobile, o_address, o_zipCode, o_city, "
                     . "o_language, o_tolkNiva, o_date, o_startTime, o_endTime, o_interpretationType,"
                     . "o_creationTime, o_comments) VALUES (:orderNumber, :state, :organizationNumber, :clientNumber , :contactPerson, "
                     . ":client, :email, :telephone, :mobile, :address, :zipCode, :city, :interpLanguage, :tolkType, "
                     . ":interpDate, :startTime, :endTime, :interpType, :creationTime, :comment )";
        $state     = 'O';
        $dateNow   = date("Y-m-d H:i:s");
        $startTime = ((intval($start_hour) * 4) + intval($start_minute));
        $endTime   = ((intval($end_hour) * 4) + intval($end_minute));
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
        $statement->bindParam(":creationTime", $dateNow);
        $statement->bindParam(":comment", $message);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $query     = "INSERT INTO t_enGangsKunder (e_orderNumber, e_organizationName) "
                         . "VALUES (:orderNumber, :organizationName)";
            $statement = $con->prepare($query);
            $statement->bindParam(":orderNumber", $orderNumber);
            $statement->bindParam(":organizationName", $organization);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $query     = "INSERT INTO t_orderLog (o_orderNumber, o_modifyPersonalNumber, o_involvedPersonalNumber, "
                             . "o_ipAddress ,o_state) VALUES (:orderNumber, :modifyPN, :involvedPN, :ipAddress, :state)";
                $statement = $con->prepare($query);

                $ordererIP = getRealIpAddress();

                $statement->bindParam(":orderNumber", $orderNumber);
                $statement->bindParam(":modifyPN", $orderer);
                $statement->bindParam(":involvedPN", $clientNumber);
                $statement->bindParam(":ipAddress", $ordererIP);
                $statement->bindParam(":state", $state);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $tolkType              = array(
                        'ÖT' => 'Övriga Tolk',
                        'GT' => 'Godkänd Tolk',
                        'AT' => 'Auktoriserad Tolk',
                        'ST' => 'Sjukvårdstolk',
                        'RT' => 'Rättstolk',
                        'NI' => 'Inte viktigt',
                        '' => ''
                    );
                    $tolkType              = $tolkType[$tolk_type];
                    $interpType            = getFullTolkningType($type);
                    $timeStart             = convertTime($startTime);
                    $timeEnd               = convertTime($endTime);
                    $client_email_content  = file_get_contents("../emailTemplates/interpretation-order-client-email.html");
                    $company_email_content = file_get_contents("../emailTemplates/interpretation-order-company-email.html");
                    $var                   = [
                        "{orderNumber}",
                        "{contactPerson}",
                        "{organization}",
                        "{email}",
                        "{telephone}",
                        "{mobile}",
                        "{address}",
                        "{post_code}",
                        "{city}",
                        "{client}",
                        "{language}",
                        "{interpType}",
                        "{date}",
                        "{timeStart}",
                        "{timeEnd}"
                    ];
                    $val                   = [
                        $orderNumber,
                        $contactPerson,
                        $organization,
                        $email,
                        $telephone,
                        $mobile,
                        $address,
                        $post_code,
                        $city,
                        $client,
                        $language,
                        $interpType,
                        $date,
                        $timeStart,
                        $timeEnd
                    ];
                    $bodyToClient          = str_replace($var, $val, $client_email_content);
                    $bodyToCompany         = str_replace($var, $val, $company_email_content);
                    $emailer               = new Emails();

                    $subjectClient  = "STÖ AB - Bokning";
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
                                if ($emailer->send_email($email, $contactPerson, $subjectClient, $bodyToClient) &&
                                    $emailer->send_email("info@tolktjanst.se", "STÖ AB", $subjectCompany,
                                        $bodyToCompany)
                                ) {
                                    $data['error'] = 0;
                                } else {
                                    $data['error']        = 1;
                                    $data['header']       = "Email error";
                                    $data['errorMessage'] = "There was a problem sending the emails!";
                                }
                            } else {
                                if ($emailer->send_email($email, $contactPerson, $subjectClient, $bodyToClient) &&
                                    $emailer->send_email($kund->k_email, $contactPerson, $subjectClient,
                                        $bodyToClient) &&
                                    $emailer->send_email("info@tolktjanst.se", "STÖ AB", $subjectCompany,
                                        $bodyToCompany)
                                ) {
                                    $data['error'] = 0;
                                } else {
                                    $data['error']        = 1;
                                    $data['header']       = "Email error";
                                    $data['errorMessage'] = "There was a problem sending the emails!";
                                }
                            }
                        }
                    } else {
                        if ($emailer->send_email($email, $contactPerson, $subjectClient, $bodyToClient) &&
                            $emailer->send_email("info@tolktjanst.se", "STÖ AB", $subjectCompany, $bodyToCompany)
                        ) {
                            $data['error'] = 0;
                        } else {
                            $data['error']        = 1;
                            $data['header']       = "Email error";
                            $data['errorMessage'] = "There was a problem sending the emails!";
                        }
                    }
                } else {
                    $data['error']        = 2;
                    $data['header']       = "Order Log Error";
                    $data['errorMessage'] = "There was a problem with logging the order in the OrderLog";
                }
            } else {
                $data['error']        = 3;
                $data['header']       = "One Time Customer Error";
                $data['errorMessage'] = "There was a problem with adding the the One time customer.";
            }

        } else {
            $data['error']        = 4;
            $data['header']       = "Order Creation Error";
            $data['errorMessage'] = "There was a problem with adding the Order in the Database!";
        }

    } catch (PDOException $e) {
        $data['error']        = 5;
        $data['header']       = "Database Error";
        $data['errorMessage'] = "An error occurred during establishing a connection with the database or error in the query!";
    }

} else {
    $data['error']        = 6;
    $data['header']       = "Fields Missing Error";
    $data['errorMessage'] = "Some of the required fields are missing!";
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);