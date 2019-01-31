<?php

// API
/* after an order has been processed, we will use the  'woocommerce_thankyou' hook, to add our function, to send the data */
add_action('woocommerce_thankyou', 'wdm_send_order_to_ext');
function wdm_send_order_to_ext( $order_id ){
	// get order object and order details
	$order = new WC_Order( $order_id );
	$email = $order->get_billing_email();
	$phone = $order->get_billing_phone();
	$shipping_type = $order->get_shipping_method();
	$shipping_cost = $order->get_total_shipping();

	// set the address fields
	$address = array(
		'billing_first_name' => $order->get_billing_first_name(),
		'billing_last_name' => $order->get_billing_last_name(),
		'billing_company' => $order->get_billing_company(),
		'billing_address_1' => $order->get_billing_address_1(),
		'billing_address_2' => $order->get_billing_address_2(),
		'billing_city' => $order->get_billing_city(),
		'billing_state' => $order->get_billing_state(),
		'billing_postcode' => $order->get_billing_postcode(),
		'shipping_first_name' => $order->get_shipping_first_name(),
		'shipping_last_name' => $order->get_shipping_last_name(),
		'shipping_company' => $order->get_shipping_company(),
		'shipping_address_1' => $order->get_shipping_address_1(),
		'shipping_address_2' => $order->get_shipping_address_2(),
		'shipping_city' => $order->get_shipping_city(),
		'shipping_state' => $order->get_shipping_state(),
		'shipping_postcode' => $order->get_shipping_postcode()
	);

	// get coupon information (if applicable)
	$cps = array();
	$cps = $order->get_items( 'coupon' );

	$coupon = array();
	foreach($cps as $cp){
		// get coupon titles (and additional details if accepted by the API)
		$coupon[] = $cp['name'];
	}
	
	/* for online payments, send across the transaction ID/key. If the payment is handled offline, you could send across the order key instead */
	$transaction_key = get_post_meta( $order_id, '_transaction_id', true );
	$transaction_key = empty($transaction_key) ? $_GET['key'] : $transaction_key;
	
	// setup the data which has to be sent
	$data = array(
//		'apiuser' => $api_username,
//		'apipass' => $api_password,
		'customer_email' => $email,
		'customer_phone' => $phone,
		'bill_firstname' => $address['billing_first_name'],
		'bill_surname' => $address['billing_last_name'],
		'bill_company' => $address['billing_company'],
		'bill_address1' => $address['billing_address_1'],
		'bill_address2' => $address['billing_address_2'],
		'bill_city' => $address['billing_city'],
		'bill_state' => $address['billing_state'],
		'bill_postcode' => $address['billing_postcode'],
		'ship_firstname' => $address['shipping_first_name'],
		'ship_surname' => $address['shipping_last_name'],
		'shipping_company' => $address['shipping_company'],
		'ship_address1' => $address['shipping_address_1'],
		'ship_address2' => $address['shipping_address_2'],
		'ship_city' => $address['shipping_city'],
		'ship_state' => $address['shipping_state'],
		'ship_postcode' => $address['shipping_postcode'],	
		'shipping_type' => $shipping_type,
		'shipping_cost' => $shipping_cost,
		'transaction_key' => $transaction_key,
		'coupon_code' => implode( ",", $coupon ),
//		'items' => $itemDetails
	);

	// get product details
	$items = $order->get_items();
	$itemDetails = array();

	foreach( $items as $key => $item) {
		$item_id = $item['product_id'];
		$product = new WC_Product($item_id);
		
//		$itemDetails[$item['product_id']] = array(
//			'item_name' => $item['name'],
//			'item_sku' => $product->get_sku(),
//			'item_ship_class' => $product->get_shipping_class(),
//			'item_price' => $item['line_total'],
//			'quantity' => $item['qty'],
//		);
		
//		if( $product->get_shipping_class() == 'barnbury' ) {
//			send_csv_mail($data);
//		} elseif ( $product->get_shipping_class() == 'northamptonshire' ) {
//			send_api_call($data);
//		}
		
		echo $item['name'].": shipping class is -> ".$product->get_shipping_class()."<br>";
	}

	// Iterating through order shipping items
	foreach( $order->get_items( 'shipping' ) as $item_id => $shipping_item_obj ){
		$order_item_name = $shipping_item_obj->get_name();
		$order_item_type = $shipping_item_obj->get_type();
		$shipping_method_title = $shipping_item_obj->get_method_title();
		$shipping_method_total = $shipping_item_obj->get_total();
		
		echo $order_item_name;
		
//		if( $order_item_name == 'Outdoor Products Pallet Delivery' ) {
//			
//		}
	}
	
//	print_r($order);
//	print_r($shipping_type);
	
//	send_api_call($data);
//	send_csv_mail($data, "Product Order ");
}

function send_api_call($data) {
	// set the username and password
	$api_username = 'testuser';
	$api_password = 'testpass';

	// to test out the API, set $api_mode as ‘sandbox’
	$api_mode = 'sandbox';
	if($api_mode == 'sandbox') {
		// sandbox URL example
		$endpoint = "https://enjqsvm2ajzd.x.pipedream.net";
	} else {
		// production URL example
		$endpoint = "http://example.com/"; 
	}
	
	// JSON
	$options = array(
	  'http' => array(
		'method'  => 'POST',
		'content' => json_encode( $data ),
		'header'=>  "Content-Type: application/json\r\n" .
					"Accept: application/json\r\n"
		)
	);

	$context  = stream_context_create( $options );
	$result = file_get_contents( $endpoint, false, $context );
	$response = json_decode( $result );
	
	// send API request via cURL
	/*$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $endpoint);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec ($ch);

	curl_close ($ch);*/

	// the handle response
	if ($response->success != 1) {
		print_r($response);
	} else {
		// success
		// print_r($data);
	}
}

function create_csv_string($data) {    
	// Open temp file pointer
	if (!$fp = fopen('php://temp', 'w+')) return FALSE;
	
	$allItems = array_pop($data);
	
	fputcsv($fp, array_keys($data));
	fputcsv($fp, $data);
	
	fputcsv($fp, array(NULL,NULL,NULL));
	fputcsv($fp, array_keys($allItems));
	fputcsv($fp, array(
		'Product Name','SKU','Shipping Class','Price','QTY'
	));
	foreach($allItems as $key => $value) {
		fputcsv($fp, $value);
	}

	// Place stream pointer at beginning
	rewind($fp);

	// Return the data
	return stream_get_contents($fp);

}

function send_csv_mail($csvData, $body, $to = 'vic@honey.co.uk',  $from = 'noreply@chesneys.co.uk', $subject = 'Product Order from Chesneys.co.uk') {
	
	$today = date("d-m-y");

	// This will provide plenty adequate entropy
	$multipartSep = '-----'.md5(time()).'-----';

	// Arrays are much more readable
	$headers = array(
		"From: $from",
		"Reply-To: $from",
		"Content-Type: multipart/mixed; boundary=\"$multipartSep\""
	);

	// Make the attachment
	$attachment = chunk_split(base64_encode(create_csv_string($csvData))); 

	// Make the body of the message
	$body = "--$multipartSep\r\n"
		. "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
		. "Content-Transfer-Encoding: 7bit\r\n"
		. "\r\n"
		. "$body\r\n"
		. "--$multipartSep\r\n"
		. "Content-Type: text/csv\r\n"
		. "Content-Transfer-Encoding: base64\r\n"
		. "Content-Disposition: attachment; filename=\"Order_Sheet_$today.csv\"\r\n"
		. "\r\n"
		. "$attachment\r\n"
		. "--$multipartSep--";

	// Send the email, return the result
	return @mail($to, $subject, $body, implode("\r\n", $headers)); 
}

?>