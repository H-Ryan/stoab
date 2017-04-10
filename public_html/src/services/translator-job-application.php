<?php
/**
 * User: Samuil
 * Date: 11-03-2016
 * Time: 12:22 AM.
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
if (isset($_POST['firstName']) && isset($_POST['firstName']) && isset($_POST['personalNumber'])
    && isset($_POST['gender']) && isset($_POST['email'])
    && (isset($_POST['phoneHome']) || isset($_POST['phoneMobile']))
    && isset($_POST['address']) && (isset($_POST['subject']) || isset($_POST['otherSubject']))
    && isset($_POST['from1']) && isset($_POST['to1']) && isset($_POST['priceWord1']) && isset($_POST['priceHour1'])
    && isset($_POST['authorized1']) && isset($_POST['monthOrYear1'])
) {
    $emailer = new Emails();
    $emailContent = "<html><head></head>
        <body style='
            width: 100%;
            height: 100%;
            font-family: Lato,\"Helvetica Neue\",Arial,Helvetica,sans-serif;'>";
    $emailContent .= '<h2>ÖVERSÄTTARE Intresseanmälan</h2>';
    $emailContent .= printRow('Namn:', $_POST['firstName']);
    $emailContent .= printRow('Efternamn:', $_POST['firstName']);
    $emailContent .= printRow('Personnummer:', $_POST['personalNumber']);
    $emailContent .= printRow('Kön:', $_POST['gender']);
    $emailContent .= printRow('E-post:', $_POST['email']);
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
    $emailContent .= printRow('Gatuadress:', $_POST['address']);
    $subject = '';
    if (isset($_POST['subject'])) {
        if (strlen($_POST['subject']) > 0) {
            if (isset($_POST['otherSubject'])) {
                if (strlen($_POST['otherSubject']) > 0) {
                    $subject = $_POST['subject'].' och '.$_POST['otherSubject'];
                } else {
                    $subject = $_POST['subject'];
                }
            } else {
                $subject = $_POST['subject'];
            }
        } elseif (isset($_POST['otherSubject'])) {
            if (strlen($_POST['otherSubject']) > 0) {
                $subject = $_POST['otherSubject'];
            }
        }
    }
    $emailContent .= printRow('Ämnesområde:', $subject);
    $prices = ['1', '2', '3', '4'];

    $emailContent .= "<h4>Språk:</h4><table style='width: 80%;
                                    margin-left: 10%;
                                    margin-right: 10%;
                                    text-align: center;
                                    font-family: verdana, arial, sans-serif;
                                    font-size: 14px;
                                    color: #000000;
                                    border-radius: 5px;
                                    border: 1px solid #000000;' cellpadding='10'>
                                            <thead>
                                            <tr>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Från
                                                </th>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Till
                                                </th>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Pris/Ord
                                                </th>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Pris/Tim
                                                </th>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Auktoriserad av kammarkollegiet t.o.m.
                                                </th>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Månad,År
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>";
    for ($i = 0; $i < sizeof($prices); ++$i) {
        if (isset($_POST['from'.$prices[$i]]) && isset($_POST['to'.$prices[$i]]) && isset($_POST['priceWord'.$prices[$i]])
            && isset($_POST['priceHour'.$prices[$i]]) && isset($_POST['authorized'.$prices[$i]])
            && isset($_POST['monthOrYear'.$prices[$i]])
        ) {
            $from = $_POST['from'.$prices[$i]];
            $to = $_POST['to'.$prices[$i]];
            $priceWord = $_POST['priceWord'.$prices[$i]];
            $priceHour = $_POST['priceHour'.$prices[$i]];
            $authorized = $_POST['authorized'.$prices[$i]];
            $monthOrYear = $_POST['monthOrYear'.$prices[$i]];
            $row = [$from, $to, $priceWord, $priceHour, $authorized, $monthOrYear];
            $emailContent .= printLangTableRow($row);
        }
    }
    $emailContent .= '</tbody></table>';
    $exp = ['1', '2', '3'];
    $emailContent .= "<h4>Bakgrundsinformation:</h4><table style='width: 80%;
                                    margin-left: 10%;
                                    margin-right: 10%;
                                    text-align: center;
                                    font-family: verdana, arial, sans-serif;
                                    font-size: 14px;
                                    color: #000000;
                                    border-radius: 5px;
                                    border: 1px solid #000000;' cellpadding='10'>
                                            <thead>
                                            <tr>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Översättareutbildning
                                                </th>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Utbildningslängdår
                                                </th>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Examensår
                                                </th>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Land
                                                </th>
                                                <th style='font-size: 14px; padding: 8px; border-radius: inherit; border: 1px solid #000000;'>
                                                    Språk Från Till
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>";
    for ($i = 0; $i < sizeof($exp); ++$i) {
        if (isset($_POST['training'.$exp[$i]]) && isset($_POST['period'.$exp[$i]]) && isset($_POST['degree'.$exp[$i]])
            && isset($_POST['country'.$exp[$i]]) && isset($_POST['lang'.$exp[$i]])
        ) {
            $training = $_POST['training'.$exp[$i]];
            $period = $_POST['period'.$exp[$i]];
            $degree = $_POST['degree'.$exp[$i]];
            $country = $_POST['country'.$exp[$i]];
            $lang = $_POST['lang'.$exp[$i]];

            $row = [$training, $period, $degree, $country, $lang];
            $emailContent .= printExpTableRow($row);
        }
    }
    $emailContent .= '</tbody></table>';
    if (isset($_POST['education'])) {
        $emailContent .= printRow('Har övriga kurs eller högskolutbildning eller arbetat som översättare:<br />', $_POST['education']);
    }

    if (isset($_POST['name1']) && isset($_POST['telephone1']) && isset($_POST['job1'])) {
        $ref = '<br />Namn Efternamn: '.$_POST['name1'].', Telefon: '.$_POST['telephone1'].', Jobb & Bransch: '.$_POST['job1'];
        $emailContent .= printRow('Referens för översättning:', $ref);
    }
    if (isset($_POST['employed'])) {
        $emailContent .= printRow('Egenföretagare:', $_POST['employed']);
    }
    if (isset($_POST['bankgiro'])) {
        $emailContent .= printRow('Bankgiro:', $_POST['bankgiro']);
    }
    if (isset($_POST['plusgiro'])) {
        $emailContent .= printRow('Plusgiro:', $_POST['plusgiro']);
    }
    if (isset($_POST['bankAccount'])) {
        $emailContent .= printRow('Bank konto:', $_POST['bankAccount']);
    }
    if (isset($_POST['bankName'])) {
        $emailContent .= printRow('Bankensnamn:', $_POST['bankName']);
    }
    if (isset($_POST['clearingNumber'])) {
        $emailContent .= printRow('Clearing nu:', $_POST['clearingNumber']);
    }
    if (isset($_POST['bankCardNumber'])) {
        $emailContent .= printRow('Kontonummer:', $_POST['bankCardNumber']);
    }

    $emailContent .= '</body></html>';
    echo json_encode(['error' => $emailer->send_email('rekrytering@c4tolk.se', 'C4 SPRÅKPARTNER AB', 'ÖVERSÄTTARE Intresseanmälan', $emailContent) ? 0 : 1]);
} else {
    echo json_encode(['error' => 1]);
}

function printRow($label, $data)
{
    return "<p><span><b>$label</b></span> <span>$data</span></p>";
}

function printExpTableRow($data)
{
    return "<tr style='background-color: #ffffff;border-radius: 5px;'>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[0]</td>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[1]</td>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[2]</td>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[3]</td>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[4]</td>
       </tr>";
}
function printLangTableRow($data)
{
    return "<tr style='background-color: #ffffff;border-radius: 5px;'>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[0]</td>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[1]</td>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[2]</td>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[3]</td>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[4]</td>
        <td style='padding: 8px; border-radius: inherit; border: 1px solid #000000;'>$data[5]</td>
       </tr>";
}
