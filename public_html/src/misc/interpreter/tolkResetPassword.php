<?php
/**
 * User: Samuil
 * Date: 18-02-2015
 * Time: 11:49 AM
 */

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
include "../../db/dbConfig.php";
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
$data = [];
$db = null;
if(isset($_POST['tolkEmail'])
    && isset($_POST['oldPassword'])
    && isset($_POST['newPass'])
    && isset($_POST['newPassRep']))
{

    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $tolkEmail = $_POST['tolkEmail'];
        $pNumber = $_POST['pNumber'];
        $newPass = encrypt_password($_POST['newPass']);
        $oldPass = encrypt_password($_POST['oldPassword']);
        $statement = $con->prepare("UPDATE t_users SET u_password = :newPass WHERE u_personalNumber = :pNumber AND u_email=:tolkEmail AND u_password =:oldPass ");
        $statement->bindParam(":newPass", $newPass);
        $statement->bindParam(":oldPass", $oldPass);
        $statement->bindParam(":pNumber", $pNumber);
        $statement->bindParam(":tolkEmail", $tolkEmail);
        $statement->execute();
        if($statement->rowCount() > 0)
        {
            $data["error"] = 0;
            session_regenerate_id();
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "Fel";
            $data["errorMessage"] = "Lösenordet du har skrivit är felaktig.";
        }
    } catch (PDOException $e) {
        $data["error"] = 2;
        $data["errorMessage"] = $e->getMessage();
    }
} else {
    $data["error"] = 3;
    $data["messageHeader"] = "Header";
    $data["errorMessage"] = "Error Message";
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);