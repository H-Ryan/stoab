<?php
/**
 * User: Samuil
 * Date: 01-04-2015
 * Time: 2:23 PM
 */
require "../email/Emails.php";
$data = [];
if (isset($_POST['name']) && isset($_POST['foretagsnamn'])
    && isset($_POST['phone']) && isset($_POST['subject'])
    && isset($_POST['email']) && isset($_POST['messageC'])) {
    $name = $_POST['name'];
    $foretagsnamn = $_POST['foretagsnamn'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $email = $_POST['email'];
    $messageC = $_POST['messageC'];

    $data['error'] = false;

    $emailer = new Emails();
    $emailContent = "<html><head></head><body>
	<div><h2>Kontaktformulär</h2>
    <div><p><h3>Namn:</h3> $name</p></div>
    <div><p><h3>Företagsnamn:</h3> $foretagsnamn</p></div>
    <div><p><h3>Telefon:</h3> $phone</p></div>
    <div><p><h3>Ämne:</h3> $subject</p></div>
    <div><p><h3>E-post:</h3> $email</p></div>
    <div><h3>Meddelande:</h3> <p>$messageC</p></div>
    </body></html>";
    $emailer->send_email("info@tolktjanst.se", "STÖ AB", "Kontaktformulär", $emailContent);
    echo json_encode($data);
}