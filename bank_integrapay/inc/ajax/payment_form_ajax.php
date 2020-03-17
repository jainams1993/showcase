<?php 
add_action('wp_ajax_payment_form_ajax','payment_form_ajax');
add_action('wp_ajax_nopriv_payment_form_ajax','payment_form_ajax');
function payment_form_ajax(){
	if (isset($_POST) && !empty($_POST)) {
		global $wpdb;
		$error = validate($_POST);
		if (!empty($error)) {
			$response = array('type' => 'error','error' => $error);
			wp_send_json($response);
		}else{
			$bankintegrapay = $wpdb->prefix . 'bankintegrapay';
		    $insert_id = $wpdb->insert( $bankintegrapay, 
		    	array(
			        'payerFirstName'	 	=> $_POST['payerFirstName'], 
			        'payerLastName' 		=> $_POST['payerLastName'],
			        'payerAddressStreet' 	=> $_POST['payerAddressStreet'], 
			        'payerAddressSuburb' 	=> $_POST['payerAddressSuburb'],
			        'payerAddressState' 	=> $_POST['payerAddressState'], 
			        'payerAddressPostCode'	=> $_POST['payerAddressPostCode'], 
			        'payerAddressCountry' 	=> $_POST['payerAddressCountry'],
			        'payerEmail' 			=> $_POST['payerEmail'], 
			        'payerPhone' 			=> $_POST['payerPhone'], 
			        'payerMobile' 			=> $_POST['payerMobile'],
			        'invoice' 				=> $_POST['invoice'],
			        'status' 				=> 1,
			       )
				);
		    $lastid = $wpdb->insert_id;
		    $integrapay_data = createToken($lastid,$_POST['amount'],$_POST);
		    switch ($integrapay_data['result']) {
					case 'ERROR':
							$result['ERROR'] = 'something went wrong please try again';
							$response = array(
					            'type' => 'failure',
					            'html'  => $result,
							);
						break;
					case 'OK':
						if(isset($integrapay_data['webPageToken'])){
							global $wpdb;
							$update = $wpdb->query("UPDATE {$wpdb->prefix}bankintegrapay SET 
								token = '".$integrapay_data['webPageToken']."' WHERE id = ".$lastid);

							$url ='https://testpayments.integrapay.com.au/DDR/DDR.aspx?webPageToken='.$integrapay_data['webPageToken'];
					 		//$url = get_option('integrapay_integrapay_payment_response','').'?webPageToken='.$integrapay_data['webPageToken'];
					 		$response = array(
					            'type' => 'success',
					            'url'  => $url,
							);
						}else{
							$result['ERROR'] = 'Something went wrong please try again';
							$response = array(
					            'type' => 'failure',
					            'html'  => $result,
							);
						}
						break;
					default:
						break;
				}	
			//$response = array('type'=>'success','insert_id'=>$insert_id,'msg'=> '<i class="fa fa-check"></i>Saved successfully');
			wp_send_json($response);
		}
	}
exit();
}

function createToken($transactionID,$total,$data){
	$input_xml = '<?xml version="1.0" encoding="utf-8"?>
	<request>
		<username>1752</username>
		<password>H/j7eJ6=!sS</password>
		<command>PreDDR</command>
		<returnUrl>returnUrl</returnUrl>
		<eddrTemplateGUID>79534f53-b5a0-4d04-91f8-acea0a23cc21</eddrTemplateGUID>
		<payerUniqueID>'.strtotime("now").'</payerUniqueID>
		<payerFirstName>'.$data['payerFirstName'].'</payerFirstName>
		<payerLastName>'.$data['payerLastName'].'</payerLastName>
		<payerEmail>'.$data['payerEmail'].'</payerEmail>
		<payerMobile>'.$data['payerMobile'].'</payerMobile>
	</request>';
	$url = 'https://testpayments.integrapay.com.au/API/API.ashx';
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

function validate($data){

	$error = array();
	foreach ($data as $key => $value) {
		switch ($key) {
			case 'payerFirstName':
				if (!empty($value)) {
					if (ctype_alpha(str_replace(' ', '', $value)) === false) {
					 	$error[$key]= "Only letters and white space allowed"; 
					}
				}else{
					$error[$key] = 'Pleaes enter first name';
				}
				break;
			case 'payerLastName':
				if (!empty($value)) {
					if (ctype_alpha(str_replace(' ', '', $value)) === false) {
					 	$error[$key]= "Only letters and white space allowed"; 
					}
				}else{
					$error[$key] = 'Pleaes enter last name';
				}
				break;	
			case 'payerAddressPostCode':
					if (!empty($value)) {
						if (!preg_match("/[0-9]+/", $value)) {
							$error[$key] = 'Please enter valid PostCode.';	
						}
					}
					break;	
			case 'payerEmail':
				if (!empty($value)) {
					if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				     	$error[$key] = 'Please enter valid email id ';
					}				
				}else{
					$error[$key] = 'Please enter email id';
				}
				break;
			case 'payerPhone':
				$regex = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";
				if (!empty($value)) {
					if (preg_match($regex, $value)) {
								
					}elseif (strlen($value)>=10 && strlen($value) <=13 ) {
						if (!preg_match('/^[0-9]*$/', $value)) {
							$error[$key] = 'Please enter valid Number';	
						}
					}else{
						$error[$key] = 'Please enter valid phone Number';
					}
				}
				break;
			case 'payerMobile':
				$regex = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";
				if (!empty($value)) {
					if (preg_match($regex, $value)) {
								
					}elseif (strlen($value)>=10 && strlen($value) <=13 ) {
						if (!preg_match('/^[0-9]*$/', $value)) {
							$error[$key] = 'Please enter valid Number';	
						}
					}else{
						$error[$key] = 'Please enter valid Number';
					}
				}else{
					$error[$key] = 'Please enter mobile Number';
				}
				break;
			case 'invoice':
				if (!empty($value)) {
					if (!preg_match("/[A-Za-z0-9]+/", $value) == TRUE) {
						$error[$key] = 'Please enter valid invoice!';
					}
				}else{
					$error[$key] = 'Please enter invoice!';
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