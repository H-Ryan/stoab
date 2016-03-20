<?php
/**
 * User: Samuil
 * Date: 22-05-2015
 * Time: 4:06 PM
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
if (isset($_GET['newsID'])) {
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $id = $_GET['newsID'];
    try {
        $statement = $con->prepare("SELECT * FROM t_newsLetter WHERE n_ID=:id");
        $statement->bindParam(":id", $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if ($statement->rowCount() > 0) {
            $record = $statement->fetch();
            $data['error'] = 0;

            $title = $record->n_title . " - Publicerat: ";
            $date1 = new DateTime($record->n_time);
            $date2 = new DateTime(date("Y-m-d H:i:s"));
            $interval = $date1->diff($date2);
            if ($interval->format('%d') === "0") {
                $title = $title."Idag";
            } else {
                $title = $title.$interval->format('%d') . " dagar sedan.";
            }
            $data['id'] = $record->n_ID;
            $data['title'] = $record->n_title;
            $data['header'] = $title;
            $data['content'] = $record->n_text;
            $data['prescript'] = $record->n_postScript;
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
    $data['error'] = 1;
    $data['message'] = "Error! Some of the fields have not been set!";
    echo json_encode($data);
}
$db->disconnect();