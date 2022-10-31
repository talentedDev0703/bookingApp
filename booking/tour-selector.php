<?php

  header('Content-Type: application/json');

  include('config.php');

  $dateFormat = 'd.m.Y';
  $timeFormat = 'H:i';
  $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $get_vars = $_GET;

  if(isset($get_vars['id'])) {

    $return = 'tour';

  } else {

    $return = 'id';

    if(!isset($get_vars['time'])) {
      $return = 'time';
    }

    if(!isset($get_vars['date'])) {
      $return = 'date';
    }

    if(!isset($get_vars['pickup'])) {
      $return = 'pickups';
    }

    if(!isset($get_vars['tour'])) {
      $return = '';
    }
  }

  /*
    array(
        'name' => 'Gruz',
        'latitude' => '42.658007',
        'longitude' => '18.086239'
    ),
  */

  $pickup_points = array(
    array(
        'name' => 'Pile',
        'latitude' => '42.641766',
        'longitude' => '18.107046'
    ),
    /*array(
        'name' => 'Sunset beach',
        'latitude' => '42.655080',
        'longitude' => '18.070775'
    ),
    array(
        'name' => 'President',
        'latitude' => '42.661088',
        'longitude' => '18.058798'
    ),*/
  );

  switch($return) {
    case 'tour':
      $query = "SELECT id, tour_name, pickup_point, timestamp, price, capacity, places_booked, manually_blocked_capacity FROM tours WHERE id = " . mysqli_real_escape_string($conn, $get_vars['id']) . " LIMIT 1";

      if ($result = $conn->query( $query )) {

        $row = $result->fetch_assoc();

        $start_time->setTimestamp($row['timestamp']);

        $row['date'] = $start_time->format($dateFormat);
        $row['time'] = $start_time->format($timeFormat);
        $row['available_capacity'] = $row['capacity'] - $row['places_booked'] - $row['manually_blocked_capacity'];

        $row['price_kids'] = number_format($row['price'] * 0.5, '2', '.', ',');
        $row['price_infants'] = '0.00';

        if(!isset($_COOKIE['auth'])) {
            $row['price'] = $row['price'] * 0.9;
            $row['price_kids'] = $row['price_kids'] * 0.9;
        }

        unset($row['timestamp']);
        unset($row['capacity']);
        unset($row['places_booked']);
        unset($row['manually_blocked_capacity']);

        echo json_encode($row);

      }

      break;
    case 'id':
      $query = "SELECT id FROM tours WHERE tour_name LIKE '" . mysqli_real_escape_string($conn, $get_vars['tour']) . "' AND pickup_point LIKE '" . mysqli_real_escape_string($conn, $get_vars['pickup']) . "' AND date = '" . mysqli_real_escape_string($conn, $get_vars['date']) . "' AND timestamp = '" . mysqli_real_escape_string($conn, $get_vars['time']) . "' AND active = 1 LIMIT 1";

      if ($result = $conn->query( $query )) {

        echo json_encode($result->fetch_assoc()['id']);

      }

      break;
    case 'time':
      $query = "SELECT timestamp FROM tours WHERE tour_name LIKE '" . mysqli_real_escape_string($conn, $get_vars['tour']) . "' AND pickup_point LIKE '" . mysqli_real_escape_string($conn, $get_vars['pickup']) . "' AND date = '" . mysqli_real_escape_string($conn, $get_vars['date']) . "' AND timestamp > " . $start_time->format("U") . " AND active = 1 ORDER BY timestamp";

      if ($result = $conn->query( $query )) {

        // if($result->num_rows > 0) {

          $all_start_times = array();

          while($row = $result->fetch_assoc()) {

            $start_time->setTimestamp($row['timestamp']);
            $row['time'] = $start_time->format($timeFormat);

            $all_start_times[] = $row;

          }

          echo json_encode($all_start_times);

        // } else {

        //   echo json_encode(array('0 results'));

        // }

      }
      break;
    case 'date':
      $query = "SELECT DISTINCT date FROM tours WHERE tour_name LIKE '" . mysqli_real_escape_string($conn, $get_vars['tour']) . "' AND pickup_point LIKE '" . mysqli_real_escape_string($conn, $get_vars['pickup']) . "' AND timestamp > " . $start_time->format("U") . " AND active = 1";

      if ($result = $conn->query( $query )) {

        // if($result->num_rows > 0) {

          $all_dates = array();

          while($row = $result->fetch_assoc()) {

            $all_dates[] = $row['date'];

          }

          echo json_encode($all_dates);

        // } else {

        //   echo json_encode(array('0 results'));

        // }

      }

      break;

    case 'pickups':

      echo json_encode($pickup_points);

      break;

    default:

      echo json_encode('No data defined');

      break;
  }




  //     $start_time->setTimestamp($tour['timestamp']);

  //     $tour['date'] = $start_time->format($dateFormat);
  //     $tour['time'] = $start_time->format($timeFormat);

  //     unset($tour['timestamp']);

  //     $pickup[] = $tour;

  //   }
  // }
