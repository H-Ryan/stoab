<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 11:53 PM
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

$data = array();
if (isset($_POST['tolkNumber'])) {
    $tolkNumber = $_POST['tolkNumber'];
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,"
            ." u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,"
            ." u.u_extraInfo, t.* FROM t_tolkar AS t, t_users AS u WHERE u.u_role = 3"
            ." AND t.t_active = 1 AND t.t_tolkNumber=:tolkNumber AND u.u_personalNumber = t.t_personalNumber";
        $statement = $con->prepare($query);
        $statement->bindParam(":tolkNumber", $tolkNumber);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if($statement->rowCount() > 0)
        {
            $data["error"] = 0;
            $data['tolk'] = $statement->fetch();
            echo json_encode($data);
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "Fel";
            $data["errorMessage"] = "Det finns ingen tolk med denna tolk nummer. Försök igen.";
            echo json_encode($data);
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    if ($db != null) {
        $db->disconnect();
    }
}