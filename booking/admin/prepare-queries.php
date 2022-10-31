<?php

  $page_config = array(
    'active' => 'tours-list',
    'title' => ''
  );

  include('../config.php');

  $datetimeFormat = 'd.m.Y H:i';
  $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

  $conn = new mysqli($servername, $username, $password, $database);

  $get_next = "SELECT id, tour_name, timestamp FROM tours WHERE timestamp > " . $start_time->format('U') . " AND active = 1 AND pickup_point = 'Pile' AND places_booked = 0";
  //$get_next = "SELECT id, tour_name, timestamp FROM tours WHERE timestamp > " . $start_time->format('U') . " AND active = 0 AND pickup_point = 'Pile'";

  $ids_to_deactivate = array();

  if ($result = $conn->query( $get_next )) {

    while($tour = $result->fetch_assoc()) {

      $start_time->setTimestamp($tour['timestamp']);
      $deactivate_times = array('16:00');
      //$activate_times = array('16:30');

      if( in_array($start_time->format('H:i'), $deactivate_times) ) {

        //echo $tour['id'] . ' ' . $tour['tour_name'] . ' ' . $start_time->format('H:i') . '<br>';
        $ids_to_deactivate[] = $tour['id'];

      }
    }
  }

  echo 'UPDATE tours SET active = 0 WHERE id IN (' . implode($ids_to_deactivate, ',') . ')';

?>
