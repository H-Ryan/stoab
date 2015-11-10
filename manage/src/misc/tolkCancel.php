<?php
/**
 * User: Samuil
 * Date: 09-11-2015
 * Time: 7:47 PM
 */

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
include "../../../src/db/dbConfig.php";
include "../../../src/db/dbConnection.php";
include "../../../src/misc/functions.php";
include "../../../src/email/Emails.php";
include "SMS_Service.php";

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

if (isset($_POST['orderNumber']) && isset($_POST['employee'])) {
    $orderNumber = $_POST['orderNumber'];
    $employeeNumber = $_POST['employee'];
    $canceled = "IC";
    $emptyTolk = null;
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
            $interpType = getFullTolkningType($order->o_interpretationType);
            $timeStart = convertTime($order->o_startTime);
            $timeEnd = convertTime($order->o_endTime);

            if ($order->o_tolkarPersonalNumber != null) {
                $query = "SELECT t.t_tolkNumber, u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,"
                    . " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,"
                    . " u.u_extraInfo, t.* FROM t_tolkar AS t, t_users AS u WHERE u.u_role = 3"
                    . " AND t.t_active = 1 AND t.t_personalNumber=:tolkPersonalNumber AND u.u_personalNumber =:tolkPersonalNumber";
                $statement = $con->prepare($query);
                $statement->bindParam(":tolkPersonalNumber", $order->o_tolkarPersonalNumber);
                $statement->execute();
                $statement->setFetchMode(PDO::FETCH_OBJ);
                if ($statement->rowCount() > 0) {
                    $tolk = $statement->fetch();
                    $query = "UPDATE t_order SET o_tolkarPersonalNumber =:tolkEmpty, o_state=:canceled WHERE o_orderNumber=:orderNumber;";
                    $statement = $con->prepare($query);
                    $statement->bindParam(":tolkEmpty", $emptyTolk);
                    $statement->bindParam(":orderNumber", $orderNumber);
                    $statement->bindParam(":canceled", $canceled);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                    if ($statement->rowCount() > 0) {
                        $query = "INSERT INTO t_orderLog (o_orderNumber, o_modifyPersonalNumber, o_involvedPersonalNumber, "
                            . "o_ipAddress ,o_state) VALUES (:orderNumber, :modifyPN, :involvedPN, :ipAddress, :state)";
                        $statement = $con->prepare($query);
                        $statement->bindParam(":orderNumber", $orderNumber);
                        $statement->bindParam(":modifyPN", $employeeNumber);
                        $statement->bindParam(":involvedPN", $order->o_kundNumber);
                        $statement->bindParam(":ipAddress", getRealIpAddress());
                        $statement->bindParam(":state", $canceled);
                        $statement->execute();
                        if ($statement->rowCount() > 0) {
                            $data["error"] = 0;
                        }else {
                            $data["error"] = 1;
                            $data["messageHeader"] = "Error";
                            $data["errorMessage"] = "Problem with Order log.";
                        }
                    } else {
                        $data["error"] = 1;
                        $data["messageHeader"] = "Error";
                        $data["errorMessage"] = "The order has already been canceled.";
                    }
                } else {
                    $data["error"] = 1;
                    $data["messageHeader"] = "Error";
                    $data["errorMessage"] = "Problem with selecting the tolk assigned to this order.";
                }
            }
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "Error";
            $data["errorMessage"] = "Problem with selecting this order.";
        }
    } catch (PDOException $e) {
        if ($db != null) {
            $db->disconnect();
        }
        return $e->getMessage();
    }
}
if ($db != null) {
    $db->disconnect();
}
session_regenerate_id();
echo json_encode($data);