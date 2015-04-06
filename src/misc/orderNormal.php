<?php
/**
 * User: Samuil
 * Date: 09-02-2015
 * Time: 2:23 PM
 */
include "../db/dbConfig.php";
include "../db/dbConnection.php";
include "functions.php";

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit ("Form submition from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}

$data = array();
/*
 * For testing purposes.
$_POST['client'] = "Samuil";
$_POST['language'] = "6";
$_POST['type'] = "KT";
$_POST['tolk_type'] = 'GT';
$_POST['date'] = '2015-02-11';
$_POST['start_hour'] = '12';
$_POST['start_minute'] = '00';
$_POST['end_hour'] = '12';
$_POST['end_minute']  = '00';
$_POST['contactPerson'] = 'fassssss';
$_POST['organization'] = 'fasssssssssss';
$_POST['email'] = 'samuil_st@abv.bg';
$_POST['telephone'] = '46700407005';
$_POST['mobile'] = '46700407005';
$_POST['address'] = "111111111111";
$_POST['post_code'] = '32123';
$_POST['city'] = '1225';
$_POST['message'] = 'sfaaaaaaaa';*/
if(isset($_POST['client']) && isset($_POST['language']) && isset($_POST['type'])
    && isset($_POST['tolk_type']) && isset($_POST['date']) && isset($_POST['start_hour'])
    && isset($_POST['start_minute']) && isset($_POST['end_hour']) && isset($_POST['end_minute'])
    && isset($_POST['contactPerson']) && isset($_POST['organization']) && isset($_POST['email'])
    && (isset($_POST['telephone']) || isset($_POST['mobile'])) && isset($_POST['address'])
    && isset($_POST['post_code']) && isset($_POST['city'])) {

    $client = $_POST['client'];
    $language = $_POST['language'];
    $type = $_POST['type'];
    $tolk_type = $_POST['tolk_type'];
    $date = $_POST['date'];
    $start_hour = $_POST['start_hour'];
    $start_minute = $_POST['start_minute'];
    $end_hour = $_POST['end_hour'];
    $end_minute = $_POST['end_minute'];
    $contactPerson = $_POST['contactPerson'];
    $organization = $_POST['organization'];
    $email = $_POST['email'];
    $telephone = "";
    if (isset($_POST['telephone'])) {
        $telephone = $_POST['telephone'];
    }
    $mobile = "";
    if (isset($_POST['mobile'])) {
        $mobile = $_POST['mobile'];
    }
    $address = $_POST['address'];
    $post_code = $_POST['post_code'];
    $city = $_POST['city'];
    $message = "";
    if (isset($_POST['message'])) {
        $message = $_POST['message'];
    }

    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
        $query = "SELECT l_languageName FROM t_languages WHERE l_languageID=:languageID";
        $statement = $con->prepare($query);
        $statement->bindParam(":languageID", $language);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $obj = $statement->fetch();

        if($statement->rowCount() > 0)
        {
            $language = $obj->l_languageName;
        }

        function compareOrderNumber($number, $connection) {
            $query = "SELECT o_orderNumber FROM t_order WHERE o_orderNumber=:orderNumber";
            $statement = $connection->prepare($query);
            $statement->bindParam(":orderNumber", $number);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ);
            $obj = $statement->fetch();
            if ($statement->rowCount() > 0)
            {
                return true;
            } else {
                return false;
            }
        }
        $orderNumber = genOrderNumber();
        while (compareOrderNumber($orderNumber, $con)) {
            $orderNumber = genOrderNumber();
        }
        $state = 'O';
        $clientPN = "0000000000";
        $clientNumber = 000000;
        $startTime = ((intval($start_hour) * 4) + intval($start_minute));
        $endTime = ((intval($end_hour) * 4) + intval($end_minute));
        echo $orderNumber." ".$state." ".$clientPN." ".$clientNumber." "
            .$contactPerson." ".$client." ".$email." ".$language." "
            .$city." ".$tolk_type." ";
        $query = "INSERT INTO t_order (o_orderNumber, o_state, o_kunderPersonalNumber, o_kundNumber, "
            ."o_orderer, o_client, o_email, o_tel, o_mobile, o_address, o_zipCode, o_city, "
            ."o_language, o_tolkNiva, o_date, o_startTime, o_endTime, o_interpretationType,"
            ."o_creationTime, o_comments) VALUES (:orderNumber, :state, :organizationNumber, :clientNumber , :contactPerson, "
            .":client, :email, :telephone, :mobile, :address, :zipCode, :city, :language, :tolkType, "
            .":date, :startTime, :endTime, :type, :creationTime, :comment )";
        $statement = $con->prepare($query);
        $tolk_type = "NI";
        $statement->bindParam(":orderNumber", $orderNumber);
        $statement->bindParam(":state", $state);
        $statement->bindParam(":organizationNumber", $clientPN);
        $statement->bindParam(":clientNumber", $clientNumber);
        $statement->bindParam(":contactPerson", $contactPerson);
        $statement->bindParam(":client", $client);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":telephone", $telephone);
        $statement->bindParam(":mobile", $mobile);
        $statement->bindParam(":address", $address);
        $statement->bindParam(":zipCode", $post_code);
        $statement->bindParam(":city", $city);
        $statement->bindParam(":language", $language);
        $statement->bindParam(":tolkType", $tolk_type);
        $statement->bindParam(":date", $date);
        $statement->bindParam(":startTime", $startTime);
        $statement->bindParam(":endTime", $endTime);
        $statement->bindParam(":type", $type);
        $statement->bindParam(":creationTime", date("Y-m-d H:i:s"));
        $statement->bindParam(":comment", $message);
        $statement->execute();
        echo "1234";
        if ($statement->rowCount() > 0) {
            $query = "INSERT INTO t_enGangsKunder (e_orderNumber, e_organizationName) "
                ."VALUES (:orderNumber, :organizationName)";
            $statement = $con->prepare($query);
            $statement->bindParam(":orderNumber", $orderNumber);
            $statement->bindParam(":organizationName", $organization);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $query = "INSERT INTO t_orderLog (o_orderNumber, o_modifyPersonalNumber, o_involvedPersonalNumber, "
                    ."o_ipAddress ,o_state) VALUES (:orderNumber, :modifyPN, :involvedPN, :ipAddress, :state)";
                $statement = $con->prepare($query);
                $statement->bindParam(":orderNumber", $orderNumber);
                $statement->bindParam(":modifyPN", $clientPN);
                $statement->bindParam(":involvedPN", $clientPN);
                $statement->bindParam(":ipAddress", getRealIpAddress());
                $statement->bindParam(":state", $state);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $data['error'] = 0;
                    echo json_encode($data);
                } else {
                    $data['error'] = 1;
                    $data['header'] = "Order Log Error";
                    $data['errorMessage'] = "There was a problem with logging the order in the OrderLog";
                    echo json_encode($data);
                }
            } else {
                $data['error'] = 1;
                $data['header'] = "One Time Customer Error";
                $data['errorMessage'] = "There was a problem with adding the the One time customer.";
                echo json_encode($data);
            }
        } else {
            $data['error'] = 1;
            $data['header'] = "Order Creation Error";
            $data['errorMessage'] = "There was a problem with adding the Order in the Database!";
            echo json_encode($data);
        }

    } catch (PDOException $e) {
        return $e->getMessage();
    }
    if ($db != null) {
        $db->disconnect();
    }
} else {
    $data['error'] = 1;
    $data['header'] = "Fields Missing Error";
    $data['errorMessage'] = "Some of the required fields are missing!";
    echo json_encode($data);
}