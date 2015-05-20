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
$data = [];
if (isset($_POST['newsTitle']) && isset($_POST['newsPrescript'])&& isset($_POST['newsContent'])) {
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $title = "<span class='newsLetterTitle'>".$_POST['newsTitle']."</span>";
    $postScript = "<p class='newsLetterPrescript'>".$_POST['newsPrescript']."</p>";
    $content = $_POST['newsContent'];
    $content = $_POST['newsContent'];
    try {
        $statement = $con->prepare("INSERT INTO t_newsLetter (n_title, n_postScript, n_time, n_text) VALUES (:title,:postScript, :cratedOn, :content)");
        $statement->bindParam(":title", $title);
        $statement->bindParam(":postScript", $postScript);
        $statement->bindParam(":cratedOn", date("Y-m-d H:i:s"));
        $statement->bindParam(":content", $content);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $data['error'] = 0;
            $data['message'] = "The news were successfully added!";
            echo json_encode($data);
        } else {
            if ($db != null) {
                $db->disconnect();
            }
            $data['error'] = 1;
            $data['message'] = "Error! There was a problem with the database!";
            echo json_encode($data);
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }
} else {
    if ($db != null) {
        $db->disconnect();
    }
    $data['error'] = 1;
    $data['message'] = "Error! Some of the fields have not been set!";
    echo json_encode($data);
}