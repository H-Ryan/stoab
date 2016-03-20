<?php
/**
 * User: Samuil
 * Date: 27-05-2015
 * Time: 12:38 PM
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
$data = [];
if (isset($_POST['newsId']) && isset($_POST['newsTitle']) && isset($_POST['newsPrescript'])&& isset($_POST['newsContent'])) {
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $id = $_POST['newsId'];
    $title = "<span class='newsLetterTitle'>".$_POST['newsTitle']."</span>";
    $postScript = "<p class='newsLetterPrescript'>".$_POST['newsPrescript']."</p>";
    $content = $_POST['newsContent'];
    $content = $_POST['newsContent'];
    try {
        $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $statement = $con->prepare("UPDATE t_newsLetter SET n_title=:title, n_postScript=:postScript, n_text=:content WHERE n_ID=:id");
        $statement->bindParam(":title", $title);
        $statement->bindParam(":postScript", $postScript);
        $statement->bindParam(":content", $content);
        $statement->bindParam(":id", $id);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $data['error'] = 0;
            $data['message'] = "The news were successfully updated!";
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