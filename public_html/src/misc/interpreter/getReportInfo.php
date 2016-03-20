<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
include "../../db/dbConfig.php";
include "../../db/dbConnection.php";
include "../functions.php";
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
$db = null;
$data = [];
if (isset($_POST['orderId'])) {

    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $orderNumber = $_POST['orderId'];
        $statement = $con->prepare("SELECT * FROM t_order WHERE o_orderNumber =:orderNumber");
        $statement->bindParam(":orderNumber", $orderNumber);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if ($statement->rowCount() > 0) {
            $data["order"] = $statement->fetch();
            $orderNumber = $_POST['orderId'];
            $statement = $con->prepare("SELECT * FROM t_report WHERE r_orderNumber =:orderNumber");
            $statement->bindParam(":orderNumber", $orderNumber);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ);
            if ($statement->rowCount() > 0) {
                $data["report"] = $statement->fetch();
                $data["error"] = 0;
            } else {
                $data["error"] = 1;
                $data["messageHeader"] = "Header";
                $data["errorMessage"] = "Error. No report for that order.";
            }
        } else {
            $data["error"] = 2;
            $data["messageHeader"] = "Header";
            $data["errorMessage"] = "Error. No such order.";
        }
    } catch (PDOException $e) {
        $data["error"] = 3;
        $data["messageHeader"] = "Header";
        $data["errorMessage"] = "Database error";
    }
} else {
    $data["error"] = 4;
    $data["messageHeader"] = "Header";
    $data["errorMessage"] = "Error Message";
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);