<?php
/**
 * User: Samuil
 * Date: 10-05-2015
 * Time: 9:36 PM
 */
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
include "../../../src/db/dbConfig.php";
include "../../../src/db/dbConnection.php";
include "../../../src/misc/functions.php";

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit ("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='" . $_SERVER['SCRIPT_NAME'] . "'>try again</a>.");
}

if (isset($_POST['newsTitle']) && isset($_POST['newsLetter'])) {
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $data = "<h2 class='newsLetterTitle'>".$_POST['newsTitle']."</h2>".$_POST['newsLetter'];

    try {
        $statement = $con->prepare("INSERT INTO t_newsLetter (n_Text, n_Time) VALUES (:text, :cratedOn)");
        $statement->bindParam(":text", $data);
        $statement->bindParam(":cratedOn", date("Y-m-d H:i:s"));
        $statement->execute();
        if ($statement->rowCount() > 0) {
            echo json_encode("The news were successfully added!");
        } else {
            if ($db != null) {
                $db->disconnect();
            }
            echo json_encode("Error!");
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }
} else {
    if ($db != null) {
        $db->disconnect();
    }
    header('Location: ../../index.php');
}