<?php
require "Mail.php";
require "Mail/mime.php";

function send_email($to, $from, $subject, $body) {
 
        $host = "localhost";
 
        $headers = array (
                'From' => $from,
                'To' => $to,
                'Subject' => $subject,
                'Content-Type'  => 'text/html; charset=UTF-8'
        );
        $mime_params = array(
          'text_encoding' => '7bit',
          'text_charset'  => 'UTF-8',
          'html_charset'  => 'UTF-8',
          'head_charset'  => 'UTF-8'
        );
        $mime = new Mail_mime();
        $mime->setHTMLBody($body);
 
        $body = $mime->get($mime_params);
        $headers = $mime->headers($headers);
 
        $smtp = Mail::factory('smtp', array ('host' => $host, 'port' => 26));
        $mail = $smtp->send($to, $headers, $body);
 
        if (PEAR::isError($mail)) {
                return false;
        } else {
                return true; 
        }
}
?>