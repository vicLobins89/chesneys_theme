<?php

// API
/* after an order has been processed, we will use the  'woocommerce_thankyou' hook, to add our function, to send the data */
add_action('woocommerce_thankyou', 'wdm_send_order_to_ext');
function wdm_send_order_to_ext( $order_id ){
	// get order object and order details
	$order = new WC_Order( $order_id );
	$email = $order->get_billing_email();
	$phone = $order->get_billing_phone();
//	$shipping_type = $order->get_shipping_method();
//	$shipping_cost = $order->get_total_shipping();

	// set the address fields
	$address = array(
		'billing_first_name' => $order->get_billing_first_name(),
		'billing_last_name' => $order->get_billing_last_name(),
		'billing_company' => $order->get_billing_company(),
		'billing_address_1' => $order->get_billing_address_1(),
		'billing_address_2' => $order->get_billing_address_2(),
		'billing_city' => $order->get_billing_city(),
		'billing_state' => $order->get_billing_state(),
		'billing_country' => $order->get_billing_country(),
		'billing_postcode' => $order->get_billing_postcode(),
		'shipping_first_name' => $order->get_shipping_first_name(),
		'shipping_last_name' => $order->get_shipping_last_name(),
		'shipping_company' => $order->get_shipping_company(),
		'shipping_address_1' => $order->get_shipping_address_1(),
		'shipping_address_2' => $order->get_shipping_address_2(),
		'shipping_city' => $order->get_shipping_city(),
		'shipping_state' => $order->get_shipping_state(),
		'shipping_country' => $order->get_shipping_country(),
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

	// get product details
	$items = $order->get_items();
	$csv_items = array();
	$api_items = array();
	
	foreach( $items as $key => $item) {
		$item_id = $item['product_id'];
		$product = new WC_Product($item_id);
		
		if( $product->get_shipping_class() == 'banbury' ) {
			$csv_items[$item['product_id']] = array(
				'item_name' => $item['name'],
				'item_sku' => $product->get_sku(),
//				'item_ship_class' => $product->get_shipping_class(),
				'item_price' => $item['line_total'],
				'quantity' => $item['qty'],
			);
		} elseif ( $product->get_shipping_class() == 'northamptonshire' ) {
			$api_items[] = array(
				'item_name' => $item['name'],
				'item_sku' => $product->get_sku(),
				'quantity' => $item['qty'],
				'price' => $item['line_total'],
			);
		}
	}
	
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
		'transaction_key' => $transaction_key,
		'coupon_code' => implode( ",", $coupon )
	);
	
	
	//API Details
	$api_key = 'a83fb1720d8382b90fad6d00aee2f4ad';
	$message_timestamp = time();
	$apiData = array(
		'half_api_key' => substr($api_key, 0, 16),
		'message_timestamp' => $message_timestamp,
		'security_hash' => md5( $message_timestamp . $api_key ),
		'test' => true,
		'order' => array(
			'client_ref' => $transaction_key,
			'date_placed'=> date('c', $order->order_date ),
			'ShippingContact' => array(
				'name' => $address['shipping_first_name'] . ' ' . $address['shipping_last_name'],
				'email' => $email,
				'phone' => $phone,
				'address' => $address['shipping_address_1'],
				'address_contd' => $address['shipping_address_2'],
				'city' => $address['shipping_city'],
				'county' => $address['shipping_state'],
				'country' => $address['shipping_country'],
				'postcode' => $address['shipping_postcode']
			),
			'BillingContact' => array(
				'name' => $address['billing_first_name'] . ' ' . $address['billing_last_name'],
				'email' => $email,
				'phone' => $phone,
				'address' => $address['billing_address_1'],
				'address_contd' => $address['billing_address_2'],
				'city' => $address['billing_city'],
				'county' => $address['billing_state'],
				'country' => $address['billing_country'],
				'postcode' => $address['billing_postcode']
			),
		),
		'items' => $api_items
	);

	// Iterating through order shipping items
	foreach( $order->get_items('shipping') as $item_id => $shipping_item_obj ){
		$shipping_name = $shipping_item_obj->get_name();
		
		if( $shipping_name == 'Standard Delivery' ) {
			
			send_api_call($apiData);
			
		} elseif( $shipping_name == 'Premium Delivery and Installation' ) {
			
			send_csv_mail($data, $csv_items, $shipping_name, "Product Order ");
			
		} elseif( $shipping_name == 'Outdoor Products Pallet Delivery' ) {
			
			send_csv_mail($data, $csv_items, $shipping_name, "Product Order ");
			
		}
	}
}

function send_api_call($data) {
	$api_mode = 'live';
	if($api_mode == 'sandbox') {
		$endpoint = "https://enum5s26ejk6a.x.pipedream.net/";
	} else {
		$endpoint = "https://api.controlport.co.uk/api/1/"; 
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

	// the handle response
	if ($response->success != 1) {
		print_r($response);
	} else {
		print_r($response);
	}
}

function create_csv_string($data, $csv_items, $ship_type) {    
	// Open temp file pointer
	if (!$fp = fopen('php://temp', 'w+')) return FALSE;
	
	fputcsv($fp, array_keys($data));
	fputcsv($fp, $data);
	
	fputcsv($fp, array('Shipping Type:',NULL,NULL));
	fputcsv($fp, array($ship_type,NULL,NULL));
	 
	fputcsv($fp, array(NULL,NULL,NULL));
	fputcsv($fp, array('Items:',NULL,NULL));
	
	fputcsv($fp, array(
		'Product Name','SKU','Price','QTY'
	));
	foreach($csv_items as $key => $value) {
		fputcsv($fp, $value);
	}

	// Place stream pointer at beginning
	rewind($fp);

	// Return the data
	return stream_get_contents($fp);

}

function send_csv_mail($csvData, $csv_items, $ship_type, $body, $to = 'vic@honey.co.uk',  $from = 'noreply@chesneys.co.uk', $subject = 'Product Order from Chesneys.co.uk') {
	
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
	$attachment = chunk_split(base64_encode(create_csv_string($csvData, $csv_items, $ship_type))); 

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