<?php

  $page_config = array(
    'active' => 'tours-list',
    'title' => 'Edit',
    'is_edit' => false
  );

  $datetimeFormat = 'd.m.Y H:i';
  $now = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));
  $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

  include('config.php');

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $get_vars = $_GET;

  // var_dump($get_vars);

  if(isset($get_vars['invalidate']) && $get_vars['invalidate']) {

      $query = "UPDATE orders SET validated = 1 WHERE id = " . mysqli_real_escape_string($conn, $get_vars['id']);

      $conn->query($query);

  }

  $class = 'invalid';
  $message = 'Voucher is invalid';
  $query = "SELECT o.name, o.adults, o.kids, o.validated, t.tour_name, t.pickup_point, t.end_point, t.timestamp FROM orders o LEFT JOIN tours t ON o.tour_id = t.id WHERE o.id = " . mysqli_real_escape_string($conn, $get_vars['id']) . " AND cancelled = 0";

  if ($result = $conn->query( $query )) {

    if($result->num_rows) {

      $row = $result->fetch_assoc();

      $start_time->setTimestamp($row['timestamp']);
      $row['date_time'] = $start_time->format($datetimeFormat);

      unset($row['timestamp']);

      // Add some buffer
      $now->modify("+30 min");

      if($now <= $start_time) {
        $class = 'valid';
        $message = 'Voucher is valid';
      }

      // echo '<pre>';
      // var_dump($row);
      // echo '</pre>';

    } else {

      $message = 'Voucher doesn\'t exist';

    }

  }
?><!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Voucher validator</title>
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
    <style>
      @import url('https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin-ext');
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font-family: 'Roboto', sans-serif; }
      .container { max-width: 600px; margin: 0 auto; box-shadow: 0 0 30px rgba(0,0,0,0.1); min-height: 100vh; }
      .header { color: #fff; padding: 40px 20px; text-align: center; }
      .header.invalid { background-color: #ee3135;}
      .header.valid { background-color: #4fc856; }
      .main { padding: 40px 20px; font-size: 16px; color: #333; }
      .row { margin-top: 20px; overflow: hidden; }
      .row:first-child { margin-top: 0; }
      .label,
      .value { display: block; float: left; width: 50%; font-weight: bold; }
      .label { font-weight: normal; }
      .button { background-color: #999; color: #fff; border: 0; font-size: 16px; text-transform: uppercase; padding: 15px 20px; border-radius: 3px; display: block; text-align: center; width: 100%; }
    </style>
  </head>
  <body>
    <div class="container">
      <header class="header <?php echo $class; ?>">
        <h1><?php echo $message; ?></h1>
      </header>
      <main class="main">
        <?php if(isset($row)): ?>
          <div class="row"><span class="label">Name:</span> <span class="value"><?php echo $row['name']; ?></span></div>
          <div class="row"><span class="label">Persons:</span> <span class="value"><?php echo $row['adults'] + $row['kids']; ?></span></div>
          <div class="row"><span class="label">Validated:</span> <span class="value"><?php echo $row['validated'] ? 'Yes' : 'No'; ?></span></div>
          <div class="row"><span class="label">Tour name:</span> <span class="value"><?php echo $row['tour_name']; ?></span></div>
          <div class="row"><span class="label">Pickup point:</span> <span class="value"><?php echo $row['pickup_point']; ?></span></div>
          <div class="row"><span class="label">End point:</span> <span class="value"><?php echo $row['end_point']; ?></span></div>
          <div class="row"><span class="label">Date / time:</span> <span class="value"><?php echo $row['date_time']; ?></span></div>
          <div class="row">
            <form action="<?php echo $base_path; ?>validate-voucher">
              <input type="hidden" value="<?php echo $get_vars['id']; ?>" name="id">
              <input type="hidden" value="1" name="invalidate">
              <button class="button">Invalidate</button>
            </form>
          </div>
        <?php else: ?>
          Try with different voucher.
        <?php endif; ?>
      </main>
    </div>
  </body>
</html>
