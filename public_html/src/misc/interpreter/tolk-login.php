<?php
/**
 * User: Samuil
 * Date: 05-02-2015
 * Time: 9:44 PM
 */
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
require "../../db/dbConfig.php";
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
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}

$data = array();
$db = null;
if(isset($_POST['interpreter_email']) && isset($_POST['interpreter_password']))
{
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $tolkEmail = $_POST['interpreter_email'];
        $password = encrypt_password($_POST['interpreter_password']);
        $user_role = 3;
        $isBlocked = 0;
        $statement = $con->prepare("SELECT t_personalNumber, t_tolkNumber FROM t_tolkar WHERE t_personalNumber IN(SELECT u_personalNumber FROM t_users WHERE u_email=:tolkEmail AND u_password=:password AND (u_role=:role OR u_role=1)  AND u_blocked=:isBlocked)");
        $statement->bindParam(":tolkEmail", $tolkEmail);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":role", $user_role);
        $statement->bindParam(":isBlocked", $isBlocked);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $user = $statement->fetch();
        if($statement->rowCount() > 0)
        {
            $personalNumber = $user->t_personalNumber;
            $customerNumber = $user->t_tolkNumber;
            $statement = $con->prepare("INSERT INTO t_loginLog (l_personalNumber, l_ipAddress) VALUES (:personalNumber, :ip)");
            $ipAddress = getRealIpAddress();
            $statement->bindParam(":personalNumber", $personalNumber);
            $statement->bindParam(":ip", $ipAddress);
            $statement->execute();
            $data["error"] = 0;
            session_regenerate_id();
            $_SESSION['personal_number']= $personalNumber;
            $_SESSION['tolk_number']= $customerNumber;
        } else {
            $data["error"] = 1;
        }
    } catch (PDOException $e) {
        $data["error"] = 2;
    }
} else {
    $data["error"] = 3;
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);