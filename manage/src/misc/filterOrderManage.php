<?php
/**
 * User: Samuil
 * Date: 24-09-2015
 * Time: 2:23 PM
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
$db = null;
if(isset($_POST['orderNumber']) || isset($_POST['clientNumber']) || (isset($_POST['clientNumber']) && isset($_POST['orderNumber'])))
{
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $orderNum = $_POST['orderNumber'];
    $clientNum = $_POST['clientNumber'];

    try {
        $statement = null;
        if (!empty($orderNum) || !empty($clientNum) || (!empty($orderNum) && !empty($clientNum))) {
            if (!empty($orderNum) && empty($clientNum)) {
                $statement = $con->prepare("SELECT * FROM t_order WHERE o_orderNumber=:orderNum AND (o_date >= CURRENT_DATE OR ((DATE_ADD(o_date, INTERVAL +1 DAY)) = CURRENT_DATE AND TIMESTAMP(DATE_ADD(o_date, INTERVAL +1 DAY), '08:15:00') > NOW()))");
                $statement->bindParam(":orderNum", $orderNum);
            } else if(empty($orderNum) && !empty($clientNum)) {
                $statement = $con->prepare("SELECT * FROM t_order WHERE o_kundNumber=:clientNum AND (o_date >= CURRENT_DATE OR ((DATE_ADD(o_date, INTERVAL +1 DAY)) = CURRENT_DATE AND TIMESTAMP(DATE_ADD(o_date, INTERVAL +1 DAY), '08:15:00') > NOW())) ORDER BY o_date");
                $statement->bindParam(":clientNum", $clientNum);
            } else {
                $statement = $con->prepare("SELECT * FROM t_order WHERE o_orderNumber=:orderNum AND (o_kundNumber=:clientNum AND o_date >= CURRENT_DATE OR ((DATE_ADD(o_date, INTERVAL +1 DAY)) = CURRENT_DATE AND TIMESTAMP(DATE_ADD(o_date, INTERVAL +1 DAY), '08:15:00') > NOW())) ORDER BY o_date");
                $statement->bindParam(":orderNum", $orderNum);
                $statement->bindParam(":clientNum", $clientNum);

            }
        } else {
            $data["error"] = 0;
            $data['orders'] = array();
            echo json_encode($data);
            if ($db != null) {
                $db->disconnect();
            }
            return;
        }

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
            $data['num'] = sizeof($data['orders']);
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "Header";
            $data["errorMessage"] = "Error Message";
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }

} else {
    $data["error"] = 1;
    $data["messageHeader"] = "Header";
    $data["errorMessage"] = "Error Message";

}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);