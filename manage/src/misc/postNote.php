<?php
/**
 * User: Samuil
 * Date: 25-06-2015
 * Time: 7:49 PM
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
if(isset($_POST['postedBy']) && !empty($_POST['postedBy']) &&
    isset($_POST['message']) && !empty($_POST['message']) &&
    isset($_POST['category']) && !empty($_POST['category']))
{
    $category = intval($_POST['category']);

    $filename = '../../data/colab.json';
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        $feed = json_decode($content, true);

        $event = new Event();
        $event->postedBy = $_POST['postedBy'];
        $event->postedOn = dateTimeNow();
        $event->message = $_POST['message'];

        $data['error'] = 0;

        if ($category === 1) {
            $feed['internal'][] = $event;
        } else if ($category === 2) {
            $feed['customers'][] = $event;
        } else if ($category === 3) {
            $feed['interpreters'][] = $event;
        } else {
            $data['error'] = 1;
            $data['message'] = "Cannot confirm the category!";
        }
        if ($data['error'] !== 1) {
            try {
                file_put_contents($filename, json_encode($feed), LOCK_EX);
                $data['newEvent'] = $event;
            } catch (Exception $e) {
                $data['error'] = 1;
                $data['message'] = "Someone else is writing now please try again in a few seconds!";
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

function dateTimeNow() {
    $timeZone = new DateTimeZone('Europe/Stockholm');
    $datetime = new DateTime();
    $datetime->setTimezone($timeZone);
    return $datetime->format('Y\-m\-d\ h:i:s');
}

class Event {
    public $postedBy = null;
    public $postedOn = null;
    public $message = null;

    public function __construct() {
        $this->postedBy = "";
        $timeZone = new DateTimeZone('Europe/Stockholm');
        $datetime = new DateTime();
        $datetime->setTimezone($timeZone);
        $this->postedOn = $datetime->format('Y\-m\-d\ h:i:s');
        $this->message = "";
    }
}