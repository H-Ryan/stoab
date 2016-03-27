<?php
/**
 * User: samuil
 * Date: 3/27/2016
 * Time: 11:26 PM
 */
ini_set("session.use_only_cookies", true);
ini_set("session.use_trans_sid", false);
include "../db/dbConfig.php";
include "../db/dbConnection.php";
include "./functions.php";
session_start();

$referrer = $_SERVER['HTTP_REFERER'];
if ( ! empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit ("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='" . $_SERVER['SCRIPT_NAME'] . "'>try again</a>.");
}

$data = array();

$db = null;
if (isset($_POST['orderNumber']) && isset($_POST['employee']) && isset($_POST['date']) && isset($_POST['start_hour']) && isset($_POST['start_minute'])
    && isset($_POST['end_hour']) && isset($_POST['end_minute']) && isset($_POST['orderer']) && isset($_POST['email'])
    && (isset($_POST['telephone']) || isset($_POST['mobile'])) && isset($_POST['address'])
) {

    $orderNumber    = $_POST['orderNumber'];
    $employeeNumber = $_POST['employee'];
    $date           = $_POST['date'];
    $start_hour     = $_POST['start_hour'];
    $start_minute   = $_POST['start_minute'];
    $end_hour       = $_POST['end_hour'];
    $end_minute     = $_POST['end_minute'];
    $contactPerson  = $_POST['orderer'];
    $email          = $_POST['email'];
    $address        = $_POST['address'];
    $telephone      = "";
    $mobile         = "";
    if (isset($_POST['telephone'])) {
        $telephone = $_POST['telephone'];
    }
    if (isset($_POST['mobile'])) {
        $mobile = $_POST['mobile'];
    }

    $startTime = ((intval($start_hour) * 4) + intval($start_minute));
    $endTime   = ((intval($end_hour) * 4) + intval($end_minute));

    $ipAddress = getRealIpAddress();

    try {
        $db  = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $query       = "UPDATE t_order SET o_orderer=:contactPerson, o_email=:email, o_tel=:telephone,o_mobile=:mobile,
 o_address=:address, o_date=:interpDate, o_startTime=:startTime, o_endTime=:endTime WHERE o_orderNumber=:orderNumber;";
        $statement   = $con->prepare($query);
        $statement->bindParam(":contactPerson", $contactPerson);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":telephone", $telephone);
        $statement->bindParam(":mobile", $mobile);
        $statement->bindParam(":address", $address);
        $statement->bindParam(":interpDate", $date);
        $statement->bindParam(":startTime", $startTime);
        $statement->bindParam(":endTime", $endTime);
        $statement->bindParam(":orderNumber", $orderNumber);
        if ($statement->execute() > 0) {
            $query = "INSERT INTO t_orderLog (o_orderNumber, o_modifyPersonalNumber, o_involvedPersonalNumber, "
                     . "o_ipAddress ,o_state) VALUES (:orderNumber, :modifyPN, :involvedPN, :ipAddress, :state)";
            $statement = $con->prepare($query);
            $state = 'modified';
            $involved = '';
            $statement->bindParam(":orderNumber", $orderNumber);
            $statement->bindParam(":modifyPN", $employeeNumber);
            $statement->bindParam(":involvedPN", $state);
            $statement->bindParam(":ipAddress", $ipAddress);
            $statement->bindParam(":state", $state);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $data['error'] = 0;
            } else {
                $data['error'] = 1;
            }
        } else {
            $data['error'] = 2;
        }
    } catch (PDOException $e) {
        $data['error'] = $e->getMessage();
    }

} else {
    $data["error"] = 4;
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);