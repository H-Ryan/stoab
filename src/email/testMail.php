<?php
/**
 * User: Samuil
 * Date: 22-02-2015
 * Time: 5:15 PM
 */
require "Emails.php";
$email = new Emails();
$to = "samuil.dragnev@gmail.com";
$recipientName = "Samuil Dragnev";
$subject = "Test email";
$body = "<h4>Test</h4>";
$email->send_email($to, $recipientName, $subject, $body);