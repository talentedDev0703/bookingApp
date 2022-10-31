<?php

  include('config.php');

  $now = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $post_vars = $_POST;

  if(
    // !isset($post_vars['pickup']) ||
    // !isset($post_vars['date']) ||
    // !isset($post_vars['time']) ||
    !isset($post_vars['id']) || // SUBMIT WITH ID
    !isset($post_vars['adults']) ||
    !isset($post_vars['name']) ||
    !isset($post_vars['email']) ||
    !isset($post_vars['phone'])
  ) {
    die('No data defined');
  }

  // PREPARE NR. OF BOOKED SEATS

  $number_of_seats = mysqli_real_escape_string($conn, $post_vars['adults']);

  if(isset($post_vars['kids'])) {
    $number_of_seats += mysqli_real_escape_string($conn, $post_vars['kids']);
  } else {
    $post_vars['kids'] = 0;
  }

  if(!isset($post_vars['infants'])) {
    $post_vars['infants'] = 0;
  }

  // GET TOUR

// SUBMIT WITH ID
  $query = "SELECT tour_name, price, places_booked, shared_capacity, timestamp, pickup_point FROM tours WHERE id = " . mysqli_real_escape_string($conn, $post_vars['id']);
//  $query = "SELECT id, tour_name, price, places_booked, shared_capacity, timestamp, pickup_point FROM tours WHERE pickup_point = '" . mysqli_real_escape_string($conn, $post_vars['pickup']) ."' AND timestamp = " . mysqli_real_escape_string($conn, $post_vars['time']) . " LIMIT 1";

  if ($result = $conn->query( $query )) {

    $row = $result->fetch_assoc();

    // SUBMIT WITHOUT ID
    // if(!isset($post_vars['id'])) {
    //  $post_vars['id'] = $row['id'];
    //}

  }

  // PREPARE TOTAL PRICE
  $price = mysqli_real_escape_string($conn, $post_vars['adults']) * $row['price'];
  if(isset($post_vars['kids'])) {
    $price += mysqli_real_escape_string($conn, $post_vars['kids']) * $row['price'] * 0.5; // 50% discount for kids, 100% for infants
  }

  if(!isset($_COOKIE['auth'])) {
    $price *= 0.9;
  }

  // PREPARE B2B USER IF EXISTS
  $user_id = 0;
  $company_id = 0;
  $is_b2b = false;

  if(isset($post_vars['b2b_impersonate'])) {

    $user_id = 0; // MEANS ADRIATIC SUNSETS BEHALF OF B2B

    $company_id = $post_vars['b2b_impersonate'];

    $is_b2b = true;
    
  } elseif(isset($post_vars['b2b']) && isset($_COOKIE['b2b'])) {

    $cookie_value = explode("+", as_simple_crypt($_COOKIE['b2b'], 'd'));

    $user_id = $cookie_value[0];

    $company_id = $cookie_value[1];

    $is_b2b = true;
  } elseif(isset($_COOKIE['referrer'])) {

    $user_id = -1;

    $company_id = $_COOKIE['referrer'];

  }

  $query = "
    INSERT INTO orders
    (
      id,
      tour_id,
      adults,
      kids,
      infants,
      name,
      email,
      phone,
      validated,
      paid,
      value,
      timestamp,
      user_id,
      company_id
    )
    VALUES
    (
      NULL,
      '" . mysqli_real_escape_string($conn, $post_vars['id']) . "',
      '" . mysqli_real_escape_string($conn, $post_vars['adults']) . "',
      '" . mysqli_real_escape_string($conn, $post_vars['kids']) . "',
      '" . mysqli_real_escape_string($conn, $post_vars['infants']) . "',
      '" . mysqli_real_escape_string($conn, $post_vars['name']) . "',
      '" . mysqli_real_escape_string($conn, $post_vars['email']) . "',
      '" . mysqli_real_escape_string($conn, $post_vars['phone']) . "',
      '0',
      '0',
      '" . $price . "',
      '" . $now->format('U') . "',
      '" . $user_id . "',
      '" . $company_id . "'
    );";

  $last_id = 0;

  if ( $conn->query($query) != TRUE ) {

    die('Error');

  } else {

    $last_id = $conn->insert_id;

  }

  // GO TO PGW
  if(!$is_b2b) {

    // PREPARE NAME
    $name = trim($post_vars['name']);
    $name_arr = explode(' ',$name);
    $firstname = $name_arr[0];
    $lastname = trim(str_replace($name_arr[0], '', $name));

    // AUTO FILLED
    $order_number = 'adriatic-' . $last_id;
    $amount = $price;
  //  $amount = 10; // 10 for test
    $cart = $number_of_seats . 'x' . $row['tour_name'];
    $cardholder_name = $firstname;
    $cardholder_surname = $lastname;
    $cardholder_email = $post_vars['email'];
    $cardholder_phone = $post_vars['phone'];

    // CONFIG
    //$store_id = '5702'; // test
    //$key = 'a0tmsLuF1W6rMfURRAKc6lzue'; // test
    //$redirect_url = 'https://testcps.corvus.hr/redirect/';
    $store_id = '6599';
    $key = 'Dmm7mwW0DgCcMQqUeULdq8xQm';
    $redirect_url = 'https://cps.corvus.hr/redirect/';
    $currency = 'HRK';

    // PREPARE STRING FOR HASH
    $hash_str = $key . ':' . $order_number . ':' . $amount . ':' . $currency;

    $post_vars = array(
      'target' => '_top',
      'mode' => 'form',
      'store_id' => $store_id,
      'order_number' => $order_number,
      'language' => 'en',
      'currency' => $currency,
      'amount' => $amount,
      'cart' => $cart,
      'hash' => sha1($hash_str),
      'cardholder_name' => $cardholder_name,
      'cardholder_surname' => $cardholder_surname,
      'cardholder_email' => $cardholder_email,
      'cardholder_phone' => $cardholder_phone,
      'require_complete' => false // true = reserve, false = process
    );

?>

<form action="<?php echo $redirect_url; ?>" id="corvusForm" method="post">

  <?php foreach ($post_vars as $k => $v): ?>

    <?php /* if(in_array($k, array('order_number','amount','cart','cardholder_name','cardholder_surname','cardholder_email','cardholder_phone'))): ?>
      <input type="text" name="<?php echo $k;?>" value="<?php echo $v;?>" readonly>
    <?php else: */ ?>
      <input type="hidden" name="<?php echo $k;?>" value="<?php echo $v;?>">
    <?php // endif; ?>

  <?php endforeach; ?>

</form>

<script type="text/javascript">
    document.getElementById('corvusForm').submit();
</script>

<?php } else { ?>

<form action="/app/en/booking/success" id="b2bForm" method="get">

  <input type="hidden" name="order_number" value="<?php echo 'adriatic-' . $last_id; ?>">

</form>

<script type="text/javascript">
    document.getElementById('b2bForm').submit();
</script>

<?php } ?>