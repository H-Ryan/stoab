<?php
/**
 * User: Samuil
 * Date: 24-06-2015
 * Time: 7:56 PM.
 */
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}

$target_dir = '../../img/uploaded/';
$target_file = $target_dir.basename($_FILES['file']['name']);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$data = array();
$data['error'] = 0;
$data['message'] = array();
if (isset($_POST['fileUpload'])) {
    $check = getimagesize($_FILES['file']['tmp_name']);
    if ($check) {
        $data['error'] = 0;
        $data['message'][] = 'File is an image - '.$check['mime'].'.';
    } else {
        $data['error'] = 1;
        $data['message'][] = 'File is not an image.';
    }
}
if (file_exists($target_file)) {
    $data['error'] = 2;
    $data['message'][] = 'Sorry, file already exists.';
}

if ($_FILES['file']['size'] > 10000000) {
    $data['error'] = 3;
    $data['message'][] = 'Sorry, your file is too large.';
}

if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg'
    && $imageFileType != 'gif') {
    $data['error'] = 4;
    $data['message'][] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
}

if ($data['error'] != 0) {
    $data['message'][] = 'Sorry, your file was not uploaded.';
} else {
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        $data['error'] = 0;
        $data['message'][] = 'The image has been uploaded.';
    } else {
        $data['error'] = 5;
        $data['message'][] = 'Sorry, there was an error uploading your file.';
    }
}
echo json_encode($data);
