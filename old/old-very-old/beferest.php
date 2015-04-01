<?php
require "../../phpDEV/email/Emails.php";
error_reporting(E_ALL ^ E_NOTICE);


$my_email = "info@tolktjanst.se";

/*

Optional.  Enter a From: email address.  Only do this if you know you need to.  By default, the email you get from the script will show the visitor's email address as the From: address.  In most cases this is desirable.  On the majority of setups this won't be a problem but a minority of hosts insist that the From: address must be from a domain on the server.  For example, if you have the domain example.com hosted on your server, then the From: email address must be something@example.com (See your host for confirmation).  This means that your visitor's email address will not show as the From: address, and if you hit "Reply" to the email from the script, you will not be replying to your visitor.  You can get around this by hard-coding a From: address into the script using the configuration option below.  Enabling this option means that the visitor's email address goes into a Reply-To: header, which means you can hit "Reply" to respond to the visitor in the conventional way.  (You can also use this option if your form does not collect an email address from the visitor, such as a survey, for example, and a From: address is required by your email server.)  The default value is: $from_email = "";  Enter the desired email address between the quotes, like this example: $from_email = "contact@example.com";  In these cases, it is not uncommon for the From: ($from_email) address to be the same as the To: ($my_email) address, which on the face of it appears somewhat goofy, but that's what some hosts require.

*/

$from_email = "noreply@tolktjanst.com";

/*

Optional.  Enter the continue link to offer the user after the form is sent.  If you do not change this, your visitor will be given a continue link to your homepage.

If you do change it, remove the "/" symbol below and replace with the name of the page to link to, eg: "mypage.htm" or "http://www.elsewhere.com/page.htm"

*/

$continue = "/";

/*

Step 3:

Save this file (FormToEmail.php) and upload it together with your webpage containing the form to your webspace.  IMPORTANT - The file name is case sensitive!  You must save it exactly as it is named above!

THAT'S IT, FINISHED!

You do not need to make any changes below this line.

*/

$errors = array();

// Remove $_COOKIE elements from $_REQUEST.

if (count($_COOKIE)) {
    foreach (array_keys($_COOKIE) as $value) {
        unset($_REQUEST[$value]);
    }
}

// Validate email field.

if (isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {

    $_REQUEST['email'] = trim($_REQUEST['email']);

//if(substr_count($_REQUEST['email'],"@") != 1 || stristr($_REQUEST['email']," ") || stristr($_REQUEST['email'],"\\") || stristr($_REQUEST['email'],":")){$errors[] = "Email address is invalid";}else{$exploded_email = explode("@",$_REQUEST['email']);if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){$errors[] = "Email address is invalid";}else{if(substr_count($exploded_email[1],".") == 0){$errors[] = "Email address is invalid";}else{$exploded_domain = explode(".",$exploded_email[1]);if(in_array("",$exploded_domain)){$errors[] = "Email address is invalid";}else{foreach($exploded_domain as $value){if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){$errors[] = "Email address is invalid"; break;}}}}}}

}

// Check referrer is from same site.

if (!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']))) {
    $errors[] = "You must enable referrer logging to use the form";
}

// Check for a blank form.

function recursive_array_check_blank($element_value)
{

    global $set;

    if (!is_array($element_value)) {
        if (!empty($element_value)) {
            $set = 1;
        }
    } else {

        foreach ($element_value as $value) {
            if ($set) {
                break;
            }
            recursive_array_check_blank($value);
        }

    }

}

recursive_array_check_blank($_REQUEST);

if (!$set) {
    $errors[] = "You cannot send a blank form";
}

unset($set);

// Display any errors and exit if errors exist.

if (count($errors)) {
    foreach ($errors as $value) {
        print "$value<br>";
    }
    exit;
}

if (!defined("PHP_EOL")) {
    define("PHP_EOL", strtoupper(substr(PHP_OS, 0, 3) == "WIN") ? "\r\n" : "\n");
}

// Build message.

function build_message($request_input)
{
    if (!isset($message_output)) {
        $message_output = "";
    }
    if (!is_array($request_input)) {
        $message_output = $request_input;
    } else {
        foreach ($request_input as $key => $value) {
            if (!empty($value)) {
                if (!is_numeric($key)) {
                    $message_output .= str_replace("_", " ", ucfirst($key)) . ": " . build_message($value) . '<br />' . '<br />';
                } else {
                    $message_output .= build_message($value) . ", ";
                }
            }
        }
    }
    return rtrim($message_output . '', ", ");
}

$message = build_message($_REQUEST);
$message = $message . PHP_EOL . PHP_EOL . "";
$message = stripslashes('' . $message . '');


$subject = "Intresseanmälan";

$subject = stripslashes($subject);

if ($from_email) {
    $headers = "From: " . strip_tags($from_email) . "\r\n";
    $headers .= PHP_EOL;
    $headers .= "Reply-To: ". strip_tags($_REQUEST['email']) . "\r\n";
    $headers .= "CC: susan@example.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

} else {

    $from_name = "";

    if (isset($_REQUEST['name']) && !empty($_REQUEST['name'])) {
        $from_name = stripslashes($_REQUEST['name']);
    }

    $headers = "From: {$from_name} <{$_REQUEST['email']}>";

}
$emailer = new Emails();
$emailer->send_email($my_email, $name, $subject, $message);

?>

<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
        <!--
        BODY {
            color: #333333;
            font-size: 12px;
            font-family: verdana, Tahoma, Helvetica, sans-serif
        }

        TD {
            color: #333333;
            font-size: 12px;
            font-family: verdana, Tahoma, Helvetica, sans-serif
        }

        .sefid {
            color: #cccccc;
            font-size: 12px;
            font-family: verdana, arial, Tahoma, Helvetica, sans-serif
        }

        .siah {
            color: #000000;
            font-size: 14px;
            font-family: verdana, arial, Tahoma, Helvetica, sans-serif
        }

        .titel {
            color: #999999;
            font-size: 27px;
            font-family: times, Helvetica, sans-serif;
            font-weight: bold
        }

        .lank {
            color: #353435
        }

        H2 {
            color: #999999;
            font-size: 18px;
            font-family: Arial, Tahoma, Helvetica, sans-serif
        }

        OL {
            color: #cc0000;
            font-size: 13px;
            font-family: arial, Tahoma, Helvetica, sans-serif;
            font-weight: bold;
        }

        .lank {
            color: #353435
        }

        H2 {
            color: #0A8A99;
            font-size: 18px;
            font-family: Arial, Tahoma, Helvetica, sans-serif
        }

        a:hover {
            color: #000000;
            background: #cccccc;
        }

        a {
            text-decoration: none;
        }

        -->
    </style>
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align=center>
    <table width="450" height="900" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td valign=top>
                <br><br>
                <blockquote><br><br>
                    <b><?php if (isset($_REQUEST['name'])) {
                            print stripslashes($_REQUEST['name']);
                        } ?></b>
                    <br><br>
                    <center><h3>Tack för beställningen! - Tolktjänst </h3>
                        <br>

                        <p><a href="<?php print $continue; ?>" target="_top">Startsida</a>&nbsp;</p>

                        <p></p>
            </td>
        </tr>
    </table>
    <br><br>
    <br><br>
</div>
</body>
</html>