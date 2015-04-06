<?php
/**
 * User: Samuil
 * Date: 05-02-2015
 * Time: 9:44 PM
 */
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
require "../db/dbConfig.php";
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
if(isset($_POST['number']) && isset($_POST['password']))
{
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $kundNumber = $_POST['number'];
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
            $statement = $con->prepare("INSERT INTO t_loginLog (l_personalNumber, l_ipAddress) VALUES (:personalNumber, :ip)");
            $statement->bindParam(":personalNumber", $personalNumber);
            $statement->bindParam(":ip", getRealIpAddress());
            $statement->execute();
            $data["error"] = 0;
            session_regenerate_id();
            $_SESSION['organization_number']= $personalNumber;
            $_SESSION['user_number']= $customerNumber;
            echo json_encode($data);
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "";
            $data["errorMessage"] = "Du har angivit ett felaktigt användarnamn eller lösenord. Försök igen.";
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