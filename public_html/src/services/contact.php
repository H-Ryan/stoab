<?php
/**
 * User: Samuil
 * Date: 01-04-2015
 * Time: 2:23 PM.
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

if (isset($_POST['name']) && isset($_POST['companyName'])
    && isset($_POST['phone']) && isset($_POST['subject'])
    && isset($_POST['email']) && isset($_POST['message'])) {
    $name = $_POST['name'];
    $foretagsnamn = $_POST['companyName'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $emailer = new Emails();
    $contact_email_content = file_get_contents('../emailTemplates/contact-email.html');
    $var = ['{name}', '{companyName}', '{phone}', '{subject}', '{email}', '{message}'];
    $val = [$name, $foretagsnamn, $phone, $subject, $email, $message];
    $emailContent = str_replace($var, $val, $contact_email_content);
    echo json_encode(['error' => !$emailer->send_email('kundtjanst@c4tolk.se', 'C4 SPRÅKPARTNER AB', 'Kontaktformulär', $emailContent)]);
} else {
    echo json_encode(['error' => true]);
}
