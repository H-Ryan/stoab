<?php
/**
 * Created by PhpStorm.
 * User: Samuil
 * Date: 25-08-2015
 * Time: 4:32 PM
 */
include "src/misc/SMS_Service.php";
$to = $_GET['to'];
$text = $_GET['text'];

$smsService = new SMS_Service();

$smsService->setTo($to);
$smsService->setText($text);
$smsService->generateSMS()->sendSMS();