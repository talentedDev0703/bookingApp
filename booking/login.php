<?php

  header('Content-Type: application/json');

  include('config.php');

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $get_vars = $_GET;

  if(count($_GET) == 0) {

    $get_vars = $_POST;

  }

  // var_dump($get_vars);

  if(
    !isset($get_vars['username']) ||
    !isset($get_vars['password'])
  ) {
    echo json_encode('No data defined');
    die;
  }

  // if (isset($_COOKIE['b2b'])) {
  //   setcookie('b2b', '', time() - 3600, "/"); // 2hr
  // }

  $query = "SELECT * FROM users WHERE username LIKE '" . mysqli_real_escape_string($conn, $get_vars['username']) . "' AND active = 1 LIMIT 1";

  if ($result = $conn->query( $query )) {

    $user = $result->fetch_assoc();

    if($user) {

      if(sha1($get_vars['password']) == $user['password']) {

        $cookie_value = as_simple_crypt($user['id'] . '+' . $user['company_id']);

        setcookie('b2b', $cookie_value, strtotime( '+180 days' ), "/");

        $return = array(
          'success' => true,
          'role' => $roles[$user['role']],
          'company' => (int)$user['company_id'],
          'username' => $user['username'],
          'b2b_cookie' => $cookie_value,
        );

        echo json_encode($return);

      } else {

        $return = array(
          'success' => false,
          'message' => 'Wrong password'
        );

        echo json_encode($return);

      }

    } else {

        $return = array(
          'success' => false,
          'message' => 'Wrong user'
        );

        echo json_encode($return);


    }

  } else {

    $return = array(
      'success' => false,
      'message' => 'Unknown error'
    );

    echo json_encode($return);

  }
// echo json_encode($result->fetch_assoc()['id']);


?>