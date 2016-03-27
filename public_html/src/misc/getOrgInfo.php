<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 7:09 PM
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
$db = null;
if (isset($_POST['branch_number'])) {
    $branch_number = $_POST['branch_number'];
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $query = "SELECT k_kundNumber, k_personalNumber, k_firstName, k_lastName, k_email, k_tel, k_mobile, k_address, k_zipCode, k_city FROM t_kunder WHERE k_kundNumber=:branch_number";
        $statement = $con->prepare($query);
        $statement->bindParam(":branch_number", $branch_number);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if ($statement->rowCount() > 0) {
            $data["error"] = 0;
            $data['orgInfo'] = $statement->fetch();
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "Header";
            $data["errorMessage"] = "Error Message";
        }
    } catch (PDOException $e) {
        $data["error"] = 1;
        $data["messageHeader"] = "Header";
        $data["errorMessage"] = "Database error";
    }
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);