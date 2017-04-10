<?php
/**
 * User: Samuil
 * Date: 18-02-2015
 * Time: 4:10 PM.
 */
 error_reporting(E_ALL); ini_set('display_errors', 1);
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
include '../../db/dbConfig.php';
include '../../db/dbConnection.php';
include '../functions.php';
require '../../email/Emails.php';

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}
$data = [];
$db = null;
$role = 3;
if (isset($_POST['interpreter_number']) && isset($_POST['interpreter_re_email'])) {
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $tolkNumber = $_POST['interpreter_number'];
        $tolkEmail = $_POST['interpreter_re_email'];
        $statement = $con->prepare('SELECT u_blocked FROM t_users WHERE u_email=:tolkEmail AND (u_role=:role OR u_role=1) AND u_personalNumber IN(SELECT t_personalNumber FROM t_tolkar WHERE t_tolkNumber=:tolkNumber )');
        $statement->bindParam(':tolkEmail', $tolkEmail);
        $statement->bindParam(':tolkNumber', $kundNumber);
        $statement->bindParam(':role', $role);
        $statement->execute();
        $bloked = $statement->fetch();
        if ($bloked == 0) {
            $password = genOrderPassword();
            $newPass = encrypt_password($password);
            try {
                $statement = $con->prepare('UPDATE t_users SET u_password =:newPass WHERE u_email =:email; ');
                $statement->bindParam(':newPass', $newPass);
                $statement->bindParam(':email', $tolkEmail);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $statementTwo = $con->prepare('SELECT u_firstName, u_lastName FROM t_users WHERE u_email =:email ');
                    $statementTwo->bindParam(':email', $tolkEmail);
                    $statementTwo->execute();
                    $statementTwo->setFetchMode(PDO::FETCH_OBJ);
                    if ($statementTwo->rowCount() > 0) {
                        $tolk = $statementTwo->fetch();
                        $name = $tolk->u_firstName.' '.$tolk->u_lastName;
                        $emailer = new Emails();
                        $subject = 'C4 SPRÅKPARTNER AB Inloggningsuppgifter hämtning.';
                        $body = "<html><body style='color: #000000'>"
                      ."<p style='color: #000000'>Hej, $name!<br /><br />"
                      .'Här får du dina inloggningsuppgifter för tolkbokning hos oss.<br />'
                      .'Ditt tolknummer är: '.$tolkNumber.'<br />'
                      .'Din tillfälliga lösenord är: '.$password.'<br /><br />'
                      .'För din egen säkerhet rekommenderar vi dig att byta lönsenord så snart du har loggat in.<br />'
                      .'<br /><br /><br /><br />'
                      .'Med vänliga hälsningar,'
                      .'C4 SPRÅKPARTNER AB - Tolktjänst</p></body></html>';
                        $emailer->send_email($tolkEmail, $name, $subject, $body);

                        $data['error'] = 0;
                    }
                } else {
                    $data['error'] = 1;
                }
            } catch (PDOException $e) {
                $data['error'] = 2;
                $data['message'] = $e;
            }
        } else {
            $data['error'] = 5;
        }
    } catch (PDOException $e) {
        $data['error'] = 2;
        $data['message'] = $e;
    }
} else {
    $data['error'] = 3;
}
if ($db != null) {
    $db->disconnect();
}
echo json_encode($data);
