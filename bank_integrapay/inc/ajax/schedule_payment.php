<?php 
add_action('wp_ajax_schedule_payment','schedule_payment');
add_action('wp_ajax_nopriv_schedule_payment','schedule_payment');
function schedule_payment(){
	if (isset($_POST) && !empty($_POST)) {
		global $wpdb;
		$error = validate_payment($_POST);
		if (!empty($error)) {
			$response = array('type' => 'error','error' => $error);
			wp_send_json($response);
		}else{
			$bankintegrapay = $wpdb->prefix . 'bankintegrapay';

			$records = $wpdb->get_row( "SELECT * FROM `".$bankintegrapay."` WHERE `payerEmail` = '".$_POST['payerEmail']."'" );
			
			$schedule_payment = $wpdb->prefix . 'schedule_payment';
		    $insert_id = $wpdb->insert( $schedule_payment, array(
											        'payerEmail'	 	=> $_POST['payerEmail'], 
											        'debitDate' 		=> $_POST['debitDate'],
											        'amount' 			=> $_POST['amount'], 
											        'status'			=> 1,
											       )
											    );
		    $lastid = $wpdb->insert_id;
		    $data_send = array('transactionID' => $lastid,
		    					'amount' => $_POST['amount'],
		    					'debitDate' => $_POST['debitDate'],
		    					'payerUniqueID' => $records->payerUniqueID,
		    				 );
		    $integrapay_data = createPaymentRequest($data_send);
		    print_r($integrapay_data);
			//$response = array('type'=>'success','insert_id'=>$insert_id,'msg'=> '<i class="fa fa-check"></i>Saved successfully');
			//wp_send_json($response);
		}
	}
exit();
}

function createPaymentRequest($data){
	$input_xml = '<?xml version="1.0" encoding="utf-8"?>
	<request>
		<username>1752</username>
		<password>H/j7eJ6=!sS</password>
		<payerUniqueID>'.$data['payerUniqueID'].'</payerUniqueID>
		<debitDate>'.$data['debitDate'].'</debitDate>
		<transactionAmountInCents>'.$data['amount'].'</transactionAmountInCents>
		<transactionID>'.$data['transactionID'].'</transactionID>
	</request>';
	//$url = 'https://sandbox.paymentsapi.io/basic/PayLinkService.svc';
	
	$url = 'https://sandbox.paymentsapi.io/PayLinkService.svc?WSDL';
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $input_xml );
	$data = curl_exec($ch);
	curl_close($ch);
	$array_data = json_decode(json_encode(simplexml_load_string($data)), true);
	return $array_data;
}

function validate_payment($data){

	$error = array();
	foreach ($data as $key => $value) {
		switch ($key) {
			case 'payerEmail':
				if (!empty($value)) {
					if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				     	$error[$key] = 'Please enter valid email id ';
					}else{
						global $wpdb;
						$bankintegrapay = $wpdb->prefix . 'bankintegrapay';
						$records = $wpdb->get_row( "SELECT * FROM `$bankintegrapay` WHERE `payerEmail` = '$value'" );
						if (!$records->resultPayerID) {
							$error[$key] = 'email is not register with bank payment service';
						}
					}			
				}else{
					$error[$key] = 'Please enter email id';
				}
				break;
			case 'debitDate':
				if (empty($value)) {
					$error[$key] = 'Please enter valid date.';
				}
				break;
			case 'amount':
				if (!empty($value)) {
					if (!preg_match("/[0-9]+/", $value)) {
						$error[$key] = 'Please enter valid amount.';	
					}
				}else{
					$error[$key] = 'Please enter amount.';	
				}
				break;		
		}
	}
	return $error;
}
?>