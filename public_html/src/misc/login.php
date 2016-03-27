<?php
/**
 * User: Samuil
 * Date: 05-02-2015
 * Time: 9:44 PM
 */
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');
require "../db/dbConfig.php";
include "../db/dbConnection.php";
include "functions.php";

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
if(isset($_POST['customerNumber']) && isset($_POST['password']))
{
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $kundNumber = $_POST['customerNumber'];
        $password = encrypt_password($_POST['password']);
        $user_role = 4;
        $isBlocked = 0;
        $statement = $con->prepare("SELECT k_personalNumber, k_kundNumber FROM t_kunder WHERE k_kundNumber=:kundNumber AND k_password=:password AND k_role=:role AND k_blocked=:isBlocked");
        $statement->bindParam(":kundNumber", $kundNumber);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":role", $user_role);
        $statement->bindParam(":isBlocked", $isBlocked);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $user = $statement->fetch();
        if($statement->rowCount() > 0)
        {
            $personalNumber = $user->k_personalNumber;
            $customerNumber = $user->k_kundNumber;
            $ipAddress = getRealIpAddress();
            $statement = $con->prepare("INSERT INTO t_loginLog (l_personalNumber, l_ipAddress) VALUES (:personalNumber, :ip)");
            $statement->bindParam(":personalNumber", $personalNumber);
            $statement->bindParam(":ip", $ipAddress);
            $statement->execute();
            $data["error"] = 0;
            session_regenerate_id();
            $_SESSION['organization_number']= $personalNumber;
            $_SESSION['user_number']= $customerNumber;
        } else {
            $data["error"] = 3;
        }
    } catch (PDOException $e) {
        $data["error"] = 2;
    }
} else {
    $data["error"] = 1;
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);