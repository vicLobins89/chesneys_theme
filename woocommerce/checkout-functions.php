<?php

// Order Complete Hook
add_action('woocommerce_order_status_processing', 'wdm_send_order_to_ext');
//add_action('woocommerce_thankyou', 'wdm_send_order_to_ext');
function wdm_send_order_to_ext( $order_id ){
	// get order object and order details
	$order = new WC_Order( $order_id );
	$order_number = $order->get_order_number();
//	$order_date = $order->order_date();
//	$order_status = $order->get_status();
//	$order_total = $order->get_total();
//	$order_discount_total = get_total_discount();
//	$payment_method = $order->get_payment_method();
//	$payment_method_title = $order->get_payment_method_title();
	$email = $order->get_billing_email();
	$phone = $order->get_billing_phone();
	$shipping_cost = $order->get_total_shipping();
	$shipping_type = $order->get_shipping_method();
	$notes = $order->get_customer_note();
//	$currency = get_woocommerce_currency_symbol();
//	$user_id = $order->get_user_id();
	$dealer_code = get_post_meta( $order_id, 'Dealer Code', true );

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
	$csv_data = array();
	$api_items = array();
	$api_items_ches = array();
	
	foreach( $items as $key => $item) {
		$item_id = $item['product_id'];
		$product = new WC_Product($item_id);
		$service_type;
		
		foreach( $order->get_items('shipping') as $item_id => $shipping_item_obj ){
			$shipping_name = $shipping_item_obj->get_name();
			if( $shipping_name == 'Deliver, Unpack &amp; Position' || $shipping_name == 'Free Deliver, Unpack &amp; Position' ) {
				$service_type = 'Deliver, Unpack & Position';
			} elseif( $shipping_name == 'Delivery Only' ) {
				$service_type = 'Delivery Only';
			}
		}
		
		if( 
			$product->get_shipping_class() == 'clean-burn' || 
			$product->get_shipping_class() == 'heat-grill' || 
			$product->get_shipping_class() == 'garden-gourmet' || 
			$product->get_shipping_class() == 'garden-party' || 
			$product->get_shipping_class() == 'terrace-gourmet' || 
			$product->get_shipping_class() == 'banburry-accessories' 
		) {
			$csv_data[] = array(
				'YourOrderRef' => $order_number,
				'CustomerName' => $address['shipping_first_name'] . ' ' . $address['shipping_last_name'],
				'CustomerAddressLine1' => $address['shipping_address_1'],
				'CustomerAddressLine2' => $address['shipping_address_2'],
				'CustomerCity' => $address['shipping_city'],
				'CustomerCounty' => $address['shipping_state'],
				'CustomerPostcode' => $address['shipping_postcode'],
				'CustomerEmail' => $email,
				'CustomerPhone' => $phone,
				'ProductCode' => $product->get_sku(),
				'Decription' => $item['name'],
				'NoItems' => $item['qty'],
				'Weight_kg' =>$product->get_weight().'kg',
				'DeliveryType' => 'Home',
				'ServiceType' => $service_type,
				'CustomerNotes' => $notes
			);
		} elseif ( $product->get_shipping_class() == 'northamptonshire' ) {
			$api_items[] = array(
				'client_ref' => $product->get_sku(),
				'quantity' => $item['qty'],
				'price' => $item['line_total'],
			);
		}
		
		$api_items_ches[] = array(
			"id" => $key,
			"name" => $item['name'],
			"price" => $product->get_price(),
			"product_id" => $item_id,
			"quantity" => $item['qty'],
			"sku" => $product->get_sku(),
			"subtotal" => ($product->get_price() * $item['qty']),
			"total" => $item['line_total']
		);
	}
	
	
	//API Details
	$api_key = 'a83fb1720d8382b90fad6d00aee2f4ad';
	$message_timestamp = time();
	$api_data = array(
		'half_api_key' => substr( $api_key, 0, 16 ),
		'message_timestamp' => $message_timestamp,
		'security_hash' => md5( $message_timestamp . $api_key ),
		'test' => false,
		'order' => array(
			'client_ref' => $order_number,
			'postage_speed' => 2,
			'postage_cost' => $shipping_cost,
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
			'items' => $api_items
		)
	);
	
	//Chesneys API Data
	$api_data_ches = array(
		"billing_address_1" => $address['billing_address_1'],
		"billing_address_2" => $address['billing_address_2'],
		"billing_city" => $address['billing_city'],
		"billing_company" => $address['billing_company'],
		"billing_country" => $address['billing_country'],
		"billing_email" => $email,
		"billing_first_name" => $address['billing_first_name'],
		"billing_last_name" => $address['billing_last_name'],
		"billing_phone" => $phone,
		"billing_postcode" => $address['billing_postcode'],
		"billing_state" => $address['billing_state'],
		
//		"currency" => $currency,
//		"customer_id" => $user_id,
//		"customer_note" => $notes,
//		"date_created" => $order_date,
//		"date_created_gmt" => $order_date,
		"dealer_code" => $dealer_code,
//		"discount_total" => $order_discount_total,

		"id" => $order_id,
		"number" => $order_number,
//		"payment_method" => $payment_method,
//		"payment_method_title" => $payment_method_title,
		
		"shipping_address_1" => $address['shipping_address_1'],
		"shipping_address_2" => $address['shipping_address_2'],
		"shipping_city" => $address['shipping_city'],
		"shipping_company" => $address['shipping_company'],
		"shipping_country" => $address['shipping_country'],
		"shipping_first_name" => $address['shipping_first_name'],
		"shipping_last_name" => $address['shipping_last_name'],
		"shipping_postcode" => $address['shipping_postcode'],
		"shipping_state" =>  $address['shipping_state'],
		"shipping_total" => $shipping_cost,

//		"status" => $order_status,
//		"total" => $order_total,
		
		'items' => $api_items_ches
	);

	// Iterating through order shipping items
	foreach( $order->get_items('shipping') as $item_id => $shipping_item_obj ){
		$shipping_name = $shipping_item_obj->get_name();
		
		if( $shipping_name == 'Standard Delivery' ) {
			send_api_call($api_data);
		} else {
			send_csv_mail($csv_data, "Product Order ");
		}
	}
	
	send_api_call_ches($api_data_ches);
}

function send_api_call_ches($data) {
     //$endpoint = "https://core.chesneys.co.uk/wcf/ChesneysWoocommerceService.svc/Test";
     $endpoint = "https://en74kwaytzvmt.x.pipedream.net/";
    
     // JSON
     $options = array(
       'http' => array(
           'method'  => 'POST',
           'content' => json_encode( $data ),
           'header'=>  "Content-Type: text/plain\r\n" .
                           "Accept: application/json\r\n"
           )
     );
 
     $context  = stream_context_create( $options );
     $result = file_get_contents( $endpoint, false, $context );
     $response = json_decode( $result );
 
     // the handle response
     if ($response->success != 1) {
           print_r($response);
           return;
     }
}

function send_api_call($data) {
	$api_mode = 'live';
	if($api_mode == 'sandbox') {
		$endpoint = "https://enh641edaecg.x.pipedream.net";
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
		return;
	}
}

function create_csv_string($csv_data) {    
	// Open temp file pointer
	if (!$fp = fopen('php://temp', 'w+')) return FALSE;
	
	fputcsv($fp, array_keys($csv_data[0]));
	foreach($csv_data as $key => $value) {
		fputcsv($fp, $value);
	}

	// Place stream pointer at beginning
	rewind($fp);

	// Return the data
	return stream_get_contents($fp);

}

function create_csv($records) {
	
	$today = date("d-m-y");

    $filepath = $today.'_Order.csv';

    $fd = fopen($filepath, 'w');
    if($fd === FALSE) {
        die('Failed to open temporary file');
	}

    fputcsv($fd, array_keys($records[0]));
    foreach($records as $record => $value) {
        fputcsv($fd, $value);
    }

    rewind($fd);
    fclose($fd);
    return $filepath;
}

function send_csv_mail($csv_data, $body, $to = 'vic@honey.co.uk, SwiftcareAdmin@Swiftcareuk.com, matt@rd-it.com, adam@chesneys.co.uk, stockists@chesneys.co.uk',  $from = 'Chesneys Order <no-reply@chesneys.co.uk>', $subject = 'Product Order from Chesneys.co.uk') {
	
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
	//$attachment = chunk_split(base64_encode(create_csv_string($csv_data))); 
	$attachment = create_csv($csv_data);

	// Send the email, return the result
	return wp_mail($to, $subject, $body, $headers, $attachment);
	//return @mail($to, $subject, $body, implode("\r\n", $headers)); 
}

?>