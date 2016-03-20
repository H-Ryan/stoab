<?php
/**
 * User: Samuil
 * Date: 27-05-2015
 * Time: 2:12 PM
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
try {
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $statement = $con->query("SELECT * FROM t_newsLetter WHERE n_time >= CURRENT_DATE() - 30 AND n_flag=1  ORDER BY n_time DESC");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_OBJ);
    $data['records'] = array();
    if ($statement->rowCount() >= 0) {

        $data['error'] = 0;
        while($row = $statement->fetch()) {
            $title = $row->n_title . " - Publicerat: ";
            $date1 = new DateTime($row->n_time);
            $date2 = new DateTime(date("Y-m-d H:i:s"));
            $interval = $date1->diff($date2);
            if ($interval->format('%d') === "0") {
                $title = $title."Idag";
            } else {
                $title = $title.$interval->format('%d') . " dagar sedan.";
            }

            $record['id'] = $row->n_ID;
            $record['header'] = $title;
            $record['prescript'] = $row->n_postScript;

            $data['records'][] = $record;
        }
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
$db->disconnect();