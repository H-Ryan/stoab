<?php
/**
 * User: Samuil
 * Date: 25-06-2015
 * Time: 8:57 PM
 */

ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit ("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}
$data = array();
$filename = '../../data/colab.json';
if(isset($_GET['isUpdate']))
{
    $data['error'] = 0;
    if (file_exists($filename)) {
        if (intval($_GET['isUpdate']) === 0) {
            $data['lastModified'] = filemtime ($filename);
            $data['data'] = json_decode(file_get_contents($filename), true);
        } else {
            if (isset($_GET['lastModified'])) {
                if ($_GET['lastModified'] < filemtime($filename)) {
                    $data['lastModified'] = filemtime($filename);
                    $data['data'] = json_decode(file_get_contents($filename), true);
                } else {
                    $data['error'] = 2;
                }
            } else {
                $data['error'] = 1;
                $data['message'] = "Some of the required fields are missing!";
            }
        }
    } else {
        $data['error'] = 1;
        $data['message'] = "The feed file is missing!";
    }
} else {
    $data['error'] = 1;
    $data['message'] = "Some of the required fields are missing!";
}

echo json_encode($data);