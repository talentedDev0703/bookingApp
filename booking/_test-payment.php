<?php

	// FILLED AUTO
	$order_number = 'adriatic-1';
	$amount = '10';
	$cart = '2xPanoramic ride';
	$cardholder_name = 'Marko';
	$cardholder_surname = 'Zvono';
	$cardholder_email = 'marko@adriatic-transfers.com';
	$cardholder_phone = '098123456';

	// CONFIG
	$store_id = '5702';
	$key = 'a0tmsLuF1W6rMfURRAKc6lzue';
	$currency = 'HRK';

	// PREPARE STRING FOR HASH
	$hash_str = $key . ':' . $order_number . ':' . $amount . ':' . $currency;
	//var_dump($hash_str);

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
		'require_complete' => true
	);

?>

<form action="https://testcps.corvus.hr/redirect/" method="post">

	<?php foreach ($post_vars as $k => $v): ?>

		<?php if(in_array($k, array('order_number','amount','cart','cardholder_name','cardholder_surname','cardholder_email','cardholder_phone'))): ?>
	       <div style="margin-top: 10px">
	           <?php echo $k; ?><br>
			   <input type="text" name="<?php echo $k;?>" value="<?php echo $v;?>">
           </div>
		<?php else: ?>
			<input type="hidden" name="<?php echo $k;?>" value="<?php echo $v;?>">
		<?php endif; ?>

	<?php endforeach; ?>

    <div style="margin-top: 10px">
	    <button type="submit">Submit</button>
	</div>

</form>
