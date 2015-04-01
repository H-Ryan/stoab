<?php
require_once('PHPMailer/class.phpmailer.php');
include("PHPMailer/class.smtp.php");
require "emailConfig.php";

class Emails
{
    private $mail = null;

    function __construct()
    {
        try {
            $this->mail = new PHPMailer(true);// the true param means it will throw exceptions on errors, which we need to catch
            $this->mail->IsSMTP(); // telling the class to use SMTP
            //$this->mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Host = EMAIL_HOST; // sets the SMTP server
            $this->mail->Port = EMAIL_PORT;                    // set the SMTP port
//            $this->mail->SMTPAuth = true;                  // enable SMTP authentication
//            $this->mail->Username = EMAIL_USER; // SMTP account username
//            $this->mail->Password = EMAIL_PASS;        // SMTP account password
            //$this->mail->SMTPSecure = EMAIL_ENCRYPT;
            $this->mail->AddReplyTo('info@tolktjanst.se', 'STÖ AB - Tolktjänst');
            $this->mail->SetFrom('noreply@tolktjanst.com', 'STÖ AB - Tolktjänst');
            $this->mail->AddReplyTo('info@tolktjanst.se', 'STÖ AB - Tolktjänst');
        } catch (phpmailerException $e) {
            $msg['error'] = true;
            $msg['mailError'] = $e->errorMessage();
            $msg['emailErrorHeader'] = "E-post skickades inte.";
            $msg['emailErrorMessage'] = "Det verkar som vi har problem med vår e-postserver. För mer information, vänligen kontakta oss.";
            echo json_encode($msg); //Pretty error messages from PHPMailer
            die();
        }
    }

    function send_email($to, $recipientName, $subject, $body)
    {
        try {
            $this->mail->AddAddress($to, $recipientName);
            $this->mail->Subject = $subject;
            //$mail->AltBody = 'Non html message'; // optional - MsgHTML will create an alternate automatically
            $this->mail->MsgHTML($body);
            //$mail->AddAttachment('images/phpmailer.gif');      // attachment
            //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
            $this->mail->Send();
            //echo "Message Sent OK<p></p>\n";
        } catch (phpmailerException $e) {
            $msg['error'] = true;
            $msg['mailError'] = $e->errorMessage();
            $msg['emailErrorHeader'] = "E-post skickades inte.";
            $msg['emailErrorMessage'] = "Det verkar som vi har problem med vår e-postserver. För mer information, vänligen kontakta oss.";
            echo json_encode($msg); //Pretty error messages from PHPMailer
            die();
        }
        $this->mail->clearAddresses();
        $this->mail->clearAttachments();
    }
}
