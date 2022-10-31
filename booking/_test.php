<?php

$dateFormat = 'Y-m-d';
$start_time = new \DateTime('2018-06-11', new \DateTimeZone('Europe/Zagreb'));
$end_time = new \DateTime('2018-11-01', new \DateTimeZone('Europe/Zagreb'));

echo '(';

while($end_time > $start_time) {

	$temp_time = new \DateTime($start_time->format($dateFormat) . ' 12:30', new \DateTimeZone('Europe/Zagreb'));

    echo $temp_time->format("U");

    echo ',';

	$start_time->modify("+1 day");
}

echo ')';