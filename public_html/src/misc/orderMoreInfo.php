<?php
/**
 * User: Samuil
 * Date: 24-02-2015
 * Time: 11:58 AM
 */
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
include "../db/dbConfig.php";
include "../db/dbConnection.php";
include "functions.php";
session_start();

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
if (isset($_POST['orderId'])) {
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $orderNumber = $_POST['orderId'];
        $statement = $con->prepare("SELECT o_orderNumber,o_kunderPersonalNumber, o_state, o_address, o_zipCode, o_city, o_client, o_tel, o_mobile, o_comments, o_tolkarPersonalNumber FROM t_order WHERE o_orderNumber=:orderNumber");
        $statement->bindParam(":orderNumber", $orderNumber);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_OBJ);
        if ($statement->rowCount() >= 0) {
            $order = $statement->fetch();
            if ($order->o_tolkarPersonalNumber != null) {
                try {
                    $tolkNum = $order->o_tolkarPersonalNumber;
                    $query = "SELECT u.u_firstName, u.u_lastName, u.u_email, u.u_tel, u.u_mobile, u.u_city, t.t_tolkNumber"
                        . " FROM t_tolkar AS t, t_users AS u WHERE "
                        . "t.t_active = 1 AND u.u_personalNumber=:personalNumber AND t.t_personalNumber=:tolkPersonalNumber";
                    $statement = $con->prepare($query);
                    $statement->bindParam(":personalNumber", $tolkNum);
                    $statement->bindParam(":tolkPersonalNumber", $tolkNum);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                    if ($statement->rowCount() > 0) {
                        $tolk =  $statement->fetch();
                        $orgPersonalNum = $order->o_kunderPersonalNumber;
                        $query = "SELECT k_organizationName FROM t_kunder WHERE k_personalNumber=:orgPersonalNum";
                        $statement = $con->prepare($query);
                        $statement->bindParam(":orgPersonalNum", $orgPersonalNum);
                        $statement->execute();

                        $statement->setFetchMode(PDO::FETCH_OBJ);
                        if ($statement->rowCount() > 0) {
                            $data["error"] = 0;
                            $data['order'] = $order;
                            $data['tolk'] = $tolk;
                            $data['org'] = $statement->fetch();
                        } else {
                            $data["error"] = 1;
                            $data["messageHeader"] = "Header";
                            $data["errorMessage"] = "Error Message";
                        }
                    } else {
                        $data["error"] = 2;
                        $data["messageHeader"] = "Header";
                        $data["errorMessage"] = "Error Message";
                    }
                } catch (PDOException $e) {
                    $data["error"] = 3;
                    $data["messageHeader"] = "Header";
                    $data["errorMessage"] = "Error Message";
                }
            } else {
                $data["error"] = 0;
                $data['order'] = $order;
            }
        } else {
            $data["error"] = 4;
            $data["messageHeader"] = "Header";
            $data["errorMessage"] = "Error Message";
        }
    } catch
    (PDOException $e) {
        $data["error"] = 5;
        $data["messageHeader"] = "Header";
        $data["errorMessage"] = "Error Message";
    }
} else {
    $data["error"] = 6;
    $data["messageHeader"] = "Header";
    $data["errorMessage"] = "Error Message";
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);