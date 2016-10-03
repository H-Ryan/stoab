<?php
/**
 * User: Samuil
 * Date: 18-06-2015
 * Time: 10:59 AM.
 */
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
include './functions.php';
include '../email/Emails.php';

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}
$emailer = new Emails();
$data = array();
$toEmail = 'info@c4tolk.se';

if (isset($_POST['name']) && isset($_POST['personalNumber']) && !empty($_POST['address']) && !empty($_POST['email']) && !empty($_POST['city_date'])) {
    $name = $_POST['name'];
    $personalNumber = $_POST['personalNumber'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $city_date = $_POST['city_date'];
    $tolk_subject = 'Samarbetsavtal';
    $messageToTolkAssign = "<!DOCTYPE html><html>
            <head>
                <meta http-equiv='Content-Type' content='text/html' charset='utf-8'>
            </head>
            <body>
            <table style='width: 80%; margin-left: 10%; margin-right: 10%; text-align: center;
                    font-family: verdana, arial, sans-serif; font-size: 14px; color: #333333;
                    border-radius: 5px; border: 1px solid #999999;' cellpadding='10'>
                <thead></thead>
                <tbody>
                <tr>
                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                        <p><span style='font-weight:bold;'>Namnförtydligande:</span> " .$name."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                        <p><span style='font-weight:bold;'>Personnummer:</span> " .$personalNumber."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                        <p><span style='font-weight:bold;'>Adress:</span> " .$address."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                        <p><span style='font-weight:bold;'>Email adress:</span> " .$email."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #d4e3e5; padding: 8px; border: 1px solid #a9c6c9;'>
                        <p><span style='font-weight:bold;'>Ort/Datum:</span> " .$city_date."</p>
                    </td>
                </tr>
                </tbody>
            </table>
            <hr style='width: 80%;margin-left: auto; margin-right: auto;'/>
            </body>
            </html>";

    $emailer->send_email($toEmail, $name, $tolk_subject, $messageToTolkAssign);
    $data['error'] = 0;
    $data['messageHeader'] = 'Framgång';
    $data['positiveMessage'] = 'E-postmeddelandet har skickats.';
    echo json_encode($data);
} else {
    $data['error'] = 3;
    $data['messageHeader'] = 'Fields Missing Error';
    $data['errorMessage'] = 'Some of the required fields are missing!';
    echo json_encode($data);
}
