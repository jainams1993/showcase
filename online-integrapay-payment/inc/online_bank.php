<?php 
add_shortcode('onlinebankpayment-response', 'calledOnlinePostDDR');
function calledOnlinePostDDR(){
	global $wpdb;
	$bankintegrapay = $wpdb->prefix . 'integrapay_bank';
	$webPageToken = $_GET['webPageToken'];
	$response_data = onlinePosteDDR($webPageToken);
	if($response_data['resultID'] == 'S'){
		$wpdb->update( $bankintegrapay, array(
					'resultPayerID'			=> $response_data['resultPayerID'],
					'payerUniqueID'			=> $response_data['payerUniqueID'],
					'payerDisplayID'		=> $response_data['payerDisplayID'],
			        'payerFirstName'	 	=> $response_data['payerFirstName'], 
			        'payerLastName' 		=> $response_data['payerLastName'],
			        'payerAddressStreet' 	=> $response_data['payerAddressStreet'], 
			        'payerAddressSuburb' 	=> $response_data['payerAddressSuburb'],
			        'payerAddressState' 	=> $response_data['payerAddressState'], 
			        'payerAddressPostCode'	=> $response_data['payerAddressPostCode'], 
			        'payerAddressCountry' 	=> $response_data['payerAddressCountry'],
			        'payerEmail' 			=> $response_data['payerEmail'], 
			        'payerPhone' 			=> $response_data['payerPhone'], 
			        'payerMobile' 			=> $response_data['payerMobile'],
			        'status' 				=> 2,
			       ),array('token' => $webPageToken)
			    );
		$onlineintegrapay = $wpdb->prefix . 'onlineintegrapay';
		$sql = "SELECT *  FROM `".$onlineintegrapay."` WHERE `token` ='".$webPageToken ."'";
		$integrapayGet  = $wpdb->get_row($sql);
		
		$tomorrow = date("Ymd", strtotime("+1 day"));
		$order_total = ($integrapayGet->amount * 100);
     	
		$schedulePaymentArray = array('payerId' => $response_data['payerUniqueID'], 'debitDate' => $tomorrow, 'amount' => $order_total);
		scheduleOnlinePayment($schedulePaymentArray);
		?>
			<div class="container woocommerce integrapay-container">
	    		<div class="thanku-msg">
		    		<h1 class="thanku-title">Thank You !</h1>
		    		<h5>for choosing <span>NC Electrical</span></h5>
		    		<div class="thanku-img">
		    			<img src="<?= plugin_dir_url('') ?>woocommerce-integrapay-payment/images/images.png" width="200" height="200">
		    		</div>
		    		<div class="thanku-content">
			    		We have just sent to your E-mail a letter with complete
			    		<br />
			    		information about your booking
		    		</div>
		    	</div>
				<p class="return-to-shop">
					<a class="button wc-backward" href="<?= site_url('shop') ?>">
						<?= __('Countinue shopping' ,'integrapay_payment') ?>

					</a>
				</p>
				<p class="order">
				<?php /*<a class="button wc-backward" href="<?= site_url('my-account/view-order/'.$integrapayGet[0]->order_id) ?>"><?= __('View Order' ,'integrapay_payment') ?>
					</a> */ ?>
				</p>
			</div>
		<?php
	}
}

function onlinePosteDDR($webPageToken){
	$username 	= get_option('integrapay_payment_username','');
	$password 	= get_option('integrapay_payment_password','');
	$url 		= get_option('integrapay_payment_api','');
	$input_xml = '<?xml version="1.0" encoding="utf-8"?>
	<request>
		<username>'.$username.'</username>
		<password>'.$password.'</password>
		<command>PostDDR</command>
		<webPageToken>'.$webPageToken.'</webPageToken>
	</request>';
	//$url = 'https://testpayments.integrapay.com.au/API/API.ashx';
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $input_xml );
	$data = curl_exec($ch);
	curl_close($ch);
    //convert the XML result into array
    return json_decode(json_encode(simplexml_load_string($data)), true);
}

function scheduleOnlinePayment($schedulePaymentArray){

	$username 	= get_option('integrapay_payment_username','');
	$password 	= get_option('integrapay_payment_password','');
	$wsdl 		= get_option('schedule_payment_url','');


	ini_set('soap.wsdl_cache_enabled', 0);
	ini_set('soap.wsdl_cache_ttl', 900);
	ini_set('default_socket_timeout', 15);
	
	$params = array (
	    "username" => $username,
	    "password" => $password,
	    "payerUniqueID" => $schedulePaymentArray['payerId'],
	    "debitDate" => $schedulePaymentArray['debitDate'],
	    "transactionAmountInCents" => $schedulePaymentArray['amount'],
	    "transactionID" => $schedulePaymentArray['payerId'],
	);
	
	//$wsdl = 'https://sandbox.paymentsapi.io/basic/PayLinkService.svc?wsdl';

	try{
	    $client = new SoapClient($wsdl);
	    $data = $client->ScheduleSinglePayment($params);
	} catch(SoapFault $fault) {
	    echo("SOAP Fault: (faultcode: " . $fault->faultcode . ", faultstring: " . $fault->faultstring . ")");
	    die;
	} catch(Exception $e) {
	    echo("SOAP Error: " . $e->getMessage());die;
	}
}
?>