<?php
/**
 * User: Samuil
 * Date: 06-04-2015
 * Time: 1:27 AM
 */
require "../email/Emails.php";
$data = [];
if (isset($_POST['firstName']) && isset($_POST['firstName']) && isset($_POST['personalNumber'])
    && isset($_POST['gender']) && isset($_POST['tax'])
    && isset($_POST['car']) && isset($_POST['email'])
    && (isset($_POST['phoneHome']) || isset($_POST['phoneMobile']))
    && isset($_POST['address']) && isset($_POST['postNumber'])
    && isset($_POST['city']) && isset($_POST['terms'])
    && isset($_POST['languageOne']) && isset($_POST['langCompetenceOne'])) {
    $fName = $_POST['firstName'];
    $lName = $_POST['lastName'];
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
    $emailContent = "<html><head></head>
        <body style='
            width: 100%;
            height: 100%;
            font-family: Lato,\"Helvetica Neue\",Arial,Helvetica,sans-serif;'>";
    $emailContent .= "<h2>Intresseanmälan</h2>";
    $emailContent .= printRow("Namn:", $fName);
    $emailContent .= printRow("Efternamn:", $lName);
    $emailContent .= printRow("Personnummer:", $personalNumber);
    $emailContent .= printRow("Kön:", $gender);
    $emailContent .= printRow("Jag har:", $tax);
    $emailContent .= printRow("Egen bil:", $car);
    $emailContent .= printRow("E-post:", $email);
    if(isset($_POST['phoneHome']) && $_POST['phoneMobile']) {
        $phoneHome = $_POST['phoneHome'];
        $mobile = $_POST['phoneMobile'];
        $emailContent .= printRow("Telefon (bostad):", $phoneHome);
        $emailContent .= printRow("Mobiltelefon:", $mobile);
    } else {
        if (isset($_POST['phoneHome'])) {
            $phoneHome = $_POST['phoneHome'];
            $emailContent .= printRow("Telefon (bostad):", $phoneHome);
        } else if (isset($_POST['phoneMobile'])) {
            $mobile = $_POST['phoneMobile'];
            $emailContent .= printRow("Mobiltelefon:", $mobile);
        }
    }
    $emailContent .= printRow("Gatuadress:", $address);
    $emailContent .= printRow("Postnummer:", $postNumber);
    $emailContent .= printRow("Postort:", $city);
    $emailContent .= printRow("Språk:", $languageOne." - ".$langOneCompetence);
    $langs = ["Two", "Three", "Four"];
    for ($i = 0; $i < sizeof($langs); $i++) {
        if (isset($_POST['language'.$langs[$i]])) {
            $language = $_POST['language'.$langs[$i]];
            if (strlen($language) > 3) {
                $lang = $_POST['language'.$langs[$i]];
                $langComp = $_POST['langCompetence'.$langs[$i]];
                $emailContent .= printRow("Språk:", $lang." - ".$langComp);
            }
        }
    }
    if(isset($_POST['experience']) && strlen($_POST['experience']) > 0) {
        $experience = $_POST['experience'];
        $emailContent .= printRow("Tidigare erfarenhet:", $experience);
    }
    if(isset($_POST['education']) && strlen($_POST['education']) > 0) {
        $education = $_POST['education'];
        $emailContent .= printRow("Tolkutbildning:", $education);
    }
    if(isset($_POST['referenceOne']) && strlen($_POST['referenceOne']) > 0) {
        $referenceOne = $_POST['referenceOne'];
        $emailContent .= printRow("Referens 1:", $referenceOne);
    }
    if(isset($_POST['referenceTwo']) && strlen($_POST['referenceTwo']) > 0) {
        $referenceTwo = $_POST['referenceTwo'];
        $emailContent .= printRow("Referens 2:", $referenceTwo);
    }
    $emailContent .= "</body></html>";
    $emailer->send_email("rekrytering@tolktjanst.se", "STÖ AB", "Intresseanmälan", $emailContent);
    echo json_encode($data);
}

function printRow($label, $data) {
    return "<p><span><b>$label</b></span> <span>$data</span></p>";
}