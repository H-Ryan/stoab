<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
include "../../db/dbConfig.php";
include "../../db/dbConnection.php";
include "../functions.php";
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

$data = [];
$db = null;
if(isset($_POST['personal_number']) && isset($_POST['currentPage']))
{
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $personal_number = $_POST['personal_number'];
        $pageNum = $_POST['currentPage'];
        $state = 'R';
        $end = $pageNum * 10;
        $start = $end - 10;

        $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $statement = $con->prepare("SELECT o_orderNumber, o_orderer, o_language, o_interpretationType, o_date, o_startTime, o_endTime, o_state
FROM t_order WHERE o_tolkarPersonalNumber =:personal_number AND o_state<>:state
AND (o_date <= CURRENT_DATE - 1 OR ((o_date + 1) = CURRENT_DATE
AND TIMESTAMP(o_date + 1, '08:15:00') <= NOW()))
ORDER BY o_date ASC LIMIT :start, 10");
        $statement->bindParam(":personal_number", $personal_number);
        $statement->bindParam(":state", $state);
        $statement->bindParam(":start", $start, PDO::PARAM_INT);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if($statement->rowCount() >= 0)
        {
            $data["error"] = 0;
            $data['orders'] = array();
            $i = 0;
            if ($statement->rowCount() > 0 ) {
                while($order = $statement->fetch()) {
                    $data['orders'][$i] = $order;
                    $i++;
                }
            }
            $statement = $con->prepare("SELECT COUNT(*) AS id FROM t_order
WHERE o_tolkarPersonalNumber =:personal_number AND o_state<>:state
AND (o_date <= CURRENT_DATE - 1 OR ((o_date + 1) = CURRENT_DATE
AND TIMESTAMP(o_date + 1, '08:15:00') <= NOW()))");
            $statement->bindParam(":personal_number", $personal_number);
            $statement->bindParam(":state", $state);
            $statement->execute();
            $data['num'] = $statement->fetchColumn();
        } else {
            $data["error"] = 1;
        }
    } catch (PDOException $e) {
        $data["error"] = 2;
    }
} else {
    $data["error"] = 3;
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);