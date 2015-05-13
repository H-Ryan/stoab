<?php
/**
 * User: Samuil
 * Date: 15-02-2015
 * Time: 4:45 PM
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
if(isset($_POST['organizationNumber']) && isset($_POST['clientNumber']) && isset($_POST['currentPage']))
{
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $organizationNumber = $_POST['organizationNumber'];
        $clientNumber = $_POST['clientNumber'];
        $pageNum = $_POST['currentPage'];
        $end = $pageNum * 10;
        $start = $end - 10;

        $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $statement = $con->prepare("SELECT o_orderNumber, o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state FROM t_order WHERE o_kunderPersonalNumber =:organizationNumber AND o_kundNumber=:clientNumber ORDER BY o_date DESC LIMIT :start, 10");
        $statement->bindParam(":organizationNumber", $organizationNumber);
        $statement->bindParam(":clientNumber", $clientNumber);

        $statement->bindParam(":start", $start, PDO::PARAM_INT);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if($statement->rowCount() >= 0)
        {
            $data["error"] = 0;
            $data['orders'] = array();
            $i = 0;
            if ($statement->rowCount() > 0 ) {
                while($order = $statement->fetch()) {
                    $data['orders'][$i] = $order;
                    $i++;
                }
            }
            echo json_encode($data);
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "Header";
            $data["errorMessage"] = "Error Message";
            echo json_encode($data);
        }
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