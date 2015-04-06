<?php
/**
 * User: Samuil
 * Date: 18-02-2015
 * Time: 11:49 AM
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
if(isset($_POST['organizationNumber'])
    && isset($_POST['clientNumber'])
    && isset($_POST['oldPassword'])
    && isset($_POST['newPass'])
    && isset($_POST['newPassRep']))
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
        $newPass = encrypt_password($_POST['newPass']);
        $oldPass = encrypt_password($_POST['oldPassword']);
        $statement = $con->prepare("UPDATE t_kunder SET k_password = :newPass WHERE k_personalNumber = :organizationNumber AND k_kundNumber=:clientNumber AND k_password =:oldPass ");
        $statement->bindParam(":newPass", $newPass);
        $statement->bindParam(":oldPass", $oldPass);
        $statement->bindParam(":organizationNumber", $organizationNumber);
        $statement->bindParam(":clientNumber", $clientNumber);
        $statement->execute();
        if($statement->rowCount() > 0)
        {
            $data["error"] = 0;
            session_regenerate_id();
            echo json_encode($data);
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "Fel";
            $data["errorMessage"] = "Lösenordet du har skrivit är felaktig.";
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