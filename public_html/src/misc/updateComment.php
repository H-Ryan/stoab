<?php
/**
 * User: Samuil
 * Date: 18-09-2015
 * Time: 1:25 PM
 */

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
include "../db/dbConfig.php";
include "../db/dbConnection.php";
include "./functions.php";
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
if(isset($_POST['orderNumber']) && isset($_POST['data']))
{
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $orderNumber = $_POST['orderNumber'];
        $newComment = $_POST['data'];
        $query = "UPDATE t_order SET o_comments =:comment WHERE o_orderNumber=:orderNumber;";
        $statement = $con->prepare($query);
        $statement->bindParam(":comment", $newComment);
        $statement->bindParam(":orderNumber", $orderNumber);
        if($statement->execute() > 0)
        {
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }
        echo json_encode($data);
    } catch (PDOException $e) {
        return $e->getMessage();
    }

} else {
    $data["error"] = 1;
    echo json_encode($data);
}
if ($db != null) {
    $db->disconnect();
}