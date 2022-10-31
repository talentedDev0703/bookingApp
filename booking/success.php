<?php

  require __DIR__ . '/vendor/autoload.php';

  use Endroid\QrCode\QrCode;
  use Konekt\PdfInvoice\InvoicePrinter;
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  header('Content-Type: application/json');

  include('config.php');

  $dateFormat = 'Y-m-d';
  $timeFormat = 'H:i';
  $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $get_vars = $_GET;

  if(!isset($get_vars['order_number'])) {
    echo json_encode('No data defined');
    die;
  }

  // GET ORDER
  $order_id = $get_vars['order_number'];
  $order_id = str_replace('adriatic-','',$order_id);

  $query = "SELECT o.tour_id, o.adults, o.kids, o.infants, o.name, o.email, o.phone, o.paid, o.value, o.user_id, o.company_id, c.voucher_format FROM orders o LEFT JOIN companies c ON o.company_id = c.id WHERE o.id = " . mysqli_real_escape_string($conn, $order_id) ." LIMIT 1";

  if ($result = $conn->query( $query )) {

    $order = $result->fetch_assoc();

    if($result->num_rows == 0) {
      echo json_encode('Error - No order');
      die;
    }

  }

  if ($order['paid'] == 1 && $order_id != 205) {
    echo json_encode('Error - Order already paid');
    die;
  }

  $is_b2b = false;

  if ($order['company_id'] > 0 && $order['company_id'] != -1) {
    $is_b2b = true;
  }

  // SET PAID TO 1
    $query = "UPDATE orders SET paid = 1 WHERE id = " . $order_id;

    if ( $conn->query($query) != TRUE ) {
      echo json_encode('Error');
      die;
    }

  // RESERVE SEATS

  $number_of_seats = $order['adults'];

  if($order['kids']) {
    $number_of_seats += $order['kids'];
  }

  // GET TOUR
  $query = "SELECT tour_name, price, places_booked, shared_capacity, timestamp, pickup_point, tour_name FROM tours WHERE id = " . $order['tour_id'];

  if ($result = $conn->query( $query )) {

    $row = $result->fetch_assoc();

    $where = 'id = ' . $order['tour_id'];

    $row['price'] = $row['price'] * 0.8; // ukloni pdv iz iznosa

    if(!isset($_COOKIE['auth'])) {
      $row['price'] = $row['price'] * 0.9; // 10% popusta za goste
    }

    if($row['shared_capacity']) {

      $query = "SELECT id FROM tours WHERE timestamp = " . $row['timestamp'] . " AND pickup_point like '" . $row['pickup_point'] . "' AND shared_capacity = 1 AND id != " . $order['tour_id'] . " LIMIT 1";

      if ($result = $conn->query( $query )) {

        $shared_row_id = $result->fetch_assoc()['id'];

        $where = 'id IN (' . mysqli_real_escape_string($conn, $order['tour_id']) . ', ' . $shared_row_id . ')';
      }

    }

    if( $order_id == 205) {

      $start_time->setTimestamp($row['timestamp']);

      $tour = array(
        'tour_name' => $row['tour_name'],
        'pickup_point' => $row['pickup_point'],
        'timestamp' => $row['timestamp'],
        'date' => $start_time->format($dateFormat),
        'time' => $start_time->format($timeFormat),
        'revenue' => $order['value'],
        'voucher_url' => $base_path . 'voucher/voucher-UIqbRDs97g.pdf'
      );

      echo json_encode($tour);
      die;
    }

    // UPDATE SEATS
    $number_of_seats += $row['places_booked'];

    $query = "UPDATE tours SET places_booked = " . $number_of_seats . " WHERE " . $where;

    if ( $conn->query($query) != TRUE ) {
      echo json_encode('Error');
      die;
    }

  }

  // GENERATE QR
  $qrCode = new QrCode('https://www.adriaticsunsets.com/booking/validate-voucher?id=' . $order_id);
  $qrCode->setSize(320);
  $qrCode->writeFile(__DIR__.'/qr/validate-' . $order_id . '.png');
  // echo $qrCode->writeString();


  // GENERATE RECEIPT

  if($is_b2b) {

    // CREATE PDF
    $invoice = new InvoicePrinter('A4', 'HRK', 'en');
    $invoice->setNumberFormat(',', '.');
  
    $invoice->setLogo("images/as-logo.jpg");
    $invoice->setColor("#f7941e");
    $invoice->setType("Voucher");
    #$invoice->setReference($solo_data->racun->broj_racuna);
    $invoice->setDate($start_time->format('d.m.Y.'));
    $invoice->setTime($start_time->format('H:i:s'));
    $invoice->setFrom(array("Adriatic Sunsets d.o.o.","Uz Glavicino 6","20207 Mlini","OIB 21242579244"));
    $invoice->setTo(array("Private person","","",""));

    $services = array(
      array(
        'name' => $row['tour_name'],
        'price' => number_format($row['price'],2,',','.'),
        'discount' => '0',
        'qty' => $order['adults'],
      )
    );

    if($order['kids']) {

      $services[] = array(
        'name' => $row['tour_name'] . ' - kids',
        'price' => number_format($row['price'],2,',','.'),
        'discount' => '0.5',
        'qty' => $order['kids'],
      );

    }

    if($order['infants']) {

      $services[] = array(
        'name' => $row['tour_name'] . ' - infants',
        'price' => number_format($row['price'],2,',','.'),
        'discount' => '1',
        'qty' => $order['infants'],
      );

    }

    $voucher_total = 0;

    foreach($services as $service) {

      $service_total = 0;
      $service_name = $service['name'];
      $service_desc = 'adults';

      if(strpos($service_name, ' - kids')) {

          $service_name = str_replace(' - kids', '', $service_name);
          $service_desc = 'kids';

      } elseif(strpos($service_name, ' - infants')) {

          $service_name = str_replace(' - infants', '', $service_name);
          $service_desc = 'infants';

      }

      $service_total = $service['qty']*$service['price']*(1-$service['discount']);
      $voucher_total += $service_total;

      $invoice->addItem($service_name,$service_desc,$service['qty'],'25%',$service['price'],$service['discount']*100,$service_total);

    }

    $invoice->addTotal("Total",str_replace(",", ".", $voucher_total));
    $invoice->addTotal("VAT 25%",str_replace(",", ".", $voucher_total*0.25));
    $invoice->addTotal("Total due", str_replace(",", ".", $voucher_total*1.25), true);
    
    $invoice->addTitle("Other information");

    $start_time->setTimestamp($row['timestamp']);

    $invoice->addParagraph("B2B Voucher");
    $invoice->addParagraph("Be ready at " . $row['pickup_point'] . " on " . $start_time->format('d.m.Y') . " " . $start_time->format($timeFormat));
    // $invoice->addParagraph("Posebni postupak oporezivanja putnickih agencija propisan je odredbama clanaka 91. do 94. Zakona o porezu na dodanu vrijednost<br>ZKI: " . $solo_data->racun->zki . "<br>JIR: " . $solo_data->racun->jir . "<br>OPERATOR: Marko Zvono<br>Paid using credit card");

    $invoice->addQR("http://www.adriaticsunsets.com/booking/qr/validate-" . $order_id . ".png");

    $filename = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);

    $invoice->render(__DIR__.'/voucher/voucher-' . $filename . '.pdf','F');


    // POS VOUCHER

    if($order['voucher_format'] == 2) {
        
        //require('./vendor/fpdf181/dash.php');
        
        $pdf = new FPDF('P','mm',array(57,120));
        $pdf->SetMargins(5,5);
        $pdf->SetAutoPageBreak(false);
        
        $pdf->AddPage();
        
        $pdf->SetFont('Helvetica','B',8);
        $pdf->Cell(0,5,'Adriatic Sunsets d.o.o.',0,1,'C');
        $pdf->SetFont('','',7);
        $pdf->Cell(0,3.5,'Uz Glavicino 6',0,1,'C');
        $pdf->Cell(0,3.5,'20207 Mlini',0,1,'C');
        $pdf->Cell(0,3.5,'OIB 21242579244',0,1,'C');
        $pdf->Ln(57);
        
        $pdf->Image('http://www.adriaticsunsets.com/booking/qr/validate-' . $order_id . '.png',5,25,47);
        
        $pdf->SetFont('','B',8);
        $pdf->Cell(0,5,$row['tour_name'],0,1);

        $start_time->setTimestamp($row['timestamp']); // @TODO: already set?

        $pdf->SetFont('','');
        $pdf->Cell(0,5,$start_time->format('d.m.Y') . ' at ' . $start_time->format($timeFormat) . 'h from ' . $row['pickup_point'],0,1);
        
        $pdf->Ln(10);
        
        $pdf->SetLineWidth(0.1);
        //$pdf->SetDash(2,2);
        $pdf->Line(5,92,52,92);
        
        $pdf->Cell(27,5,'Adults',0,0);

        $pdf->SetFont('','B');
        $pdf->Cell(20,5,$order['adults'],0,1,'R');
        
        $pdf->SetFont('','');
        $pdf->Cell(27,5,'Kids',0,0);
        
        $pdf->SetFont('','B');
        $pdf->Cell(20,5,$order['kids'],0,1,'R');
        
        $pdf->SetFont('','');
        $pdf->Cell(27,5,'Infants',0,0);
        
        $pdf->SetFont('','B');
        $pdf->Cell(20,5,$order['infants'],0,1,'R');
        
        $pdf->Output('F',__DIR__.'/voucher/pos-' . $filename . '.pdf');

    }

  } else {

    $cr = curl_init();

    $post_receipt = [
      'token' => '3dae0164e6e8b1fd95962cec7d59a2411',
      'tip_usluge' => '1',
      'prikazi_porez' => '1',
      'tip_racuna' => '4', // bez oznake
      'kupac_naziv' => $order['name'],

      // STAVKE RACUNA
      'usluga' => '1',
      'opis_usluge_1' => $row['tour_name'],
      'cijena_1' => number_format($row['price'],2,',','.'),
      'jed_mjera_1' => '20', // pax
      'kolicina_1' => $order['adults'],
      'popust_1' => '0',
      'porez_stopa_1' => '25',

      'nacin_placanja' => '3', // kartice
      'jezik_racuna' => '2', // en
      // 'napomene' => 'Posebni postupak oporezivanja putnickih agencija propisan je odredbama clanaka 91. do 94. Zakona o porezu na dodanu vrijednost',
      'fiskalizacija' => '1'
    ];

    $merged_params = http_build_query($post_receipt);

    if($order['kids']) {
      $post_kids = [
        'usluga' => '2',
        'opis_usluge_2' => $row['tour_name'] . ' - kids',
        'cijena_2' => number_format($row['price'],2,',','.'),
        'jed_mjera_2' => '20', // pax
        'kolicina_2' => $order['kids'],
        'popust_2' => '50',
        'porez_stopa_2' => '25',
      ];
      $merged_params = $merged_params . '&' . http_build_query($post_kids);
    }

    if($order['infants']) {
      $post_infants = [
        'usluga' => '3',
        'opis_usluge_3' => $row['tour_name'] . ' - infants',
        'cijena_3' => number_format($row['price'],2,',','.'),
        'jed_mjera_3' => '20', // pax
        'kolicina_3' => $order['infants'],
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
    $invoice->setTo(array("Private person","","",""));

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

      $invoice->addItem($service_name,$service_desc,$usluga->kolicina,($usluga->porez_stopa . '%'),$usluga->cijena,$usluga->popust,$usluga->suma);

    }

    $invoice->addTotal("Total",str_replace(",", ".", $solo_data->racun->neto_suma));
    $invoice->addTotal("VAT 25%",str_replace(",", ".", $solo_data->racun->porezi[0]->porez));
    $invoice->addTotal("Total due", str_replace(",", ".", $solo_data->racun->bruto_suma), true);
    
    $invoice->addTitle("Other information");

    $start_time->setTimestamp($row['timestamp']); // @TODO: already set?

    $invoice->addParagraph("Be ready at " . $row['pickup_point'] . " on " . $start_time->format('d.m.Y') . " " . $start_time->format($timeFormat));
    
    // $invoice->addParagraph("Posebni postupak oporezivanja putnickih agencija propisan je odredbama clanaka 91. do 94. Zakona o porezu na dodanu vrijednost<br>ZKI: " . $solo_data->racun->zki . "<br>JIR: " . $solo_data->racun->jir . "<br>OPERATOR: Marko Zvono<br>Paid using credit card");
    $invoice->addParagraph("ZKI: " . $solo_data->racun->zki . "<br>JIR: " . $solo_data->racun->jir . "<br>OPERATOR: Marko Zvono<br>Paid using credit card");

    $invoice->addQR("http://www.adriaticsunsets.com/booking/qr/validate-" . $order_id . ".png");

    $filename = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);

    $invoice->render(__DIR__.'/voucher/voucher-' . $filename . '.pdf','F');

    /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
  }

  // SEND email

  $start_time->setTimestamp($row['timestamp']); // @TODO: already set?

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
    $mail->addAddress($order['email'], $order['name']);
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    $mail->addBCC('marko@adriatic-transfers.com');

    //Attachments
    $mail->addAttachment(__DIR__.'/voucher/voucher-' . $filename . '.pdf', 'voucher.pdf');

    $mail->AddEmbeddedImage('images/as-logo.png', 'as_logo');
    $body = '<img src="cid:as_logo" /><br>';
    $body .= '<h2>You’ve successfully booked ' . $row['tour_name'] . '</h2>';
    $body .= '<p>Be ready at ' . $row['pickup_point'] . ' on ' . $start_time->format('d.m.Y') . ' ' . $start_time->format($timeFormat) . '.</p>';
    $body .= '<p>Find attached voucher. Take it with you.</p>';

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Your booking is confirmed';
    $mail->Body    = $body;
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    // echo 'Message has been sent';
  } catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    die;
  }

  // RESPONSE

  $tour = array(
    'tour_name' => $row['tour_name'],
    'pickup_point' => $row['pickup_point'],
    'timestamp' => $row['timestamp'],
    'date' => $start_time->format($dateFormat),
    'time' => $start_time->format($timeFormat),
    'revenue' => $order['value'],
  );

  if ($is_b2b) {

    $tour['b2b'] = true;

    if($order['voucher_format'] == 2) {
        $tour['voucher_url'] = $base_path . 'voucher/pos-' . $filename . '.pdf';
    } else {
        $tour['voucher_url'] = $base_path . 'voucher/voucher-' . $filename . '.pdf';
    }
  }

  echo json_encode($tour);
