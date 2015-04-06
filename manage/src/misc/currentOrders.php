<?php
/**
 * User: Samuil
 * Date: 15-02-2015
 * Time: 4:45 PM
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
if(isset($_POST['code']))
{
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $statement = $con->prepare("SELECT o_orderNumber, o_kundNumber, o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state FROM t_order WHERE o_date >= CURRENT_DATE ORDER BY o_date DESC");
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if($statement->rowCount() >= 0)
        {
            $data["error"] = 0;
            $data['orders'] = array();
            $data['customers'] = array();
            $i = 0;
            while($order = $statement->fetch()) {
                $statementTwo = $con->prepare("SELECT k_organizationName FROM t_kunder WHERE k_kundNumber=:clientNumber");
                $statementTwo->bindParam(":clientNumber", $order->o_kundNumber);
                $statementTwo->execute();
                $statementTwo->setFetchMode(PDO::FETCH_OBJ);
                if ($statementTwo->rowCount() > 0) {
                    $data['customers'][$i] = $statementTwo->fetch();
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