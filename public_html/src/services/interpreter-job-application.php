<?php
/**
 * User: Samuil
 * Date: 06-04-2015
 * Time: 1:27 AM.
 */
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
session_cache_limiter('nocache');
header('Expires: '.gmdate('r', 0));
header('Content-type: application/json');
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
$data = [];
if (isset($_POST['firstName']) && isset($_POST['firstName']) && isset($_POST['personalNumber'])
    && isset($_POST['gender']) && isset($_POST['tax'])
    && isset($_POST['car']) && isset($_POST['email'])
    && (isset($_POST['phoneHome']) || isset($_POST['phoneMobile']))
    && isset($_POST['address']) && isset($_POST['postNumber'])
    && isset($_POST['city']) && (isset($_POST['terms']) && $_POST['terms'] === 'on')
    && isset($_POST['language0']) && isset($_POST['langCompetence0'])
) {
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
    $languageOne = $_POST['language0'];
    $langOneCompetence = $_POST['langCompetence0'];

    $emailer = new Emails();
    $emailContent = "<html><head></head>
        <body style='
            width: 100%;
            height: 100%;
            font-family: Lato,\"Helvetica Neue\",Arial,Helvetica,sans-serif;'>";
    $emailContent .= '<h2>TOLK Intresseanmälan</h2>';
    $emailContent .= printRow('Namn:', $fName);
    $emailContent .= printRow('Efternamn:', $lName);
    $emailContent .= printRow('Personnummer:', $personalNumber);
    $emailContent .= printRow('Kön:', $gender);
    $emailContent .= printRow('Jag har:', $tax);
    $emailContent .= printRow('Egen bil:', $car);
    $emailContent .= printRow('E-post:', $email);
    if (isset($_POST['phoneHome']) && $_POST['phoneMobile']) {
        $phoneHome = $_POST['phoneHome'];
        $mobile = $_POST['phoneMobile'];
        $emailContent .= printRow('Telefon (bostad):', $phoneHome);
        $emailContent .= printRow('Mobiltelefon:', $mobile);
    } else {
        if (isset($_POST['phoneHome'])) {
            $phoneHome = $_POST['phoneHome'];
            $emailContent .= printRow('Telefon (bostad):', $phoneHome);
        } elseif (isset($_POST['phoneMobile'])) {
            $mobile = $_POST['phoneMobile'];
            $emailContent .= printRow('Mobiltelefon:', $mobile);
        }
    }
    $emailContent .= printRow('Gatuadress:', $address);
    $emailContent .= printRow('Postnummer:', $postNumber);
    $emailContent .= printRow('Postort:', $city);
    $emailContent .= printRow('Modersmål:', $languageOne.' - '.$langOneCompetence);
    $langs = ['1', '2', '3', '4'];
    for ($i = 0; $i < (sizeof($langs)); ++$i) {
        echo $i;
        echo $_POST['language'.$langs[$i]];
        if (isset($_POST['language'.$langs[$i]])) {
            $language = $_POST['language'.$langs[$i]];
            if (strlen($language) >= 3) {
                $lang = $_POST['language'.$langs[$i]];
                $langComp = $_POST['langCompetence'.$langs[$i]];
                $emailContent .= printRow('Språk '.($i + 1).':', $lang.' - '.$langComp);
            }
        }
    }
    if (isset($_POST['experience']) && strlen($_POST['experience']) > 0) {
        $experience = $_POST['experience'];
        $emailContent .= printRow('Tidigare erfarenhet:', $experience);
    }
    if (isset($_POST['education']) && strlen($_POST['education']) > 0) {
        $education = $_POST['education'];
        $emailContent .= printRow('Tolkutbildning:', $education);
    }
    if (isset($_POST['referenceOne']) && strlen($_POST['referenceOne']) > 0) {
        $referenceOne = $_POST['referenceOne'];
        $emailContent .= printRow('Referens 1:', $referenceOne);
    }
    if (isset($_POST['referenceTwo']) && strlen($_POST['referenceTwo']) > 0) {
        $referenceTwo = $_POST['referenceTwo'];
        $emailContent .= printRow('Referens 2:', $referenceTwo);
    }
    $emailContent .= '</body></html>';
    echo json_encode(['error' => $emailer->send_email('rekrytering@c4tolk.se', 'Tolkning i Kristianstad AB', 'TOLK Intresseanmälan', $emailContent) ? 0 : 1]);
} else {
    echo json_encode(['error' => 1]);
}

function printRow($label, $data)
{
    return "<p><span><b>$label</b></span> <span>$data</span></p>";
}
