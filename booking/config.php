<?php

	$base_path = '/booking/';

	$servername = "localhost";
	$username = "adriatic_marko";
	$password = "!Vbe=w}^FP3e";
	$database = "adriatic_main";


    $roles = array(
        1 => 'Administrator',
        2 => 'Seller',
    );

    $voucher_formats = array(
        1 => 'A4',
        2 => 'POS (57mm)'
    );

    function as_simple_crypt( $string, $action = 'e' ) {
        $secret_key = 'more je ljeto';
        $secret_iv = 'more nije ljeto';
     
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
     
        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
     
        return $output;
    }

?>
