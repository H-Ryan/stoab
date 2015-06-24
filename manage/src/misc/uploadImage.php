<?php
/**
 * User: Samuil
 * Date: 24-06-2015
 * Time: 7:56 PM
 */
$target_dir = "../../../images/uploaded/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

$data = array();
$data['error'] = 0;
$data['message'] = array();
if(isset($_POST["fileUpload"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        $data['error'] = 0;
        $data['message'][] = "File is an image - " . $check["mime"] . ".";
    } else {
        $data['error'] = 1;
        $data['message'][] = "File is not an image.";
    }
}
if (file_exists($target_file)) {
    $data['error'] = 1;
    $data['message'][] = "Sorry, file already exists.";
}

if ($_FILES["file"]["size"] > 500000) {
    $data['error'] = 1;
    $data['message'][] = "Sorry, your file is too large.";
}

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    $data['error'] = 1;
    $data['message'][] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
}

if ( $data['error'] == 1) {
    $data['message'][] = "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $data['error'] = 0;
        $data['message'][] = "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
    } else {
        $data['error'] = 1;
        $data['message'][] = "Sorry, there was an error uploading your file.";
    }
}
echo json_encode($data);