<?php
/**
 * Samuil.
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
if (isset($_POST['name']) && isset($_POST['clientNumber'])
    && isset($_POST['phone']) && isset($_POST['by'])
    && isset($_POST['email']) && isset($_POST['ddate'])
    && isset($_POST['fromLang']) && isset($_POST['toLang'])
) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $emailer = new Emails();
    $contact_email_content = file_get_contents('../emailTemplates/translation-order-email.html');

    $var = ['{clientNumber}', '{name}', '{phone}',
        '{email}', '{by}', '{ddate}',
        '{fromLang}', '{toLang}', '{comment}', ];
    $val = [$_POST['clientNumber'], $_POST['name'], $_POST['phone'],
        $_POST['email'], $_POST['by'], $_POST['ddate'], $_POST['fromLang'], $_POST['toLang'], $_POST['comment'], ];
    $emailContent = str_replace($var, $val, $contact_email_content);

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        echo json_encode(['error' => !$emailer->send_email_with_attachment('kundtjanst@c4tolk.se', 'Tolkning i Kristianstad AB', 'Beställning av översättning', $emailContent, $_FILES['attachment']['tmp_name'], $_FILES['attachment']['name'])]);
    } else {
        echo json_encode(['error' => true]);
    }
} else {
    echo json_encode(['error' => true]);
}
