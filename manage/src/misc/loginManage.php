<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
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

if (isset($_POST['email']) && isset($_POST['password'])) {
    session_start();
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $email = $_POST['email'];
        $password = encrypt_password($_POST['password']);
        $employeeRole = 2;
        $adminRole = 1;
        $isBlocked = 0;
        $statement = null;
        try {
            $statement = $con->prepare("SELECT u_personalNumber, u_role FROM t_users WHERE u_email=:email AND u_password=:password AND (u_role=:employeeRole OR u_role=:adminRole) AND u_blocked=:isBlocked");
            $statement->bindParam(":email", $email);
            $statement->bindParam(":password", $password);
            $statement->bindParam(":employeeRole", $employeeRole);
            $statement->bindParam(":adminRole", $adminRole);
            $statement->bindParam(":isBlocked", $isBlocked);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ);
            if ($statement->rowCount() > 0) {
                $user = $statement->fetch();
                $role = $user->u_role;
                $personalNumber = $user->u_personalNumber;
                /*$statement = $con->prepare("INSERT INTO t_loginLog (l_personalNumber, l_ipAddress) VALUES (:personalNumber, :ip)");
                $statement->bindParam(":personalNumber", $personalNumber);
                $statement->bindParam(":ip", getRealIpAddress());
                $statement->execute();*/
                session_regenerate_id();
                $_SESSION['personal_number'] = $personalNumber;
                if ($db != null) {
                    $db->disconnect();
                }
                header('Location: ../../main.php');
            } else {
                if ($db != null) {
                    $db->disconnect();
                }
                header('Location: ../../index.php');
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br />\n";
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }
} else {
    if ($db != null) {
        $db->disconnect();
    }
    header('Location: ../../index.php');
}