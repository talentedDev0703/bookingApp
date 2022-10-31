<?php

  header('Content-Type: application/json');

  include('config.php');

  $dateFormat = 'Y-m-d';
  $timeFormat = 'H:i';
  $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $pickup_points = array(
    'Pile' => array(
      'latitude' => '42.641766',
      'longitude' => '18.107046'
    ),
    'Gruz' => array(
      'latitude' => '42.658007',
      'longitude' => '18.086239'
    ),
    'Sunset beach' => array(
      'latitude' => '42.655080',
      'longitude' => '18.070775'
    ),
    'President' => array(
      'latitude' => '42.661088',
      'longitude' => '18.058798'
    ),
  );

  $get_next = "SELECT id, tour_name, pickup_point, timestamp, price FROM tours WHERE timestamp > " . $start_time->format('U') . " AND active = 1 ORDER BY timestamp LIMIT 3";

  if ($result = $conn->query( $get_next )) {

    $next_tours = array();

    while($tour = $result->fetch_assoc()) {

      $start_time->setTimestamp($tour['timestamp']);

      $tour['date'] = $start_time->format($dateFormat);
      $tour['time'] = $start_time->format($timeFormat);

      $tour['latitude'] = $pickup_points[$tour['pickup_point']]['latitude'];
      $tour['longitude'] = $pickup_points[$tour['pickup_point']]['longitude'];

      //unset($tour['timestamp']);

      $next_tours[] = $tour;

    }
  }

  echo json_encode($next_tours);
