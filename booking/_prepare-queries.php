<?php // prepare-queries.php

/* INSERT INTO asbooking.tours (
id, tour_name, pickup_point, end_point, date, timestamp, bus, capacity, price, language, shared_capacity, active, places_booked, manually_blocked_capacity
) VALUES (
NULL,  'Panoramic ride',  'Pile',  'Pile',  '2018-06-11',  '1528705800',  '1',  '19',  '150', NULL,  '0',  '1',  '0',  '0'
); */

$dateFormat = 'Y-m-d';
$start_time = new \DateTime('2019-04-01', new \DateTimeZone('Europe/Zagreb'));
$end_time = new \DateTime('2019-11-01', new \DateTimeZone('Europe/Zagreb'));


$tours = array(
    //array("Panoramic ride","Pile","Pile","2018-06-11","08:00","1","19","150",NULL,"0","1","0","0"),
    array("Panoramic ride","Pile","Pile","2018-06-11","09:00","1","19","150",NULL,"0","1","0","0"),
    array("Panoramic ride","Pile","Pile","2018-06-11","09:45","1","19","150",NULL,"1","1","0","0"), // SHARED
    array("Panoramic ride","Pile","Pile","2018-06-11","10:30","1","19","150",NULL,"0","1","0","0"),
    array("Panoramic ride","Pile","Pile","2018-06-11","11:15","1","19","150",NULL,"0","1","0","0"),
    array("Panoramic ride","Pile","Pile","2018-06-11","12:00","1","19","150",NULL,"0","1","0","0"),
    array("Panoramic ride","Pile","Pile","2018-06-11","12:45","1","19","150",NULL,"0","1","0","0"),
    array("Panoramic ride","Pile","Pile","2018-06-11","13:30","1","19","150",NULL,"0","1","0","0"),
    array("Panoramic ride","Pile","Pile","2018-06-11","14:15","1","19","150",NULL,"0","1","0","0"),
    array("Panoramic ride","Pile","Pile","2018-06-11","15:00","1","19","150",NULL,"0","1","0","0"),
    array("Panoramic ride","Pile","Pile","2018-06-11","15:45","1","19","150",NULL,"1","1","0","0"), // SHARED
    array("Panoramic ride","Pile","Pile","2018-06-11","16:30","1","19","150",NULL,"0","1","0","0"),

	array("Total Sightseeing","Pile","Pile","2018-06-11","09:45","1","19","250","English","1","1","0","0"), // SHARED
    array("Total Sightseeing","Pile","Pile","2018-06-11","15:45","1","19","250","English","1","1","0","0"), // SHARED

    array("Sunset WOW","Pile","Sunset beach","2018-06-11","18:00","1","19","250",NULL,"0","1","0","0"),



/*	array("Panoramic ride","Pile","Pile","2018-06-11","08:30","1","19","150",NULL,"0","1","0","0"),
	array("Panoramic ride","Pile","Pile","2018-06-11","10:30","1","19","150",NULL,"0","1","0","0"),
	array("Panoramic ride","Pile","Pile","2018-06-11","12:30","1","19","150",NULL,"0","1","0","0"),
	array("Panoramic ride","Pile","Pile","2018-06-11","14:30","1","19","150",NULL,"1","1","0","0"),
	array("Panoramic ride","Pile","Pile","2018-06-11","16:30","1","19","150",NULL,"0","1","0","0"),
	array("Sunset WOW","Pile","Sunset beach","2018-06-11","18:30","1","19","250",NULL,"0","1","0","0"),
	array("Total Sightseeing","Pile","Pile","2018-06-11","14:30","1","19","250","English","1","1","0","0"),
	array("Panoramic ride","Sunset beach","Pile","2018-06-11","08:30","2","19","150",NULL,"0","1","0","0"),
	array("Panoramic ride","Pile","Pile","2018-06-11","11:00","2","19","150",NULL,"0","1","0","0"),
	array("Panoramic ride","Sunset beach","Pile","2018-06-11","14:00","2","19","150",NULL,"1","1","0","0"),
	array("Panoramic ride","Pile","Pile","2018-06-11","16:00","2","19","150",NULL,"0","1","0","0"),
	array("Panoramic ride","Sunset beach","Pile","2018-06-11","18:30","2","19","150",NULL,"0","1","0","0"),
	array("Total Sightseeing","Sunset beach","Pile","2018-06-11","14:00","2","19","250","English","1","1","0","0"),
	array("Sunset WOW","Sunset beach","Sunset beach","2018-06-11","18:30","2","19","250",NULL,"0","1","0","0"),
	array("Panoramic ride","President","Pile","2018-06-11","09:00","3","19","150",NULL,"0","1","0","0"),
	array("Panoramic ride","Pile","Pile","2018-06-11","11:30","3","19","150",NULL,"0","1","0","0"),
	array("Panoramic ride","President","Pile","2018-06-11","13:30","3","19","150",NULL,"1","1","0","0"),
	array("Panoramic ride","Pile","Pile","2018-06-11","15:30","3","19","150",NULL,"0","1","0","0"),
	array("Sunset WOW","President","Sunset beach","2018-06-11","18:30","3","19","250",NULL,"0","1","0","0"),
	array("Total Sightseeing","President","Pile","2018-06-11","13:30","3","19","250","English","1","1","0","0"),*/
);

// echo '<pre>';
// var_dump($array);
// echo '</pre>';

$temp_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

while($end_time > $start_time) {

	foreach($tours as $k => $tour) {

        if(($k == 9 || $k == 10 || $k == 12) && ($start_time->format('n') == 4 || $start_time->format('n') == 10)) {
            continue;
        }

        if($k == 10 && $start_time->format('n') == 9) {
            continue;
        }

        if($tour[0] != 'Sunset WOW') {
		    $temp_time = new \DateTime($start_time->format($dateFormat) . ' ' . $tour[4], new \DateTimeZone('Europe/Zagreb'));
        } else {

            if($start_time->format('n') == 10) {
                $temp_time = new \DateTime($start_time->format($dateFormat) . ' 16:00', new \DateTimeZone('Europe/Zagreb'));
            }

            if($start_time->format('n') == 4 || $start_time->format('n') == 9) {
                $temp_time = new \DateTime($start_time->format($dateFormat) . ' 16:30', new \DateTimeZone('Europe/Zagreb'));
            }

            if($start_time->format('n') == 5 || $start_time->format('n') == 8) {
                $temp_time = new \DateTime($start_time->format($dateFormat) . ' 17:15', new \DateTimeZone('Europe/Zagreb'));
            }

            if($start_time->format('n') == 6 || $start_time->format('n') == 7) {
                $temp_time = new \DateTime($start_time->format($dateFormat) . ' 18:00', new \DateTimeZone('Europe/Zagreb'));
            }

        }
		//$temp_time = new \DateTime($start_time->format($dateFormat), new \DateTimeZone('Europe/Zagreb'));

        //echo $temp_time->format('U') . ', ';

		echo "INSERT INTO adriatic_main.tours (
		id, tour_name, pickup_point, end_point, date, timestamp, bus, capacity, price, language, shared_capacity, active, places_booked, manually_blocked_capacity
		)
		VALUES (
		NULL,  '" . $tour[0] . "',  '" . $tour[1] . "',  '" . $tour[2] . "',  '" . $start_time->format($dateFormat) . "',  '" . $temp_time->format("U") . "',  '" . $tour[5] . "',  '" . $tour[6] . "',  '" . $tour[7] . "', NULL,  '" . $tour[9] . "',  '" . $tour[10] . "',  '" . $tour[11] . "',  '" . $tour[12] . "'
		);<br>";
    }

	$start_time->modify("+1 day");
}