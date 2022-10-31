<?php

  header('Content-Type: application/json');

  include('config.php');

  $start_time = new \DateTime('first day of this month 00:00:00', new \DateTimeZone('Europe/Zagreb'));

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $get_vars = $_GET;

  // var_dump($get_vars);

  if(!isset($get_vars['company_id'])) {
    echo json_encode('No data defined');
    die;
  }

  $get_company = "SELECT name FROM companies WHERE id = '" . mysqli_real_escape_string($conn, $get_vars['company_id']) . "'";

  if ($result = $conn->query( $get_company )) {

    $company = $result->fetch_assoc()['name'];

  }

  $query = "SELECT id, name FROM users WHERE company_id = '" . mysqli_real_escape_string($conn, $get_vars['company_id']) . "' AND active = 1 AND role != 1";

  //$users_in_company = array();
  $users_in_company = array(
    array(
      'id' => '-1',
      'name' => 'Referrals'
    ),
    array(
      'id' => '0',
      'name' => 'Telephone sales'
    )
  );

  if ($result = $conn->query( $query )) {

    while($row = $result->fetch_assoc()) {

      $users_in_company[] = $row;

    }

  }

  if($users_in_company) {

    $total_current_month = 0;

    foreach($users_in_company as $k => $user) {
      $query = "SELECT SUM(value) as value, COUNT(*) as count FROM orders WHERE user_id = '" . $user['id'] . "' AND paid = 1 AND timestamp >= '" . $start_time->format('U') . "' AND cancelled = 0";

      if ( $result = $conn->query($query) ) {

        //var_dump($result->fetch_assoc());

        $row = $result->fetch_assoc();

        $users_in_company[$k]['tours'] = $row['count'];

        $user_value = (float)$row['value'];

        $users_in_company[$k]['value'] = number_format ( $user_value, 2 , ",", ".");
        $total_current_month += $user_value;
      }

    }

    // za svakog usera dohvati pojedinačne narudžbe (zbroj cijene + količina, current month, last month)
    // zbroji to u array

    $return = array(
      'users' => $users_in_company,
      'company' => $company,
      'current_month_name' => date('F'),
      'current_month_total' => number_format ( $total_current_month, 2 , ",", "."),
    );

    echo json_encode($return);

  } else {

    echo json_encode('No users in selected company');

  }
