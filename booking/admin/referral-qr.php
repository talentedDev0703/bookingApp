<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

require __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;

if(isset($_GET['company_id'])) {

	$qrCode = new QrCode('https://www.adriaticsunsets.com/?referrer=' . $_GET['company_id']);
	$qrCode->setSize(640);
	header('Content-Type: '.$qrCode->getContentType());
    echo $qrCode->writeString();

} else {

    die('No ID');

}