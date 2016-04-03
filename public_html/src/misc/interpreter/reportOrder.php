<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
header('Content-type: application/json');
include "../../db/dbConfig.php";
include "../../db/dbConnection.php";
include "../functions.php";
require "../../email/Emails.php";
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
$db = null;
if (isset($_POST['tolk_number']) && isset($_POST['rep_mission_id']) && isset($_POST['rep_extra_time']) && isset($_POST['rep_customer_name'])) {

    $rep_mission_id = $_POST['rep_mission_id'];
    $rep_extra_time = $_POST['rep_extra_time'];
    $rep_customer_name = $_POST['rep_customer_name'];
    $tolkNumber = $_POST['tolk_number'];

    $rep_outlay = "";
    if (isset($_POST['rep_outlay'])) {
        $rep_outlay = $_POST['rep_outlay'];
    }
    $rep_time = "";
    if (isset($_POST['rep_hours']) && isset($_POST['rep_minutes'])) {
        $rep_time = ($_POST['rep_hours'] * 4) + isset($_POST['rep_minutes']);
    }
    $rep_mileage = 0;
    if (isset($_POST['rep_mileage'])) {
        $rep_mileage = $_POST['rep_mileage'];
    }
    $rep_ticket_cost = 0;
    if (isset($_POST['rep_ticket_cost'])) {
        $rep_ticket_cost = $_POST['rep_ticket_cost'];
    }
    $rep_comments = "";
    if (isset($_POST['rep_comments'])) {
        $rep_comments = $_POST['rep_comments'];
    }

    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $query = "INSERT INTO t_report (r_orderNumber, r_extraTime, r_carDistance, r_ticketCost, "
            . "r_travelTime, r_comments, r_customerName, r_reportTime) "
            . "VALUES (:rep_mission_id, :rep_extra_time, :rep_mileage, :rep_ticket_cost , :rep_time, "
            . ":rep_comments, :rep_customer_name, :r_reportTime)";
        $statement = $con->prepare($query);
        $date = date("Y-m-d H:i:s");
        $statement->bindParam(":rep_mission_id", $rep_mission_id);
        $statement->bindParam(":rep_extra_time", $rep_extra_time);
        $statement->bindParam(":rep_mileage", $rep_mileage);
        $statement->bindParam(":rep_ticket_cost", $rep_ticket_cost);
        $statement->bindParam(":rep_time", $rep_time);
        $statement->bindParam(":rep_comments", $rep_comments);
        $statement->bindParam(":rep_customer_name", $rep_customer_name);
        $statement->bindParam(":r_reportTime", $date);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $query = "UPDATE t_order SET o_state =:state WHERE o_orderNumber=:orderNumber;";
            $statement = $con->prepare($query);
            $state = 'R';
            $statement->bindParam(":orderNumber", $rep_mission_id);
            $statement->bindParam(":state", $state);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ);
            $orders = array();
            if ($statement->rowCount() > 0) {
                $query = "SELECT u.u_firstName, u.u_lastName, u.u_email, t.* FROM t_tolkar AS t, t_users AS u
WHERE (u.u_role = 3 OR u.u_role = 1) AND t.t_active = 1 AND t.t_tolkNumber=:tolkNumber AND u.u_personalNumber = t.t_personalNumber";
                $statement = $con->prepare($query);
                $statement->bindParam(":tolkNumber", $tolkNumber);
                $statement->execute();
                $statement->setFetchMode(PDO::FETCH_OBJ);
                if ($statement->rowCount() > 0) {
                    $tolk = $statement->fetch();
                    $emailer = new Emails();

                    $contact_email_content = file_get_contents("../../emailTemplates/report-confirmation-email.html");
                    $var = ["{rep_mission_id}", "{rep_extra_time}", "{rep_travel_time}", "{rep_mileage}",
                        "{rep_ticket_cost}", "{rep_client_name}", "{rep_comment}"];
                    $val = [$rep_mission_id, getExtraTime($rep_extra_time), $_POST['rep_hours'].":".$_POST['rep_minutes'], $rep_mileage." km",
                        $rep_ticket_cost." SEK", $rep_customer_name, $rep_comments];
                    $emailContent = str_replace($var, $val, $contact_email_content);

                    $subject = "STÖ AB - Uppdrag rapporten är klar: $rep_mission_id";

                    $data['error'] = ($emailer->send_email($tolk->u_email, $tolk->u_firstName." ".$tolk->u_lastName, $subject, $emailContent)) ? 0 : 1;
                } else {
                    $data["error"] = 1;
                    $data["messageHeader"] = "Fel";
                    $data["errorMessage"] = "Det finns ingen tolk med denna tolk nummer. Försök igen.";
                }
            } else {
                $data['error'] = 2;
            }
        } else {
            $data['error'] = 3;
        }

    } catch (PDOException $e) {
        $data['error'] = 4;
        $data['message'] = $e->getMessage();
    }
} else {
    $data['error'] = 5;
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);