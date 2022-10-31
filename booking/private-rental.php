<?php

  require __DIR__ . '/vendor/autoload.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  //header('Content-Type: application/json');

  $post_vars = $_POST;

  if(
    !isset($post_vars['date']) ||
    !isset($post_vars['time']) ||
    !isset($post_vars['name']) ||
    !isset($post_vars['email'])
  ) {
    echo 'No data defined';
    die;
  }

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
    $mail->setFrom('noreply@adriaticsunsets.com', 'Adriatic Sunsets');
    $mail->addAddress('booking@adriaticsunsets.com', $post_vars['name']);
    $mail->addReplyTo($post_vars['email'], $post_vars['name']);
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('marko@adriatic-transfers.com');

    $mail->AddEmbeddedImage('images/as-logo.png', 'as_logo');

    // PREPARE BODY
    $body = '<img src="cid:as_logo" /><br>';
    $body .= '<br>';
    $body .= 'Date: ' . $post_vars['date'] . '<br>';
    $body .= 'Time: ' . $post_vars['time'] . '<br>';
    $body .= 'Name: ' . $post_vars['name'] . '<br>';
    $body .= 'Email: ' . $post_vars['email'] . '<br>';
    if(isset($post_vars['phone'])) {
        $body .= 'Phone: ' . $post_vars['phone'];
    }

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Private rental request';
    $mail->Body    = $body;
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    // echo 'Message has been sent';
  } catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    die;
  }

?>

<form action="/app/en/booking/success" id="privateRentalForm" method="get">

  <input type="hidden" name="order_number" value="private">

</form>

<script type="text/javascript">
    document.getElementById('privateRentalForm').submit();
</script>
