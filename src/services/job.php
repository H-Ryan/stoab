<?php
/**
 * User: Samuil
 * Date: 06-04-2015
 * Time: 1:27 AM
 */
require "../email/Emails.php";
$data = [];
if (isset($_POST['name']) && isset($_POST['personalNumber'])
    && isset($_POST['gender']) && isset($_POST['tax'])
    && isset($_POST['car']) && isset($_POST['email'])
    && (isset($_POST['phoneHome']) || isset($_POST['phoneMobile']))
    && isset($_POST['address']) && isset($_POST['postNumber'])
    && isset($_POST['city']) && isset($_POST['terms'])
    && isset($_POST['languageOne']) && isset($_POST['langCompetenceOne'])) {
    $name = $_POST['name'];
    $personalNumber = $_POST['personalNumber'];
    $gender = $_POST['gender'];
    $tax = $_POST['tax'];
    $car = $_POST['car'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $postNumber = $_POST['postNumber'];
    $city = $_POST['city'];
    $languageOne = $_POST['languageOne'];
    $langOneCompetence = $_POST['langCompetenceOne'];

    $data['error'] = false;

    $emailer = new Emails();
    $emailContent = "<html><head></head><body>";
    $emailContent .= "<div><p><h3>Namn:</h3> $name</p></div>";
    $emailContent .= "<div><p><h3>Personnummer:</h3> $personalNumber</p></div>";
    $emailContent .= "<div><p><h3>Kön:</h3> $gender</p></div>";
    $emailContent .= "<div><p><h3>Jag har:</h3> $tax</p></div>";
    $emailContent .= "<div><p><h3>Egen bil:</h3> $car</p></div>";
    $emailContent .= "<div><p><h3>E-post:</h3> $email</p></div>";
    if(isset($_POST['phoneHome']) && $_POST['phoneMobile']) {
        $phoneHome = $_POST['phoneHome'];
        $mobile = $_POST['phoneMobile'];
        $emailContent .= "<div><p><h3>Telefon (bostad):</h3> $phoneHome</p></div>";
        $emailContent .= "<div><p><h3>Mobiltelefon:</h3> $mobile</p></div>";
    } else {
        if (isset($_POST['phoneHome'])) {
            $phoneHome = $_POST['phoneHome'];
            $emailContent .= "<div><p><h3>Telefon (bostad):</h3> $phoneHome</p></div>";
        } else if (isset($_POST['phoneMobile'])) {
            $mobile = $_POST['phoneMobile'];
            $emailContent .= "<div><p><h3>Mobiltelefon:</h3> $mobile</p></div>";
        }
    }
    $emailContent .= "<div><p><h3>Gatuadress:</h3> $address</p></div>";
    $emailContent .= "<div><p><h3>Postnummer:</h3> $postNumber</p></div>";
    $emailContent .= "<div><p><h3>Postort:</h3> $city</p></div>";
    $emailContent .= "<div><p><h3>Språk:</h3> $languageOne - $langOneCompetence</p></div>";
    $langs = ["Two", "Three", "Four"];
    for ($i = 0; $i < sizeof($langs); $i++) {
        if (!empty(isset($_POST['language'.$langs[$i]]))) {
            $lang = $_POST['language'.$langs[$i]];
            $langComp = $_POST['langCompetence'.$langs[$i]];
            $emailContent .= "<div><p><h3>Språk:</h3> $lang - $langComp</p></div>";
        }
    }
    $emailContent .= "</body></html>";
    $emailer->send_email("info@tolktjanst.se", "STÖ AB", "Kontaktformulär", $emailContent);
    echo json_encode($data);
}