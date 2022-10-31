<?php

require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\QrCode;

if(isset($_GET['id'])) {

	$qrCode = new QrCode('http://www.adriaticsunsets.com/booking/validate-voucher?id=' . $_GET['id']);
	$qrCode->setSize(320);
	$qrCode->writeFile(__DIR__.'/qr/validate-' . $_GET['id'] . '.png');

	echo "Success";

} else {

	echo "Error";

}