<?php
/**
 * User: Samuil
 * Date: 21-02-2015
 * Time: 11:57 AM
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
$language = $_POST['language'];
$city = (isset($_POST['city']) ? $_POST['city'] : "");
$state = (isset($_POST['state']) ? $_POST['state'] : "");
$tolkNum = (isset($_POST['tolkNum']) ? $_POST['tolkNum'] : "");
$tolkFName = (isset($_POST['tolkFirstName']) ? "%".$_POST['tolkFirstName']."%" : "");
$tolkLName = (isset($_POST['tolkLastName']) ? "%".$_POST['tolkLastName']."%" : "");

$db = null;
try {
    $db = new dbConnection(HOST, DATABASE, USER, PASS);
    $con = $db->get_connection();
    $isActive = 1;
    $query = "";
    $statement = null;
    if (!empty($language)) {
        if (!empty($city) || !empty($state)) {
            if (!empty($city) && !empty($state)) {
                //By language, city and state
                $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,".
                    " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,".
                    " u.u_extraInfo, s.t_sprakName, s.t_rate, t.* FROM t_tolkar AS t,".
                    " t_users AS u, t_tolkSprak AS s WHERE u.u_role = 3 AND".
                    " t.t_active =:isActive AND u.u_personalNumber = t.t_personalNumber".
                    " AND t.t_personalNumber = s.t_personalNumber AND s.t_sprakName =:language".
                    " AND u.u_state =:state AND u.u_city =:city ORDER BY u.u_firstName";
                $statement = $con->prepare($query);
                $statement->bindParam(":language",$language);
                $statement->bindParam(":state",$state);
                $statement->bindParam(":city",$city);
            } elseif (!empty($state)) {
                //By language and state
                $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,".
                    " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,".
                    " u.u_extraInfo, s.t_sprakName, s.t_rate, t.* FROM t_tolkar AS t,".
                    " t_users AS u, t_tolkSprak AS s WHERE u.u_role = 3 AND".
                    " t.t_active =:isActive AND u.u_personalNumber = t.t_personalNumber".
                    " AND t.t_personalNumber = s.t_personalNumber AND s.t_sprakName =:language".
                    " AND u.u_state =:state ORDER BY u.u_firstName";
                $statement = $con->prepare($query);
                $statement->bindParam(":language",$language);
                $statement->bindParam(":state",$state);
            } elseif (!empty($city)) {
                //By language and city
                $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,".
                    " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,".
                    " u.u_extraInfo, s.t_sprakName, s.t_rate, t.* FROM t_tolkar AS t,".
                    " t_users AS u, t_tolkSprak AS s WHERE u.u_role = 3 AND".
                    " t.t_active =:isActive AND u.u_personalNumber = t.t_personalNumber".
                    " AND t.t_personalNumber = s.t_personalNumber AND s.t_sprakName =:language ORDER BY u.u_firstName".
                    " AND u.u_city =:city";
                $statement = $con->prepare($query);
                $statement->bindParam(":language",$language);
                $statement->bindParam(":city",$city);
            }
        } else {
            //By language
            $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,".
                " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,".
                " u.u_extraInfo, s.t_sprakName, s.t_rate, t.* FROM t_tolkar AS t,".
                " t_users AS u, t_tolkSprak AS s WHERE u.u_role = 3 AND".
                " t.t_active =:isActive AND u.u_personalNumber = t.t_personalNumber".
                " AND t.t_personalNumber = s.t_personalNumber AND s.t_sprakName =:language ORDER BY u.u_firstName";
            $statement = $con->prepare($query);
            $statement->bindParam(":language", $language);
        }
    }
    else {
        if (!empty($tolkNum) || !empty($tolkFName) || !empty($tolkLName)) {
            if (!empty($tolkNum) && (strlen($tolkFName) <= 2 && strlen($tolkLName) <= 2)) {
                $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,".
                    " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,".
                    " u.u_extraInfo, s.t_sprakName, s.t_rate, t.* FROM t_tolkar AS t,".
                    " t_users AS u, t_tolkSprak AS s WHERE u.u_role = 3 AND".
                    " t.t_active =:isActive AND u.u_personalNumber = t.t_personalNumber".
                    " AND t.t_personalNumber = s.t_personalNumber AND t.t_tolkNumber =:tolkNum ORDER BY u.u_firstName";
                $statement = $con->prepare($query);
                $statement->bindParam(":tolkNum", $tolkNum, PDO::PARAM_INT);
            } else {
                if (!empty($tolkFName) && !empty($tolkLName) && (strlen($tolkFName) > 2 && strlen($tolkLName) > 2)) {
                    $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,".
                        " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,".
                        " u.u_extraInfo, s.t_sprakName, s.t_rate, t.* FROM t_tolkar AS t,".
                        " t_users AS u, t_tolkSprak AS s WHERE u.u_role = 3 AND".
                        " t.t_active =:isActive AND u.u_personalNumber = t.t_personalNumber".
                        " AND t.t_personalNumber = s.t_personalNumber AND (u.u_firstName LIKE :tolkFName".
                        " OR u.u_lastName LIKE :tolkLName) ORDER BY u.u_firstName";
                    $statement = $con->prepare($query);
                    $statement->bindParam(":tolkFName", $tolkFName, PDO::PARAM_STR);
                    $statement->bindParam(":tolkLName", $tolkLName, PDO::PARAM_STR);
                    $data['tt'] = 0;
                    $data['ttrt'] = $tolkLName;
                } else {
                    if (!empty($tolkFName) && strlen($tolkFName) > 2) {
                        $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,".
                            " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,".
                            " u.u_extraInfo, s.t_sprakName, s.t_rate, t.* FROM t_tolkar AS t,".
                            " t_users AS u, t_tolkSprak AS s WHERE u.u_role = 3 AND".
                            " t.t_active =:isActive AND u.u_personalNumber = t.t_personalNumber".
                            " AND t.t_personalNumber = s.t_personalNumber AND u.u_firstName LIKE :tolkFName ORDER BY u.u_firstName";
                        $statement = $con->prepare($query);
                        $data['tt'] = 1;
                        $statement->bindParam(":tolkFName", $tolkFName, PDO::PARAM_STR);
                    } else if (!empty($tolkLName) && strlen($tolkLName) > 2) {
                        $query = "SELECT u.u_personalNumber, u.u_firstName, u.u_lastName, u.u_email,".
                            " u.u_tel, u.u_mobile, u.u_address, u.u_zipCode, u.u_state, u.u_city,".
                            " u.u_extraInfo, s.t_sprakName, s.t_rate, t.* FROM t_tolkar AS t,".
                            " t_users AS u, t_tolkSprak AS s WHERE u.u_role = 3 AND".
                            " t.t_active =:isActive AND u.u_personalNumber = t.t_personalNumber".
                            " AND t.t_personalNumber = s.t_personalNumber AND u.u_lastName LIKE :tolkLName ORDER BY u.u_firstName";
                        $statement = $con->prepare($query);
                        $statement->bindParam(":tolkLName", $tolkLName, PDO::PARAM_STR);
                        $data['tt'] = 2;
                    } else {
                        $query = "SELECT * FROM t_tolkar AS t WHERE  t.t_active =:isActive AND t.t_tolkNumber =:tolkNum";
                        $statement = $con->prepare($query);
                        $fail = 00000000000;
                        $statement->bindParam(":tolkNum", $fail, PDO::PARAM_INT);
                    }
                }
            }
        } else {
            $query = "SELECT * FROM t_tolkar AS t WHERE  t.t_active =:isActive AND t.t_tolkNumber =:tolkNum";
            $statement = $con->prepare($query);
            $fail = 00000000000;
            $statement->bindParam(":tolkNum", $fail, PDO::PARAM_INT);
        }
    }
    $data['ttrt'] = $tolkLName;
    $statement->bindParam(":isActive", $isActive);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_OBJ);
    if ($statement->rowCount() > 0) {
        $data["error"] = 0;
        $data['tolks'] = array();
        $i = 0;
        $prevTolk = null;
        while ($tolk = $statement->fetch()) {

            if ($prevTolk == null) {
                $data['tolks'][$i] = $tolk;
                $i++;
            } else {
                if ($tolk->u_personalNumber != $prevTolk->u_personalNumber) {
                    $data['tolks'][$i] = $tolk;
                    $i++;
                }
            }
            $prevTolk = $tolk;
        }
    } else {
        $data["error"] = 1;
        $data["messageHeader"] = "Header";
        $data["errorMessage"] = "Error Message";

    }
} catch (PDOException $e) {
    $data["error"] = 1;
    $data['exception'] = $e->getMessage();
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);