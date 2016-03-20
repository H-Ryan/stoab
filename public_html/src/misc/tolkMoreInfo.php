<?php
/**
 * User: Samuil
 * Date: 11-09-2015
 * Time: 12:13 PM
 */
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
include "../db/dbConfig.php";
include "../db/dbConnection.php";
include "./functions.php";

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
$tolkPN = $_GET['tolkPN'];

$db = null;
try {
    $db = new dbConnection(HOST, DATABASE, USER, PASS);
    $con = $db->get_connection();
    $isActive = 1;
    $query = "";
    $statement = null;
    if (!empty($tolkPN)) {
        $query = "SELECT * FROM t_tolkSprak WHERE t_personalNumber=:tolkPN AND t_sprakName<>''";
        $statement = $con->prepare($query);
        $statement->bindParam(":tolkPN", $tolkPN);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if ($statement->rowCount() > 0) {

            $data["error"] = 0;
            $data['langs'] = array();
            $i = 0;
            while ($language = $statement->fetch()) {
                $data['langs'][$i] = $language;
                $i++;
             }
            $statement = $con->prepare("SELECT * FROM t_order WHERE o_date >= CURRENT_DATE AND o_tolkarPersonalNumber=:tolkNum ORDER BY o_date");
            $statement->bindParam(":tolkNum", $tolkPN);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ);
            $i = 0;
            if ($statement->rowCount() > 0) {
                while($order = $statement->fetch()) {
                    $statementTwo = $con->prepare("SELECT k_organizationName FROM t_kunder WHERE k_kundNumber=:clientNumber");
                    $statementTwo->bindParam(":clientNumber", $order->o_kundNumber);
                    $statementTwo->execute();
                    $statementTwo->setFetchMode(PDO::FETCH_OBJ);
                    if ($statementTwo->rowCount() > 0) {
                        $data['customers'][$i] = $statementTwo->fetch();
                        $data['orders'][$i] = $order;
                    }
                    $i++;

                }
                $data["f"] = $i;
            }
            echo json_encode($data);
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "Header";
            $data["errorMessage"] = "Error Message";
            echo json_encode($data);
        }
    } else {
        $data["error"] = 1;
        $data["messageHeader"] = "Header";
        $data["errorMessage"] = "Error Message";
        echo json_encode($data);
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
if ($db != null) {
    $db->disconnect();
}