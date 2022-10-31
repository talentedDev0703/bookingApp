<?php

  header('Content-Type: application/json');

  include('config.php');

  $start_time = new \DateTime('first day of this month 00:00:00', new \DateTimeZone('Europe/Zagreb'));
  $tour_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

  $now = $tour_time->format('U');
  $yesterday = strtotime('-24 hours');

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $get_vars = $_GET;

  if(!isset($get_vars['company_id'])) {
    echo json_encode('No data defined');
    die;
  }

  $cookie_value = explode("+", as_simple_crypt($_COOKIE['b2b'], 'd'));

  if($cookie_value[1] != $get_vars['company_id']) {
    echo json_encode('You are not allowed to get orders for company id ' . $get_vars['company_id']);
    die;
  }

  if(isset($get_vars['remove']) && $get_vars['remove']) {

    $cancel_order_id = mysqli_real_escape_string($conn, $get_vars['remove']);

    // Get order
    $query = "SELECT tour_id, adults, kids, cancelled FROM orders WHERE id = " . $cancel_order_id;

    if ($result = $conn->query( $query )) {

      $order = $result->fetch_assoc();

    }

    if($order['cancelled'] == 0) {

      // Calculate allocated seats

      $number_of_seats = $order['adults'] + $order['kids'];

      // vrati na turi i na shared turi
      $query = "SELECT tour_name, price, places_booked, shared_capacity, timestamp, pickup_point, tour_name FROM tours WHERE id = " . $order['tour_id'];

      if ($result = $conn->query( $query )) {

        $row = $result->fetch_assoc();

        $where = 'id = ' . $order['tour_id'];

        if($row['shared_capacity']) {

          $query = "SELECT id FROM tours WHERE timestamp = " . $row['timestamp'] . " AND pickup_point like '" . $row['pickup_point'] . "' AND shared_capacity = 1 AND id != " . $order['tour_id'] . " LIMIT 1";

          if ($result = $conn->query( $query )) {

            $shared_row_id = $result->fetch_assoc()['id'];

            $where = 'id IN (' . mysqli_real_escape_string($conn, $order['tour_id']) . ', ' . $shared_row_id . ')';
          }

        }

        // UPDATE SEATS
        $number_of_seats = $row['places_booked'] - $number_of_seats;

        $query = "UPDATE tours SET places_booked = " . $number_of_seats . " WHERE " . $where;

        if ( $conn->query($query) != TRUE ) {
          echo json_encode('Error');
          die;
        }

      }

      // Set cancelled = 1

      $query = "UPDATE orders SET cancelled = 1 WHERE id = " . $cancel_order_id . " AND " . $yesterday . " <= timestamp";

      if ( $conn->query($query) != TRUE ) {
        echo json_encode('Error');
        die;
      }
    }
  }

  $query = "SELECT o.id, o.name as buyer_name, o.timestamp as order_timestamp, o.cancelled, t.tour_name, t.pickup_point, t.timestamp FROM orders o LEFT JOIN tours t ON o.tour_id = t.id WHERE o.company_id = '" . mysqli_real_escape_string($conn, $get_vars['company_id']) . "' AND o.timestamp >= '" . $start_time->format('U') . "'";

  $all_orders = array();

  if ( $result = $conn->query($query) ) {

    $row = $result->fetch_assoc();

    while($row = $result->fetch_assoc()) {

      $row['id'] = (int)$row['id'];
      $row['cancelled'] = (bool)$row['cancelled'];

      $tour_time->setTimestamp($row['timestamp']);
      $row['pickup_time'] = $tour_time->format('H:i');
      $row['pickup_date'] = $tour_time->format('d.m.Y');
      $row['cancel_available'] = ($yesterday <= $row['order_timestamp'] && $now <= $row['timestamp'] && $row['cancelled'] == false);

      unset($row['timestamp']);
      unset($row['order_timestamp']);

      $all_orders[] = $row;

    }

  }

  if( count($all_orders) > 0 ) {
    echo json_encode($all_orders);
  } else {
    echo json_encode('No orders in current month');
  }
