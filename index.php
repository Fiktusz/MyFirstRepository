<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include( 'php/_settings.php' );
include( root . 'php/classes/mysql.class.php' );
include( root . 'php/classes/coolUrl.class.php' );
include( root . 'php/classes/pay.class.php' );
include( root . 'php/classes/mail.class.php' );

$pages = $coolUrl->getAllArguments();

$openPage = ( isset( $pages[1] ) AND !empty( $pages[1] ) ) ? $pages[1] . '.php' : 'home.php';

if( file_exists( root . 'php/pages/' . $openPage ) )
	include( root . 'php/pages/' . $openPage );

$data = ( isset( $_POST['data'] ) ) ? $_POST['data'] : null;

if( $data ) :

	$price = $data['price'];
	$currency = $data['currency'];
	$order = $data['order'];
	
	if( isset( $data['bank'] ) OR isset( $data['card'] ) ) :
		
		$type = ( isset( $data['bank'] ) ) ? 1 : 0;
		
		$sql = "INSERT INTO trustpay_orders(order_number,price,currency,status,pay_type) VALUES(?,?,?,?,?)";
		$parrams = array( $order, $price, $currency, 1, $type );
		
		$mysql->insertData( $sql, $parrams );
		
	endif;
	
	if( isset( $data['bank'] ) )
		$pay->redirectToBankTransfer( $price, $currency, $order );
	
	if( isset( $data['card'] ) )
		$pay->redirectToPay( $price, $currency, $order );
		
endif;

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>TrustPay - Test</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
	
	<link rel="stylesheet" href="css/style.css?r=rss">
</head>

<body>
	
	<?php include( root . 'pages/' . $openPage ); ?>
	
</body>
</html>
