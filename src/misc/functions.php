<?php
/**
 * This method will convert the passed String plus the
 * two salts, one placed at the beginning and one at
 * the end, into 40 char hexadecimal String
 * @param $str
 * @return string
 */
function encrypt_password($str)
{
    $salt1 = ".N,s7";
    $salt2 = "!R@t1";
    return hash("sha512", $salt1 . $str . $salt2); //(algo, msg, binary(true)/hexa(false))
}

function getFullTolkningType($str)
{
    if ($str === "KT") {
        return 'Kontakttolkning';
    } elseif ($str === "TT") {
        return 'Telefontolkning';
    } elseif ($str === "KP") {
        return 'Kontaktperson';
    } elseif ($str === "SH") {
        return 'Studiehandledning';
    } elseif ($str === "SS") {
        return "Språkstöd";
    } else {
        return "";
    }
}

function genOrderNumber()
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $randomString = '';
    for ($i = 0; $i < 2; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    for ($i = 0; $i < 4; $i++) {
        $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
    }
    return $randomString;
}

function genOrderPassword()
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function getRealIpAddress()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function convertTime($value)
{
    $minutes = array("00", "15", "30", "45");
    return (($value - ($value % 4)) / 4) . ":" . $minutes[($value % 4)];
}