<?php
/**
 * User: Samuil
 * Date: 09-05-2015
 * Time: 7:50 PM
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
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}

$data = array();
if(isset($_GET['organizationNumber']) && isset($_GET['clientNumber']))
{
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $organizationNumber = $_GET['organizationNumber'];
        $clientNumber = $_GET['clientNumber'];

        $statement = $con->prepare("SELECT COUNT(*) AS id FROM t_order WHERE o_kunderPersonalNumber =:organizationNumber AND o_kundNumber=:clientNumber");
        $statement->bindParam(":organizationNumber", $organizationNumber);
        $statement->bindParam(":clientNumber", $clientNumber);
        $statement->execute();
        $num = $statement->fetchColumn();

        $data["error"] = 0;
        $data["numOfOrders"] = $num;
        echo json_encode($data);
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    if ($db != null) {
        $db->disconnect();
    }
} else {
    $data["error"] = 1;
    $data["messageHeader"] = "Header";
    $data["errorMessage"] = "Error Message";
    echo json_encode($data);
}