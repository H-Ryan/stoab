<?php
/**
 * User: Samuil
 * Date: 19-06-2015
 * Time: 12:04 PM
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
$db = null;
$data = array();
if(isset($_POST['orderNumber']) ||
    (isset($_POST['tolkNumber']) || (isset($_POST['tolkNumber']) && isset($_POST['dateFilter']))) ||
    (isset($_POST['clientNumber']) || (isset($_POST['clientNumber']) && isset($_POST['dateFilter']))))
{

    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $orderNum = $_POST['orderNumber'];
    $tolkNum = $_POST['tolkNumber'];
    $clientNum = $_POST['clientNumber'];
    $dateFilter = $_POST['dateFilter'];

    try {
        $statement = null;
        if (!empty($orderNum)) {
            $statement = $con->prepare("SELECT * FROM t_order WHERE o_orderNumber=:orderNum AND o_date <= CURRENT_DATE - 1");
            $statement->bindParam(":orderNum", $orderNum);
        } else {
            if (!empty($tolkNum)) {
                if (!empty($dateFilter)) {
                    $statement = $con->prepare("SELECT * FROM t_order WHERE o_date >=:dateFilter AND o_date <= CURRENT_DATE - 1 AND o_tolkarPersonalNumber IN (SELECT t_personalNumber FROM t_tolkar WHERE t_tolkNumber=:tolkNum) ORDER BY o_date DESC");
                    $statement->bindParam(":dateFilter", $dateFilter);
                    $statement->bindParam(":tolkNum", $tolkNum);
                } else {
                    $statement = $con->prepare("SELECT * FROM t_order WHERE o_date <= CURRENT_DATE - 1 AND o_tolkarPersonalNumber IN (SELECT t_personalNumber FROM t_tolkar WHERE t_tolkNumber=:tolkNum) AND o_date >= DATE_ADD(CURDATE(), INTERVAL -100 DAY) ORDER BY o_date DESC");
                    $statement->bindParam(":tolkNum", $tolkNum);
                }
            } else if (!empty($clientNum)) {
                if (!empty($dateFilter)) {
                    $statement = $con->prepare("SELECT * FROM t_order WHERE o_date >=:dateFilter AND o_date <= CURRENT_DATE - 1 AND o_kundNumber=:clientNum  ORDER BY o_date DESC");
                    $statement->bindParam(":dateFilter", $dateFilter);
                    $statement->bindParam(":clientNum", $clientNum);
                } else {
                    $statement = $con->prepare("SELECT * FROM t_order WHERE o_kundNumber=:clientNum AND o_date <= CURRENT_DATE - 1 AND o_date >= DATE_ADD(CURDATE(), INTERVAL -100 DAY) ORDER BY o_date DESC");
                    $statement->bindParam(":clientNum", $clientNum);
                }
            } else {
                $data["error"] = 0;
                $data['orders'] = array();
                return;
            }
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
            $data["error1"] = 2;
            $data["messageHeader"] = "Header";
            $data["errorMessage"] = "Error Message";
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }

} else {
    $data["error"] = 1;
    $data["error1"] = 3;
    $data["messageHeader"] = "Header";
    $data["errorMessage"] = "Error Message";
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);