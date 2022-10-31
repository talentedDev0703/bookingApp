<?php

  require __DIR__ . '/vendor/autoload.php';

  use Endroid\QrCode\QrCode;
  use Konekt\PdfInvoice\InvoicePrinter;
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  header('Content-Type: application/json');

  include('config.php');

  // $dateFormat = 'Y-m-d';
  // $timeFormat = 'H:i';
  // $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $post_vars = $_POST;

  if(
    !isset($post_vars['pickup']) ||
    !isset($post_vars['date']) ||
    !isset($post_vars['time']) ||
    // !isset($post_vars['id']) || // SUBMIT WITH ID
    !isset($post_vars['adults']) ||
    !isset($post_vars['name']) ||
    !isset($post_vars['email']) ||
    !isset($post_vars['phone'])
  ) {
    echo json_encode('No data defined');
    die;
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
//   $query = "SELECT tour_name, price, places_booked, shared_capacity, timestamp, pickup_point, tour_name FROM tours WHERE id = " . mysqli_real_escape_string($conn, $post_vars['id']);
  $query = "SELECT id, tour_name, price, places_booked, shared_capacity, timestamp, pickup_point, tour_name FROM tours WHERE pickup_point = '" . mysqli_real_escape_string($conn, $post_vars['pickup']) ."' AND timestamp = " . mysqli_real_escape_string($conn, $post_vars['time']) . " LIMIT 1";

  if ($result = $conn->query( $query )) {

    $row = $result->fetch_assoc();

    // SUBMIT WITHOUT ID
    if(!isset($post_vars['id'])) {
      $post_vars['id'] = $row['id'];
    }

    $where = 'id = ' . mysqli_real_escape_string($conn, $post_vars['id']);

    if($row['shared_capacity']) {

      $query = "SELECT id FROM tours WHERE timestamp = " . $row['timestamp'] . " AND pickup_point like '" . $row['pickup_point'] . "' AND shared_capacity = 1 AND id != " . mysqli_real_escape_string($conn, $post_vars['id']) . " LIMIT 1";

      if ($result = $conn->query( $query )) {

        $shared_row_id = $result->fetch_assoc()['id'];

        $where = 'id IN (' . mysqli_real_escape_string($conn, $post_vars['id']) . ', ' . $shared_row_id . ')';
      }

    }

    // UPDATE SEATS

    $number_of_seats += $row['places_booked'];

    $query = "UPDATE tours SET places_booked = " . $number_of_seats . " WHERE " . $where;

    if ( $conn->query($query) != TRUE ) {
      echo json_encode('Error');
      die;
    }

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
      paid
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
      '0'
    );";

var_dump($query);
die('ins');

  $last_id = 0;

  if ( $conn->query($query) != TRUE ) {

    echo json_encode('Error');
    die;

  } else {

    $last_id = $conn->insert_id;

  }

  // GENERATE QR
  $qrCode = new QrCode('https://www.adriaticsunsets.com/booking/validate-voucher?id=' . $last_id);
  $qrCode->setSize(320);
  $qrCode->writeFile(__DIR__.'/qr/validate-' . $last_id . '.png');
  // echo $qrCode->writeString();


  // GENERATE RECEIPT
  $cr = curl_init();

  $post_receipt = [
    'token' => '3dae0164e6e8b1fd95962cec7d59a2411',
    'tip_usluge' => '1',
    'prikazi_porez' => '1',
    'tip_racuna' => '4', // bez oznake
    'kupac_naziv' => $post_vars['name'],

    // STAVKE RACUNA
    'usluga' => '1',
    'opis_usluge_1' => $row['tour_name'],
    'cijena_1' => $row['price'] * 0.8, // without VAT
    'jed_mjera_1' => '20', // pax
    'kolicina_1' => $post_vars['adults'],
    'popust_1' => '0',
    'porez_stopa_1' => '25',

    'nacin_placanja' => '3', // kartice
    'jezik_racuna' => '2', // en
    //'fiskalizacija' => '1'
  ];

  $merged_params = http_build_query($post_receipt);

  if($post_vars['kids']) {
    $post_kids = [
      'usluga' => '2',
      'opis_usluge_2' => $row['tour_name'] . ' - kids',
      'cijena_2' => $row['price'] * 0.8, // without VAT
      'jed_mjera_2' => '20', // pax
      'kolicina_2' => $post_vars['kids'],
      'popust_2' => '50',
      'porez_stopa_2' => '25',
    ];
    $merged_params = $merged_params . '&' . http_build_query($post_kids);
  }

  if($post_vars['infants']) {
    $post_infants = [
      'usluga' => '3',
      'opis_usluge_3' => $row['tour_name'] . ' - infants',
      'cijena_3' => $row['price'] * 0.8, // without VAT
      'jed_mjera_3' => '20', // pax
      'kolicina_3' => $post_vars['infants'],
      'popust_3' => '100',
      'porez_stopa_3' => '25',
    ];
    $merged_params = $merged_params . '&' . http_build_query($post_infants);
  }

  curl_setopt($cr, CURLOPT_URL,"https://api.solo.com.hr/racun");
  curl_setopt($cr, CURLOPT_POST, 1);
  curl_setopt($cr, CURLOPT_POSTFIELDS, $merged_params);

  curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);

  $server_output = curl_exec ($cr);

  curl_close ($cr);

  $solo_data = json_decode($server_output);

  // CREATE PDF
  $invoice = new InvoicePrinter('A4', 'HRK', 'en');
  $invoice->setNumberFormat(',', '.');

  $date_time = explode(" ", $solo_data->racun->datum_racuna);

  $invoice->setLogo("images/as-logo.jpg");
  $invoice->setColor("#f7941e");
  $invoice->setType("Voucher");
  $invoice->setReference($solo_data->racun->broj_racuna);
  $invoice->setDate($date_time[0]);
  $invoice->setTime($date_time[1]);
  $invoice->setFrom(array("Adriatic Sunsets d.o.o.","Uz Glavicino 6","20207 Mlini","OIB 21242579244"));
  $invoice->setTo(array("Private person"));

  foreach($solo_data->racun->usluge as $usluga) {

    $service_name = $usluga->opis_usluge;
    $service_desc = 'adults';

    if(strpos($service_name, ' - kids')) {

        $service_name = str_replace(' - kids', '', $service_name);
        $service_desc = 'kids';

    } elseif(strpos($service_name, ' - infants')) {

        $service_name = str_replace(' - infants', '', $service_name);
        $service_desc = 'infants';

    }

    $invoice->addItem($service_name,$service_desc,$usluga->kolicina,$usluga->porez_stopa,$usluga->cijena,$usluga->popust,$usluga->suma);

  }

  $invoice->addTotal("Total",str_replace(",", ".", $solo_data->racun->neto_suma));
  $invoice->addTotal("VAT 25%",str_replace(",", ".", $solo_data->racun->porezi[0]->porez));
  $invoice->addTotal("Total due", str_replace(",", ".", $solo_data->racun->bruto_suma), true);
  
  $invoice->addTitle("Fiscalization information");
  
  $invoice->addParagraph("ZKI: bef6286e21c60da5f2e546f385ffd2a4<br>JIR: bef6286e21c60da5f2e546f385ffd2a4<br>OPERATOR: Marko Zvono<br>Paid using credit card");

  $invoice->addQR("http://www.adriaticsunsets.com/booking/qr/validate-1.png");

  $filename = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);

  $invoice->render(__DIR__.'/voucher/voucher-' . $filename . '.pdf','F');

  /* I => Display on browser, D => Force Download, F => local path save, S => return document path */


  // SEND email

  $mail = new PHPMailer(true);
  try {
    //Server settings
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'booking@adriaticsunsets.com';
    $mail->Password = 'I37btoDX';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('booking@adriaticsunsets.com', 'Adriatic Sunsets');
    $mail->addAddress($post_vars['email'], $post_vars['name']);
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment(__DIR__.'/voucher/voucher-' . $filename . '.pdf', 'voucher.pdf');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Your booking is confirmed';
    $mail->Body    = 'Find your voucher in attachment';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    // echo 'Message has been sent';
  } catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    die;
  }

  // RESPONSE
  echo json_encode('Success');
