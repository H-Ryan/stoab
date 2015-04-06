<?php
/**
 * User: Samuil
 * Date: 18-02-2015
 * Time: 4:10 PM
 */

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
include "../db/dbConfig.php";
include "../db/dbConnection.php";
include "functions.php";
require "../email/Emails.php";

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
if(isset($_POST['number']) && isset($_POST['email']))
{
    $db = null;
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    try {
        $clientPN = $_POST['number'];
        $clientEmail = $_POST['email'];
        $password = genOrderPassword();
        $newPass = encrypt_password($password);
        $statement = $con->prepare("UPDATE t_kunder SET k_password = :newPass WHERE k_kundNumber = :pn AND k_email =:email ");
        $statement->bindParam(":newPass", $newPass);
        $statement->bindParam(":email", $clientEmail);
        $statement->bindParam(":pn", $clientPN);
        $statement->execute();
        if($statement->rowCount() > 0)
        {
            $statementTwo = $con->prepare("SELECT k_firstName, k_lastName FROM t_kunder WHERE k_kundNumber = :pn AND k_email =:email ");
            $statementTwo->bindParam(":email", $clientEmail);
            $statementTwo->bindParam(":pn", $clientPN);
            $statementTwo->execute();
            $statementTwo->setFetchMode(PDO::FETCH_OBJ);
            if($statementTwo->rowCount() > 0)
            {
                $kund = $statementTwo->fetch();
                $name = $kund->k_firstName." ".$kund->k_lastName;
                $emailer = new Emails();
                $subject = "STÖ-AB Inloggningsuppgifter hämtning.";
                $body = "<html><body style='color: #000000'>"
                    ."<p style='color: #000000'>Hej, $name!<br /><br />"
                    ."Här får du dina inloggningsuppgifter för tolkbokning hos oss.<br />"
                    ."Ditt kundnummer är: ".$clientPN."<br />"
                    ."Din tillfälliga lösenord är: ".$password."<br /><br />"
                    ."För din egen säkerhet rekommenderar vi dig att byta lönsenord så snart du har loggat in.<br />"
                    ."<br /><br /><br /><br />"
                    ."Med vänliga hälsningar,"
                    ."STÖ AB - Tolktjänst</p></body></html>";
                $emailer->send_email($clientEmail, $name, $subject, $body);

                $data["error"] = 0;
                $data["messageHeader"] = "Klart";
                $data["successMessage"] = "Vi har skickat dina inloggningsuppggfter per e-post.";
                echo json_encode($data);
            }
        } else {
            $data["error"] = 1;
            $data["messageHeader"] = "";
            $data["errorMessage"] = "Felaktigt kundnummer eller e-postadress.";
            echo json_encode($data);
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    if ($db != null) {
        $db->disconnect();
    }
} else {
    $data["error"] = 1;
    $data["messageHeader"] = "Header";
    $data["errorMessage"] = "Error Message";
    echo json_encode($data);
}