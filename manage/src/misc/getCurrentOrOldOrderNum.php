<?php
/**
 * User: Samuil
 * Date: 09-05-2015
 * Time: 3:42 PM
 */
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
include "../../../src/db/dbConfig.php";
include "../../../src/db/dbConnection.php";
include "../../../src/misc/functions.php";
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
if(isset($_GET['isManage']))
{
    $isManage = filter_input(INPUT_GET, "isManage", FILTER_VALIDATE_BOOLEAN,
        array("flags" => FILTER_NULL_ON_FAILURE));
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $num = 0;
        if ($isManage) {
            $num = $con->query("SELECT COUNT(*) AS id FROM t_order WHERE o_date >= CURRENT_DATE")->fetchColumn();
        } else {
            $num = $con->query("SELECT COUNT(*) AS id FROM t_order WHERE o_date <= CURRENT_DATE - 1 AND o_date >= CURRENT_DATE - 100")->fetchColumn();
        }
        $data["error"] = 0;
        $data["numOfOrders"] = $num;
        echo json_encode($data);
    } catch (PDOException $e) {
        echo $e->getMessage();
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