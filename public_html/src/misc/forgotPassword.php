<?php
/**
 * User: Samuil
 * Date: 18-02-2015
 * Time: 4:10 PM.
 */
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
session_cache_limiter('nocache');
header('Expires: '.gmdate('r', 0));
header('Content-type: application/json');
include '../db/dbConfig.php';
include '../db/dbConnection.php';
include 'functions.php';
require '../email/Emails.php';

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}
$data = array();
if (isset($_POST['customerNumber']) && isset($_POST['email'])) {
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $clientPN = $_POST['customerNumber'];
        $clientEmail = $_POST['email'];
        $statement = $con->prepare('SELECT k_blocked FROM t_kunder WHERE k_kundNumber=:kundNumber');
        $statement->bindParam(':kundNumber', $kundNumber);
        $statement->execute();
        $bloked = $statement->fetch();
        if ($statement->rowCount() > 0) {
            if ($bloked == 0) {
                $password = genOrderPassword();
                $newPass = encrypt_password($password);
                $statement = $con->prepare('UPDATE t_kunder SET k_password = :newPass WHERE k_kundNumber = :pn AND k_email =:email ');
                $statement->bindParam(':newPass', $newPass);
                $statement->bindParam(':email', $clientEmail);
                $statement->bindParam(':pn', $clientPN);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $statementTwo = $con->prepare('SELECT k_firstName, k_lastName FROM t_kunder WHERE k_kundNumber = :pn AND k_email =:email ');
                    $statementTwo->bindParam(':email', $clientEmail);
                    $statementTwo->bindParam(':pn', $clientPN);
                    $statementTwo->execute();
                    $statementTwo->setFetchMode(PDO::FETCH_OBJ);
                    if ($statementTwo->rowCount() > 0) {
                        $kund = $statementTwo->fetch();
                        $name = $kund->k_firstName.' '.$kund->k_lastName;
                        $emailer = new Emails();
                        $subject = 'Tolkning i Kristianstad AB Inloggningsuppgifter hämtning.';
                        $body = "<html><body style='color: #000000'>"
                        ."<p style='color: #000000'>Hej, $name!<br /><br />"
                        .'Här får du dina inloggningsuppgifter för tolkbokning hos oss.<br />'
                        .'Ditt kundnummer är: '.$clientPN.'<br />'
                        .'Din tillfälliga lösenord är: '.$password.'<br /><br />'
                        .'För din egen säkerhet rekommenderar vi dig att byta lönsenord så snart du har loggat in.<br />'
                        .'<br /><br /><br /><br />'
                        .'Med vänliga hälsningar,'
                        .'Tolkning i Kristianstad AB - Tolktjänst</p></body></html>';
                        $emailer->send_email($clientEmail, $name, $subject, $body);

                        $data['error'] = 0;
                    }
                } else {
                    $data['error'] = 3;
                }
            } else {
                $data['error'] = 5;
            }
        } else {
            $data['error'] = 6;
        }
    } catch (PDOException $e) {
        $data['error'] = 2;
    }
} else {
    $data['error'] = 1;
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);
