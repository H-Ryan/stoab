<?php
/**
 * User: Samuil
 * Date: 11-03-2016
 * Time: 7:21 AM.
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

if (isset($_POST['org_name']) && isset($_POST['org_number'])
    && isset($_POST['invoice_reference']) && isset($_POST['contact_person'])
    && isset($_POST['phone']) && isset($_POST['mobile']) && isset($_POST['email'])
    && isset($_POST['address']) && isset($_POST['zip']) && isset($_POST['city'])
) {
    $org_name = $_POST['org_name'];
    $org_number = $_POST['org_number'];
    $invoice_reference = $_POST['invoice_reference'];
    $contact_person = $_POST['contact_person'];
    $phone = $_POST['phone'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $zip = $_POST['zip'];
    $city = $_POST['city'];

    $emailer = new Emails();
    $emailContent = "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Kund registrering begäran</title>
</head>
<body>
<h2>Kund registrering begäran</h2>
<h3><hr />Huvudkontor<hr /></h3>
<p><b>Org namn:</b> $org_name</p>
<p><b>Org nr:</b> $org_number</p>
<p><b>Faktura referensnr:</b> $invoice_reference</p>
<p><b>Kontaktperson:</b> $contact_person</p>
<p><b>Telefonnr:</b> $phone</p>
<p><b>Mobil:</b> $mobile</p>
<p><b>E-post:</b> $email</p>
<p><b>Adress:</b> $address</p>
<p><b>Postnummer:</b> $zip</p>
<p><b>Postort:</b> $city</p>";

    // Branch

    if (isset($_POST['branch_name']) && isset($_POST['branch_contact_person']) && isset($_POST['branch_phone'])
        && isset($_POST['branch_mobile']) && isset($_POST['branch_email']) && isset($_POST['branch_address'])
        && isset($_POST['branch_zip']) && isset($_POST['branch_city'])
    ) {
        $branch_name = $_POST['branch_name'];
        $branch_contact_person = $_POST['branch_contact_person'];
        $branch_phone = $_POST['branch_phone'];
        $branch_mobile = $_POST['branch_mobile'];
        $branch_email = $_POST['branch_email'];
        $branch_address = $_POST['branch_address'];
        $branch_zip = $_POST['branch_zip'];
        $branch_city = $_POST['branch_city'];

        $emailContent .= "<hr /><h3>Avdelning<hr /></h3>
<p><b>Avdelningsnamn:</b> $branch_name</p>
<p><b>Kontaktperson:</b> $branch_contact_person</p>
<p><b>Telefonnr:</b> $branch_phone</p>
<p><b>Mobil:</b> $branch_mobile</p>
<p><b>E-post:</b> $branch_email</p>
<p><b>Adress:</b> $branch_address</p>
<p><b>Postnummer:</b> $branch_zip</p>
<p><b>Postort:</b> $branch_city</p>";
    }
    $emailContent .= '</body></html>';

    echo json_encode(['error' => $emailer->send_email('info@sarvari.se', 'C4 SPRÅKPARTNER AB', 'Kund registrering begäran', $emailContent) ? 0 : 1]);
} else {
    echo json_encode(['error' => 1]);
}
